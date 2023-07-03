FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update
RUN apt install -y --no-install-recommends \
    vim \
    curl \
    ca-certificates \
    zip \
    unzip \
    git \
    mysql-client \
    mysql-server \
    apache2 \
    ufw \
    php8.1 \
    php8.1-curl \
    php8.1-xml

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www
RUN composer global require laravel/installer && composer clear-cache
ENV PATH="/root/.composer/vendor/bin:${PATH}"

RUN rm -rf *
# ADD ./ ./

# RUN chown -R www-data:www-data /var/www \
#      && chmod -R 755 /var/www/storage

# ADD ./apache2-sites-available/80.conf /etc/apache2/sites-available/000-default.conf
# RUN mv ./conf-file/80.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80


ENTRYPOINT ["/bin/sh", "-c", "email_ctl -c && db_ctl -c && apache2_ctl && service apache2 restart && exec /bin/bash"]

