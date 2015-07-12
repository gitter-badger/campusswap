# Campus Swap College Student Marketplace
==========

### Deploy
1) Create a database in MySQL, then import the latest SQL dump from /var/sql/latest_date.sql
`mysql -u root -p campusswap < var/sql/7-9-12.sql`
2) Configure etc/config.ini - Set your configuration file. Below is an example.
3) Configure etc/log4php - Set the path to the log file in /var/log/main.log

### Current SQL Dump: 7-12-15.sql
- Log table modified to include intrusion_ip column for better intrusion detection
- Other changes in SQL db structure
- TODO: Update SQL file changeset for 7-12-15.sql

## Configuration File - /etc/Config.ini
`
[CONFIG]
; DB Configuration
db_user = "root"
db_password = "welcomee"
db_server = "localhost"
db_database = "campusswap"
; Website URL and File System Directory Absolute Path
url = "http://localhost/campusswap/"
dir = "/home/vasili/Documents/Sites/campusswap/"
; Debug Info DIV
debug = "false" ; Turn the Debug panel on or off
debug_location = "foot" ; 'head' or 'foot' - Where to display debug panel
; Version Number
version = 0.0.1
; Enviorment: 'dev' for Development, 'stage' for Staging, 'prod' for Production
enviorment = "dev"
`

### php.ini Configuration
- To debug with XDebug, locate your xdebug.so file, then add the following sections to php.ini file
` zend_extension = /usr/lib/php5/20121212/xdebug.so `
and also, add the following settings into the following file
`/etc/php5/cli/conf.d/20-xdebug.ini file`
`
zend_extension=/usr/lib/php5/20121212/xdebug.so
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_host=127.0.0.1
xdebug.remote_port=9000
`

### Tasks

Through out the project there are many TODO comments, and if you are using a good IDE, it should have a list of the project TODO's generated.
All work for Campus Swap has been marked by a TODO

### Planned Functionality
- Implement a Manager System - User's who have a permission level of moderator can review user for posts as well as other moderator authority.
- Allow very very basic styling in posts, and better filtering of bad stuff and styling code that is now allowed.

