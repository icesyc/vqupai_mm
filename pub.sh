#!/bin/bash
SERVER=123.57.73.43
if [ "$1" == "dist" ];then
    HOST="mm"
    INDEX_FILE="index.php"
else
    HOST="mmtest"
    INDEX_FILE="index-test.php"
fi
echo "publish to $HOST:"

scp -r protected/controllers root@$SERVER:/alidata/www/vqupai/$HOST/protected/
scp -r protected/models root@$SERVER:/alidata/www/vqupai/$HOST/protected/
scp -r protected/views root@$SERVER:/alidata/www/vqupai/$HOST/protected/
scp -r protected/components root@$SERVER:/alidata/www/vqupai/$HOST/protected/
scp -r $INDEX_FILE root@$SERVER:/alidata/www/vqupai/$HOST/index.php
scp -r protected/config/production.php root@$SERVER:/alidata/www/vqupai/$HOST/protected/config/main.php

#scp -r protected/components root@$SERVER:/alidata/www/vqupai/$HOST/protected
#scp -r protected/extensions root@$SERVER:/alidata/www/vqupai/$HOST/protected
#scp -r css root@$SERVER:/alidata/www/vqupai/$HOST/
scp -r js root@$SERVER:/alidata/www/vqupai/$HOST/
