# 06 - Detailed Design Specification (DDS)

## 1. Pengenalan
Dokumen Detailed Design Specification (DDS) mendefinisikan reka bentuk teknikal terperinci bagi komponen perisian e-Disiplin (SDMS), merangkumi struktur modul, lapisan perkhidmatan, dan interaksi komponen (Dikemaskini Fasa 2.1).

## 2. Seni Bina Lapisan (Layered Architecture Fasa 2.1)

```
[ Frontend: Blade Templates + Alpine.js + Tailwind CSS 4 ]
                            │
                            ▼ (HTTP Requests / Form Submission)
[ Routing & Auth Middleware (Laravel Breeze + Spatie Laravel Permission) ]
                            │
                            ▼
[ Validation Layer: Form Requests (app/Http/Requests) ]
                            │
                            ▼
[ Presentation Layer: Controllers (app/Http/Controllers) ] <--- Thin Controllers
                            │
                            ▼
[ Domain / Service Layer: Actions & Services (app/Services / app/Actions) ]
       │                         │                            │
       ├─────────────────────────┼────────────────────────────┼─────────────────────────┐
       ▼                         ▼                            ▼                         ▼
[ EskalasiKesService ] [ KelasGuruService ]         [ AI Service Layer ]     [ InAppNotificationService ]
       │                         │                            │                         │
       └─────────────────────────┴──────────────┬─────────────┴─────────────────────────┘
                                                ▼
              [ Data Access Layer: Eloquent Models (app/Models) ]
                (Pengguna model uses Spatie HasRoles trait)
                                                │
                                                ▼
                  [ Database: MySQL 8 (InnoDB Storage) ]
```

## 3. Komponen Utama & Tanggungjawab

### 3.1 Domain Services & Actions
1. **`LaporKesAction`**: Mengurus pendaftaran kes disiplin baharu, pemprosesan lampiran fail, pendaftaran sejarah status awal (`DILAPORKAN`), rakaman audit log, dan pemicuan notifikasi.
2. **`EskalasiKesService`**: Menguruskan alur kelulusan berurutan (Sequential Approval) bagi kes BERAT menerusi entiti `eskalasi_kes`.
   - Mengendalikan penugasan Peringkat 1 (PK HEM) dan Peringkat 2 (Pengetua/Guru Besar).
   - Merekodkan tarikh tugasan (`ditugaskan_pada`), nota keputusan, dan tarikh keputusan (`diputuskan_pada`).
3. **`KelasGuruService`**: Menguruskan penugasan guru kelas menerusi jadual `kelas_guru` (Single Source of Truth). Merekodkan `tarikh_mula` dan `tarikh_tamat` penugasan.
4. **`VoidRekodDisiplinAction`**: Mengurus proses pembatalan rasmi kes disiplin (`is_void = true`, `void_reason`, `voided_by`, `voided_at`) dan mendaftar log aktiviti audit.
5. **`AiDisciplineService`**: Membungkus komunikasi Laravel AI SDK. Menggunakan `config('ai.default_model')`, melakukan anonymization PII data murid, dan mencatat transaksi ke jadual `ai_prompt_history`.
6. **`InAppNotificationService`**: Menguruskan pendaftaran rekod `notifikasi` dalaman (`is_dibaca`, unread count, mark as read, mark all as read) mengikut `sekolah_id`.

### 3.2 Kawalan Kebenaran (Policies & Spatie RBAC Integration)
- **`RekodDisiplinPolicy`**:
  - `viewAny()`: Mengesahkan pengguna mengikut `sekolah_id`.
  - `create()`: Mana-mana Guru yang disahkan berdaftar di sekolah.
  - `update()` / `tetapkanTindakan()`: Hanyalah Guru Disiplin, PK HEM, atau Pengetua sekolah berkenaan.
  - `luluskanEskalasi()`: Menyemak tugasan aktif dalam `eskalasi_kes` (PK HEM untuk Peringkat 1, Pengetua/Guru Besar untuk Peringkat 2).
  - `void()`: Guru Disiplin / PK HEM / Pengetua dengan sebab pembatalan wajib.
- **`Pentadbir Sekolah` Boundary**: Disemak melalui permission Spatie (`sekolah.urus`, `pengguna.urus`, `kelas.urus`, `murid.urus`, `penjaga.urus`). Tidak mempunyai akses automatik kepada `RekodDisiplinPolicy@update` atau `@void`.

### 3.3 Pengurusan Storage Fail Bukti
- Fail bukti kes disimpan menggunakan disk `local_private` di `storage/app/private/lampiran_disiplin/{sekolah_id}/{rekod_uuid}/`.
- Capaian fail menggunakan controller terurus `LampiranDisiplinController@download` yang menyemak kebenaran policy sebelum memulangkan fail (*Stream Download*).
