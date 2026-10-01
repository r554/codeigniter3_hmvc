# Changelog

Semua perubahan penting pada project ini akan didokumentasikan dalam file ini.

Format file ini mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
dan project ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.0] - 2026-10-01

### Added

- Menambahkan dokumentasi penggunaan aplikasi melalui `README.md`.
- Menambahkan wizard instalasi first-run pada endpoint `/install`.
- Menambahkan pemeriksaan kebutuhan sistem sebelum instalasi, meliputi:
  - PHP versi 7.0 atau lebih baru.
  - Ekstensi PHP `mysqli`, `mbstring`, `openssl`, `json`, `curl`, `fileinfo`,
    dan `zip`.
  - Permission tulis pada folder konfigurasi, log, upload, dan backup.
- Menambahkan fitur test koneksi database dari halaman installer.
- Menambahkan pembuatan tabel inti aplikasi secara otomatis.
- Menambahkan seed data awal untuk:
  - Status grup.
  - Grup Administrator.
  - Menu dasar aplikasi.
  - Hak akses Administrator.
  - Akun administrator pertama.
- Menambahkan mekanisme `install.lock` untuk mencegah installer dijalankan ulang
  setelah instalasi berhasil.
- Menambahkan route installer:
  - `/install`
  - `/install/requirements`
  - `/install/test_database`
  - `/install/install`
- Menambahkan fitur Two-Factor Authentication (2FA) berbasis TOTP untuk admin:
  - Library `Twofa_lib.php` untuk generate secret, QR code, verifikasi kode,
    dan backup codes.
  - Model `M_twofa.php` untuk mengelola status 2FA di tabel `admin`.
  - Halaman verifikasi 2FA (`verify_2fa.php`) dengan tampilan AdminLTE login-box.
  - Tab **Keamanan** pada halaman `/profile` untuk setup, verifikasi, dan
    penonaktifan 2FA menggunakan modal AJAX.
- Menambahkan tab **Keamanan 2FA** pada halaman `/konfigurasi` untuk:
  - Melihat status 2FA seluruh admin.
  - Mengatur global toggle 2FA, rate limit, dan jumlah backup codes.
  - Mereset 2FA admin lain melalui endpoint `reset-2fa-admin`.
- Menambahkan route untuk manajemen 2FA di level profil dan konfigurasi.
- Menambahkan backup codes satu kali pakai yang disimpan dalam format JSON.
- Menambahkan audit logging untuk event 2FA menggunakan helper `activity`.

### Changed

- Proses setup aplikasi kini dapat dilakukan melalui browser tanpa menjalankan
  import database secara manual untuk tabel inti.
- Dokumentasi konfigurasi environment, backup, storage, email, dan penggunaan
  modul telah diperjelas di `README.md`.
- `M_auth.php` diperbarui untuk mengambil kolom `two_factor_enabled` dan
  `two_factor_secret` saat login.
- `Auth.php` diperbarui untuk meneruskan pengguna dengan 2FA aktif ke halaman
  verifikasi sebelum mengakses dashboard.
- Tabel `admin` diperluas dengan kolom `two_factor_enabled`,
  `two_factor_secret`, dan `two_factor_backup_codes`.

### Security

- Installer dikunci setelah instalasi berhasil menggunakan file
  `apps/config/install.lock`.
- Password admin pada proses seed tidak disimpan sebagai plaintext.
- Installer memvalidasi requirement sistem sebelum menjalankan proses instalasi.
- Implementasi 2FA dilengkapi dengan rate limiting (maksimal percobaan verifikasi
  sesuai konfigurasi `.env`) dan session timeout 10 menit untuk setup 2FA.
- Validasi user ID pada sesi setup 2FA untuk mencegah session hijacking.

### Known Limitations

- Schema installer saat ini mencakup tabel inti autentikasi dan hak akses.
  Tabel bisnis tambahan perlu disiapkan melalui migrasi atau schema database
  sesuai kebutuhan deployment.
- Verifikasi instalasi sebaiknya dilakukan pada database kosong dan environment
  development terlebih dahulu.

## [0.1.0] - 2026-09-17

### Added

- Initial documented release of the CodeIgniter 3 HMVC application.
- Struktur modular untuk Dashboard, Default, Setting, Category, Master, Support,
  WebService, dan CrudGenerator.
- Login, logout, user management, grup pengguna, dan hak akses menu.
- Konfigurasi aplikasi, email, storage, backup, restore, dan activity log.
- Master data serta modul laporan aplikasi.

[Unreleased]: https://github.com/your-org/your-project/compare/v0.2.0...HEAD
[0.2.0]: https://github.com/your-org/your-project/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/your-org/your-project/releases/tag/v0.1.0
