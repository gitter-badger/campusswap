campusswap
==========

Collge student marketplace


Deployment:
1) Create a database in MySQL, then import the latest SQL dump from /var/sql
2) Configure etc/config.ini based on the below format
3) Configure etc/log4php - set the directory where you want the log





Config Params:
[CONFIG_DEV] - Set in lib/Config.php Line 24, so you can easily switch between dev, stage and prod enviorments
db_user = Datebase Username
db_password = Database Password
db_server = Datbase Address (IE: Localhost)
db_database = Database Name 
url = "http://localhost/site_url/" - this is for frontent links
dir = "/Users/vaskaloidis/Sites/site_directory/" - this is for backend PHP includes
debug = "true" - the debug menu displayed
version = 0.0.1 - what version, we will use this in the future for DB updates

