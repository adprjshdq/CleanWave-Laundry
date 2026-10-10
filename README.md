# 🧺 CleanWave — Sistem Laundry Online Full-Stack

Proyek ini telah dipisahkan secara rapi menjadi 2 folder utama: **`frontend`** dan **`backend`**:

```
Sistem Laundry/
├── 🌐 frontend/              # Client-Side (HTML5, CSS3, JavaScript ES6)
│   ├── index.html           # Halaman Utama / Landing Page
│   ├── layanan.html         # Katalog Tarif & Jenis Layanan
│   ├── order.html           # Form Pemesanan & Kalkulator Subtotal Real-Time
│   ├── lacak.html           # Live Status Tracking Pesanan (Visual Timeline)
│   ├── css/
│   │   └── style.css        # CleanWave Design System & Komponen UI
│   └── js/
│       └── app.js           # Logika interaktif & kalkulasi kupon diskon
│
└── ⚙️ backend/               # Server-Side & Database (Laravel, PHP, MySQL)
    ├── app/                 # Controllers, Models, Services, Middleware
    ├── database/            # 10 File Migrasi & Database Seeder Lengkap
    ├── routes/              # Web & API Routes
    ├── resources/views/     # Admin Panel & Blade Templates
    ├── tests/               # Automated Feature Tests (PHPUnit 100% Passed)
    └── README.md            # Dokumentasi teknis backend lengkap
```

---

## 🚀 Cara Menjalankan

### 1. Menjalankan Frontend
Cukup buka salah satu file berikut langsung di browser (atau gunakan ekstensi Live Server):
- `frontend/index.html` (Landing Page)
- `frontend/order.html` (Form Order)
- `frontend/lacak.html` (Tracking Order)

### 2. Menjalankan Backend Laravel
```bash
cd backend
php artisan serve
```
Akses server di: **[http://localhost:8000](http://localhost:8000)**

#### Akun Demo Bawaan:
- **Admin**: `admin@cleanwave.com` | Password: `password`
- **Pelanggan**: `customer@cleanwave.com` | Password: `password`
- **Petugas**: `budi@cleanwave.com` | Password: `password`
