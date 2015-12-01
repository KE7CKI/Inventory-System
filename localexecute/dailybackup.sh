#!/bin/bash

currentDate=$(date "+%Y%m%d")

mysql -u defyproducts -pMiracleDay01 -e "select * from defy_inventory.daily_log" > /var/www/html/defy.titansp.com/inventory_logs/$currentDate.dailylog.txt

unix2dos /var/www/html/defy.titansp.com/inventory_logs/$currentDate.dailylog.txt

#remove old daily_log table
mysql -u defyproducts -pMiracleDay01 -e "DROP TABLE IF EXISTS defy_inventory.daily_log"

#create new daily_log table
mysql -u defyproducts -pMiracleDay01 -e "CREATE TABLE defy_inventory.daily_log (id int(11) NOT NULL , number varchar(15) DEFAULT '0', count int(10) DEFAULT '0', PRIMARY KEY (id), FULLTEXT KEY searchable (number)) ENGINE=MyISAM AUTO_INCREMENT=169 DEFAULT CHARSET=latin1"
