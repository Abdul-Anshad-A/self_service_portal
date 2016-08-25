#!/usr/bin/env python

import sys
from pysphere import VIServer, VIProperty, MORTypes
from pysphere.resources import VimService_services as VI
from pysphere.vi_task import VITask

# Todo:
# Specify networks on a distributed switch
# Ability to set CPU/Memory reservations
# Convert into a ansible module

# CONNECTION PARAMTERS
server = "vcr40.mgt.domain.edu"
user = 'username'
password = 'password'
resource_pool_path = ""
#cluster_name = "test_dev_cluster"

# REQUIRED PARAMETERS
vmname = "testvm"
datacentername = "Dev_Datacenter"
hostname = "esx14.mgt.domain.edu"
annotation = "hotdog it worked"
memorysize = 512 #MB
cpucount = 1
cd_iso_location = "hit04_iso_datastore/ISO/Redhat/rhel-server-6.3-x86_64-dvd.iso" #located in the ESX datastore

# find your os in
# http://www.vmware.com/support/developer/vc-sdk/visdk41pubs/ApiReference/vim.vm.GuestOsDescriptor.GuestOsIdentifier.html
guestosid = "rhel6_64Guest"

network_name = "dv_172_16_1_0_24"

disksize = 2 #In GB
disksizetwo = 3 #In GB

# Convert to kilobytes
disksize = disksize * 1024 * 1024
disksizetwo = disksizetwo * 1024 * 1024

# OPTIONAL PARAMETERS
datastore = "hit04_sata_datastore04" #if None, will use the first datastore available
datastoretwo = "hit04_sata_datastore05" #if None, will use the first datastore available
extra_config = {'vcpu.hotadd':'TRUE', 'mem.hotadd':'TRUE'}

# CONNECT TO THE SERVER
s = VIServer()
s.connect(server, user, password)


# GET INITIAL PROPERTIES AND OBJECTS
dcmor = [k for k,v in s.get_datacenters().items() if v==datacentername][0]
dcprops = VIProperty(s, dcmor)


# networkFolder managed object reference
nfmor = dcprops.networkFolder._obj
dvpg_mors = s._retrieve_properties_traversal(property_names=['name','key'],
                                    from_node=nfmor, obj_type='DistributedVirtualPortgroup')

# Get the portgroup managed object.
dvpg_mor = None
for dvpg in dvpg_mors:
    if dvpg_mor:
        break
    for p in dvpg.PropSet:
        if p.Name == "name" and p.Val == network_name:
            dvpg_mor = dvpg
        if dvpg_mor:
            break

if dvpg_mor == None:
    print "Didnt find the dvpg %s, exiting now" % (network_name)
    sys.exit() 

# Get the portgroup key
portgroupKey = None
for p in dvpg_mor.PropSet:
    if p.Name == "key":
        portgroupKey = p.Val

# Grab the dvswitch uuid and portgroup properties
dvswitch_mors = s._retrieve_properties_traversal(property_names=['uuid','portgroup'],
                                    from_node=nfmor, obj_type='DistributedVirtualSwitch')

dvswitch_mor = None
# Get the dvswitches managed object
for dvswitch in dvswitch_mors:
    if dvswitch_mor:
        break
    for p in dvswitch.PropSet:
        if p.Name == "portgroup":
            pg_mors = p.Val.ManagedObjectReference
            for pg_mor in pg_mors:
                if dvswitch_mor:
                    break
                key_mor = s._get_object_properties(pg_mor, property_names=['key'])
                for key in key_mor.PropSet:
                    if key.Val == portgroupKey:
                        dvswitch_mor = dvswitch 

# Get the switches uuid
dvswitch_uuid = None
for p in dvswitch_mor.PropSet:
    if p.Name == "uuid":
        dvswitch_uuid = p.Val
                     

# hostFolder managed object reference
hfmor = dcprops.hostFolder._obj
crmors = s._retrieve_properties_traversal(property_names=['name','host'],
                                    from_node=hfmor, obj_type='ComputeResource')

hostmor = [k for k,v in s.get_hosts().items() if v==hostname][0]

crmor = None
for cr in crmors:
    if crmor:
        break
    for p in cr.PropSet:
        if p.Name == "host":
            for h in p.Val.get_element_ManagedObjectReference():
                if h == hostmor:
                    crmor = cr.Obj
                    break
            if crmor:
                break
crprops = VIProperty(s, crmor)

# get resource pool
if resource_pool_path:
    cluster = [k for k,v in s.get_clusters().items() if v==cluster_name][0]
    rpmor = [k for k,v in s.get_resource_pools(from_mor=cluster).items()
            if v == resource_pool_path][0]
else:
    rpmor = crprops.resourcePool._obj


vmfmor = dcprops.vmFolder._obj

# CREATE VM CONFIGURATION

# get config target
request = VI.QueryConfigTargetRequestMsg()
_this = request.new__this(crprops.environmentBrowser._obj)
_this.set_attribute_type(crprops.environmentBrowser._obj.get_attribute_type ())
request.set_element__this(_this)
h = request.new_host(hostmor)
h.set_attribute_type(hostmor.get_attribute_type())
request.set_element_host(h)
config_target = s._proxy.QueryConfigTarget(request)._returnval

# get default devices
request = VI.QueryConfigOptionRequestMsg()
_this = request.new__this(crprops.environmentBrowser._obj)
_this.set_attribute_type(crprops.environmentBrowser._obj.get_attribute_type ())
request.set_element__this(_this)
h = request.new_host(hostmor)
h.set_attribute_type(hostmor.get_attribute_type())
request.set_element_host(h)
#config_option = s._proxy.QueryConfigOption(request)._returnval
#default_devs = config_option.DefaultDevice

# get network name
#network_name = None
#for n in config_target.Network:
#    if n.Network.Accessible:
#        #print n.Network.Name
#        network_name = n.Network.Name

def find_datastore(datastore):
    # Verify the datastore exists and put it in brackets if it does.
    ds = None
    for d in config_target.Datastore:
        if (d.Datastore.Accessible and
        (datastore and d.Datastore.Name == datastore)
        or (not datastore)):
            ds = d.Datastore.Datastore
            datastore = d.Datastore.Name
            break
    if not ds:
        raise Exception("couldn't find datastore")
    datastore_name = "[%s]" % datastore
    return datastore_name, ds


# add parameters to the create vm task
create_vm_request = VI.CreateVM_TaskRequestMsg()
config = create_vm_request.new_config()
vmfiles = config.new_files()
datastore_name, ds = find_datastore(datastore)
vmfiles.set_element_vmPathName(datastore_name)
config.set_element_files(vmfiles)
config.set_element_name(vmname)
config.set_element_annotation(annotation)
config.set_element_memoryMB(memorysize)
config.set_element_numCPUs(cpucount)
config.set_element_guestId(guestosid)
devices = []


def add_scsi_controller(type="paravirtual", bus_num=0, disk_ctrl_key=1):
    ### add a scsi controller
    scsi_ctrl_spec = config.new_deviceChange()
    scsi_ctrl_spec.set_element_operation('add')
    
    if type == "lsi":
        # For RHEL5
        scsi_ctrl = VI.ns0.VirtualLsiLogicController_Def("scsi_ctrl").pyclass()
    elif type == "paravirtual":
        # For RHEL6
        scsi_ctrl = VI.ns0.ParaVirtualSCSIController_Def("scsi_ctrl").pyclass()
    elif type == "lsi_sas":
        scsi_ctrl = VI.ns0.VirtualLsiLogicSASController_Def("scsi_ctrl").pyclass()
    elif type == "bus_logic":
        scsi_ctrl = VI.ns0.VirtualBusLogicController_Def("scsi_ctrl").pyclass()
    
    scsi_ctrl.set_element_busNumber(bus_num)
    scsi_ctrl.set_element_key(disk_ctrl_key)
    scsi_ctrl.set_element_sharedBus("noSharing")
    scsi_ctrl_spec.set_element_device(scsi_ctrl)
    # Add the scsi controller to the VM spec.
    devices.append(scsi_ctrl_spec)
    return disk_ctrl_key 



def add_disk(datastore, type="thin", size=200000, disk_ctrl_key=1, disk_number=0, key=0):
    ### add a vmdk disk
    # Verify the datastore exists
    datastore_name, ds = find_datastore(datastore)
    # create a new disk - file based - for the vm
    disk_spec = config.new_deviceChange()
    disk_spec.set_element_fileOperation("create")
    disk_spec.set_element_operation("add")
    disk_ctlr = VI.ns0.VirtualDisk_Def("disk_ctlr").pyclass()
    disk_backing = VI.ns0.VirtualDiskFlatVer2BackingInfo_Def("disk_backing").pyclass()
    disk_backing.set_element_fileName(datastore_name)
    disk_backing.set_element_diskMode("persistent")
    if type == "thin":
        disk_backing.set_element_thinProvisioned(1)
    #disk_ctlr.set_element_key(0)
    disk_ctlr.set_element_key(key)
    disk_ctlr.set_element_controllerKey(disk_ctrl_key)
    disk_ctlr.set_element_unitNumber(disk_number)
    disk_ctlr.set_element_backing(disk_backing)
    disk_ctlr.set_element_capacityInKB(size)
    disk_spec.set_element_device(disk_ctlr)
    devices.append(disk_spec)



def add_cdrom(type="client", cd_iso_location=None):
    ### Add a cd-rom 
    # TODO: 
    # * Make it optional that the power on at boot option is checked.

    # Make sure the datastore exists.
    if cd_iso_location:
        iso_location = cd_iso_location.split('/', 1)  
        datastore, ds = find_datastore(iso_location[0])
        iso_path = iso_location[1]
    
    # find ide controller
    ide_ctlr = None
    config_option = s._proxy.QueryConfigOption(request)._returnval
    default_devs = config_option.DefaultDevice

    for dev in default_devs:
        if dev.typecode.type[1] == "VirtualIDEController":
            ide_ctlr = dev
    
    # add a cdrom based on a physical device
    if ide_ctlr:
        cd_spec = config.new_deviceChange()
        cd_spec.set_element_operation('add')
        cd_ctrl = VI.ns0.VirtualCdrom_Def("cd_ctrl").pyclass()

        if type == "iso":
            iso = VI.ns0.VirtualCdromIsoBackingInfo_Def("iso").pyclass()
            ds_ref = iso.new_datastore(ds)
            ds_ref.set_attribute_type(ds.get_attribute_type())
            iso.set_element_datastore(ds_ref)
            iso.set_element_fileName("%s %s" % (datastore, iso_path))
            cd_ctrl.set_element_backing(iso)
            cd_ctrl.set_element_key(20)
            cd_ctrl.set_element_controllerKey(ide_ctlr.get_element_key())
            cd_ctrl.set_element_unitNumber(0)
            cd_spec.set_element_device(cd_ctrl)
        if type == "client":
            client = VI.ns0.VirtualCdromRemoteAtapiBackingInfo_Def("client").pyclass()
            client.set_element_deviceName("")
            cd_ctrl.set_element_backing(client)
            cd_ctrl.set_element_key(20)
            cd_ctrl.set_element_controllerKey(ide_ctlr.get_element_key())
            cd_ctrl.set_element_unitNumber(0)
            cd_spec.set_element_device(cd_ctrl)

        devices.append(cd_spec)



def add_nic(nic_type="vmxnet3", network_name="VM Network", network_type="standard"):
    ### add a NIC 
    # The network Name must be set as the device name to create the NIC.
    # Different network card types are: "VirtualE1000", "VirtualE1000e","VirtualPCNet32", "VirtualVmxnet", "VirtualNmxnet2", "VirtualVmxnet3"
    nic_spec = config.new_deviceChange()
    if network_name:
        nic_spec.set_element_operation("add")

        if nic_type == "e1000":
            nic_ctlr = VI.ns0.VirtualE1000_Def("nic_ctlr").pyclass()
        elif nic_type == "e1000e":
            nic_ctlr = VI.ns0.VirtualE1000e_Def("nic_ctlr").pyclass()
        elif nic_type == "pcnet32":
            nic_ctlr = VI.ns0.VirtualPCNet32_Def("nic_ctlr").pyclass()
        elif nic_type == "vmxnet":
            nic_ctlr = VI.ns0.VirtualVmxnet_Def("nic_ctlr").pyclass()
        elif nic_type == "vmxnet2":
            nic_ctlr = VI.ns0.VirtualVmxnet2_Def("nic_ctlr").pyclass()
        elif nic_type == "vmxnet3":
            nic_ctlr = VI.ns0.VirtualVmxnet3_Def("nic_ctlr").pyclass()
    
        if network_type == "standard":
            # Standard switch
            nic_backing = VI.ns0.VirtualEthernetCardNetworkBackingInfo_Def("nic_backing").pyclass()
            nic_backing.set_element_deviceName(network_name)
        elif network_type == "dvs":
            nic_backing_port = VI.ns0.DistributedVirtualSwitchPortConnection_Def("nic_backing_port").pyclass()
            nic_backing_port.set_element_switchUuid(dvswitch_uuid)
            nic_backing_port.set_element_portgroupKey(portgroupKey)

            # http://www.vmware.com/support/developer/vc-sdk/visdk400pubs/ReferenceGuide/vim.vm.device.VirtualEthernetCard.DistributedVirtualPortBackingInfo.html
            nic_backing = VI.ns0.VirtualEthernetCardDistributedVirtualPortBackingInfo_Def("nic_backing").pyclass()
            nic_backing.set_element_port(nic_backing_port)

            # How they do it in powershell
            # http://www.lucd.info/2010/03/04/dvswitch-scripting-part-8-get-and-set-network-adapters/
            # How they do it in ruby
            # https://github.com/fog/fog/pull/1431/files
        
        nic_ctlr.set_element_addressType("generated")
        nic_ctlr.set_element_backing(nic_backing)
        nic_ctlr.set_element_key(4)
        nic_spec.set_element_device(nic_ctlr)
        devices.append(nic_spec)


# Add a scsi controller to the VM spec.
disk_ctrl_key = add_scsi_controller()
# Add the first disk (thinly-provisioned) to the VM spec.
add_disk(datastore, "thin", disksize, disk_ctrl_key, 0, 0)
# Add a second disk (thick-provisioned) to the VM spec.
add_disk(datastoretwo, "thin", disksizetwo, disk_ctrl_key, 1, 1)
# Add a CD-ROM device to the VM.
add_cdrom("iso", cd_iso_location)
add_nic("vmxnet3", network_name, "dvs")
#add_nic("vmxnet3", "VM Network", "standard")



config.set_element_deviceChange(devices)
create_vm_request.set_element_config(config)
folder_mor = create_vm_request.new__this(vmfmor)
folder_mor.set_attribute_type(vmfmor.get_attribute_type())
create_vm_request.set_element__this(folder_mor)
rp_mor = create_vm_request.new_pool(rpmor)
rp_mor.set_attribute_type(rpmor.get_attribute_type())
create_vm_request.set_element_pool(rp_mor)
host_mor = create_vm_request.new_host(hostmor)
host_mor.set_attribute_type(hostmor.get_attribute_type())
create_vm_request.set_element_host(host_mor)

# CREATE THE VM
taskmor = s._proxy.CreateVM_Task(create_vm_request)._returnval
task = VITask(taskmor, s)
task.wait_for_state([task.STATE_SUCCESS, task.STATE_ERROR])
if task.get_state() == task.STATE_ERROR:
    raise Exception("Error creating vm: %s" % task.get_error_message())




# If there is any extra config options specified, set them here.
if extra_config:
    vm = s.get_vm_by_name(vmname)    
    vm.set_extra_config(extra_config)

s.disconnect()

