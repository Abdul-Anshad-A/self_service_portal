#!/usr/bin/python
from pysphere import VIServer
from pysphere import MORTypes, VIServer, VITask, VIProperty, VIMor, VIException
import json
import argparse
import sys

##### To read the VCENTER server details ####
my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())


##### Argument Parser #####
parser = argparse.ArgumentParser(description="To activate windows server VM's")
parser.add_argument("--activate", metavar = ("vmname", "username", "password"), nargs=3, help="To activate windows server VM's")
args = parser.parse_args()



server = VIServer()
server.connect(my_data['ip'], my_data['username'], my_data['password'])

def winActivate(name):
	activatedict = {}
	try:
		vm = server.get_vm_by_name(name[0])
		vm.login_in_guest(name[1], name[2])
		os_name = vm.get_property('guest_id')
		os_name = os_name.lower()
		if os_name.find("windows") >= 0:
			vm.send_file("/var/www/html/scripts/misc/activate1.bat", r"C:\Users\Administrator\Desktop\activate1.bat", overwrite=True)
			vm.send_file("/var/www/html/scripts/misc/activate2.bat", r"C:\Users\Administrator\Desktop\activate2.bat", overwrite=True)
			vm.start_process(r"C:\Users\Administrator\Desktop\activate1.bat")
			vm.start_process(r"C:\Users\Administrator\Desktop\activate2.bat")
			activatedict[name[0]] = "SUCCESS"
		else:
			activatedict["error"] = "Please select a windows VM to activate"

	except VIException as inst:
		#print inst
		activatedict["error"] = str(inst)
	print json.dumps(activatedict)


if args.activate:
	winActivate(args.activate)


server.disconnect()
