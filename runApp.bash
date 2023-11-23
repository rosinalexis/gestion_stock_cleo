#!/bin/bash
composer install
./symfony console d:d:c 
./symfony console d:m:m

./symfony console app:new-user admin@admin admin001
