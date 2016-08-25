#!/bin/bash
TEST=$(ifconfig -a | sed 's/[ \t].*//;/^$/d' | grep -v lo)
INTERFACE=$(echo $TEST | awk '{print $1}')
ifconfig $INTERFACE $1 netmask $3
route add default gw $2
echo "nameserver $4" > /etc/resolv.conf
