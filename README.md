# Network Monitor

## Installation
1. Clone this repo to your server
2. Setup a virtual host on your server such that this app can be accessed with a url *http://sub.domain.com*
3. Clone `config.sample.php` to `config.php`
4. Change all placeholder values ( something like {THIS} ) with the values that correspond with your setup environment
5. Visit *http://sub.domain.com*.
	default credentials is netmon: Security86

Note: If the app will be used on a local network, the virtual host should be setup on a unique port so the app can be accessed on *http://ipaddress:port*

## Setup MySQL
1. Install MySQL
2. Create a database in MySQL
3. Set `DB_TYPE` option in `config.php` to `mysql`
4. Create a database and save the information in `config.php`
5. Import `dev/data/scheme.sql` and `dev/data/data.sql` into your database

## Monitoring Script
The file `monitor.php` is responsible for monitoring devices and saving the device status. This file should run every minute in order for the app to function properly. Follow your platform specific instructions to enable `monitor.php` to run every minute

### Linux & Mac
1. Type `crontab -e` into a command terminal
2. Add the following line to your crontab
   
   `* * * * *	cd {PATH/TO/THIS/FOLDER} && php monitor.php >> cron.log`

   Do not forget to replace {PATH/TO/THIS/FOLDER} with the actual path on your computer

### Windows
1. 