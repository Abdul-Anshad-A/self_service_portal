#!/usr/bin/python
import os
from pysphere import VIServer
server = VIServer()
server.connect("172.16.1.215", "root", "password", trace_file="debug.txt")

print "Server Type : "+server.get_server_type() 
print "Server API version : "+server.get_api_version()
vmlist = server.get_registered_vms()
print "List of registered VM's :", vmlist
vm1 = server.get_vm_by_path(vmlist[0])
print "Current status", vm1.get_status(basic_status=True)
print "Is the VM powering ON? :", vm1.is_powering_on()
print "Is the VM powering OFF? :", vm1.is_powering_off()
properties = vm1.get_properties()
#print properties
print "Guest ID of the VM :", vm1.get_property('guest_id')
if vm1.get_status(basic_status=True) !="POWERED ON":
	print "Powering on the VM :", vm1.power_on()
else:
	print "VM  is already POWERED ON"
	print "Current status", vm1.get_status(basic_status=True)


server.disconnect()
