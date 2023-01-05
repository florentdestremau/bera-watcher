# Installation

- [PHP 8.2](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)

```shell
git clone git@github.com:florentdestremau/bera-watcher.git && cd bera-watcher
composer install
make start
```

# Server provision

```shell
# devops
sudo apt -y install postgresql # only if database is local
sudo apt -y install redis nginx acl postgresql-client supervisor wkhtmltopdf
sudo timedatectl set-timezone Europe/Paris

# php
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt -y install php8.2 php8.2-{bcmath,bz2,curl,apcu,intl,gd,mbstring,opcache,sqlite3,redis,xml,zip,fpm}
sudo apt -y install php-gd php-gmp php-curl zip unzip php-igbinary
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```
# Server cron

```shell
*/15 15-17 * * * php /var/www/bera-watcher/current/bin/console app:extract-daily-beras
```
