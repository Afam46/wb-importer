# WB API Importer

Импорт данных из API Wildberries

## Установка

    git clone https://github.com/Afam46/wb-importer.git
    cd wb-importer
    cp .env.example .env
    composer install

    # Отредактируйте файл .env, указав настройки базы данных

    DB_CONNECTION=mysql
    DB_HOST=mysql-wb-importer.alwaysdata.net
    DB_PORT=3306
    DB_DATABASE=wb-importer_test 
    DB_USERNAME=wb-importer
    DB_PASSWORD=
    
    # Хост и ключ для API также указываются в .env

    API_HOST=
    API_KEY=

## Чтобы очистить БД

    php artisan migrate:fresh

## Импорт данных

| Команда | Описание |
|---------|----------|
| `php artisan import:sales` | Импорт продаж |
| `php artisan import:orders` | Импорт заказов |
| `php artisan import:stocks` | Импорт складов |
| `php artisan import:incomes` | Импорт доходов |
| `php artisan import:all` | Все импорты подряд |
