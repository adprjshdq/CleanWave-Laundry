# 🧺 CleanWave — Sistem Laundry Online Terintegrasi

[![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PHPUnit](https://img.shields.io/badge/Tests-100%25%20Passed-brightgreen?style=for-the-badge&logo=phpunit&logoColor=white)](https://phpunit.de)

> **Proyek Portofolio Full-Stack Web Application**: Sistem pemesanan dan pelacakan cucian online end-to-end yang mengintegrasikan layanan laundry kiloan, laundry satuan, pelacakan proses real-time, verifikasi pembayaran digital, serta panel operasional administrator lengkap.

---

## 🌟 Demo Akun Bawaan (Database Seeder)

| Role | Email | Password | Akses & Hak Istimewa |
|---|---|---|---|
| **Super Admin** | `admin@cleanwave.com` | `password` | Kelola semua order, verifikasi bukti transfer, CRUD tarif layanan |
| **Petugas Laundry** | `budi@cleanwave.com` | `password` | Penanggung jawab pencucian & pengantaran kurir |
| **Pelanggan Demo** | `customer@cleanwave.com` | `password` | Pemesanan, pelacakan real-time, riwayat, ulasan kepuasan |

---

## 🚀 Fitur Utama

### 👤 Portal Pelanggan (Customer)
1. **Landing Page Modern**: Hero banner interaktif, kalkulator tarif, jaminan mutu higienis, dan ulasan kepuasan.
2. **Katalog Layanan Interaktif**: Pemisahan kategori (Kiloan, Satuan/Khusus, Perlengkapan Rumah).
3. **Form Pemesanan Multi-Step**:
   - Pemilihan layanan jamak dengan kuantitas dinamis.
   - Pilihan metode antar-jemput (Antar-Jemput Kurir / Drop-Off Outlet).
   - Input/pilihan alamat pengiriman terpadu.
   - Penggunaan kupon diskon (`CLEANWAVE10`, `HEMAT15K`) dengan kalkulasi subtotal instan.
   - Pilihan metode bayar (QRIS, Transfer Bank, Tunai saat pengantaran).
4. **Live Status Tracking (Real-Time)**:
   - Pelacakan berdasarkan kode pesanan (contoh: `CW-20261010-0042`).
   - Indikator progres visual 7 tahap dari penjemputan hingga selesai.
5. **Unggah Bukti Bayar**: Form upload foto resi/transfer dengan status verifikasi.
6. **Ulasan & Penilaian (Rating 1-5 Bintang)**: Pelanggan dapat memberikan rating dan masukan setelah pesanan berstatus selesai.

### 🛡️ Panel Administrator
1. **Dashboard Metrik KPI**: Total pesanan, pesanan sedang dicuci, akumulasi omset pendapatan, dan antrean verifikasi transfer.
2. **Manajemen Pesanan Lengkap**: Filter berdasarkan status, pencarian nomor HP/nama, pembaruan status pengerjaan, dan penugasan kurir.
3. **Katalog & Tarif Layanan (CRUD)**: Tambah layanan baru, atur estimasi hari, harga satuan per kg/pcs.
4. **Verifikasi Pembayaran**: Tinjau lampiran bukti foto transfer pelanggan dan satu klik verifikasi lunas.

---

## 🗄️ Arsitektur & Skema Database

```
users (id, name, email, password, role, phone, avatar)
  ├── customers (id, user_id, loyalty_points, membership_level)
  │     ├── addresses (id, customer_id, label, full_address, city)
  │     └── orders (id, customer_id, employee_id, address_id, coupon_id, order_code, status, subtotal, discount, delivery_fee, total)
  │           ├── order_items (id, order_id, service_id, quantity, price_per_unit, subtotal)
  │           ├── payments (id, order_id, payment_code, method, status, amount, proof_image, paid_at)
  │           └── reviews (id, order_id, customer_id, rating, comment)
  ├── employees (id, user_id, position, shift, is_available)
  ├── service_categories (id, name, icon, description)
  │     └── services (id, category_id, name, unit, price_per_unit, estimated_days)
  └── coupons (id, code, discount_type, discount_value, min_order, expired_at)
```

---

## 💻 Panduan Instalasi Lokal

### 1. Clone Repositori
```bash
git clone https://github.com/your-username/cleanwave-laundry.git
cd cleanwave-laundry
```

### 2. Instalasi Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

Ubah konfigurasi database di `.env` (Gunakan MySQL atau SQLite):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cleanwave_laundry
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Data Demo
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Jalankan Server Lokal
```bash
# Terminal 1: Vite Asset Compiler
npm run dev

# Terminal 2: PHP Development Server
php artisan serve
```

Akses aplikasi di browser: **[http://localhost:8000](http://localhost:8000)**

---

## 🧪 Menjalankan Automated Tests

Semua fitur inti dilengkapi dengan automated testing PHPUnit:
```bash
php artisan test
```

Hasil test:
```text
PASS  Tests\Feature\CleanWaveLaundryTest
✓ public pages load successfully
✓ tracking returns order details
✓ customer can create laundry order
✓ customer cannot access admin panel
✓ admin can access dashboard

Tests:    5 passed (13 assertions)
Duration: 1.94s
```

---

## 📦 Deployment ke Production (VPS / Nginx)

1. **Build asset produksi**:
   ```bash
   npm run build
   ```
2. **Optimasi cache Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **Konfigurasi Nginx Server Block**:
   ```nginx
   server {
       listen 80;
       server_name cleanwave.domainanda.com;
       root /var/www/cleanwave-laundry/public;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
       }
   }
   ```

---

*CleanWave Laundry dirancang dan dibangun dengan standar arsitektur bersih, aman dari celah CSRF/SQL Injection, dan responsif di seluruh perangkat mobile & desktop.*
