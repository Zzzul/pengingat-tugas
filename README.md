

# PENGINGAT-TUGAS
Buat pengingat kalo ada tugas kuliah beserta deadline nya. sebenernya inget cuma males ngerjainnya...

## Demo
<a  href="https://pengingat-tugas.herokuapp.com/" target="blank">Here</a>

## What inside?
- Laravel ^8.x - [Laravel 8](https://laravel.com/docs/8.x)
- Laravel UI ^3.x - [Laravel/ui](https://github.com/laravel/ui/tree/3.x)
-   Livewire ^2.x - [laravel-livewire.com](https://laravel-livewire.com)

## Installation
Clone or download this repository
```shell
$ git clone https://github.com/Zzzul/pengingat-tugas.git
```

Install all dependencies
```shell
# install composer dependency
$ composer install

# install npm packages
$ npm install

# build dev 
$ npm run dev
```

Generate app key, configure `.env` file and do migration.
```shell
# create copy of .env
$ cp .env.example .env

# create laravel key
$ php artisan key:generate

# laravel migrate
$ php artisan migrate

# Start local development server
$ php artisan serve
```
