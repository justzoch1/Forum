## Порядок установки:

### 1. Клонирование репозитория
   
   ``` git clone https://github.com/justzoch1/Forum.git ```

### 2. Установка composer

   ``` php composer install ```

### 3. Запуск Docker контейнеров
   
   ``` docker compose up -d ```
   

#### 4. Вход в контейнер приложения
   
   ``` docker compose exec -it app bash ```
   

#### 5. Добавление ключа приложения
   
   ``` php artisan key:generate ```
   
#### 6. Запуск миграций
   
   ``` php artisan migrate ```
   

## Запуск сервера:
Откройте браузер и перейдите по адресу: http://localhost:8876/ 

Теперь ваше приложение должно быть готово к использованию)

## Файл .env:

На текущий момент изменению подверглись только переменные подключения к базе данных.

APP_NAME=<переменная_названия_приложения>Laravel

APP_ENV=<переменная_состояния_приложения>local

APP_KEY=<переменная_уникального_ключа_приложения>

APP_DEBUG=<переменная_состояния_приложения>true

APP_TIMEZONE=<переменная_часового_пояса>UTC

APP_URL=<пременная_url_адресса_приложения>http://localhost

APP_LOCALE=<переменная_локали_приложения>en

APP_FALLBACK_LOCALE=<переменная_резервной_локали>en

APP_FAKER_LOCALE=<переменная_локали_для_фиктивных_данных>enUS

APP_FAKER_LOCALE=<переменная_локали_для_фиктивных_данных>enUS

APPMAINTENANCEDRIVER=<переменная_драйвера_режима_обслуживания>file

APPMAINTENANCESTORE=database

BCRYPTROUNDS=<переменная_количества_раундов_хеширования>12

LOGC_HANNEL=<переменная_канала_логов>stack

LOG_STACK=<переменная_обработки_исключений_в_логах>single

LOG_DEPRECATIONS_CHANNEL=<переменная_канала_логов_устаревших_функций>null

LOG_LEVEL=<переменная_уровня_логов>debug

SESSION_DRIVER=<переменная_драйвера_сеанса>database

SESSION_LIFE_TIME=<переменная_продолжительности_жизни_сеанса>120

SESSION_ENCRYPT=<переменная_шифрования_данных_сеанса>false

SESSION_PATH=<переменная_пути_к_файлу_cookie_сеанса>/

SESSION_DOMAIN=<переменная_домена_для_файлов_cookie_сеанса>null

BROADCAST_CONNECTION=<переменная_соединения_для_вещания_событий>log

FILE_SYSTEM_DISK=<переменная_дискафайловой_системы>local

QUEUE_CONNECTION=<переменная_соединения_очереди>database

CACHE_STORE=<переменная_драйвера_кэша>database

CACHE_PREFIX=<переменная_префикса_ключа_кэша>

MEMCACHED_HOST=<переменная_хоста>127.0.0.1

DB_CONNECTION=<переменная_подключения>pgsql

DB_HOST=<переменная_хоста>pgsql

DB_PORT<переменная_порта>5432

DB_DATABASE=<переменная_названия_базы_данных>forumdb

DB_USERNAME=<переменная_имени_пользователя>admin

DB_PASSWORD=<переменная_пароля>111

REDIS_CLIENT=<переменная_подключения>phpredis

REDIS_HOST=<переменная_хоста>127.0.0.1

REDIS_PASSWORD=переменная_пароля>null

REDIS_PORT=<переменная_порта>6379

MAIL_MAILER=<переменная_драйвера_почты>log

MAIL_HOST=<переменна_яхоста>127.0.0.1

MAIL_PORT=<переменная_порта>2525

MAIL_USERNAME=<переменная_имени_пользователя>null

MAIL_PASSWORD=<переменная_пароля>null

MAIL_ENCRYPTION=<переменная_типа_шифрования>null

MAIL_FROM_ADDRESS=<переменная_адреса_отправителя>"hello@example.com"

MAIL_FROMNAME=<переменная_имени_отправителя>"${APPNAME}"

AWS_ACCESS_KEY_ID=<переменная_идентификатора_ключа_доступаAWS>

AWS_SECRET_ACCESS_KEY=<переменная_секретного_ключа_доступаAWS>

AWS_DEFAULT_REGION=<переменная_региона_AWS_по_умолчанию>us-east-1

AWS_BUCKET=<переменнаяименикорзиныAWSS3>

AWS_USE_PATH_STYLE_ENDPOINT=<>false

VITE_APP_NAME=<переменная_названия_приложения>"${APPNAME}"



