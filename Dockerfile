FROM bmoorman/ubuntu:focal

ARG DEBIAN_FRONTEND=noninteractive

ENV HTTPD_SERVERNAME=localhost \
    HTTPD_PORT=2876

RUN apt-get update \
 && apt-get install --yes --no-install-recommends \
    apache2 \
    curl \
    libapache2-mod-php \
    php-curl \
    php-memcached \
    php-redis \
    php-sqlite3 \
    ssl-cert \
    wget \
 && a2enmod \
    remoteip \
    rewrite \
    ssl \
 && sed --in-place --regexp-extended \
    --expression 's/^(Include\s+ports\.conf)$/#\1/' \
    /etc/apache2/apache2.conf \
 && wget --quiet --directory-prefix /usr/local/bin "https://dl.eff.org/certbot-auto" \
 && chmod +x /usr/local/bin/certbot-auto \
 && apt-get autoremove --yes --purge \
 && apt-get clean \
 && rm --recursive --force /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY apache2/ /etc/apache2/
COPY htdocs/ /var/www/html/
COPY bin/ /usr/local/bin/

VOLUME /config

EXPOSE ${HTTPD_PORT}

CMD ["/etc/apache2/start.sh"]

HEALTHCHECK --interval=60s --timeout=5s CMD /etc/apache2/healthcheck.sh || exit 1
