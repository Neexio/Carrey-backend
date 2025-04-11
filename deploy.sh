#!/bin/bash

# Backup database
mysqldump -u carrfacy_wpq8 -pEirik16498991 carrfacy_wpq8 > backup.sql

# Backup WordPress files
tar -czf wordpress_backup.tar.gz wp-content/

# Update WordPress core
wp core update

# Update plugins
wp plugin update --all

# Update themes
wp theme update --all

# Clear cache
wp cache flush

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Restart Nginx
sudo systemctl restart nginx

echo "Deployment completed successfully!" 