 ## Порядок установки:

### 1. Клонирование репозитория
   
   ``` git clone https://github.com/justzoch1/Forum.git ```

### 2. Запуск Docker контейнеров
   
   ``` docker compose up -d ```
   
#### 3. Запуск npm
   
   ``` npm run dev ```
   
#### 4. Вход в контейнер приложения( все следующие комманды должны выполняться из этого контейнера)
   
   ``` docker compose exec -it app bash ```
   
#### 5. Генерация swagger ui
   
   ``` php artisan l5-swagger:generate ```
   
#### 6. Запуск миграций
   
   ``` php artisan migrate ```
   

## Запуск сервера:

Откройте браузер и перейдите по адресу: http://localhost:8876/

Swagger доступен по адрессу http://localhost:8876/api/swagger

Теперь ваше приложение должно быть готово к использованию)
