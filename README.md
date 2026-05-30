# 🏫 Sistem Pendukung Keputusan (SPK) Evaluasi Siswa PAUD - Metode SAW

Aplikasi Sistem Pendukung Keputusan (SPK) berbasis web untuk melakukan evaluasi tingkat perkembangan anak usia dini di **PAUD Betlehem Tebedak**. Sistem ini dikembangkan menggunakan **CodeIgniter 3** dengan arsitektur **Clean Model Layer** dan menggunakan database **SQLite3** untuk performa pembacaan data yang instan, ringan, dan efisien.

Perhitungan perangkingan akhir semester dilakukan secara matematis menggunakan metode **Simple Additive Weighting (SAW)** berdasarkan kriteria penilaian perkembangan anak.

---

## 🛠️ Prasyarat Sistem (Prerequisites)

Sebelum memulai instalasi, pastikan perangkat lokal Anda telah memenuhi spesifikasi berikut:
* **PHP:** Versi `7.4` (Sangat direkomendasikan PHP 7.4 untuk stabilitas CI3).
* **PHP Extensions:** `php-sqlite3`, `php-gd`, `php-mbstring`, `php-xml`.
* **Dependency Manager:** [Composer](https://getcomposer.org/) (Sudah terinstal secara global).
* **Web Server:** Apache (XAMPP / MAMP) atau native PHP CLI server.

---

## 📥 Panduan Instalasi Lengkap

Ikuti urutan instruksi di bawah ini secara berurutan untuk memasang aplikasi di lingkungan lokal Anda:

### 1. Kloning Repositori
Buka terminal jalankan perintah berikut untuk mengunduh proyek ke perangkat Anda:
```bash
git clone https://github.com/username/saw-paud-ci3.git
cd saw-paud-ci3
```

### 2. Instalasi Dependensi Vendor (Composer)

Sistem menggunakan Dompdf untuk menangani konversi string HTML menjadi berkas cetak PDF. Pasang semua pustaka yang dibutuhkan melalui Composer:

```bash
composer install
```

### 3. Konfigurasi File Database SQLite3

Pastikan berkas file database lokal `.db` diletakkan di dalam folder `application/database/`.

Copy file base.db ke database.db
```bash
cp database/database.base.db database/database.db
```

Buka file `application/config/database.php` dan pastikan kodenya mengarah ke berkas lokal Anda (kunci `hostname` **wajib dikosongkan**):

```php
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'          => '',
    'hostname'     => '', // WAJIB KOSONG UNTUK SQLITE3
    'username'     => '', // Kosongkan untuk SQLite3
    'password'     => '', // Kosongkan untuk SQLite3
    'database'     => APPPATH . 'database/database.db', // Jalur file utama di sini
    'dbdriver'     => 'sqlite3',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE
);

```

### 4. Konfigurasi Base URL Applications

Buka file `application/config/config.php`, sesuaikan parameter `base_url` dengan alamat server lokal Anda:

```php
$config['base_url'] = 'http://localhost/saw-paud-ci3/';
// Atau jika menggunakan PHP CLI Server:
// $config['base_url'] = 'http://localhost:8000/';

```

### 5. Jalankan Web Server

Anda dapat memindahkan folder ini ke direktori web server Anda (`htdocs` / `www`) atau langsung menjalankannya secara native via PHP CLI di folder root proyek:

```bash
php -S localhost:8000

```

Buka browser Anda dan akses alamat `http://localhost:8000`.

---

## 🔐 Akun Akses Default (Development)

Gunakan kredensial berikut untuk masuk pertama kali ke dalam sistem dashboard evaluasi pada mode pengembangan:

* **URL Login:** `http://localhost:8000/auth`
* **Username:** `admin`
* **Password:** `password`

---

## 📂 Struktur Arsitektur Kode (Clean Model Approach)

Proyek ini bermigrasi dari pola gemuk di Service Layer ke pendekatan **Clean Controller - Rich Model** demi efisiensi query database SQLite3 lokal:

```text
saw-paud-ci3/
├── application/
│   ├── config/             # Berkas konfigurasi core aplikasi (Database, Routes, Config)
│   ├── core/
│   │   └── MY_Controller.php # Master session guard untuk otomatisasi proteksi login
│   ├── controllers/        # Tipis (Thin) - Hanya mengontrol request & response flow
│   │   ├── Dashboard.php
│   │   ├── Evaluation.php
│   │   ├── Ranking.php
│   │   ├── Student.php
│   │   └── Teacher.php
│   ├── models/             # Tebal (Fat) - Tempat core business logic, query, & hitungan SAW
│   │   ├── M_class.php
│   │   ├── M_criteria.php
│   │   ├── M_evaluation.php
│   │   ├── M_saw.php       # Core Matematika Algoritma SAW
│   │   └── M_teacher.php
│   ├── libraries/
│   │   └── Export_pdf.php  # Custom wrapper driver Dompdf untuk streaming RAM ke file PDF
│   └── views/              # UI Render Layer (TailwindCSS + Material Symbols)
│       ├── components/     # Reusable partials (Sidebar, Alerts, PDF Templates)
│       ├── layouts/        # Struktur global layout (Header & Footer)
│       └── pages/          # Content views masing-masing modul utama
├── vendor/                 # Pustaka third-party otomatis dikelola oleh Composer (Ignored)
└── .gitignore              # Proteksi Git track dari file database lokal, vendor, & log sampah

```