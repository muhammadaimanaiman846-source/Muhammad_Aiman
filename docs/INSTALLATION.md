# EcoBank – Bank Sampah Digital
PHP Native + MySQL (PDO) + Bootstrap 5. Butuh PHP 8.0+ (ekstensi pdo_mysql, aktif di XAMPP) dan koneksi internet (Bootstrap/Chart.js via CDN).
## Instalasi
1. Ekstrak `ecobank.zip`, pindahkan folder `ecobank` ke `C:\xampp\htdocs\`.
2. Jalankan Apache & MySQL di XAMPP.
3. Buka http://localhost/phpmyadmin → Import → pilih `database/ecobank_db.sql`.
4. Buka http://localhost/ecobank/install.php (membuat akun demo), lalu hapus `install.php`.
5. Buka http://localhost/ecobank/ . Jika password MySQL bukan kosong, ubah `config/database.php`.
## Akun demo (password: Password123)
admin@ecobank.test · petugas@ecobank.test · warga@ecobank.test
## Uji alur utama
Warga ajukan setoran → Petugas/Admin verifikasi (saldo bertambah) → Warga ajukan penarikan → Admin setujui.
## Belum tersedia
Artikel, kegiatan, jadwal, peta Leaflet, lupa password, upload gambar, laporan lanjutan.
