# Aplikasi Barang Masuk & Keluar

Features
-------------
- Login
- Barang
- Category Barang
- Barang Masuk
- Barang Keluar
- Laporan Stock Barang
- Laporan Barang Masuk
- Laporan Barang Keluar
-------------

Installation
-------------
`git clone https://github.com/drbarazi/app-importir.git`

`cd importir`

`composer install`

`cp .env.example .env`

`php artisan key:generate`

`php artisan jwt:secret`

`php artisan migrate`

`php artisan db:seed`

-------------

API Documentation
-------------
###Authentication
####Login
```
    [POST] => {base_url}/api/auth/login
    header {
        "Content-Type": "application/json"
    }

    body {
        "email": "admin@gmail.com",
        "password": "password"
    }
```