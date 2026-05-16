# WB API Importer

Импорт данных из API Wildberries (тестовое задание).

## Доступы к БД (для проверки)

Хост: `mysql-wb-importer.alwaysdata.net`  
База: `wb-importer_test`  
Пользователь: `wb-importer`  
Пароль: `4642542123Qq!`

## Установка

    git clone https://github.com/Afam46/wb-importer.git
    cd wb-importer
    cp .env.example .env
    composer install

    # Отредактируйте файл .env, указав настройки базы данных
    # Хост и ключ для API также указываются в .env

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
