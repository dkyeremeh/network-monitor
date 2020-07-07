# Network Monitor

The app monitors your online services/devices and sends an email or SMS when any of them go online or offline

## Features
1. Send SMS or email when device/service is online or offline
1. Send only one notification till device status changes
1. Supports multiple users
1. Device/Service status report
1. Uses PHP/MySQL (Sqlite, PostGres and MsSQL are coming soon)

## Installation
1. Clone this repo to your server
1. Setup a virtual host on your server such that this app can be accessed with a url *http://sub.domain.com*
1. Clone `.env.example` to `.env` and change values to correspond with your setup environment
1. Visit *http://sub.domain.com*.
	default credentials is `netmon`: `Security86`

Note: If the app will be used on a local network, the virtual host should be setup on a unique port so the app can be accessed on *http://ipaddress:port*

## Setup Database
1. Install MySQL
2. Create a database in MySQL
3. Set `DB_CONNECTION` option in `.env` to `mysql`
4. Create a database and save the information in `.env`
5. Import `dev/data/scheme.sql` and `dev/data/data.sql` into your database

## Monitoring Script
The file `monitor.php` is responsible for monitoring devices and saving the device status. This file should run every minute in order for the app to function properly. Follow your platform specific instructions to enable `monitor.php` to run every minute

### Linux & Mac
1. Type `crontab -e` into a command terminal
2. Add the following line to your crontab
   
   `* * * * *	cd {PATH/TO/THIS/FOLDER} && php monitor.php >> cron.log`

   Do not forget to replace {PATH/TO/THIS/FOLDER} with the actual path on your computer

### Windows
Not yet tested (Contributions are welcome)

## ROADMAP
- Support for multiple databases
- Email queuing (for reliability)
- Support for multiple SMTP servers (to ensure emails are sent)
- Run script when device/service goes online/offline (useful for restarting app, etc)