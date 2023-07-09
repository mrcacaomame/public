FROM ubuntu:22.04

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    ca-certificates \
    vim \
    curl \
    zip \
    unzip \
    mysql-client

RUN apt-get install -y --no-install-recommends \
    apache2 \
    php8.1 \
    php8.1-curl \
    php8.1-xml \
    php-pdo \
    php-mysql \
    php-xdebug

RUN apt-get install -y --no-install-recommends \
    g++ \
    cmake \
    make \
    libssh-dev

RUN apt-get install -y --no-install-recommends \
openssh-client

RUN apt-get clean && rm -rf /var/lib/apt-get/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www
RUN composer global require laravel/installer && composer clear-cache
ENV PATH="/root/.composer/vendor/bin:${PATH}"

RUN echo "xdebug.mode=debug" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.client_port=9000" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /etc/php/8.1/apache2/conf.d/20-xdebug.ini

RUN rm -rf *
RUN a2enmod rewrite

EXPOSE 80 443 9000

ADD ./main/www /var/www
ADD ./bin /usr/local/bin
ADD ./sites-available /etc/apache2/sites-available
ADD ./exec /root/exec
ADD ./config/bashrc /root/.bashrc
ADD ./config/vimrc /root/.vimrc

RUN service apache2 start \
    && a2ensite laravel-80.conf \
    && service apache2 reload

WORKDIR /root
RUN cd exec \
    &&mkdir build \
    && cd build \
    && cmake .. \
    && make \
    && make install

RUN rm -rf exec

WORKDIR /var/www
RUN chown -R www-data:www-data storage \
    && chmod -R 775 storage

RUN php artisan key:generate

RUN mkdir -p /dev && touch /dev/null
ENTRYPOINT ["/bin/sh", "-c", "set-mysql -c && set-email -c && service apache2 restart > /dev/null && exec /bin/bash"]