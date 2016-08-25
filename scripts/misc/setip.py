#!/usr/bin/python
from pysphere import VIServer
from pysphere import MORTypes, VIServer, VITask, VIProperty, VIMor, VIException
import json
import argparse
import sys

##### To read the VCENTER server details ####
my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())


##### Argument Parser #####
parser = argparse.ArgumentParser(description="To Set ip for VM's")
parser.add_argument("--setip", metavar = ("vmname", "ip", "gateway", "netmask", "dns", "guesos_username", "guestos_password", "ostype"), nargs=8, help="To set IP for a VM")
args = parser.parse_args()



server = VIServer()
server.connect(my_data['ip'], my_data['username'], my_data['password'])

def setip(vmname_ip):
	ipdict = {}
	try:
		#print vmname_ip[0]
		#print vmname_ip[1]
		vm = server.get_vm_by_name(vmname_ip[0])
		vm.login_in_guest(vmname_ip[5], vmname_ip[6])
		#vm.start_process("/sbin/ifconfig", args=["eth1", vmname_ip[1]], cwd="/sbin/")
		#os_name = vm.get_property('guest_id')
		if vmname_ip[7] == "linux":
        	        vm.send_file("/var/www/html/scripts/misc/setnetwork.sh", r"/root/setnetwork.sh", overwrite=True)
                	vm.start_process("/bin/chmod", args=["+x", "/root/setnetwork.sh"])
	                vm.start_process("/root/setnetwork.sh", args=[vmname_ip[1], vmname_ip[2], vmname_ip[3], vmname_ip[4]], cwd="/root")
        	        ipdict[vmname_ip[0]] = "SUCCESS"

		elif vmname_ip[7] == "windows":
			vm.send_file("/var/www/html/scripts/misc/winautoip.bat", r"C:\Users\Administrator\Desktop\winautoip.bat", overwrite=True)
			vm.start_process("C:\Users\Administrator\Desktop\winautoip.bat", args = [vmname_ip[1], vmname_ip[2], vmname_ip[3], vmname_ip[4]])
			ipdict[vmname_ip[0]] = "SUCCESS"

	except VIException as inst:
		#print inst
		ipdict["error"] = str(inst)
	print json.dumps(ipdict)


if args.setip:
	setip(args.setip)


server.disconnect()
