import sys
from pysphere import VIServer, VITask
from pysphere.resources import VimService_services as VI

server = VIServer()
server.connect("172.16.1.239", "Administrator", "pass*&^")

memory_mb = 2048
hd_sizes_kb = {'Hard disk 1': 40000000} #1 GB


vm_obj = server.get_vm_by_name("Windows server")

hd_to_modify = []
for dev in vm_obj.properties.config.hardware.device:
    if dev._type == "VirtualDisk" and dev.deviceInfo.label in hd_sizes_kb:
        dev_obj = dev._obj
	dev_obj.set_element_capacityInKB(hd_sizes_kb[dev.deviceInfo.label])
	hd_to_modify.append(dev_obj)

request = VI.ReconfigVM_TaskRequestMsg()
_this = request.new__this(vm_obj._mor)
_this.set_attribute_type(vm_obj._mor.get_attribute_type())
request.set_element__this(_this)
spec = request.new_spec()

#set the new RAM size
spec.set_element_memoryMB(memory_mb)

#Change the HDs sizes
dev_changes = []
for hd in hd_to_modify:
    dev_change = spec.new_deviceChange()
    dev_change.set_element_operation("edit")
    dev_change.set_element_device(hd)
    dev_changes.append(dev_change)
if dev_changes:
    spec.set_element_deviceChange(dev_changes)


request.set_element_spec(spec)
ret = server._proxy.ReconfigVM_Task(request)._returnval

#Wait for the task to finish
task = VITask(ret, server)
status = task.wait_for_state([task.STATE_SUCCESS, task.STATE_ERROR])
if status == task.STATE_SUCCESS:
    print "VM successfully reconfigured"
elif status == task.STATE_ERROR:
    print "Error reconfiguring vm: %s" % task.get_error_message() 


server.disconnect() 
