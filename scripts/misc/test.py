#!/usr/bin/env python
import json
import sys
import argparse
from connect_class import Connect
from basic_operations import VmBasicOperations


##### To read the VCENTER server details ####
my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())


##### Argument Parser #####
parser = argparse.ArgumentParser(description="To Perform Basic VM Operations")
parser.add_argument("--getallvms", action='store_true', help="To get all registered VM's and their power status")
parser.add_argument("--getallvmnames", action='store_true', help="To get all registered VM names's")
parser.add_argument("--getvcenterinfo", action='store_true', help="To get the Vcenter server information")
parser.add_argument("--poweron", metavar='vmname', help="To power on the specified VM")
parser.add_argument("--poweroff", metavar='vmname', help="To power off the specified VM")
parser.add_argument("--clone", metavar = ("basename", "template", "resourcepool", "count", "amount"), nargs=5, help="To clone a VM or a Template")
args = parser.parse_args()


##### To establish connection with the VCENTER server #####
conobj = Connect(my_data['ip'], my_data['username'], my_data['password'])
server  = conobj.connectServer()

#### To pass the server object to perform basic vm operations ###
vmobj = VmBasicOperations(server)

### Class to create custom dictionary which appends key values as lists ####
class mdict(dict):

    def __setitem__(self, key, value):
        """add the given value to the list of values for this key"""
        self.setdefault(key, []).append(value)

def getvcenterinfo():
	vcenter = mdict()
	key = ('servertype', 'version', 'datacenter', 'cluster', 'hosts', 'datastores')
	(servertype, version, datacenter, cluster, hosts, datastores) = vmobj.vcenterInfo()
	vcenter['servertype'] = servertype
	vcenter['version'] = version
	for value in datacenter.values():
		vcenter[key[2]] = value
	for value in cluster.values():
		vcenter[key[3]] = value
	for value in hosts.values():
		vcenter[key[4]] = value
	for value in datastores.values():
		vcenter[key[5]] = value
	print json.dumps(vcenter)
	

def getallvms():
	vmlist = vmobj.getAllVms()
	vmpath = []
	for vm in vmlist:
		vmpath.append(vmobj.vmPath(vm))

	dict = {}
	for namestat in vmpath:
		(name, status) = vmobj.vmStatus(namestat)
		dict[name] = status
	print json.dumps(dict)

def getallvmnames():
	vmlist = vmobj.getAllVms()
	vmpath = []
	for vm in vmlist:
		vmpath.append(vmobj.vmPath(vm))
	dict = {}
	for namestat in vmpath:
		(name, uuid) = vmobj.vmNames(namestat)
		dict[name] = uuid
	print json.dumps(dict)


def poweron(vmname):
	powerstat = {}
	try:
		powerobj = server.get_vm_by_name(vmname)
	except:
		unknown = {vmname:"VM NOT FOUND"}
		print json.dumps(unknown)
		exit(1)
	status = vmobj.powerOn(powerobj)
	powerstat[vmname] = status
	print json.dumps(powerstat)


def poweroff(vmname):
        powerstat = {}
        try:
                powerobj = server.get_vm_by_name(vmname)
        except:
                unknown = {vmname:"VM NOT FOUND"}
                print json.dumps(unknown)
                exit(1)
        status = vmobj.powerOff(powerobj)
        powerstat[vmname] = status
        print json.dumps(powerstat)


def clone(clone):
	clonestat = vmobj.clone(clone, my_data['ip'], my_data['username'], my_data['password'])
	#print json.dumps(clonestat)
	print clonestat
	
		
		


if args.getallvms:
	getallvms()
if args.getallvmnames:
	getallvmnames()
if args.getvcenterinfo:
	getvcenterinfo()
if args.poweron:
	poweron(args.poweron)
if args.poweroff:
	poweroff(args.poweroff)
if args.clone:
	clone(args.clone)


#### To disconnec the VCENTER server connection
conobj.disconnectServer()
