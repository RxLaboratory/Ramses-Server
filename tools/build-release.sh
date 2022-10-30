#!/bin/bash

src_path=../src/
dckr_path=../docker/
dest_path=build

# convert to absolute paths
src_path=$(cd "$src_path"; pwd)
dckr_path=$(cd "$dckr_path"; pwd)

rm -r -f $dest_path
mkdir $dest_path
mkdir $dest_path/docker
mkdir $dest_path/www
mkdir $dest_path/www/install
mkdir $dest_path/www/config

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

# docker
cp -r -t $dest_path "$dckr_path"
mkdir $dest_path/docker/www/ramses
# Main docker files
for file in $src_path/*; do
    cp -t $dest_path/docker/www/ramses "$file"
    echo "Deployed $file"
done
cp -t $dest_path/docker/www/ramses "$src_path/.htaccess"
# Install docker files
mkdir $dest_path/docker/www/ramses/install
for file in $src_path/install/*; do
    cp -t $dest_path/docker/www/ramses/install "$file"
    echo "Deployed $file"
done
# Config docker files
mkdir $dest_path/docker/www/ramses/config
for file in $src_path/config/*; do
    cp -t $dest_path/docker/www/ramses/config "$file"
    echo "Deployed $file"
done
# Set docker to use mysql
sed -i "s/sqlMode = 'sqlite'/sqlMode = 'mysql'/" $dest_path/docker/www/ramses/config/config.php

# Zip
cd $dest_path/www/
zip -r -m ramses-server.zip *
mv ramses-server.zip ../ramses-server.zip
cd ../docker/
zip -r -m ramses-server_docker.zip *
mv ramses-server_docker.zip ../ramses-server_docker.zip
cd ..
rm -rf docker/
rm -rf www/

echo "Done!"