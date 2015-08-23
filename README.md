# Campus Swap College Student Marketplace
==========

[![Join the chat at https://gitter.im/vaskaloidis/campusswap](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/vaskaloidis/campusswap?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Deploy
* Create a database in MySQL, then import the latest SQL dump from /var/sql/latest_date.sql
```
mysql -u root -p campusswap < var/sql/7-9-12.sql
```
* Configure etc/config.ini - Set your configuration file. (Example Below)
* Configure the logger in etc/log4php - Set the path to the log file in /var/log/main.log

## Current SQL Dump: 7-9-15.sql

## Architecture
* Modules - Various website sections that contain (mostly) PHP code
* Interface - Various website sections that contain (mostly) GUI Code
* Lib - This folder contains the various libraries. there are 4 types: 
  * DAO - Data Access Objects
  * Objects - Each Object represents a database table
  * Enum - Contains ENUM-styled PHP classes. These are experimental and are not implemented (yet).
  * Util - Various Utilities. Parser is used for parsing data. Config parses the config file. Helper consists of various helper classes for both the GUI and backend. LogUtil is used for logging (needs improvement). Timer is used for timing various code execution times. Database is used for connecting and querying to the DB.

## Configuration File - /etc/Config.ini
```
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
```

### Analytics
Analytics logic modify 
```
/modules/analytics.php
```

### Tasks

Through out the project there are many TODO comments, and if you are using a good IDE, it should have a list of the project TODO's generated.
All work for Campus Swap has been marked by a TODO

### Planned Functionality
* Implement a Manager System - User's who have a permission level of moderator can review user for posts as well as other moderator authority.
* Allow very very basic styling in posts, and better filtering of bad stuff and styling code that is now allowed.

