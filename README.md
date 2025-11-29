# ⚔️ LifeQuest

Sebuah platform gamifikasi produktivitas berbasis web yang dikembangkan menggunakan **Laravel 12**.
Sistem ini dirancang untuk mengubah aktivitas positif dan 'to-do list' harian menjadi sebuah petualangan RPG yang menarik. Pengguna dapat menyelesaikan tugas (Quests) untuk mendapatkan imbalan (XP & Gold), naik level, dan membangun kebiasaan positif di dunia nyata.

> **Gamifikasi produktivitas** 🎮 + **RPG System** 🗡️ + **Habit Tracking** 📊 = **LifeQuest** ⚔️

-----

## 🚀 Tech Stack

  - **Framework:** Laravel 12
  - **Language:** PHP 8.3+
  - **Database:** MySQL / SQLite (development)
  - **Frontend:** Blade Templates, Custom CSS, Alpine.js, Tailwind CSS
  - **Build Tools:** Vite, NPM, Composer
  - **JS Libraries:** SweetAlert2, Cropper.js, Axios
  - **Icons:** Bootstrap Icons
  - **External API:** OpenWeatherMap (Weather Widget)

-----

## 🏗️ Arsitektur Aplikasi

### Model-View-Controller (MVC)

```
app/
├── Models/               # 4 Core Models
│   ├── User.php         # User dengan RPG stats
│   ├── Quest.php        # Quest definitions
│   ├── QuestLog.php     # Quest history & submissions
│   └── Achievement.php  # Achievement system
├── Http/Controllers/    # 12 Controllers
│   ├── DashboardController.php
│   ├── QuestController.php
│   ├── AchievementController.php
│   ├── LeaderboardController.php
│   ├── ProfileController.php
│   └── Admin/          # Admin Controllers
│       ├── AdminDashboardController.php
│       ├── AdminQuestController.php
│       ├── AdminAchievementController.php
│       └── SubmissionController.php
├── Services/           # Business Logic
│   └── AchievementService.php
└── Helpers/           # Global Helper Functions
    └── helpers.php
```

### Database Schema (23 Migrations)

**Core Tables:**
- `users` - User accounts dengan RPG stats (level, XP, gold, intelligence, strength, stamina, agility)
- `quests` - Quest templates (admin & user created)
- `quest_logs` - Quest progress tracking & submissions
- `achievements` - Achievement definitions dengan JSON conditions
- `user_achievements` - Unlocked achievements (pivot table)

-----

## ⚙️ Fitur Utama

### 👥 Manajemen Pengguna

  - **Role System:** Admin & Player dengan middleware-based access control
  - **Autentikasi:** Laravel Breeze (Login, Register, Email Verification, Password Reset)
  - **RPG Stats:** Intelligence, Strength, Stamina, Agility (dapat ditingkatkan via quests)

### 📊 Dashboard Player

  - **Statistik Global:** Total XP, Gold, Level, Quest Completed, Achievements Unlocked
  - **Progress Harian:** Quest hari ini, XP earned today
  - **Widget Interaktif:** 
    - Real-time Clock
    - Weather Widget (OpenWeatherMap API)
    - Motivational Quotes
  - **Recent Activities:** 5 quest terakhir yang diselesaikan

### 🛡️ Sistem Quest Lengkap

#### Tipe Quest
  - **Admin Quest:** Dibuat oleh admin, biasanya memerlukan submission proof
  - **User Quest:** Quest personal yang dibuat player sendiri

#### Frekuensi Quest
  - **Daily (Harian):** Reset setiap hari tengah malam, bisa diambil ulang
  - **Weekly (Mingguan):** Reset setiap minggu
  - **Once (Sekali):** Quest permanen, selesai satu kali

#### Difficulty Levels
  - **Easy:** 1x reward multiplier
  - **Medium:** 1.5x reward multiplier  
  - **Hard:** 2x reward multiplier

#### Quest Rewards
  - **XP** - Experience points untuk leveling
  - **Gold** - Virtual currency
  - **Stats Boost** - Intelligence / Strength / Stamina / Agility

#### Quest Workflow (Player)
1. Browse available quests
2. Take quest → Quest log created dengan status 'pending'
3. Complete quest:
   - **User Quest:** Langsung complete, dapat reward
   - **Admin Quest:** Upload submission proof → status 'submitted'
4. Menunggu admin review (untuk admin quest)
5. Terima reward setelah approved

### 🏆 Achievement System (Auto-Unlock)

  - **Achievement Conditions** (berbasis JSON):
    ```json
    {"quests_completed": 10}  // Complete 10 quests
    {"stat": "intelligence", "value": 50}  // Reach 50 intelligence
    {"gold_earned": 1000}  // Earn 1000 gold
    ```
  - **Auto-Detection:** `AchievementService` otomatis check & unlock saat quest completed
  - **Achievement Rewards:** Bonus XP & Gold
  - **Progress Tracking:** Visual progress bar untuk locked achievements

### 💼 Panel Admin Komprehensif

#### Quest Management (CRUD)
  - Create/Edit/Delete admin quests
  - Set difficulty, frequency, rewards, stat bonus
  - Link quest ke achievement tertentu
  - Toggle active/inactive status

#### Submission Review System
  - List semua submissions dari players
  - View submission proof (images, documents)
  - **Approve:** Berikan reward & unlock achievement
  - **Reject:** Kembalikan ke 'pending' dengan admin notes
  - Notifikasi otomatis ke player

#### Achievement Management (CRUD)
  - Create achievements dengan kondisi unlock
  - Upload custom icon untuk setiap achievement
  - Set reward values (XP & Gold)

### 📈 Leveling System

  - **Formula:** `Level = floor(pow(XP / 100, 0.5)) + 1`
  - **Contoh Progression:**
    - Level 1: 0 XP
    - Level 2: 100 XP  
    - Level 3: 400 XP
    - Level 4: 900 XP
    - Level 5: 1,600 XP
    - Level 10: 8,100 XP

### 🏅 Leaderboard Global

  - Ranking pemain berdasarkan total XP (level tertinggi)
  - Menampilkan Top 100 players
  - Public untuk semua authenticated users
  - Lihat stats pemain lain (level, gold, intelligence, dll)

### 👤 Profile Management

  - Update nama, email, password
  - **Avatar Upload** dengan image cropping (Cropper.js)
  - Delete account option
  - View personal stats & achievements

### 🔔 Notification System

  - **Badge Counter:** Real-time notification count di navbar
  - **For Admin:** Notifikasi saat ada submission baru dari player
  - **For Player:** Notifikasi saat admin membuat quest baru

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

## 🎯 Workflow Penggunaan

### Player Journey
1. **Register & Login** → Verifikasi email
2. **Dashboard** → Cek stats, daily progress, widgets
3. **Browse Quests** → Lihat quest admin & buat quest sendiri
4. **Take Quest** → Quest masuk ke quest log
5. **Complete Quest:**
   - User Quest: Langsung selesai → Terima reward
   - Admin Quest: Upload bukti → Tunggu approval
6. **Earn Rewards** → XP, Gold, Stats meningkat
7. **Level Up** → Achievement auto-unlock
8. **Leaderboard** → Bandingkan dengan player lain

### Admin Journey
1. **Login** → Admin dashboard
2. **Create Quest** → Set difficulty, rewards, requirements
3. **Manage Quests** → CRUD operations pada quest
4. **Review Submissions:**
   - Lihat bukti dari player
   - Approve → Player dapat reward
   - Reject → Kembali ke pending dengan notes
5. **Manage Achievements** → Create & edit achievements
6. **Monitor Platform** → Lihat statistik & aktivitas user

-----

## 🌟 Highlights & Features

✅ **Role-based Access Control** - Middleware untuk Admin & Player  
✅ **RPG Gamification** - Level, XP, Gold, 4 Stats  
✅ **Quest System** - Daily/Weekly/Once dengan auto-reset  
✅ **Achievement Auto-Unlock** - Service layer dengan JSON conditions  
✅ **Submission Review** - Admin approval workflow  
✅ **File Upload** - Avatar dengan cropping, submission proofs  
✅ **Notification System** - Real-time badge counter  
✅ **Leaderboard** - Global player ranking  
✅ **Responsive Design** - Mobile-friendly dengan Tailwind CSS  

-----

## 📜 Lisensi

Project ini dibuat **for educational purpose only**.  
Tidak diperjualbelikan dan ditujukan untuk kebutuhan akademik **Universitas Riau**.

-----

## 👨‍💻 Pengembang

<div align="center">

### **Muhammad Thariq Adzikra**

**NIM:** 2303113029  
**Program Studi:** Sistem Informasi B 2023  
**Fakultas:** Matematika dan Ilmu Pengetahuan Alam  
**Universitas:** Universitas Riau

---

**Mata Kuliah:**  
*Pengembangan Sistem Informasi Berbasis Web Lanjut*

---

📧 Email: [Contact via GitHub](https://github.com/ThariqAdzikra)  
🔗 Repository: [LifeQuest](https://github.com/ThariqAdzikra/LifeQuest)

</div>

-----

<div align="center">

**Made with ❤️ and ☕ for learning purposes**

</div>
