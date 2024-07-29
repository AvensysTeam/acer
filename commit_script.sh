#!/bin/bash

# Navigate to your project directory
cd /var/www/html/acer-avensys-app

# Add all changes to the staging area
git add .

# Commit the changes with a message
git commit -m "Routine commit"

# Push the changes to the main branch on GitHub
git push origin main
