#!/bin/bash
if [ "$1" == "dist" ];then
    HOST="mm"
    INDEX_FILE="index.php"
	CONFIG_FILE="production.php"
else
    HOST="mmtest"
    INDEX_FILE="index-test.php"
	CONFIG_FILE="test.php"
fi
echo "publish to $HOST:"

if [[ "$2" == "full" ]];then
	mv protected/config/main.php protected/config/main.php.bak
	cp protected/config/production.php protected/config/main.php
	scp -r . root@115.28.134.105:/alidata/www/vqupai/$HOST/
	scp -r $INDEX_FILE root@115.28.134.105:/alidata/www/vqupai/$HOST/index.php
	mv protected/config/main.php.bak protected/config/main.php
else
	scp -r protected/controllers root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
	scp -r protected/models root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
	scp -r protected/views root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
	scp -r protected/components root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/
	scp -r $INDEX_FILE root@115.28.134.105:/alidata/www/vqupai/$HOST/index.php
	scp -r protected/config/$CONFIG_FILE root@115.28.134.105:/alidata/www/vqupai/$HOST/protected/config/main.php
	#scp -r protected/components root@115.28.134.105:/alidata/www/vqupai/$HOST/protected
	#scp -r protected/extensions root@115.28.134.105:/alidata/www/vqupai/$HOST/protected
fi

