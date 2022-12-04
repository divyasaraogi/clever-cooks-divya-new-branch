#!/bin/bash

D_DIR="/var/www/clevercooks.duckdns.org/html/"

cd clever-cooks
git pull
if [ $? -eq 0 ]; then
  rsync -av public/ $D_DIR
fi
cd ..
