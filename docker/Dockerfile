FROM amazonlinux:2.0.20211201.0
# Base
RUN yum install -y git vim zip unzip
# Change timezone
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime
# Install php8.1
RUN amazon-linux-extras install epel \
    && yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm \
    && yum install -y ImageMagick libxml2-devel nc \
    && yum groupinstall -y "Development Tools" \
    && yum install -y php81 \
    && yum install -y  php81-php-bcmath php81-php-fpm php81-php-gd php81-php-intl php81-php-mbstring php81-php-mysqlnd php81-php-odbc php81-php-opcache php81-php-pdo php81-php-pecl-redis5 php81-php-pecl-zip php81-php-pear php81-php-devel php81-php-process php81-php-xml php81-php-xmlrpc mod_ssl \
    && yum install -y php81-php-pecl-xdebug \
    && yum groupremove -y "Development Tools" \
    && yum clean all
# Alternatives
RUN alternatives --install /usr/bin/php php /usr/bin/php81 1
# Install nginx
RUN amazon-linux-extras install nginx1
# Enable nginx
RUN systemctl enable nginx.service
# Make directory for php-fpm socket
RUN mkdir -p /var/run/php-fpm
# Enable php-fpm
RUN systemctl enable php81-php-fpm
# Install composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer
# Install nodejs
RUN curl -fsSL https://rpm.nodesource.com/setup_16.x | bash  \
    && yum install -y nodejs
# Install yarn
RUN curl -sL https://dl.yarnpkg.com/rpm/yarn.repo | tee /etc/yum.repos.d/yarn.repo \
    && yum install -y yarn
# Log
RUN rm -f /var/log/nginx/* \
    && cd /var/log/nginx \
    && ln -s /dev/stdout access.log \
    && ln -s /dev/stderr error.log

# Automatic start
ENTRYPOINT /opt/remi/php81/root/usr/sbin/php-fpm && /usr/sbin/nginx -g "daemon off;"