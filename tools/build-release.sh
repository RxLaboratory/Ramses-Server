#!/bin/bash

src_path=../src/
dckr_mysql_path=../docker-mysql/
dckr_sqlite_path=../docker-sqlite/
dest_path=build

# convert to absolute paths
src_path=$(cd "$src_path"; pwd)
dckr_mysql_path=$(cd "$dckr_mysql_path"; pwd)
dckr_sqlite_path=$(cd "$dckr_sqlite_path"; pwd)

rm -r -f $dest_path
mkdir $dest_path
mkdir $dest_path/docker-mysql
mkdir $dest_path/docker-sqlite
mkdir $dest_path/www
mkdir $dest_path/www/install
mkdir $dest_path/www/config

### MAIN

# Main www files
for file in $src_path/*; do
    cp -t $dest_path/www "$file"
    echo "Deployed $file"
done
cp -t $dest_path/www "$src_path/.htaccess"

# install folder
for file in $src_path/install/*; do
    cp -t $dest_path/www/install "$file"
    echo "Deployed $file"
done

# config folder
for file in $src_path/config/*; do
    cp -t $dest_path/www/config "$file"
    echo "Deployed $file"
done

### DOCKER MySQL

# docker
cp -r -t $dest_path "$dckr_mysql_path"
mkdir $dest_path/docker-mysql/www/ramses
# Main docker files
for file in $src_path/*; do
    cp -t $dest_path/docker-mysql/www/ramses "$file"
    echo "Deployed $file"
done
cp -t $dest_path/docker-mysql/www/ramses "$src_path/.htaccess"
# Install docker files
mkdir $dest_path/docker-mysql/www/ramses/install
for file in $src_path/install/*; do
    cp -t $dest_path/docker-mysql/www/ramses/install "$file"
    echo "Deployed $file"
done
# Config docker files
mkdir $dest_path/docker-mysql/www/ramses/config
for file in $src_path/config/*; do
    cp -t $dest_path/docker-mysql/www/ramses/config "$file"
    echo "Deployed $file"
done
# Set docker to use mysql
sed -i "s/sqlMode = 'sqlite'/sqlMode = 'mysql'/" $dest_path/docker-mysql/www/ramses/config/config.php
# Set docker to accept non SSL connection
sed -i "s/forceSSL = true/forceSSL = false/" $dest_path/docker-mysql/www/ramses/config/config.php

### DOCKER SQLITE

# docker
cp -r -t $dest_path "$dckr_sqlite_path"
mkdir $dest_path/docker-sqlite/www/ramses
# Main docker files
for file in $src_path/*; do
    cp -t $dest_path/docker-sqlite/www/ramses "$file"
    echo "Deployed $file"
done
cp -t $dest_path/docker-sqlite/www/ramses "$src_path/.htaccess"
# Install docker files
mkdir $dest_path/docker-sqlite/www/ramses/install
for file in $src_path/install/*; do
    cp -t $dest_path/docker-sqlite/www/ramses/install "$file"
    echo "Deployed $file"
done
# Config docker files
mkdir $dest_path/docker-sqlite/www/ramses/config
for file in $src_path/config/*; do
    cp -t $dest_path/docker-sqlite/www/ramses/config "$file"
    echo "Deployed $file"
done
# Set docker to use sqlite
sed -i "s/sqlMode = 'mysql'/sqlMode = 'sqlite'/" $dest_path/docker-sqlite/www/ramses/config/config.php
# Set docker to accept non SSL connection
sed -i "s/forceSSL = true/forceSSL = false/" $dest_path/docker-sqlite/www/ramses/config/config.php

# Zip
cd $dest_path/www/
zip -r -m ramses-server.zip *
mv ramses-server.zip ../ramses-server.zip

cd ../docker-mysql/
zip -r -m ramses-server_docker-mysql.zip *
mv ramses-server_docker-mysql.zip ../ramses-server_docker-mysql.zip

cd ../docker-sqlite/
zip -r -m ramses-server_docker-sqlite.zip *
mv ramses-server_docker-sqlite.zip ../ramses-server_docker-sqlite.zip

cd ..
rm -rf docker-mysql/
rm -rf docker-sqlite/
rm -rf www/

echo "Done!"