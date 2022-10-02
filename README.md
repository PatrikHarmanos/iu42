## Web application for listing cities (Ui42 candidate assignment)

### Instructions

1. Clone this project to your computer (git clone)
2. Go to the project folder in your terminal (cd project_name)
3. run commnad (composer install)
4. create file .env and copy content from .env.example to it
5. in .env file change database connection for your database (DB_ connection, host, port, database, username, password)
6. run commnad for generating app key (php artisan key:generate)
7. run migrations (php artisan migrate)
8. run command to scrape data about cities (php artisan data:import)
9. run commnad to geocode data (php artisan data:geocode)
10. run application (php artisan serve)
