# OrangeToolz

### Quickstart

### Used

```sh
$ - PHP >= 7.4
$ - Laravel >= 8.0
$ - MySql
$ - Jquery
$ - Ajax
```

### 1. Clone Repository

```sh
$ git clone https://github.com/minarkhan/OrangeToolz.git
```

### 2. BackEnd Installation

```sh
$ cd OrangeToolz
$ composer install
or
$ composer update
```

### 3. Environment Setup

```sh
$ clone .env.example as .env
$ DB_DATABASE=(db_name)
```

### 4. Run BackEnd

```sh
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan storage:link
$ php artisan serve
$ Open http://localhost:8000 as project URL
```
### 5. API Installation

```sh
$ composer require laravel/passport
$ php artisan migrate
$ php artisan passport:install
$ php artisan passport:client --personal
$ Open http://localhost:8000/api as API Base URL
```

### FrontEnd

```sh
$ URL http://localhost:8000
$ Laravel Blade
$ Bootstrap
$ Third Party Plugins like Toastr, Sweet Alert Etc.
```

### Features of Project

- Login System
- Registration System
- Admin Panel (Dashboard)
- User Management
- Profile Management
- To-Do List and
- Tasks Management

```sh
$ Login System: http://localhost:8000/login
$ Registration System: http://localhost:8000/register
$ Admin Panel: http://localhost:8000/dashboard
$ User Management: http://localhost:8000/user-management
$ My profile Management: http://localhost:8000/my-profile
$ TODO List and Tasks Management: http://localhost:8000/todo
```

### Note : Clear Cache And Storage Link

```sh
$ php artisan optimize:clear
$ php artisan storage:link
```

### Usage

```sh
$ Go to http://localhost:8000/login
$ User: admin@gmail.com
$ Password: 12345678
```

### Usage API

```sh
$ Login API http://localhost:8000/api/login
$ {"email":"admin@gmail.com","password":"12345678"} //Json Formate
```

### Happy Coding...
### Thank You
# areducation
