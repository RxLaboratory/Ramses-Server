#!/bin/bash

src_path=../src/
dest_path=/opt/lampp/htdocs/ramses

# convert to absolute paths
src_path=$(cd "$src_path"; pwd)

rm -r -f $dest_path
mkdir $dest_path

# Main files
for file in $src_path/*.php; do
    cp -t "$dest_path" "$file"
    echo "Deployed $file"
done
# htaccess
cp -t "$dest_path" "$src_path/.htaccess" 

# Copy config
mkdir $dest_path/config
for file in $src_path/config/*.php; do
    cp -t "$dest_path/config" "$file"
    echo "Deployed $file"
done

# Copy install
mkdir $dest_path/install
for file in $src_path/install/*.php; do
    cp -t "$dest_path/install" "$file"
    echo "Deployed $file"
done