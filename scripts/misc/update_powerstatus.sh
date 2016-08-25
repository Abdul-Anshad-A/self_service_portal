#!/bin/sh

SLEEP=10

# do stuff
/var/www/html/scripts/misc/test-tmp.py --getallvms > /var/www/html/scripts/misc/powerstatus-tmp.json 2>&1
sleep $SLEEP
cp /var/www/html/scripts/misc/powerstatus-tmp.json /var/www/html/scripts/misc/powerstatus.json

# do stuff
/var/www/html/scripts/misc/test-tmp.py --getallvms > /var/www/html/scripts/misc/powerstatus-tmp.json 2>&1
sleep $SLEEP
cp /var/www/html/scripts/misc/powerstatus-tmp.json /var/www/html/scripts/misc/powerstatus.json

# do stuff
/var/www/html/scripts/misc/test-tmp.py --getallvms > /var/www/html/scripts/misc/powerstatus-tmp.json 2>&1
sleep $SLEEP
cp /var/www/html/scripts/misc/powerstatus-tmp.json /var/www/html/scripts/misc/powerstatus.json

# do stuff
/var/www/html/scripts/misc/test-tmp.py --getallvms > /var/www/html/scripts/misc/powerstatus-tmp.json 2>&1
sleep $SLEEP
cp /var/www/html/scripts/misc/powerstatus-tmp.json /var/www/html/scripts/misc/powerstatus.json

# echo and restart...
exec $0
