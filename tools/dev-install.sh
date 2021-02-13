#"!/bin/bash"

# Installs the ramses server in a "ramses" subdir of the destination

destination=/var/www

# php
src=../src/
install=../src/install
# convert to absolute paths
src=$(cd "$src"; pwd)
install=$(cd "$install"; pwd)

# create
rm -r -f "$destination/ramses"
mkdir "$destination/ramses"
mkdir "$destination/ramses/install"

for file in $install/*; do
    ln -s -t "$destination/ramses/install/" "$file"
    echo "Linked $file"
done

for file in $src/*.php; do
    ln -s -t "$destination/ramses" "$file"
    echo "Linked $file"
done

echo "Done!"