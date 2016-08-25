from pysphere import *
import pprint
server = VIServer()
server.connect("172.16.1.239","Administrator","pass*&^")

vmlist = server.get_registered_vms()
for vm in vmlist:
  vm_mor = server.get_vm_by_path(vm)
  vm_name = vm_mor.properties.config.name
  for usage in vm_mor.properties.storage.perDatastoreUsage:
    print vm_name, usage.datastore.name, usage.committed / 1024 ** 3
  
  try: 
    for disk in vm_mor.properties.guest.disk:
      print vm_name, disk.diskPath, float(disk.capacity) / 1024 **3 , float(disk.freeSpace) / 1024 ** 3
  except:
	  print vm_name


server.disconnect
