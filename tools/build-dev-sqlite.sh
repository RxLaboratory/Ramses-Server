#!/bin/bash

src_path=../src/
dckr_sqlite_path=../docker-sqlite/
dest_path=build-dev

# convert to absolute paths
src_path=$(cd "$src_path"; pwd)
dckr_sqlite_path=$(cd "$dckr_sqlite_path"; pwd)

rm -r -f $dest_path
mkdir $dest_path
mkdir $dest_path/docker-sqlite

### DOCKER SQLITE

# docker
cp -r -t $dest_path "$dckr_sqlite_path"
mkdir $dest_path/docker-sqlite/www/ramses
# Main docker files
for file in $src_path/*.php; do
    cp -t $dest_path/docker-sqlite/www/ramses "$file"
    echo "Deployed $file"
done
cp -t $dest_path/docker-sqlite/www/ramses "$src_path/.htaccess"
# Install docker files
mkdir $dest_path/docker-sqlite/www/ramses/install
for file in $src_path/install/*.php; do
    cp -t $dest_path/docker-sqlite/www/ramses/install "$file"
    echo "Deployed $file"
done
cp -t $dest_path/docker-sqlite/www/ramses/install "$src_path/install/ramses.sqlite"
# Config docker files
mkdir $dest_path/docker-sqlite/www/ramses/config
for file in $src_path/config/*.php; do
    cp -t $dest_path/docker-sqlite/www/ramses/config "$file"
    echo "Deployed $file"
done
# Set docker to use sqlite
sed -i "s/sqlMode = 'mysql'/sqlMode = 'sqlite'/" $dest_path/docker-sqlite/www/ramses/config/config.php
# Set docker to accept non SSL connection
sed -i "s/forceSSL = true/forceSSL = false/" $dest_path/docker-sqlite/www/ramses/config/config.php
# Set loglevel to DATA
sed -i "s/logLevel = 'WARNING'/logLevel = 'DATA'/" $dest_path/docker-sqlite/www/ramses/config/config.php
# Set devMode to true
sed -i "s/devMode = false/devMode = true/" $dest_path/docker-sqlite/www/ramses/config/config.php

# Rename folder
mv $dest_path/docker-sqlite $dest_path/ramses-dev-sqlite

# Mount
cd $dest_path/ramses-dev-sqlite
docker compose up -d

echo "Done!"