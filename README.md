# 🚗 Sistem Parkir Kampus - Smart Campus Parking System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-3.x-yellow.svg)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg)](https://tailwindcss.com)

Sistem Parkir Kampus adalah aplikasi web modern yang dirancang untuk mengelola parkir kendaraan di lingkungan kampus dengan fitur-fitur canggih dan antarmuka yang user-friendly.

## ✨ Fitur Utama

### 🏠 Frontend Features
- **Landing Page Interaktif**: Halaman utama dengan informasi ketersediaan parkir real-time
- **Registrasi Kendaraan**: Form pendaftaran kendaraan baru dengan validasi lengkap
- **Dashboard User**: Antarmuka pengguna untuk memantau status parkir aktif
- **Real-time Parking Status**: Monitoring ketersediaan slot parkir secara real-time
- **Responsive Design**: Tampilan yang optimal di semua perangkat (desktop, tablet, mobile)

### 🚗 Sistem Parkir
- **Multi-Area Parking**: Dukungan untuk berbagai area parkir di kampus
- **Vehicle Management**: Pengelolaan data kendaraan (motor, mobil, dll.)
- **Real-time Slot Tracking**: Pelacakan slot parkir tersedia/terisi secara real-time
- **Automatic Time Calculation**: Perhitungan durasi parkir otomatis
- **Fee Calculation**: Kalkulasi biaya parkir berdasarkan jenis kendaraan dan durasi

### 💳 Sistem Pembayaran
- **Multiple Payment Methods**: 
  - Pembayaran tunai
  - QR Code payment
- **QR Code Generation**: Generasi QR code otomatis untuk pembayaran digital
- **Payment Status Tracking**: Pelacakan status pembayaran real-time
- **Payment Confirmation**: Halaman konfirmasi pembayaran

### 👥 User Management
- **Auto Registration**: Registrasi otomatis saat pertama kali parkir
- **User Authentication**: Sistem login/logout yang aman
- **Profile Management**: Pengelolaan profil pengguna
- **Password Management**: Fitur ganti password dengan validasi keamanan
- **Role-based Access**: Sistem peran pengguna (admin, user)

### 📊 Admin Panel (Filament)
- **Modern Admin Interface**: Panel admin yang powerful menggunakan Filament
- **Dashboard Overview**: Statistik dan overview sistem parkir
- **Transaction Management**: Pengelolaan transaksi parkir
- **Vehicle Management**: CRUD kendaraan terdaftar
- **Area Parking Management**: Pengelolaan area parkir
- **User Management**: Administrasi pengguna sistem
- **Real-time Widgets**: Widget real-time untuk monitoring
- **Parking Capacity Alerts**: Peringatan kapasitas parkir
- **Recent Transactions Table**: Tabel transaksi terbaru
- **Data Export/Import**: Fitur ekspor/impor data

### 📈 Monitoring & Analytics
- **Parking History**: Riwayat parkir lengkap
- **Duration Tracking**: Pelacakan durasi parkir detail
- **Fee Analytics**: Analisis pendapatan parkir
- **Capacity Monitoring**: Monitoring kapasitas real-time
- **Alert System**: Sistem peringatan untuk area hampir penuh

### 🔧 Technical Features
- **Database Relationships**: Relasi database yang terstruktur dengan baik
- **Soft Deletes**: Penghapusan data dengan recovery option
- **Data Validation**: Validasi form yang komprehensif
- **CSRF Protection**: Perlindungan keamanan CSRF
- **Session Management**: Pengelolaan sesi yang aman
- **Error Handling**: Penanganan error yang user-friendly

## 📋 Prasyarat

Sebelum memulai, pastikan Anda memiliki:

- **PHP 8.2+** dengan ekstensi yang diperlukan
- **Composer** untuk dependency management
- **Node.js & NPM** untuk asset compilation
- **Database** (MySQL, PostgreSQL, SQLite)
- **Web Server** (Apache, Nginx, atau Laravel Sail)

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/your-username/sistem-parkir.git
cd sistem-parkir
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_parkir
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Migration & Seeding
```bash
# Run migrations
php artisan migrate

# Seed database dengan data sample
php artisan db:seed
```

### 6. Compile Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📁 Struktur Project

```
sistem-parkir/
├── app/
│   ├── Filament/           # Admin panel resources
│   │   ├── Resources/      # Resource definitions
│   │   └── Widgets/        # Dashboard widgets
│   ├── Http/
│   │   └── Controllers/    # Application controllers
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes (if needed)
└── public/                # Public assets
```

## 🎯 Penggunaan

### Untuk Pengguna (Mahasiswa/Staff)

1. **Registrasi Parkir Pertama**:
   - Kunjungi halaman utama
   - Isi form registrasi kendaraan
   - Pilih area parkir yang tersedia
   - Submit untuk memulai parkir

2. **Login & Dashboard**:
   - Login menggunakan email yang terdaftar
   - Lihat status parkir aktif di dashboard
   - Monitor durasi dan estimasi biaya

3. **Keluar Parkir**:
   - Klik tombol "Keluar Parkir"
   - Pilih metode pembayaran (tunai/QR)
   - Selesaikan pembayaran
   - Lihat konfirmasi pembayaran

### Untuk Admin

1. **Akses Admin Panel**:
   - Kunjungi `/admin`
   - Login dengan akun admin

2. **Pengelolaan Data**:
   - Kelola area parkir
   - Monitor transaksi real-time
   - Lihat statistik penggunaan
   - Kelola data kendaraan dan pengguna

## 🔧 Konfigurasi

### Payment Settings
Untuk mengaktifkan pembayaran QR, sesuaikan konfigurasi di controller:
```php
// ParkingController.php
$qrCallbackUrl = route('payment.qr_success', ['transaction_id' => $transaction->id]);
```

### Admin Panel
Sesuaikan konfigurasi Filament di `AdminPanelProvider.php`:
```php
->brandName('Sistem Parkir Kampus')
->favicon(asset('favicon.ico'))
->colors([
    'primary' => Color::Blue,
])
```

## 🛠️ Development

### Menambah Fitur Baru

1. **Model Baru**:
```bash
php artisan make:model NamaModel -mfs
```

2. **Controller Baru**:
```bash
php artisan make:controller NamaController
```

3. **Filament Resource**:
```bash
php artisan make:filament-resource NamaResource
```

### Testing
```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## 📱 API Documentation

Sistem ini menyediakan beberapa endpoint API untuk integrasi:

- `GET /api/parking-areas` - Daftar area parkir
- `GET /api/available-slots/{area_id}` - Slot tersedia per area
- `POST /api/parking/start` - Mulai parkir
- `POST /api/parking/end` - Selesai parkir

## 🔒 Keamanan

- **CSRF Protection**: Semua form dilindungi CSRF token
- **SQL Injection Prevention**: Menggunakan Eloquent ORM
- **XSS Protection**: Input sanitization otomatis
- **Authentication**: Sistem autentikasi Laravel built-in
- **Session Security**: Konfigurasi sesi yang aman

## 🤝 Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

## 👥 Tim Pengembang

- **Developer**: [Your Name]
- **UI/UX Designer**: [Designer Name]
- **Project Manager**: [PM Name]

## 📞 Support

Jika Anda mengalami masalah atau membutuhkan bantuan:

- 📧 Email: parkir@ui.ac.id
- 📱 WhatsApp: +62-xxx-xxxx-xxxx
- 🐛 Issue Tracker: [GitHub Issues](https://github.com/your-username/sistem-parkir/issues)

## 🎉 Acknowledgments

- Laravel Framework
- Filament Admin Panel
- Tailwind CSS
- Font Awesome Icons
- Dan semua kontributor open source

---

**© 2025 Sistem Parkir Kampus - Universitas Indonesia**
