#!/bin/bash

# test

cd ..
git fetch --all
git reset --hard origin/master
git pull

chmod +x git/pull.sh
