FROM ubuntu:22.04

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN apt-get update
RUN apt-get install --no-install-recommends -y \
    apache2 \
    php8.1 \
    php-xdebug

RUN apt-get clean && rm -rf /var/lib/apt-get/lists/*

RUN a2enmod rewrite

EXPOSE 80 9003

ENTRYPOINT ["/bin/sh","-c","service apache2 restart && exec /bin/bash"]