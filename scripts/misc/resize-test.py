from pysphere import VIServer, VITask
from pysphere.resources import VimService_services as VI

server = VIServer()
server.connect("172.16.1.239", "Administrator", "pass*&^")

memory_mb = 512


vm_obj = server.get_vm_by_name("DEV_VM.clone")


request = VI.ReconfigVM_TaskRequestMsg()
_this = request.new__this(vm_obj._mor)
_this.set_attribute_type(vm_obj._mor.get_attribute_type())
request.set_element__this(_this)
spec = request.new_spec()

#set the new RAM size
spec.set_element_memoryMB(memory_mb)

#set the no of CPU and socket
spec.set_element_numCPUs(2)
#spec.set_element_numCoresPerSocket(2)

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
