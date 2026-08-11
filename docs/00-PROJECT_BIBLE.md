# 00 - PROJECT BIBLE: e-Disiplin (SDMS)

## 1. Identiti Projek
- **Nama Projek**: e-Disiplin
- **Nama Penuh**: Sistem Rekod Disiplin Murid Digital
- **Nama Teknikal**: School Discipline Management System (SDMS)
- **Tujuan**: Membangunkan sistem enterprise pengurusan rekod disiplin murid sekolah rendah dan menengah yang selamat, boleh diaudit, serta berskala tinggi.

## 2. Prinsip Utama Projek (Dikemaskini Fasa 2.1)
1. **Kebolehpercayaan Data & Auditability**: Setiap rekod disiplin disemak melalui alur kerja rasmi. Tiada sebarang rekod disiplin rasmi yang boleh dipadam sesuka hati (strictly NO hard delete pada rekod disiplin; gunakan proses Void/Batal ber-audit).
2. **Kebenaran Manusia (Human Decision Authority)**: AI beraksi strictly sebagai pembantu analitik & ringkasan. Keputusan rasmi disiplin kekal 100% di tangan Pengetua/Guru Besar, PK HEM, atau Guru Disiplin.
3. **Penyelenggaraan & Scalability**: Kod dibina mengikut standard Laravel 13, Service-Action Pattern, Thin Controller, serta persediaan Multi-School (`sekolah_id`).
4. **Single Source of Truth**:
   - Sejarah penempatan kelas murid diuruskan STRICTLY menerusi `sejarah_kelas_murid` (Tiada `kelas_id` pada `murid`).
   - Sejarah penugasan guru kelas diuruskan STRICTLY menerusi `kelas_guru` (Tiada `guru_kelas_id` pada `kelas`).
5. **Kebatasan Multi-School Unique Constraint**: Penanda unik pengenalan murid (seperti `no_kp`) diikat bersama `sekolah_id` (`UNIQUE(sekolah_id, no_kp)`) untuk menjamin pengasingan data multi-tenant.
6. **Spatie Laravel Permission Standard**: Pengurusan peranan dan kebenaran mengguna pakai pakej rasmi `spatie/laravel-permission` (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`).

## 3. Matriks Keputusan Rasmi & Entiti Baharu Fasa 2.1

| Komponen | Keputusan Rasmi Fasa 2.1 | Catatan Seni Bina |
| :--- | :--- | :--- |
| **Entiti `eskalasi_kes`** | **DITAMBAH**. Menyimpan assignment, penugasan, dan keputusan eskalasi kes disiplin (Guru Disiplin → PK HEM → Pengetua). | Rekod sejarah penugasan & keputusan ber-audit. |
| **Entiti `kelas_guru`** | **DITAMBAH**. Mengantikan `kelas.guru_kelas_id` untuk menyokong sejarah dan pertukaran guru kelas. | Historic tracking peranan guru kelas. |
| **Pengecualian `kelas_id` Murid** | **DIBUANG dari `murid`**. Sejarah penempatan murid diuruskan strictly melalui `sejarah_kelas_murid`. | Single Source of Truth. |
| **Alur Kerja Kes BERAT** | **SEQUENTIAL APPROVAL**: Guru → Guru Disiplin → `MENUNGGU_KELULUSAN` → PK HEM (Kelulusan 1) → Pengetua (Pengesahan Akhir) → `DITUTUP`. | Direkodkan via `eskalasi_kes`. |
| **Peranan `Pentadbir Sekolah`** | **PENTADBIRAN SAHAJA**. Mengurus sekolah, pengguna, tahun akademik, kelas, murid, penjaga. Tiada kuasai disiplin automatik. | Dimasukkan konsisten dalam RBAC. |
| **Multi-School Unique Constraint** | **`UNIQUE(sekolah_id, no_kp)`** pada jadual `murid`. | Tenant isolation hardening. |
| **Konfigurasi AI Model** | **DYNAMIC CONFIG LAYER** (`config('ai.default_model')`). `ai_prompt_history.model` menyimpan snapshot sebenar. | Flexible model selection. |

## 4. Struktur Terperingkat Dokumentasi
Dokumentasi teknikal projek disusur mengikut urutan berikut di dalam folder `docs/`:
- `01-Project-Vision.md`
- `02-BRS.md`
- `03-URS.md`
- `04-SRS.md`
- `05-Business-Rules.md`
- `06-DDS.md`
- `07-Data-Dictionary.md`
- `08-AIRS.md`
- `09-Security-Design.md`
- `10-Workflow.md`
- `11-ERD.md`
- `12-UML.md`
- `13-Laravel-Architecture.md`
- `14-Migration-Blueprint.md`
- `15-Seeder-Blueprint.md`
- `PHASE_2.1_DESIGN_CORRECTION_REPORT.md`
