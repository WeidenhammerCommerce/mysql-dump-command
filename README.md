# mysql-dump-command
## Install via composer (need to update for using github)

composer config repositories.hammer-mysql-dump-command git git@github.com:WeidenhammerCommerce/mysql-dump-command.git
composer require hammer/mysql-dump-command:dev-master
bin/magento module:enable Hammer_MysqlDumpCommand
bin/magento s:up

## How to use it?
bin/magento hammer:dump
