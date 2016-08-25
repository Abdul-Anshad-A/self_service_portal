from pysphere import * 
from pysphere.resources import VimService_services as VI 
from pysphere.vi_task import VITask 

#new_mac = "00:0c:29:ac:70:96" 

#Connect to the server 
s = VIServer() 
s.connect("172.16.1.239", "Administrator", "pass*&^") 

#Get VM 
vm = s.get_vm_by_name("DEV_VM.clone") 

#Find Virtual Nic device 
net_device = None 
for dev in vm.properties.config.hardware.device: 
    if dev._type in ["VirtualE1000", "VirtualE1000e", 
                     "VirtualPCNet32", "VirtualVmxnet", "VirtualVmxnet3"]: 
        net_device = dev._obj 
        break
print net_device 
print vm.properties.config.hardware.device

if not net_device: 
    s.disconnect() 
    raise Exception("The vm seems to lack a Virtual Nic") 

