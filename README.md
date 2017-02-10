# DSI4EU

## Requirements
- PHP 7
- MySQL (MariaDB 10.1)
- Apache 2.4
- Composer
- Git
- PHP extensions
    - apc

## Installations
- git clone https://github.com/nestauk/DSI4EU.git
- create MySQL user and database
- import into MySQL database all the files from **/src/mysql-update**
- copy **/src/config.sample.php** into **/src/config.php**
- update **/src/config.php** with correct information
- run "_composer install_" in / (root)