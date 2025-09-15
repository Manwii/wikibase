FROM wikibase/wikibase:1.35

# Install pdo_mysql (needed for MediaWiki DB connection)
RUN docker-php-ext-install pdo_mysql

# Install OAuth extension (matching MediaWiki 1.35 branch)
RUN set -eux; \
    cd /var/www/html/extensions; \
    git clone -b REL1_35 https://gerrit.wikimedia.org/r/mediawiki/extensions/OAuth OAuth; \
    chown -R www-data:www-data /var/www/html/extensions/OAuth


