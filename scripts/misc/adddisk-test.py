#!/usr/bin/python
import json
import sys


my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())


HOST = my_data['ip']
USER = my_data['username']
PASSWORD = my_data['password']

import argparse

parser = argparse.ArgumentParser(description="To add disk to the virtual machine")
parser.add_argument("--adddisk", metavar = ("vmname", "size_in_GB", "disk_type", "eager_lazy", "disk_mode"), nargs=5, help="Details to add the virtual disk")
args = parser.parse_args()


#DATASTORE_NAME = "datastore4" #WHERE THE DISK WILL BE CREATED AT
DISK_SIZE_IN_GB = int(args.adddisk[1])

from pysphere import VIServer, VITask
from pysphere.resources import VimService_services as VI
from pysphere import MORTypes, VIServer, VITask, VIProperty, VIMor, VIException
import sys


s = VIServer()
s.connect(HOST, USER, PASSWORD)

VM_NAME = args.adddisk[0]
statusdict = {}
try:
	vm = s.get_vm_by_name(VM_NAME)
except VIException as ins:
	statusdict["ERROR"] = str(ins)
	print json.dumps(statusdict)
	sys.exit()	


data_temp = vm.get_property('path')
data_temp = data_temp.split(']')[0]
DATASTORE_NAME  = data_temp[1:]
temp = vm.get_property('disks')
unitnum = []
for i, val in enumerate(temp):
	unitnum.append(temp[i]['device']['unitNumber'])
unitnum.sort()
UNIT_NUMBER = unitnum[(len(unitnum))-1] + 1



request = VI.ReconfigVM_TaskRequestMsg()
_this = request.new__this(vm._mor)
_this.set_attribute_type(vm._mor.get_attribute_type())
request.set_element__this(_this)
    
spec = request.new_spec()

dc = spec.new_deviceChange()
dc.Operation = "add"
dc.FileOperation = "create"

hd = VI.ns0.VirtualDisk_Def("hd").pyclass()
hd.Key = -100
hd.UnitNumber = UNIT_NUMBER
hd.CapacityInKB = DISK_SIZE_IN_GB * 1024 * 1024
hd.ControllerKey = 1000

backing = VI.ns0.VirtualDiskFlatVer2BackingInfo_Def("backing").pyclass()
backing.FileName = "[%s]" % DATASTORE_NAME

DISK_MODE = args.adddisk[4]
backing.DiskMode = DISK_MODE #persistent, independent_persistent and independent_nonpersistent
backing.Split = False
backing.WriteThrough = False
if args.adddisk[2] == "thick" and args.adddisk[3] == "eager":
	backing.ThinProvisioned = False #if True - thin disk else thick disk
	backing.EagerlyScrub = True
elif args.adddisk[2] == "thick" and args.adddisk[3] == "lazy":
	backing.ThinProvisioned = False #if True - thin disk else thick disk
	backing.EagerlyScrub = False
elif args.adddisk[2] == "thin" and args.adddisk[3] == "None":
	backing.ThinProvisioned = True #if True - thin disk else thick disk
	backing.EagerlyScrub = False
hd.Backing = backing

dc.Device = hd

spec.DeviceChange = [dc]
request.Spec = spec

task = s._proxy.ReconfigVM_Task(request)._returnval
vi_task = VITask(task, s)

#Wait for task to finis
status = vi_task.wait_for_state([vi_task.STATE_SUCCESS,
                                 vi_task.STATE_ERROR])
if status == vi_task.STATE_ERROR:
    #print "ERROR CONFIGURING VM:", vi_task.get_error_message()
    statusdict["ERROR"] = str(vi_task.get_error_message())
else:
    #print "VM CONFIGURED SUCCESSFULLY"
    statusdict[VM_NAME] = "SUCCESS"

print json.dumps(statusdict)

s.disconnect()
