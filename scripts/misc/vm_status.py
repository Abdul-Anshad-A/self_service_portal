#!/usr/bin/python

import os

from pysphere import VIServer, VIProperty, MORTypes, VITask

from pysphere.resources import VimService_services as VI
from pprint import pprint
import json
import argparse

my_data = json.loads(open("//var/www/html/scripts/misc/config.json").read())

HOST = my_data['ip']
USER = my_data['username']
PASSWORD = my_data['password']

parser = argparse.ArgumentParser(description="To get VM info")
parser.add_argument("--vmname", metavar='vmname', help="Name of the VM")
args = parser.parse_args()

#DATASTORE = "datastore1" #WHERE THE DISK WILL BE CREATED AT
#VM_PATH = "[datastore1-data] vm-test-name/vm-test-name.vmx"
VM_NAME = []
VM_NAME.append(args.vmname)

s = VIServer()
s.connect(HOST, USER, PASSWORD)

result = s._retrieve_properties_traversal(property_names=["name"], obj_type="VirtualMachine")

#vm_names = [r.PropSet[0].Val for r in result]
#print vm_names

#for vmname in vm_names:
for vmname in VM_NAME:
    vm = s.get_vm_by_name(vmname)

    diskinfo = {}
    vminfo = vm.get_properties(from_cache=False)

    #pprint(vminfo)
    #exit(1)
    for disk in vminfo["disks"]:
        diskinfo[disk["label"]] = {"path": disk["descriptor"], "size": disk["capacity"]}

    if 'hostname' in vminfo:
        hostname = vminfo['hostname']
    else:
        hostname = None

    if 'net' in vminfo:
        net = vminfo['net']
    else:
        net = None

    status = {"name": vm.properties.name,
              "path": vminfo['path'],
              "guest_id": vminfo['guest_id'],
              "hostname": hostname,
              "overallStatus": vm.properties.overallStatus,
              "VM_type": vm.properties.config.guestFullName,
              "num_cpu": vminfo['num_cpu'],
              "memory_mb": vminfo["memory_mb"],
              "disks": diskinfo,
              "net": net}

    #pprint(status)
    print json.dumps(status)

