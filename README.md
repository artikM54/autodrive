# autodrive

## Запуск парсера

- подключить БД в .env
- выполнить команду "php artisan migrate"
- разместить файл xml на локальной машине
- запустить команду "php artisan parse:offers 'путь к файлу'" (или без параметра пути, расположив файл по дефолтному пути в storage "app\public\data.xml")
