 ## Порядок установки:

### 1. Клонирование репозитория
   
   ``` git clone https://github.com/justzoch1/Forum.git ```
   

### 2. Запуск Docker контейнеров
   
   ``` docker compose up -d ```
   

#### 3. Вход в контейнер приложения
   
   ``` docker compose exec -it app bash ```
   

#### 4. Запуск миграций
   
   ``` php artisan migrate ```
   

## Запуск сервера:
Откройте браузер и перейдите по адресу: http://localhost:8876/ 

Теперь ваше приложение должно быть готово к использованию)
