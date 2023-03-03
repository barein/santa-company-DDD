FROM bitnami/php-fpm:8.2.1

#Install SURY repo to install php8.2 extensions
RUN apt-get update \
    && apt-get install -y zlib1g-dev \
    && apt install -y nano \
    && apt install -y curl wget gnupg2 ca-certificates lsb-release apt-transport-https \
    && wget https://packages.sury.org/php/apt.gpg \
    && apt-key add apt.gpg \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php8.list \
    && apt update \
##Install php8.2 extensions
    && apt install -y php8.2-ctype php8.2-iconv php8.2-simplexml php8.2-tokenizer php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring php8.2-intl php8.2-bcmath php8.2-bz2 php8.2-readline php8.2-zip \
## activate pdo_pgsql extension
#    && sed -rie 's/^;extension=pdo_pgsql$/extension=pdo_pgsql/' /opt/bitnami/php/lib/php.ini \
##Disable opcache
    && sed -rie 's/^opcache.enable = 1$/opcache.enable = 0/' /opt/bitnami/php/lib/php.ini \
##Give more memory to php
#    && sed -rie 's/^memory_limit = 128M$/memory_limit = 500M/' /opt/bitnami/php/lib/php.ini \
##Install php redis extension
    && apt-get -y install autoconf \
    && apt-get -y install gcc make autoconf libc-dev pkg-config \
    && printf "\n" | pecl install redis \
    && sed -i '$ a extension=redis.so' /opt/bitnami/php/lib/php.ini \
##Install Data Structure extension
    && pecl install ds \
    && sed -i '$ a extension=ds.so' /opt/bitnami/php/lib/php.ini
##Install nodejs
#    && curl -sL https://deb.nodesource.com/setup_14.x | bash - \
#    && apt install nodejs

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

#Create user
ARG USER_ID
ARG GROUP_ID
ARG USERNAME

RUN addgroup --gid $GROUP_ID $USERNAME \
    && adduser \
      --system \
      --uid $USER_ID\
      --gid $GROUP_ID \
      --disabled-password \
      --disabled-login \
      $USERNAME \
    # enable non-root user usage
    && chmod -R g+w /opt/bitnami/php/var /opt/bitnami/php/tmp  /opt/bitnami/php/logs \
    && chgrp -R $USERNAME \
      /opt/bitnami/php/var \
      /opt/bitnami/php/tmp \
      /opt/bitnami/php/logs

USER $USERNAME

WORKDIR /app

RUN PATH=$PATH:/apps/vendor/bin:bin