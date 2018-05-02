# DSI4EU

## Server requirements
- PHP 7
    - `apc` extension (optional)
- MySQL (MariaDB 10)
- Apache 2.4
- Composer
- Git

## Installation
- `git clone https://github.com/nestauk/DSI4EU.git`
- create MySQL user and database
- import into MySQL database all the files from _database/migrations_ and _database/seeds_
- copy _config/config.sample.php_ to _config/config.php_ and update with correct information
- `composer install`
- `vendor/bin/phinx migrate -c config/phinx.php  -e production`

## Integration with data visualisation
- `cd www/data-viz`
- `git clone https://github.com/nestauk/DSI4EU_Dataviz.git`
- follow installation instructions from _www/data-viz/README.md_ 
- create symlink for data visualisation `ln -s www/viz www/data-viz/public`
- make sure the **.htaccess** file exists in _www/data-viz/public/_ 

## Platform
The entry point for all the requests is the _www/index.php_ file. 
All requests are directed to this file by the web server. 
The **index.php** bootstraps the application and will invoke a **controller** ( in _src/DSI/Controller/_, _src/Controllers/_ ) 
based on the http route.

The controllers are responsible to execute any required **actions** 
( in _src/DSI/UseCase/_, _src/Actions/_ ) before loading the **views** ( in _www/views/_, _src/Views/_ ).
 
Command line calls are made to _cli.php_. Run `php cli.php` to list all the possible options.
