#!/bin/bash

# Create relevant dirs
mkdir -p data/db/
mkdir -p data/compiled/
mkdir -p data/cache/
mkdir -p data/logs/

# Npm install

echo "Building css && js"
grunt css js

echo "Setup Complete"