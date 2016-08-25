     #connect with vmware server:
    server=VIServer()
    server.connect(<remote host>, <user>, <password>)

    # Get vm object
    vm_obj = server.get_vm_by_name(target_vm)

    # Customize hostname and IP address
            request = VI.CustomizeVM_TaskRequestMsg()
            _this = request.new__this(vm_obj._mor)
            _this.set_attribute_type(vm_obj._mor.get_attribute_type())
            request.set_element__this(_this)
            spec = request.new_spec()
            globalIPSettings = spec.new_globalIPSettings()
            spec.set_element_globalIPSettings(globalIPSettings)
            # NIC settings, I used static ip, but it is able to set DHCP here, but I did not test it.
            nicSetting = spec.new_nicSettingMap()
            adapter = nicSetting.new_adapter()
            fixedip = VI.ns0.CustomizationFixedIp_Def("ipAddress").pyclass()
            fixedip.set_element_ipAddress(ip_address)
            adapter.set_element_ip(fixedip)
            adapter.set_element_subnetMask(netmask)
            nicSetting.set_element_adapter(adapter)
            spec.set_element_nicSettingMap([nicSetting,])
            identity = VI.ns0.CustomizationLinuxPrep_Def("identity").pyclass()
            identity.set_element_domain("domain name")
            hostName = VI.ns0.CustomizationFixedName_Def("hostName").pyclass()
            hostName.set_element_name(target_vm.replace("_", ""))
            identity.set_element_hostName(hostName)
            spec.set_element_identity(identity)
            request.set_element_spec(spec)
            task = server._proxy.CustomizeVM_Task(request)._returnval
            vi_task = VITask(task, server)
            status = vi_task.wait_for_state([vi_task.STATE_SUCCESS, vi_task.STATE_ERROR], <timeout setting>)
