#!/bin/bash
if [ "$1" == "dist" ];then
    HOST="mm"
    INDEX_FILE="index.php"
else
    HOST="mmtest"
    INDEX_FILE="index-test.php"
fi
echo "publish to $HOST:"

scp -r protected/controllers root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
scp -r protected/models root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
scp -r protected/views root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
scp -r protected/components root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
scp -r $INDEX_FILE root@115.28.134.105:/alidata/www/vqupai/$HOST/index.php
scp -r protected/config/production.php root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/config/main.php
#scp -r protected/components root@115.28.134.105:/alidata/www/vqupai/$HOST/protected
#scp -r protected/extensions root@115.28.134.105:/alidata/www/vqupai/$HOST/protected
