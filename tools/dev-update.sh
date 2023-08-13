#!/bin/bash

src_path=../src
dest_path=/opt/lampp/htdocs/ramses

for file in $dest_path/*.php; do
    rm -r "$file"
done

# Main files
for file in $src_path/*.php; do
    cp -t "$dest_path" "$file"
    echo "Deployed $file"
done
# htaccess
cp -t "$dest_path" "$src_path/.htaccess" 
