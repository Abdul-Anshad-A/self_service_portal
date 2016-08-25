#!usr/bin/python
import json
import sys
import argparse
from pysphere import VIServer
import sqlite3
import os


##### To read the VCENTER server details ####
my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())
conn = sqlite3.connect('/var/www/html/scripts/misc/vmdatabase.db')
c = conn.cursor()

##### Argument Parser #####
parser = argparse.ArgumentParser(description="To Perform VNC Operations")
parser.add_argument("--enable", metavar = ("vmname", "port", "password"), nargs=3, help="To enable VNC")
parser.add_argument("--disable", metavar = ("vmname"), nargs=1, help="To disable")
parser.add_argument("--console", metavar = ("vmname"), nargs=1, help="To launch console")
parser.add_argument("--vncstatus", metavar = ("all"), nargs=1, help="To get vnc status")
parser.add_argument("--changepwd", metavar = ("vmname", "password"), nargs=2, help="To change VM vnc password")
args = parser.parse_args()


s = VIServer()
s.connect(my_data['ip'], my_data['username'], my_data['password'])

vncdict = {}


def enable_vnc(enable_details):
        vm = s.get_vm_by_name(enable_details[0])
	proceed = check_vnc(vm, enable_details)
	if proceed == 1:
		settings = {'RemoteDisplay.vnc.enabled': 'true', 'RemoteDisplay.vnc.password': enable_details[2], 'RemoteDisplay.vnc.port': enable_details[1]}
		vm.set_extra_config(settings)
		vncdict["VMNAME"] = enable_details[0]
		vncdict["VNC"] = "True"
		host = vm.properties.runtime.host.name
		vncdict["HOST"] = str(host)
		vncdict["PORT"] = enable_details[1]
		#print json.dumps(vncdict)
		
		start_proxy(vncdict["HOST"], vncdict["PORT"])
	

def disable_vnc(disable_details):
        vm = s.get_vm_by_name(disable_details[0])
	disable_uuid = vm.properties.config.instanceUuid
	t = (disable_uuid,)
	present = []
	result = c.execute('SELECT * FROM stocks WHERE uuid=?', t)
	for row in result:
		present = row
	if present:
		#print "vnc is enabled"
		settings = {'RemoteDisplay.vnc.enabled': 'false'}
		vm.set_extra_config(settings)
		del_uuid = (disable_uuid,)
		c.execute('DELETE FROM stocks WHERE uuid=?', del_uuid)
		cmd = "%s %s %s" %("sudo", "/var/www/html/scripts/misc/kill-port-process.sh", present[4])
		#print cmd
		os.system(cmd)
		conn.commit()
		vncdict["VMNAME"] = disable_details[0]
		vncdict["VNC"] = "False"
		print json.dumps(vncdict)

	else:
		#print "vnc is not enabled"
		vncdict["ERROR"] = "VNC IS NOT ENABLED"
		print json.dumps(vncdict)
		sys.exit()
	


def check_vnc(vmname_uuid, port_details):
	global uuid
	uuid = vmname_uuid.properties.config.instanceUuid
	#print uuid
	t = (uuid,)
	present = []
	result = c.execute('SELECT * FROM stocks WHERE uuid=?', t)
	for row in result:
		present = row
	if present:
		#print "VNC is already enabled"
		vncdict["ERROR"] = "VNC IS ALREADY ENABLED"
		print json.dumps(vncdict)
		sys.exit()
	else:
		#print "VNC is not enabled"
		port_to_check = port_details[1]
		port_result = c.execute('SELECT * FROM stocks')
		for row in port_result:
			if int(row[4]) == int(port_to_check):
				#print "port is laready used"
				vncdict["ERROR"] = "PORT IS ALREADY USED"
				print json.dumps(vncdict)
				sys.exit()
	return 1
			

def start_proxy(host, port):
	t = (uuid,)
	result =  c.execute('SELECT * FROM stocks WHERE uuid=?', t)
	present = []
	for row in result:
		present = row
	if present:
		#print present
		#print "exists"
		vncdict["ERROR"] = "DB already has an entry for this uuid"
		print json.dumps(vncdict)
		sys.exit()
		
	else:
		#print "not exists"
		cmd = "%s %s %s %s %s %s %s %s" %("nohup", "sudo", "/var/www/html/scripts/misc/noVNC/utils/launch.sh", "--vnc", host+":"+port, "--listen", port, "&>/var/www/html/scripts/misc/noVNC/utils/launch_log.txt &")
		return_code = os.system(cmd)
		#print return_code
		values = (uuid, vncdict["VMNAME"], 1, vncdict["HOST"], vncdict["PORT"])
		c.execute('INSERT INTO stocks VALUES (?, ?, ?, ?, ?)', values)
		conn.commit()
		print json.dumps(vncdict)


def console_url(console_vmname):
	vm = s.get_vm_by_name(console_vmname[0])
	uuid = vm.properties.config.instanceUuid
	t = (uuid,)
	present = []
	result = c.execute('SELECT * FROM stocks WHERE uuid=?', t)
	for row in result:
		present = row
	if present:
		#print "VNC is present"
		#print "http://%s:%s/vnc_auto.html"%("172.16.1.240", present[4])
		url = "http://%s:%s/vnc_auto.html"%("172.16.1.240", present[4])
		vncdict["VMNAME"] = console_vmname[0]
		vncdict["URL"] = url
		print json.dumps(vncdict)
		
	else:
		#print "vnc is not enabled , please enable it first"
		vncdict["ERROR"] = "VNC IS NOT ENABLED YET"
		print json.dumps(vncdict)



def vnc_status(vnc_details):
	data = []
	result = c.execute('SELECT * FROM stocks')
	for row in result:
		data.append(row)
	print json.dumps(data)


def change_password(password_details):
	vm = s.get_vm_by_name(password_details[0])
	uuid = vm.properties.config.instanceUuid
	t = (uuid,)
	present = []
        result = c.execute('SELECT * FROM stocks WHERE uuid=?', t)
        for row in result:
                present = row
        if present:
		#print "VNC IS ENABLED"
		settings = {'RemoteDisplay.vnc.password': password_details[1]}
		vm.set_extra_config(settings)
		vncdict[password_details[0]] = "PASSWORD SUCCESSFULLY CHANGED"
		print json.dumps(vncdict)
		
        else:
                #print "vnc is not enabled , please enable it first"
                vncdict["ERROR"] = "VNC IS NOT YET ENABLED FOR THIS VM"
                print json.dumps(vncdict)




if args.enable:
	enable_vnc(args.enable)

if args.disable:
	disable_vnc(args.disable)

if args.console:
	console_url(args.console)

if args.vncstatus:
	vnc_status(args.vncstatus)

if args.changepwd:
	change_password(args.changepwd)



s.disconnect()
