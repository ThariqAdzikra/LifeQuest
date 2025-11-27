# ⚔️ LifeQuest

Sebuah platform gamifikasi produktivitas berbasis web yang dikembangkan menggunakan **Laravel**.
Sistem ini dirancang untuk mengubah aktivitas positif dan 'to-do list' harian menjadi sebuah petualangan RPG yang menarik. Pengguna dapat menyelesaikan tugas (Quests) untuk mendapatkan imbalan (XP & Gold), dan membangun kebiasaan positif di dunia nyata.

-----

## 🚀 Tech Stack

  - **Framework:** Laravel 12
  - **Language:** PHP 8.3
  - **Database:** MySQL
  - **Frontend:** Blade, Custom CSS, Alpine.js
  - **Frontend (JS Libraries):** SweetAlert2, Cropper.js, Bootstrap 5 (JS Components)
  - **Environment:** Composer, NPM, Vite

-----

## ⚙️ Fitur Utama

### 👥 Manajemen Pengguna

  - Role: **Admin** & **Player** (Pengguna)
  - Autentikasi & otorisasi bawaan Laravel

### 📊 Modul Dashboard

  - Menampilkan ringkasan statistik pemain (XP, Gold, Stats)
  - Widget Kustom: Jam Real-time, Cuaca (via API), dan Quotes Motivasi
  - Ringkasan progres harian

### 🛡️ Modul Quest

  - **Admin Quest:** Quest resmi yang dibuat oleh Admin, seringkali memerlukan bukti (submission)
  - **User Quest:** Quest kustom yang dibuat pengguna untuk diri sendiri
  - Frekuensi Quest: Harian (Daily), Mingguan (Weekly), dan Sekali Selesai (Once)
  - Logika Reset Otomatis untuk quest harian/mingguan

### 🏆 Modul Achievements

  - Daftar pencapaian yang dapat dibuka oleh pemain
  - Dikelola sepenuhnya oleh Admin

### 👤 Modul Profil Pengguna

  - Pengaturan profil (Ganti nama, email, password)
  - Fitur upload avatar kustom dengan *cropping* (menggunakan Cropper.js)

### 👑 Panel Admin

  - **Review Submissions:** Admin dapat menyetujui (Approve) atau menolak (Reject) bukti quest yang dikirim pemain
  - **CRUD Quests:** Mengelola semua quest resmi (Admin Quest)
  - **CRUD Achievements:** Mengelola semua pencapaian di platform

### 🔔 Sistem Notifikasi Real-time

  - Penanda (badge) angka pada ikon lonceng untuk notifikasi baru
  - **Admin:** Mendapat notifikasi saat ada submission baru
  - **Player:** Mendapat notifikasi saat ada quest admin baru

-----

## 🧩 Cara Instalasi

1.  **Clone Repository**

    ```bash
    git clone https://github.com/ThariqAdzikra/LifeQuest.git
    cd LifeQuest
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    npm run dev
    ```

3.  **Konfigurasi Environment**

      * Duplikat file `.env.example` menjadi `.env`
      * Atur koneksi database:

    <!-- end list -->

    ```env
    DB_DATABASE=lifequest
    DB_USERNAME=root
    DB_PASSWORD=
    ```

      * *Opsional: Atur API Key untuk widget cuaca (OpenWeatherMap)*

    <!-- end list -->

    ```env
    OPENWEATHER_API_KEY=API_KEY_ANDA
    ```

4.  **Generate Key & Migrasi Database**

    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

5.  **Storage Link** (Sangat penting untuk upload avatar)

    ```bash
    php artisan storage:link
    ```

6.  **Jalankan Server**

    ```bash
    php artisan serve
    ```

-----

## 🔐 Akun Default

| Role | Email | Password |
| :--- | :--- | :--- |
| Admin | `admin@example.com` | `password` |
| Player | `test@example.com` | `password` |

-----

## 📜 Lisensi

Project ini dibuat **for educational purpose only**.
Tidak diperjualbelikan dan ditujukan untuk kebutuhan akademik Universitas Riau.

-----

## 👨‍💻 Pengembang

**Thariq Adzikra**
**2303113029**
*Mata Kuliah Pengembangan Sistem Informasi Berbasis Web Lanjut*
Fakultas Matematika dan Ilmu Pengetahuan Alam – Universitas Riau
