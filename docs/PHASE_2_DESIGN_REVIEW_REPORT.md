# PHASE 2 DESIGN REVIEW REPORT: e-Disiplin (SDMS)

**Masa Laporan**: 2026-08-10  
**Fasa Semasa**: PHASE 2 - DATABASE & SYSTEM DESIGN  
**Status**: DOKUMENTASI & REKA BENTUK SIAP SEPENUHNYA (READY FOR USER REVIEW)

---

## 1. Dokumen Yang Telah Siap Dibuat

### 1.1 Dokumen Asas & Spesifikasi Utama (`docs/`)
1. [00-PROJECT_BIBLE.md](file:///c:/laragon/www/e-displin/docs/00-PROJECT_BIBLE.md) - Rujukan teras projek & matriks keputusan rasmi.
2. [01-Project-Vision.md](file:///c:/laragon/www/e-displin/docs/01-Project-Vision.md) - Visi, objektif strategik, dan nilai teras sistem.
3. [02-BRS.md](file:///c:/laragon/www/e-displin/docs/02-BRS.md) - Business Requirements Specification & skop Fasa 1.
4. [03-URS.md](file:///c:/laragon/www/e-displin/docs/03-URS.md) - User Requirements Specification & matriks kebenaran peranan.
5. [04-SRS.md](file:///c:/laragon/www/e-displin/docs/04-SRS.md) - Software Requirements Specification & stack teknologi rasmi.
6. [05-Business-Rules.md](file:///c:/laragon/www/e-displin/docs/05-Business-Rules.md) - Peraturan perniagaan terperinci (BRUL-01 hingga BRUL-06).
7. [06-DDS.md](file:///c:/laragon/www/e-displin/docs/06-DDS.md) - Detailed Design Specification & seni bina lapisan.
8. [07-Data-Dictionary.md](file:///c:/laragon/www/e-displin/docs/07-Data-Dictionary.md) - Kamus data terperinci 16 jadual pangkalan data.
9. [08-AIRS.md](file:///c:/laragon/www/e-displin/docs/08-AIRS.md) - AI Requirements Specification, anonymization & audit trail.
10. [09-Security-Design.md](file:///c:/laragon/www/e-displin/docs/09-Security-Design.md) - Reka bentuk keselamatan, RBAC, file upload hardening & void security.
11. [10-Workflow.md](file:///c:/laragon/www/e-displin/docs/10-Workflow.md) - Carta alir kes, state transition matrix, dan alur kerja Void.
12. [11-ERD.md](file:///c:/laragon/www/e-displin/docs/11-ERD.md) - Entity Relationship Diagram & Mermaid.js ERD.
13. [12-UML.md](file:///c:/laragon/www/e-displin/docs/12-UML.md) - Use Case Diagram & Sequence Diagrams.
14. [13-Laravel-Architecture.md](file:///c:/laragon/www/e-displin/docs/13-Laravel-Architecture.md) - Blueprint struktur direktori Laravel (`Actions`, `Services`, `Enums`, `Policies`).
15. [14-Migration-Blueprint.md](file:///c:/laragon/www/e-displin/docs/14-Migration-Blueprint.md) - Urutan kronologi 17 fail migration & indeks.
16. [15-Seeder-Blueprint.md](file:///c:/laragon/www/e-displin/docs/15-Seeder-Blueprint.md) - Pelan pengisian data awal (Roles, Permissions, Kategori, Demo Users).

### 1.2 Rekod Keputusan Seni Bina (`architecture/decisions/`)
1. [ADR-001-blade-over-livewire.md](file:///c:/laragon/www/e-displin/architecture/decisions/ADR-001-blade-over-livewire.md) - Penggunaan Blade + Alpine.js (Strictly No Livewire/Vue/React).
2. [ADR-002-integer-id-plus-uuid.md](file:///c:/laragon/www/e-displin/architecture/decisions/ADR-002-integer-id-plus-uuid.md) - Dual identifier strategy (Internal ID + Public UUID).
3. [ADR-003-ai-human-decision.md](file:///c:/laragon/www/e-displin/architecture/decisions/ADR-003-ai-human-decision.md) - Sempadan автоnomy AI (Human-in-the-Loop decision maker).
4. [ADR-004-multi-school.md](file:///c:/laragon/www/e-displin/architecture/decisions/ADR-004-multi-school.md) - Multi-school readiness (`sekolah_id`) & kesediaan PPD/JPN masa hadapan.
5. [ADR-005-service-layer.md](file:///c:/laragon/www/e-displin/architecture/decisions/ADR-005-service-layer.md) - Enforcing Thin Controller, Service-Action pattern.

---

## 2. Keputusan Architecture Utama

- **Framework**: Laravel 13, PHP 8.4, MySQL 8 (InnoDB, `utf8mb4`).
- **Frontend Layer**: Laravel Blade Templates + Tailwind CSS 4 + Alpine.js. (DILARANG: Livewire, Vue, React, Inertia).
- **Core Pattern**: Thin Controller (< 100 baris), Form Request Validation, Action/Service Layer, Policy Authorization.
- **Identifier**: Internal `id` (`BIGINT AUTO_INCREMENT`) untuk Foreign Key, Public `uuid` (`CHAR(36)`) untuk Routing & Client Exposure.
- **Tenant Scope**: Pengasingan data baris berasaskan `sekolah_id` pada entiti utama. Bersedia untuk PPD/JPN (Future Expansion).
- **Notifications**: In-App Notification sahaja bagi Fasa 1 (`notifikasi` table). Extensible untuk Email/SMS/WhatsApp di masa hadapan.

---

## 3. Ringkasan Database Entity & Relationship

Sistem mentakrifkan 16 jadual pangkalan data utama:

1. `sekolah` (Soft Delete)
2. `tahun_akademik`
3. `pengguna` (Soft Delete)
4. `kelas` (Soft Delete)
5. `murid` (Soft Delete)
6. `penjaga` (Soft Delete)
7. `murid_penjaga` (Pivot Table)
8. `sejarah_kelas_murid` (Pivot History Table)
9. `kategori_disiplin` (Soft Delete)
10. `rekod_disiplin` (**STRICTLY NO DELETE / NO SOFT DELETE**. Void Mechanism: `is_void`, `void_reason`, `voided_by`, `voided_at`).
11. `tindakan_disiplin` (**IMMUTABLE**)
12. `lampiran_disiplin` (File Upload Metadata)
13. `sejarah_status_kes` (**IMMUTABLE**)
14. `notifikasi` (In-App Channel)
15. `aktiviti_log` (**IMMUTABLE**)
16. `ai_prompt_history` (**IMMUTABLE**)

---

## 4. Security Controls & Data Safeguards

- **Strict Access Control**: Peranan RBAC dikawal melalui `spatie/laravel-permission` & Laravel Policies (`RekodDisiplinPolicy`).
- **Void Auditability**: Pembatalan kes disiplin wajib melepasi kelulusan Guru Disiplin / PK HEM / Pengetua beserta input wajib `void_reason` (min 10 aksara) dan mendaftar log audit `VOID_KES`.
- **File Upload Protection**: Fail lampiran bukti disimpan dalam `storage/app/private/lampiran_disiplin` (di luar public web root) dan divalidasi jenis MIME server-side.
- **Web Security Standards**: CSRF (`@csrf`), XSS Escaping (`{{ }}`), SQL Parameter Binding, Rate Limiting Middleware, IDOR Protection via UUID.

---

## 5. Kawalan AI (AI Controls & Privacy Safeguards)

- **Human Authority**: AI tidak mempunyai kuasa autonomy untuk menukar status kes, menutup kes, atau mengenakan hukuman.
- **Data Minimization & Anonymization**: Perkhidmatan `AiDisciplineService` menapis nama murid, No. MyKad, dan alamat peribadi sebelum prompt dihantar kepada OpenAI API.
- **Immutable AI Audit Trail**: Setiap transaksi promosi/balasan AI direkodkan dalam jadual `ai_prompt_history` (`user_id`, `prompt`, `response`, `tokens`, `latency`).

---

## 6. Unresolved Issues, Assumptions & Risks

### 6.1 Unresolved Issues (Tiada - Semua Keputusan Clarification Telah Diselesaikan)
Semua 5 isu yang diaudit sebelum ini telah disahkan oleh pengguna pada 2026-08-10 (Merit/Demerit = Disabled; Kes Ringan = Kept; PPD/JPN = Future Expansion; Notification = In-App; Soft Delete/Void Policy = Approved).

### 6.2 Andaian (Assumptions)
1. Persekitaran pelayan Laragon / Local Server menyokong PHP 8.4 dan MySQL 8.
2. Akaun OpenAI API key sedia ada untuk kegunaan pengujian integrasi AI pada Fasa 4.

### 6.3 Risiko (Risks)
1. **Risiko Integriti Data**: Risiko pengguna tersilap buat laporan kes disiplin. *Mitigasi*: Mekanisme Pembatalan Kes (`Void`) dengan `void_reason` wajib & log aktiviti audit telah disediakan.
2. **Risiko Kebocoran Data Antarsekolah**: *Mitigasi*: Global Scope & Policy Enforcement pada `sekolah_id` wajib diuji menggunakan ujian automatik Pest PHP.

---

## 7. Perkara Yang Memerlukan Approval Mengenai Langkah Seterusnya

Dokumentasi dan reka bentuk Fasa 2 (**PHASE 2: DATABASE & SYSTEM DESIGN**) telah **100% LENGKAP** dan konsisten tanpa sebarang konflik.

**TIDAK SEBARANG APPLIKASI KOD, INSTALLATION LARAVEL, MIGRATION, ATAU MODEL DIBUAT.**

### Langkah Seterusnya Yang Memerlukan Arahan Pengguna:
- [ ] **Meluluskan (Approve) Phase 2 Design Review Report ini.**
- [ ] **Memberikan arahan eksplisit untuk memulakan Fasa 3 (PHASE 3: LARAVEL SETUP)**.
