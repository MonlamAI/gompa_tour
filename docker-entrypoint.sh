#!/bin/bash

# Run composer install
composer install --verbose --no-dev --no-interaction --optimize-autoloader

# Start Apache
apache2-foreground
