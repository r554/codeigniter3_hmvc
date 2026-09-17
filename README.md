# CodeIgniter 3 HMVC Application

Aplikasi administrasi berbasis **CodeIgniter 3 HMVC** dengan AdminLTE.
Aplikasi menyediakan manajemen pengguna, grup dan hak akses, master data,
laporan, backup, activity log, konfigurasi email/storage, serta installer
first-run.

## Daftar Isi

- [Fitur](#fitur)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi Cepat dengan XAMPP](#instalasi-cepat-dengan-xampp)
- [Instalasi melalui Wizard](#instalasi-melalui-wizard)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Login](#login)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Backup dan Restore](#backup-dan-restore)
- [Struktur Direktori](#struktur-direktori)
- [Troubleshooting](#troubleshooting)
- [Keamanan Produksi](#keamanan-produksi)

## Fitur

- Login dan logout pengguna.
- Manajemen user dan profil.
- Manajemen grup pengguna.
- Hak akses per menu: lihat, tambah, ubah, dan hapus.
- Manajemen menu dinamis dan icon menu.
- Dashboard AdminLTE.
- Master data perusahaan, kontak, cabang, department, folder, brand,
  karyawan, SOP, slider, dan form checklist.
- Laporan dan filter data.
- Activity log untuk mencatat aktivitas penting.
- Backup database, file, dan full backup.
- Restore database dan file.
- Penyimpanan lokal atau S3-compatible storage seperti AWS S3, Cloudflare R2,
  MinIO, dan DigitalOcean Spaces.
- Konfigurasi email SMTP.
- Jadwal backup melalui pseudo-cron.
- Installer first-run dengan pemeriksaan kebutuhan sistem.

## Kebutuhan Sistem

Minimum yang disarankan:

| Komponen      | Minimum                                                            |
| ------------- | ------------------------------------------------------------------ |
| PHP           | 7.0 atau lebih baru                                                |
| Web server    | Apache 2.4+ atau Nginx                                             |
| Database      | MySQL 5.7+ atau MariaDB 10+                                        |
| PHP extension | `mysqli`, `mbstring`, `openssl`, `json`, `curl`, `fileinfo`, `zip` |
| Browser       | Chrome, Edge, Firefox, atau Safari versi terbaru                   |
| Storage       | Ruang kosong sesuai ukuran database dan file upload                |

Installer otomatis mengecek PHP, extension, dan permission folder sebelum
instalasi dapat dijalankan.

## Instalasi Cepat dengan XAMPP

1. Install XAMPP yang memiliki PHP 7 atau lebih baru.
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Salin folder project ke:

   ```text
   C:\xampp\htdocs\codeigniter3_hmvc
   ```

4. Pastikan folder berikut dapat ditulis oleh web server:

   ```text
   apps/config
   apps/logs
   upload
   backups
   ```

5. Buka browser dan akses:

   ```text
   http://localhost/codeigniter3_hmvc/install
   ```

6. Ikuti wizard instalasi sampai selesai.

> Jika project ditempatkan di folder lain, sesuaikan URL dengan nama folder
> project tersebut.

## Instalasi melalui Wizard

### 1. Pemeriksaan kebutuhan sistem

Halaman installer menampilkan status setiap kebutuhan. Semua item wajib berstatus
lulus, terutama PHP, extension database, dan permission folder.

Jika extension gagal, buka file `php.ini`, aktifkan extension yang diperlukan,
lalu restart Apache.

### 2. Pengaturan database

Isi form berikut:

- **Host**: biasanya `localhost`.
- **Username**: username MySQL, default XAMPP biasanya `root`.
- **Password**: password MySQL.
- **Nama database**: database yang akan digunakan aplikasi, misalnya
  `coatesapp`.

Klik **Test koneksi** sebelum melanjutkan.

> Wizard harus menggunakan database kosong atau database yang memang disiapkan
> untuk aplikasi ini. Selalu lakukan backup jika database sudah berisi data.

### 3. Akun administrator

Isi nama, email, username, dan password administrator. Password minimal 6
karakter. Akun akan dibuat dengan grup `Administrator` dan status aktif.

### 4. Menjalankan instalasi

Klik **Install sekarang**. Wizard akan:

1. Membuat tabel inti aplikasi.
2. Membuat status grup.
3. Membuat grup Administrator.
4. Membuat menu dasar.
5. Memberikan seluruh akses menu kepada Administrator.
6. Membuat akun administrator.
7. Membuat file `apps/config/install.lock`.

Setelah berhasil, buka halaman login menggunakan akun yang dibuat.

### 5. Menjalankan installer ulang

Installer otomatis terkunci setelah instalasi berhasil. Akses berikut akan ditolak:

```text
http://localhost/codeigniter3_hmvc/install
```

Jangan menghapus `apps/config/install.lock` pada server produksi. Jika perlu
mengulang instalasi di lingkungan development, hapus lock hanya setelah
memastikan database aman dan konfigurasi siap.

## Konfigurasi Environment

Salin atau edit file `.env` pada root project. Contoh konfigurasi aplikasi:

```dotenv
APP_NAME=AdminBro
APP_SKIN=skin-blue
APP_FAVICON=assets/tambahan/gambar/44521256.png
APP_LOGO=assets/tambahan/gambar/logo.png
APP_FIXED_LAYOUT=0
APP_SIDEBAR_COLLAPSE=0
APP_BOXED_LAYOUT=0
```

### Email SMTP

```dotenv
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=akun@example.com
MAIL_PASSWORD=password-smtp
MAIL_FROM_EMAIL=akun@example.com
MAIL_FROM_NAME=Nama Aplikasi
```

### Storage

Untuk penyimpanan lokal:

```dotenv
STORAGE_DRIVER=local
STORAGE_LOCAL_PATH=uploads
```

Untuk storage S3-compatible:

```dotenv
STORAGE_DRIVER=s3
STORAGE_BUCKET=nama-bucket
STORAGE_REGION=us-east-1
STORAGE_KEY=access-key
STORAGE_SECRET=secret-key
STORAGE_ENDPOINT=https://endpoint-storage.example.com
STORAGE_PATH_STYLE=1
```

### Backup cloud dan jadwal

```dotenv
BACKUP_UPLOAD_TO_CLOUD=false
BACKUP_CLOUD_DELETE_LOCAL=false
BACKUP_SCHEDULE_ENABLED=false
BACKUP_SCHEDULE_TYPE=full
BACKUP_SCHEDULE_FREQUENCY=daily
BACKUP_SCHEDULE_TIME=02:00
CRON_SECRET=ganti-dengan-secret-kuat
```

Setelah mengubah `.env`, restart Apache jika konfigurasi belum terbaca.

## Login

URL login default:

```text
http://localhost/codeigniter3_hmvc/login
```

Gunakan username dan password administrator yang dibuat saat wizard instalasi.
Setelah login berhasil, aplikasi mengarahkan pengguna ke dashboard.

## Panduan Penggunaan

### Mengelola user

1. Login sebagai administrator.
2. Buka menu **Setting → User**.
3. Klik **Add Data** untuk membuat user baru.
4. Isi nama, email, username, password, grup, dan status.
5. Simpan data.
6. Gunakan tombol edit atau hapus pada daftar user bila diperlukan.

### Mengelola grup

1. Buka **Setting → User Grup**.
2. Buat grup baru dengan nama dan deskripsi.
3. Klik tombol pengaturan hak akses pada grup.
4. Atur permission `View`, `Add`, `Edit`, dan `Delete` untuk setiap menu.
5. Simpan perubahan.
6. Hubungkan user ke grup dari halaman User.

### Mengelola menu

1. Buka **Setting → Menu**.
2. Tambahkan menu parent atau submenu.
3. Isi nama menu, link, icon, dan urutan.
4. Pastikan link sesuai route aplikasi.
5. Atur hak akses grup setelah menu dibuat.

### Mengelola master data

Menu master digunakan untuk menyimpan data referensi aplikasi, seperti:

- Company dan Contact.
- Employee.
- Department dan Cabang.
- Brand dan Folder.
- SOP.
- Slider.
- Form checklist.

Alur umum setiap modul adalah membuka menu, menekan **Add Data**, mengisi form,
menyimpan, lalu memakai fitur edit atau filter jika tersedia.

### Activity log

Buka **Setting → Activity Log** untuk melihat aktivitas pengguna, waktu,
modul, alamat IP, dan keterangan aktivitas. Gunakan filter atau export bila
tersedia. Penghapusan log sebaiknya dibatasi kepada administrator.

### Konfigurasi aplikasi

Buka **Setting → Konfigurasi Website** untuk mengatur identitas aplikasi,
logo, tampilan, email, dan storage. Uji koneksi email atau storage setelah
menyimpan konfigurasi.

## Backup dan Restore

Buka **Setting → Backup & Restore**.

### Membuat backup

- **Backup Database**: hanya database.
- **Backup Files**: hanya file upload/aplikasi yang dipilih.
- **Full Backup**: database dan file.

Simpan salinan backup di lokasi berbeda dari server utama.

### Restore

1. Pastikan backup yang dipilih berasal dari aplikasi yang kompatibel.
2. Buat backup kondisi saat ini terlebih dahulu.
3. Pilih file backup.
4. Jalankan restore.
5. Periksa login, menu, dan data penting setelah proses selesai.

## Struktur Direktori

```text
apps/
├── config/              Konfigurasi CodeIgniter dan install.lock
├── core/                Base controller dan loader HMVC
├── hooks/               Hook aplikasi dan pseudo-cron
├── libraries/           Library tambahan, mailer, template
├── modules/             Modul Dashboard, Default, Installer, Setting, dll.
├── models/              Model aplikasi global
└── views/               Layout dan view global
assets/                  CSS, JavaScript, gambar, dan AdminLTE
backups/                 File backup aplikasi
i­n­dex.php               Entry point aplikasi
.env                     Konfigurasi environment
.htaccess                Rewrite dan proteksi akses
upload/                  File upload pengguna
system/                  Framework CodeIgniter 3
```

## Troubleshooting

### Halaman installer tidak ditemukan

- Pastikan folder `apps/modules/Installer` tersedia.
- Pastikan route installer ada di `apps/config/routes.php`.
- Pastikan rewrite Apache aktif dan `.htaccess` tidak diblokir.
- Coba akses `index.php/install`.

### Koneksi database gagal

- Pastikan MySQL aktif.
- Periksa host, username, password, dan nama database.
- Pastikan extension `mysqli` aktif.
- Pastikan user database memiliki hak `CREATE`, `INSERT`, `SELECT`, dan
  `ALTER` pada database tujuan.

### Requirement permission gagal

Berikan permission tulis pada `apps/config`, `apps/logs`, `upload`, dan
`backups`. Pada Windows, buka **Properties → Security** folder, lalu berikan
hak Modify kepada user web server atau akun yang menjalankan Apache.

### Installer tetap terkunci

Ini adalah perilaku keamanan normal setelah instalasi berhasil. Jangan hapus
lock pada produksi. Untuk development, pastikan database sudah di-backup,
hapus `apps/config/install.lock`, lalu buka ulang `/install`.

### Login gagal setelah instalasi

- Pastikan username dan password sesuai form installer.
- Pastikan user memiliki `status` aktif.
- Pastikan grup Administrator tersedia.
- Periksa log CodeIgniter di `apps/logs`.

## Keamanan Produksi

- Ganti password administrator default segera setelah login pertama.
- Gunakan password database yang kuat dan batasi hak user database.
- Jangan commit `.env`, password SMTP, access key, atau secret ke Git.
- Pastikan `install.lock` tetap ada.
- Nonaktifkan directory listing pada Apache/Nginx.
- Lindungi folder `backups` agar tidak dapat diunduh langsung dari browser.
- Gunakan HTTPS.
- Aktifkan backup berkala dan uji proses restore.
- Batasi akses menu Module Destroyer dan konfigurasi kepada administrator.
- Pantau `apps/logs` dan Activity Log secara berkala.

## Pengembangan

Project ini menggunakan CodeIgniter 3 dengan struktur HMVC. Modul baru sebaiknya
memiliki folder controller, model, views, dan config sendiri di dalam
`apps/modules`. Setelah menambahkan route atau konfigurasi, lakukan pengujian
pada environment development terlebih dahulu.

## Lisensi

Project ini dirilis di bawah **MIT License**. Lihat file [LICENSE](LICENSE)
untuk teks lisensi lengkap.

Copyright (c) 2026 Muhammad Rifqi Firmansyah.
