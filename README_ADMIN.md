# Dokumentasi Panel Admin

## Gambaran Umum
Panel Admin adalah antarmuka khusus untuk mengelola parameter inti sistem absensi berbasis QR dalam aplikasi Laravel ini. Ini memungkinkan administrator untuk mengonfigurasi pengaturan pemindaian seperti radius geolokasi, waktu tunggu, opsi akurasi, dan periode cooldown. Pengaturan ini secara dinamis memengaruhi fungsi pemindaian siswa tanpa memerlukan perubahan kode atau restart server.

Panel ini dibangun menggunakan sistem autentikasi Laravel dengan guard 'admin' khusus, rute terlindungi, dan tabel pengaturan key-value sederhana untuk persistensi.

## Petunjuk Akses
1. **URL**: Buka `http://localhost:8000/admin/login` (dengan asumsi server Laravel berjalan di port 8000).
2. **Kredensial**:
   - **Nama Pengguna/Nama**: `admin`
   - **Kata Sandi**: `admin123`
3. **Masuk**: Masukkan kredensial dan kirim formulir. Setelah login berhasil, Anda akan diarahkan ke dashboard admin (`/admin/dashboard`).
4. **Keluar**: Klik tombol "Logout" di dashboard untuk mengakhiri sesi.

**Catatan**: Saat ini, hashing kata sandi dinonaktifkan sementara untuk akses yang lebih mudah (validasi teks biasa). Ini tidak aman dan harus diaktifkan kembali untuk produksi (lihat bagian "Mengaktifkan Kembali Keamanan" di bawah).

## Fitur
Dashboard menyediakan formulir untuk melihat dan memperbarui parameter yang dapat dikonfigurasi berikut:

- **Radius (meter)**: Jarak maksimum yang diizinkan untuk siswa dianggap "hadir" selama pemindaian QR (default: 50m). Menggunakan rumus Haversine untuk perhitungan jarak geolokasi.
- **Waktu Tunggu Geolokasi (ms)**: Batas waktu untuk mendapatkan lokasi siswa (default: 10000ms atau 10 detik).
- **Umur Maksimum (ms)**: Umur maksimum data lokasi yang di-cache (default: 0ms, artinya selalu ambil lokasi segar).
- **Aktifkan Akurasi Tinggi**: Toggle untuk GPS presisi tinggi (default: true). Meningkatkan akurasi tetapi mungkin mengonsumsi lebih banyak baterai.
- **Cooldown Pemindaian (detik)**: Waktu minimum antara pemindaian berturut-turut oleh siswa yang sama (default: 10s) untuk mencegah penyalahgunaan.

### Memperbarui Pengaturan
1. Masuk ke dashboard.
2. Ubah nilai di bidang formulir.
3. Klik "Simpan Perubahan".
4. Pembaruan divalidasi (misalnya, radius harus numerik positif) dan disimpan ke database segera.
5. Perubahan berlaku untuk semua pemindaian selanjutnya tanpa restart server.

### Contoh Penggunaan
- Atur radius menjadi 100m untuk ruang kelas yang lebih besar.
- Kurangi cooldown menjadi 5s untuk pengujian lebih cepat.
- Nonaktifkan akurasi tinggi untuk performa lebih baik pada perangkat low-end.

## Detail Teknis
- **Rute** (di `routes/web.php`):
  - `GET /admin/login` → Formulir login admin.
  - `POST /admin/login` → Penanganan autentikasi.
  - `GET /admin/dashboard` → Dashboard dengan formulir pengaturan (middleware: 'admin').
  - `POST /admin/settings` → Perbarui pengaturan.
  - `POST /admin/logout` → Keluar.
- **Controller**:
  - `AdminAuthController`: Menangani login, logout, dan manajemen sesi.
  - `AdminController`: Mengambil/menampilkan pengaturan dan memproses pembaruan.
- **Model**:
  - `User`: Diperluas dengan enum 'role' termasuk 'admin'.
  - `Setting`: Model key-value untuk parameter (misalnya, key: 'radius', value: '50').
- **View**:
  - `admin/login.blade.php`: Formulir login sederhana.
  - `admin/dashboard.blade.php`: Formulir berbasis Bootstrap untuk pengaturan.
- **Middleware**: Middleware 'admin' khusus memeriksa peran 'admin'.
- **Database**:
  - Tabel `users`: Pengguna admin di-seed dengan peran 'admin'.
  - Tabel `settings`: Menyimpan parameter sebagai nilai JSON atau string.
- **Integrasi**: Pengaturan diambil di `ScanController` dan diteruskan ke JS frontend untuk logika geolokasi dan validasi.

## Seeding dan Pengaturan
Untuk membuat ulang pengguna admin dan pengaturan default:
```
php artisan db:seed --class=AdminSettingsSeeder
```
Ini menghapus pengguna 'admin' yang ada dan membuat yang baru dengan kata sandi teks biasa, plus seeding default.

Seeding database lengkap (termasuk guru, siswa, dll.):
```
php artisan db:seed
```

## Pertimbangan Keamanan (Pengaturan Sementara)
- **Status Saat Ini**: Penyimpanan dan validasi kata sandi teks biasa (tanpa hashing). Ini hanya untuk pengembangan/pengujian.
- **Mengaktifkan Kembali Hashing** (Direkomendasikan untuk Produksi):
  1. Edit `database/seeders/AdminSettingsSeeder.php`:
     - Ubah kata sandi menjadi `bcrypt('admin123')`.
     - Hapus langkah delete jika tidak diperlukan.
  2. Edit `app/Http/Controllers/Auth/AdminAuthController.php` (metode login):
     - Ganti pemeriksaan teks biasa (`$user->password === $credentials['password']`) dengan `Auth::guard('admin')->attempt(['name' => $credentials['name'], 'password' => $credentials['password']])`.
     - Hapus login manual.
  3. Seeding ulang: `php artisan db:seed --class=AdminSettingsSeeder`.
  4. Perbarui `config/auth.php` jika diperlukan untuk driver hashing kata sandi (default: bcrypt).
  5. Uji login untuk mengonfirmasi hashing berfungsi.

- **Praktik Terbaik**:
  - Gunakan kata sandi yang kuat dan unik.
  - Aktifkan HTTPS di produksi.
  - Tambahkan pembatasan laju pada upaya login.
  - Pertimbangkan autentikasi dua faktor untuk admin.

## Pemecahan Masalah
- **Login Gagal**: Pastikan server berjalan (`php artisan serve`), database di-seed, dan kredensial tepat (case-sensitive).
- **Pengaturan Tidak Berlaku**: Bersihkan cache (`php artisan cache:clear`) dan periksa `storage/logs/laravel.log` untuk kesalahan.
- **Izin Ditolak**: Verifikasi middleware di rute; peran admin harus 'admin'.
- **Kesalahan Konsol**: Periksa alat pengembang browser; masalah umum: token CSRF hilang atau konflik JS.

Untuk kustomisasi lebih lanjut atau masalah, rujuk README proyek utama atau dokumentasi Laravel.

**Terakhir Diperbarui**: [Tanggal Saat Ini]
