# pharmacy_management
## Installing Laravel 9 Passport from GitHub

1. Clone the repository:
```
git clone https://github.com/morhafdr/pharmacy_m.git
```


2. Navigate to the project directory:
```
cd pharmacy_m
```
3. Install dependencies:
```
composer install
```

4. Create a `.env` file:


```
cp .env.example .env
```

5. Generate the application key:


```
php artisan key:generate
```
6. make database connection
7. Run database migrations:


```
php artisan migrate
```

8. Install Passport:


```
php artisan passport:install
```

9. Start the server:


```
php artisan serve
```

That's it! You now have a Laravel 9 Passport project installed from GitHub and ready to use.

