<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Laravel basecode (v8.62.0)

#### Server Requirements
* PHP 8.x
* Apache 2.x
* Mysql 8.x
* Composer 2.x

#### Installation steps

##### 1. Install packages
```sh
$ composer install
```
##### 2. Copy .env.example and create .env file
```sh
$ sudo cp .env.example .env
```
##### 3. Update database connection in .env
##### 4. Create tables using laravel migration
```sh
$ php artisan migrate
```
##### 5. Run seeders for initial database entries.
```sh
$ php artisan db:seed
```
##### 6. Generate key
```sh
$ php artisan key:generate
```
##### 7. Change storage directory permission
```sh
$ sudo chmod -R guo+w storage
```
##### 8. Create symbolic link of storage/app/public directory
```sh
$ php artisan storage:link
```
##### 9. Check in browser: http://localhost/basecode_laravel/code/public
##### 10. Backend Url: http://localhost/basecode_laravel/code/public/backend
**Backend login credentials:** `admin@basecode.com / 12345678`
##### 11. Change the backend credentials before start the development.
##### 12. Generate swagger document
```sh
$ php artisan l5-swagger:generate
```
##### Swagger Url: http://localhost/basecode_laravel/code/public/api/documentation
=======
# PORA_DatingApp
Backend_API
>>>>>>> 4f186bfebe67fcc4079b8808118bf5aa92429978
