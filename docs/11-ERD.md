# 11 - Entity Relationship Diagram (ERD V2) Specification

## 1. Pengenalan
Dokumen ini menggambarkan struktur perhubungan entiti (Entity Relationship Diagram V2) pangkalan data e-Disiplin mengikut notasi Mermaid.js (Dikemaskini Fasa 2.1).

## 2. Mermaid ERD V2 Diagram

```mermaid
erDiagram
    sekolah ||--o{ tahun_akademik : "mempunyai"
    sekolah ||--o{ pengguna : "mempunyai"
    sekolah ||--o{ kelas : "mempunyai"
    sekolah ||--o{ murid : "mempunyai"
    sekolah ||--o{ penjaga : "mempunyai"
    sekolah ||--o{ kategori_disiplin : "mempunyai"
    sekolah ||--o{ rekod_disiplin : "mempunyai"
    sekolah ||--o{ notifikasi : "mempunyai"

    tahun_akademik ||--o{ kelas : "mewakili_sesi"
    tahun_akademik ||--o{ kelas_guru : "mewakili_sesi_guru"
    tahun_akademik ||--o{ sejarah_kelas_murid : "mengurus_pendaftaran"

    pengguna ||--o{ kelas_guru : "tugasan_guru_kelas"
    pengguna ||--o{ rekod_disiplin : "dilaporkan_oleh"
    pengguna ||--o{ eskalasi_kes : "ditugaskan_oleh"
    pengguna ||--o{ eskalasi_kes : "diterima_oleh"
    pengguna ||--o{ tindakan_disiplin : "ditetapkan_oleh"
    pengguna ||--o{ sejarah_status_kes : "dikemaskini_oleh"
    pengguna ||--o{ aktiviti_log : "dilakukan_oleh"
    pengguna ||--o{ ai_prompt_history : "dipicu_oleh"
    pengguna ||--o{ model_has_roles : "mempunyai_peranan_spatie"

    kelas ||--o{ kelas_guru : "sejarah_guru_kelas"
    kelas ||--o{ sejarah_kelas_murid : "mengandungi"

    murid ||--o{ sejarah_kelas_murid : "mempunyai_sejarah_kelas"
    murid ||--o{ murid_penjaga : "dihubungkan"
    murid ||--o{ rekod_disiplin : "mempunyai_rekod"

    penjaga ||--o{ murid_penjaga : "dihubungkan"

    kategori_disiplin ||--o{ rekod_disiplin : "dikelaskan"

    rekod_disiplin ||--o{ eskalasi_kes : "rekod_eskalasi"
    rekod_disiplin ||--o{ tindakan_disiplin : "menerima"
    rekod_disiplin ||--o{ lampiran_disiplin : "lampiran_bukti"
    rekod_disiplin ||--o{ sejarah_status_kes : "jejak_status"
    rekod_disiplin ||--o{ ai_prompt_history : "rujukan_kes_ai"

    roles ||--o{ role_has_permissions : "mempunyai"
    roles ||--o{ model_has_roles : "diassign_kepada"
    permissions ||--o{ role_has_permissions : "diberikan_kepada"
    permissions ||--o{ model_has_permissions : "diberikan_secara_direct"

    sekolah {
        bigint id PK
        char uuid UK
        varchar kod_sekolah UK
        varchar nama_sekolah
        datetime deleted_at
    }

    pengguna {
        bigint id PK
        char uuid UK
        bigint sekolah_id FK
        varchar no_kp
        varchar email UK
        datetime deleted_at
    }

    kelas {
        bigint id PK
        char uuid UK
        bigint sekolah_id FK
        bigint tahun_akademik_id FK
        varchar nama_kelas
        datetime deleted_at
    }

    kelas_guru {
        bigint id PK
        bigint kelas_id FK
        bigint pengguna_id FK
        bigint tahun_akademik_id FK
        varchar peranan
        date tarikh_mula
    }

    murid {
        bigint id PK
        char uuid UK
        bigint sekolah_id FK
        varchar no_kp UK_sekolah_nokp
        varchar nama_penuh
        enum status_murid
        datetime deleted_at
    }

    sejarah_kelas_murid {
        bigint id PK
        bigint murid_id FK
        bigint kelas_id FK
        bigint tahun_akademik_id FK
    }

    rekod_disiplin {
        bigint id PK
        char uuid UK
        varchar no_kes UK
        bigint sekolah_id FK
        bigint murid_id FK
        bigint pelapor_id FK
        enum tahap_kes
        enum status_kes
        boolean is_void
    }

    eskalasi_kes {
        bigint id PK
        char uuid UK
        bigint rekod_disiplin_id FK
        bigint ditugaskan_oleh_id FK
        bigint penerima_id FK
        varchar jenis_eskalasi
        varchar status
        datetime ditugaskan_pada
    }
```

## 3. Matriks Perhubungan Entiti Utama (Relationship Cardinality Summary V2)
- `sekolah` (1) ── (N) `pengguna`, `murid`, `kelas`, `rekod_disiplin`
- `kelas` (1) ── (N) `kelas_guru` (Menghapuskan `guru_kelas_id` pada `kelas`)
- `murid` (1) ── (N) `sejarah_kelas_murid` (Menghapuskan `kelas_id` pada `murid`)
- `rekod_disiplin` (1) ── (N) `eskalasi_kes` (Sequential Approval tracking)
- `rekod_disiplin` (1) ── (N) `tindakan_disiplin` (**IMMUTABLE**)
- `rekod_disiplin` (1) ── (N) `sejarah_status_kes` (**IMMUTABLE**)
- `pengguna` (1) ── (N) `model_has_roles` (Spatie Laravel Permission Integration)
