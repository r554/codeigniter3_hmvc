# Changelog

Semua perubahan penting pada project ini akan didokumentasikan dalam file ini.

Format file ini mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
dan project ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

### Changed

- Proses setup aplikasi kini dapat dilakukan melalui browser tanpa menjalankan
  import database secara manual untuk tabel inti.
- Dokumentasi konfigurasi environment, backup, storage, email, dan penggunaan
  modul telah diperjelas di `README.md`.

### Security

- Installer dikunci setelah instalasi berhasil menggunakan file
  `apps/config/install.lock`.
- Password admin pada proses seed tidak disimpan sebagai plaintext.
- Installer memvalidasi requirement sistem sebelum menjalankan proses instalasi.

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

[Unreleased]: https://github.com/your-org/your-project/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/your-org/your-project/releases/tag/v0.1.0
