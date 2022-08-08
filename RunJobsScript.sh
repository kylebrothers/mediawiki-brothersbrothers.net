#!/bin/bash
php /var/www/html/maintenance/runJobs.php --wait
FILE=/var/www/html/extensions/SemanticMediaWiki/.smw.json
if [ -f "$FILE" ]; then
    echo "$FILE exists."
else 
    echo "$FILE does not exist."
    php /var/www/html/maintenance/udpate.php --wait
fi
