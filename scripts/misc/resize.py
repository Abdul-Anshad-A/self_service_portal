#!/usr/bin/python
from pysphere import VIServer
from pysphere import MORTypes, VIServer, VITask, VIProperty, VIMor, VIException
from pysphere.resources import VimService_services as VI
import json
import argparse
import sys

##### To read the VCENTER server details ####
my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())


##### Argument Parser #####
parser = argparse.ArgumentParser(description="To activate windows server VM's")
parser.add_argument("vmname")
#parser.add_argument("username")
#parser.add_argument("password")
parser.add_argument("--memory", metavar = ("memsize"), nargs=1, help="To resize a virtual machines memory")
parser.add_argument("--cpu", metavar = ("num_cpu", "num_cores_per_socket"), nargs=2, help="To resize a virtual machines CPU")
parser.add_argument("--disk", metavar = ("disksize", "virtual disk name"), nargs=2, help="To resize a virtual machines disk in GB")
args = parser.parse_args()



server = VIServer()
server.connect(my_data['ip'], my_data['username'], my_data['password'])


def requestNewSpec(vmname):
	statusdict = {}

	try:
		vm_obj = server.get_vm_by_name(vmname)
	except VIException as inst:
		statusdict["error"] = str(inst)
		print json.dumps(statusdict)
		sys.exit()
		
        request = VI.ReconfigVM_TaskRequestMsg()
        _this = request.new__this(vm_obj._mor)
        _this.set_attribute_type(vm_obj._mor.get_attribute_type())
        request.set_element__this(_this)
        spec = request.new_spec()

	if args.memory:
		memory_mb = int(args.memory[0])
		#set the new RAM size
		spec.set_element_memoryMB(memory_mb)

	if args.disk:
		hd_name = str(args.disk[1])
		hd_sizes_kb = {hd_name: int(args.disk[0])*1024*1024} #GB
		hd_to_modify = []
		for dev in vm_obj.properties.config.hardware.device:
		    if dev._type == "VirtualDisk" and dev.deviceInfo.label in hd_sizes_kb:
		        dev_obj = dev._obj
		        dev_obj.set_element_capacityInKB(hd_sizes_kb[dev.deviceInfo.label])
		        hd_to_modify.append(dev_obj)
		#Change the HDs sizes
		dev_changes = []
		for hd in hd_to_modify:
		    dev_change = spec.new_deviceChange()
		    dev_change.set_element_operation("edit")
		    dev_change.set_element_device(hd)
		    dev_changes.append(dev_change)
		if dev_changes:
		    spec.set_element_deviceChange(dev_changes)

	if args.cpu:
		numcpu = int(args.cpu[0])
		numcorespersocket = int(args.cpu[1])
		temp = float(numcpu)/float(numcorespersocket)
		temp = (temp % 1)
		if temp == float(0):
			if (numcpu / numcorespersocket ) > 8:
				statusdict["cpu"] = "Only 8 socket for a VM is supported"
				print json.dumps(statusdict)
				sys.exit()
			else:
				spec.set_element_numCPUs(numcpu)
				spec.set_element_numCoresPerSocket(numcorespersocket)
		else:
			statusdict["cpu"] = "No of CPU divided by No of Cores per Socket should always be a Integer"
			print json.dumps(statusdict)
			sys.exit()


	request.set_element_spec(spec)
	ret = server._proxy.ReconfigVM_Task(request)._returnval
	
	#Wait for the task to finish
	task = VITask(ret, server)
	status = task.wait_for_state([task.STATE_SUCCESS, task.STATE_ERROR])
	if status == task.STATE_SUCCESS:
	    #print "VM successfully reconfigured"
            statusdict[vmname] = "VM successfully reconfigured"
	elif status == task.STATE_ERROR:
	    #print "Error reconfiguring vm: %s" % task.get_error_message()
            errormsg = str(task.get_error_message())
	    statusdict["error"] = errormsg
	
	print json.dumps(statusdict)

#tempdict = {}
#tempdict["error"] = "just testing"
#print json.dumps(tempdict)
#sys.exit()
requestNewSpec(args.vmname)


server.disconnect()
