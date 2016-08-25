#!/bin/bash
/bin/kill -9 $(/sbin/fuser -n tcp $1 2> /dev/null)
