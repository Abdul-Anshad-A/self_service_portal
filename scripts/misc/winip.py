#!/usr/bin/python
from pysphere import VIServer
server = VIServer()
server.connect("172.16.1.10" , "Administrator", "pass*&^")
vm = server.get_vm_by_name("Windows")
vm.login_in_guest("Administrator", "pass*&^")
vm.send_file("/var/www/html/scripts/misc/winautoip.bat", r"C:\Users\Administrator\Desktop\winautoip.bat", overwrite=True)
vm.start_process("C:\Users\Administrator\Desktop\winautoip.bat", args = ["172.16.1.20", "172.16.1.254", "255.255.255.0"])
server.disconnect()
