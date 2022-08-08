#!/bin/bash
FILE=/var/www/html/extensions/SemanticMediaWiki/.smw.json
if [ -f "$FILE" ]; then
    echo "$FILE exists."
else 
    echo "$FILE does not exist."
    /usr/local/bin/php /var/www/html/maintenance/update.php
fi
/usr/local/bin/php /var/www/html/maintenance/runJobs.php