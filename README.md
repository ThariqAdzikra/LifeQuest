# 🗡️ LifeQuest Edge (Vite + React + Hono + Supabase)

Rewrite modern dari aplikasi gamifikasi produktivitas **LifeQuest** (sebelumnya berbasis PHP/Laravel), kini dirancang khusus untuk berjalan di arsitektur **Serverless & Edge Runtime**.

Aplikasi ini siap dideploy secara instan menggunakan **Wrangler ke Cloudflare Pages** maupun ke **Vercel**.

---

## 🌟 Fitur Utama yang Telah Dimigrasi

1. **Gamifikasi RPG Lengkap:**
   * Rumus Leveling eksak dari Laravel: $\text{Level} = \lfloor \sqrt{\text{EXP} / 100} \rfloor + 1$
   * Atribut Karakter: **Intelligence, Strength, Stamina, Agility** yang bertambah dari pengerjaan quest.
   * Koin Gold untuk reward.
2. **Papan Quest (Quest Board):**
   * **Quest Saya:** Tracking status *Aktif*, *Sedang Direview*, *Ditolak*, dan *Selesai*.
   * **Quest Admin:** Quest resmi tantangan harian/mingguan dengan bukti pengerjaan (*submission proof*).
   * **Quest Pribadi:** Rancang quest custom dengan target stat dan frekuensi (Once, Daily, Weekly).
   * **Animasi Selebrasi:** Efek ledakan confetti interaktif saat menyelesaikan quest.
3. **Portal Admin:**
   * Review antrean bukti quest pengerjaan pemain (preview foto/screenshot/dokumen).
   * Tombol *Approve* (otomatis memberikan EXP, Gold, Stat, dan Lencana Title) & *Reject* (dengan catatan admin).
   * CRUD Quest Resmi Admin.
4. **Papan Peringkat (Leaderboard):**
   * Top 20 pemain non-admin diurutkan berdasarkan jumlah quest admin yang diselesaikan.
   * Tie-breaker otomatis (nama alfabetis A-Z jika poin sama).
   * Kartu peringkat user aktif.
5. **Koleksi Pencapaian (Achievements):**
   * Tampilan lencana RPG berdasarkan tingkat kelangkaan (*Common, Rare, Epic, Legendary*).
6. **Demo Mode Interaktif:**
   * Langsung bisa dicoba secara lokal tanpa perlu langsung mengkoneksikan Supabase.
   * Tombol switch role *Demo: Player* vs *Demo: Admin* di Navbar.

---

## 🛠️ Tech Stack

* **Frontend:** React 18, TypeScript, Vite, Tailwind CSS, Lucide Icons, Canvas Confetti.
* **Backend API:** Hono.js (Web Standards Fetch API, sub-millisecond cold starts).
* **Database & Auth:** Supabase (PostgreSQL, Supabase Auth, Storage Buckets).
* **Deployment CLI:** Wrangler (Cloudflare Pages) & Vercel CLI.

---

## 🚀 Panduan Memulai Cepat (Local Development)

### 1. Masuk ke Direktori Project
```powershell
cd D:\Server\LifeQuest-edge
```

### 2. Jalankan Server Development
```powershell
npm run dev
```
Buka browser di `http://localhost:5173`. Aplikasi otomatis berjalan dalam mode preview interaktif!

---

## 🗄️ Setup Database Supabase

1. Buat project baru gratis di [Supabase](https://supabase.com).
2. Masuk ke menu **SQL Editor** di dashboard Supabase Anda.
3. Buka file `supabase/schema.sql` di project ini, salin seluruh kodenya, lalu tempelkan ke SQL Editor Supabase dan klik **Run**.
   * Skema ini otomatis membuat tabel `profiles`, `quests`, `quest_logs`, `achievements`, trigger akun baru, RLS policies, dan storage bucket `submissions`.
4. Salin kredensial dari **Project Settings -> API**:
   * `Project URL`
   * `anon public key`
   * `service_role key`
5. Buat file `.env` di root project (`D:\Server\LifeQuest-edge\.env`):
```env
VITE_SUPABASE_URL=https://xxxxxxxx.supabase.co
VITE_SUPABASE_ANON_KEY=eyJhbGci...
SUPABASE_SERVICE_ROLE_KEY=eyJhbGci...
```

---

## ☁️ Deployment Guide

### Opsi 1: Deploy ke Cloudflare Pages (via Wrangler)

1. **Login ke Cloudflare via Wrangler:**
   ```powershell
   npx wrangler login
   ```
2. **Build aplikasi untuk produksi:**
   ```powershell
   npm run build
   ```
3. **Deploy folder build ke Cloudflare Pages:**
   ```powershell
   npx wrangler pages deploy dist --project-name lifequest
   ```
4. **Tambahkan Environment Variables di Cloudflare Dashboard:**
   * Buka Cloudflare Dashboard -> **Workers & Pages** -> Pilih project **lifequest**.
   * Masuk ke **Settings** -> **Environment variables**.
   * Tambahkan:
     * `VITE_SUPABASE_URL`
     * `VITE_SUPABASE_ANON_KEY`
     * `SUPABASE_SERVICE_ROLE_KEY`

---

### Opsi 2: Deploy ke Vercel

1. **Install & Login Vercel CLI:**
   ```powershell
   npx vercel login
   ```
2. **Deploy ke Vercel:**
   ```powershell
   npx vercel
   ```
   *(Pilih konfigurasi default, output directory: `dist`)*
3. **Deploy untuk Production:**
   ```powershell
   npx vercel --prod
   ```
4. **Tambahkan Environment Variables di Vercel Dashboard:**
   * Buka project di dashboard Vercel -> **Settings** -> **Environment Variables**.
   * Tambahkan `VITE_SUPABASE_URL`, `VITE_SUPABASE_ANON_KEY`, dan `SUPABASE_SERVICE_ROLE_KEY`.

---

## 📁 Struktur Direktori

```text
LifeQuest-edge/
├── api/                       # Backend Hono.js (Edge-ready API)
│   └── index.ts               # Router, gamifikasi, leaderboard, admin handlers
├── functions/api/             # Cloudflare Pages Functions
│   └── [[route]].ts           # Edge entrypoint untuk Wrangler
├── public/                    # Aset statis & gambar karakter
│   └── images/                # char.png, heroLanding.jpg
├── src/                       # Frontend React + TypeScript
│   ├── components/            # Navbar, QuestCard, StatsWidget, Modals
│   ├── context/               # AuthContext (Supabase Auth & Demo State)
│   ├── lib/                   # Supabase client & API client
│   ├── pages/                 # Landing, Login, Register, Dashboard, Quests, Admin, dll.
│   └── types/                 # TypeScript interfaces & leveling formulas
├── supabase/                  # PostgreSQL Schema & Seed Data
│   └── schema.sql             # Skema lengkap siap copy-paste ke Supabase
├── index.html                 # HTML template
├── package.json               # NPM scripts & dependencies
├── tsconfig.json              # TypeScript configuration
├── vite.config.ts             # Bundler configuration
├── wrangler.jsonc             # Cloudflare Pages deployment config
└── vercel.json                # Vercel serverless routing config
```

