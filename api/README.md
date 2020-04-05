# README

## Description
This is where we keep the code for the `API` of the **YBANK** project

## Get started

1. Clone the repository

2. Create a local environment for the project (using Docker/WAMPP/XAMPP etc.)

3. Copy `.env.example` file content in a new file named `.env`

4. Run `composer install`

5. Run `php artisan key:generate`

6. Create a database named `ybank` in your local DB client (phpmyadmin/MySQL Workbench etc.)

7. Run `php artisan migrate --seed`

8. The API should now be up and running and you can test it via Postman or something similar
