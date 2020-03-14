# micromiddleware
Micromiddleware untuk Laravel Microservice Alter Indonesia

## Installasi
```bash
composer require 
```

## Penggunaan
* Pada file app/Http/Kernel.php pada bagian $routeMiddleware tambahkan
```bash
'auth.micro'    => \Febrianrz\Micromidlleware\MicroAuthenticate::class,
```
* Pada bagian api.php, gunakan middleware 
```bash
Route::middleware('auth.micro')
```
* Pada setiap request, data user dapat digunakan dengan cara
```bash
$request->user
```