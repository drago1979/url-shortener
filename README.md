# URL - shortener

## About the app
Accepts user`s URLs & creates short versions.
  
Web interface: 
* submit "long-url"; 
* preview submitted url`s details (long, short, visited); 

## Version requirements
- PHP – 8.2.4
- DB - 10.4.28-MariaDB
- Laravel – 10.8.0

## Installation

1. Clone the project using the following link:
```
https://github.com/drago1979/url-shortener.git

```
2. Create .env file in project directory and copy content from .env.example file.  Add valid data (DB credentials etc.) to .env.  
3. In your terminal (working folder) run
```
composer install
```  

```
php artisan migrate
```
