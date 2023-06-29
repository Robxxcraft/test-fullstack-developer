## Test fullstack developer

Beberapa aplikasi yang harus diinstal:

- Composer.
- Xampp.
- Node js & NPM.

### 1. Jalankan perintah dibawah ini untuk menyalin project

```sh
git clone https://github.com/Robxxcraft/test-fullstack-developer
```
<br>

### 2. Masuk ke dalam project yang telah disalin

```sh
cd test-fullstack-developer
```
<br />


### 3. Install package composer

```sh
composer install
```
<br />

### 4. Install package npm

```sh
npm install
```
<br />

### 5. Buat database baru dengan nama test_fullstack

```sh
> mysql -u root -p
> create database test_fullstack;
```
<br />

### 6. Migrate table yang sudah dibuat

```sh
php artisan migrate
```
<br />

### 7. Buat data buatan dari seeder

```sh
php artisan db:seed
```

### 8. Jalankan dua perintah ini terminal yang berbeda

```sh
1. npm run dev
2. php artisan serve
```
<br />