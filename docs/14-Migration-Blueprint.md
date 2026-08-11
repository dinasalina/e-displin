# 14 - Migration Blueprint V2: e-Disiplin (SDMS)

## 1. Pengenalan
Dokumen Migration Blueprint V2 ini mendefinisikan urutan kronologi tepat pembinaan fail migration pangkalan data MySQL 8 bagi memastikan tiada rujukan Foreign Key yang terputus atau ralat kebergantungan (Dikemaskini Fasa 2.1).

## 2. Urutan Kebergantungan (Dependency Order) & Fail Migration

Secara keseluruhannya, sebanyak **19 fail migration** akan dibina mengikut urutan berpandukan kebergantungan jadual:

```text
database/migrations/
├── 2026_01_01_000001_create_sekolah_table.php
├── 2026_01_01_000002_create_tahun_akademik_table.php         (FK: sekolah_id)
├── 2026_01_01_000003_create_pengguna_table.php               (FK: sekolah_id)
├── 2026_01_01_000004_create_permission_tables.php             (Spatie Package: roles, permissions, model_has_roles, etc.)
├── 2026_01_01_000005_create_kelas_table.php                  (FK: sekolah_id, tahun_akademik_id)
├── 2026_01_01_000006_create_kelas_guru_table.php             (FK: kelas_id, pengguna_id, tahun_akademik_id)
├── 2026_01_01_000007_create_murid_table.php                  (FK: sekolah_id | UK: sekolah_id, no_kp)
├── 2026_01_01_000008_create_penjaga_table.php                (FK: sekolah_id)
├── 2026_01_01_000009_create_murid_penjaga_table.php          (FK: murid_id, penjaga_id)
├── 2026_01_01_000010_create_sejarah_kelas_murid_table.php    (FK: murid_id, kelas_id, tahun_akademik_id)
├── 2026_01_01_000011_create_kategori_disiplin_table.php      (FK: sekolah_id)
├── 2026_01_01_000012_create_rekod_disiplin_table.php         (FK: sekolah_id, murid_id, pelapor_id, kategori_disiplin_id)
├── 2026_01_01_000013_create_eskalasi_kes_table.php           (FK: rekod_disiplin_id, ditugaskan_oleh_id, penerima_id)
├── 2026_01_01_000014_create_tindakan_disiplin_table.php      (FK: rekod_disiplin_id, tetap_oleh_id)
├── 2026_01_01_000015_create_lampiran_disiplin_table.php      (FK: rekod_disiplin_id, muat_naik_oleh_id)
├── 2026_01_01_000016_create_sejarah_status_kes_table.php     (FK: rekod_disiplin_id, dikemaskini_oleh_id)
├── 2026_01_01_000017_create_notifikasi_table.php             (FK: sekolah_id, penerima_id)
├── 2026_01_01_000018_create_aktiviti_log_table.php            (FK: sekolah_id, pengguna_id)
└── 2026_01_01_000019_create_ai_prompt_history_table.php     (FK: sekolah_id, pengguna_id, rekod_disiplin_id)
```

## 3. Indeks & Indeks Unik Multi-School
1. **Unik Multi-School Murid**:  
   `$table->unique(['sekolah_id', 'no_kp'], 'unique_sekolah_murid_nokp');`
2. **Indeks Composite Prestasi**:
   - `rekod_disiplin`: `['sekolah_id', 'status_kes', 'tahap_kes']`
   - `eskalasi_kes`: `['rekod_disiplin_id', 'penerima_id', 'status']`
   - `notifikasi`: `['sekolah_id', 'penerima_id', 'is_dibaca']`
   - `kelas_guru`: `['kelas_id', 'tahun_akademik_id']`
