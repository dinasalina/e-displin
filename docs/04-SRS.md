# 04 - Software Requirements Specification (SRS)

## 1. Keperluan Sistem & Spesifikasi Perkakasan/Perisian

### 1.1 Stack Teknologi Rasmi (Dikemaskini Fasa 2.1)
- **Enjin Backend**: Laravel 13.x (PHP 8.4+)
- **Pangkalan Data**: MySQL 8.0+ (Engine: InnoDB, Charset: `utf8mb4`, Collation: `utf8mb4_unicode_ci`, Timezone: `Asia/Kuala_Lumpur`)
- **Frontend Framework**: Laravel Blade Templates + Tailwind CSS 4 + Alpine.js
- **Pengesahan & Kebenaran Rasmi**: Laravel Breeze (Blade-stack) + **`spatie/laravel-permission` (v6+)**
  - Mengguna pakai jadual rasmi Spatie: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`.
  - Model `Pengguna` menggunakan trait `Spatie\Permission\Traits\HasRoles`. DILARANG membina jadual `pengguna_role` berasas custom.
- **Enjin AI Integration**: Laravel AI SDK + Configuration Layer (`config/ai.php`).
- **Kerangka Ujian & Quality**: Pest PHP + Laravel Pint + Larastan / PHPStan (Level 5+)

### 1.2 Kebatasan Negatif Strict
- **Livewire**: DILARANG.
- **Vue.js / React.js / Inertia.js**: DILARANG.
- **Custom `pengguna_role` Table**: DILARANG.
- **Double Source of Truth**: DILARANG meletakkan `kelas_id` dalam `murid` atau `guru_kelas_id` dalam `kelas`.

## 2. Keperluan Fungsional Sistem (System Functional Requirements - SFR)

### SFR-01: Identifier & Multi-School Unique Constraint
- Semua jadual teras wajib mengandungi Primary Key `id` (`BIGINT UNSIGNED AUTO_INCREMENT`) dan `uuid` (`CHAR(36)`).
- Rujukan URL dan Route Binding wajib menggunakan `uuid`. Dilarang mendedahkan ID integer secara terbuka.
- **Multi-School Unique Constraint**: Medan `no_kp` bagi `murid` diikat secara unik mengikut sekolah: `UNIQUE(sekolah_id, no_kp)`.

### SFR-02: Single Source of Truth Class Assignment
- Sejarah penempatan murid dalam kelas ditentukan STRICTLY melalui jadual `sejarah_kelas_murid` mengikut tahun akademik aktif.
- Sejarah penugasan guru kelas diuruskan STRICTLY melalui jadual `kelas_guru`.

### SFR-03: Sequential Escalation via `eskalasi_kes`
- Kes ber-tahap `BERAT` diuruskan secara berurutan (*sequential approval*) melalui jadual `eskalasi_kes`:
  1. Assignment Peringkat 1 kepada PK HEM (`jenis_eskalasi = 'SEMAKAN_PK_HEM'`).
  2. Assignment Peringkat 2 kepada Pengetua/Guru Besar (`jenis_eskalasi = 'PENGESAHAN_PENGETUA'`).

### SFR-04: Dynamic AI Configuration, Privacy & Retention Layer
- Panggilan AI dipandu oleh `config/ai.php` (`config('ai.default_model')`).
- Lapisan `AiDisciplineService` menapis semua PII murid (Nama, MyKad, Alamat, Ibu Bapa) sebelum menghantar prompt kepada AI Provider.
- Polisi retensi sejarah AI: Data audit dalam `ai_prompt_history` disimpan secara *immutable* selama 5 tahun.

### SFR-05: In-App Notification System (`notifikasi`)
- Sistem menggunakan jadual `notifikasi` khusus untuk Fasa 1.
- Menyokong pengiraan `unread count` (`is_dibaca = false`), pengemaskinian status *mark as read*, *mark all as read*, serta pengasingan ketat `sekolah_id`.

## 3. Keperluan Bukan Fungsional (Non-Functional Requirements - NFR)

### NFR-01: Prestasi & Indeks Pangkalan Data
- Masa tindak balas paparan Blade View mestilah kurang daripada 500ms untuk 95% daripada permintaan standard.
- Indeks pangkalan data MySQL mestilah dioptimumkan pada `sekolah_id`, `uuid`, `status_kes`, `tahap_kes`, `tarikh_kejadian`, dan indeks unik berpasangan `(sekolah_id, no_kp)`.

### NFR-02: Keselamatan & Tenant Isolation
- Perlindungan penuh terhadap serangan SQL Injection, XSS, CSRF (`@csrf`), dan IDOR.
- Pengasingan tenant berasaskan `sekolah_id` dikuatkuasakan pada semua query Eloquent dan Policy.
