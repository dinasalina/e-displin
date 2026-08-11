# 09 - Security Design & Access Control Specification

## 1. Pengenalan
Dokumen Security Design ini menggariskan seni bina keselamatan maklumat, kawalan capaian berasaskan peranan (RBAC Spatie Permission), perlindungan pangkalan data, dan kawalan muat naik fail bagi e-Disiplin (Dikemaskini Fasa 2.1).

## 2. Kawalan Capaian Berasaskan Peranan (RBAC Matrix Fasa 2.1)

Menggunakan **`spatie/laravel-permission`** rasmi (`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`) digabungkan dengan **Laravel Policies**:

| Kebenaran / Peranan | Guru | Guru Kelas | Guru Disiplin | PK HEM | Pengetua | Pentadbir Sekolah | Super Admin |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| `sekolah.urus` |  NO |  NO |  NO |  NO |  NO |  YES |  YES |
| `pengguna.urus` |  NO |  NO |  NO |  NO |  NO |  YES |  YES |
| `kelas.urus` |  NO |  NO |  NO |  NO |  NO |  YES |  YES |
| `murid.urus` |  NO |  NO |  NO |  NO |  NO |  YES |  YES |
| `penjaga.urus` |  NO |  NO |  NO |  NO |  NO |  YES |  YES |
| `disiplin.lapor` |  YES |  YES |  YES |  YES |  YES |  NO |  NO |
| `disiplin.lihat.sendiri` |  YES |  YES |  YES |  YES |  YES |  NO |  NO |
| `disiplin.lihat.kelas` |  NO |  YES |  YES |  YES |  YES |  NO |  NO |
| `disiplin.lihat.sekolah` |  NO |  NO |  YES |  YES |  YES |  NO |  NO |
| `disiplin.semak` |  NO |  NO |  YES |  YES |  YES |  NO |  NO |
| `disiplin.tindakan.ringan` |  NO |  NO |  YES |  YES |  YES |  NO |  NO |
| `disiplin.eskalasi.pkhem` |  NO |  NO |  NO |  YES |  YES |  NO |  NO |
| `disiplin.eskalasi.pengetua` |  NO |  NO |  NO |  NO |  YES |  NO |  NO |
| `disiplin.void` |  NO |  NO |  YES |  YES |  YES |  NO |  NO |

> **Nota Keselamatan Pentadbir Sekolah**: Pentadbir Sekolah mempunyai hak pengurusan master data sekolah tetapi **TIDAK** mempunyai kebenaran automatik untuk mengurus kes disiplin.

## 3. Matriks Kebolehcapaian & Pemadaman Data (Data Deletion Security)

```
+---------------------+-------------------+-----------------------------------+
| Entiti              | Jenis Kebenaran   | Kawalan Keselamatan               |
+---------------------+-------------------+-----------------------------------+
| sekolah             | Soft Delete       | Super Admin Sahaja                |
| pengguna            | Soft Delete       | Pentadbir Sekolah / Super Admin   |
| murid               | Soft Delete       | Pentadbir Sekolah                 |
| penjaga             | Soft Delete       | Pentadbir Sekolah                 |
| kelas               | Soft Delete       | Pentadbir Sekolah                 |
| kelas_guru          | Historic Record   | Pentadbir Sekolah                 |
| kategori_disiplin   | Soft Delete       | Pentadbir Sekolah                 |
| rekod_disiplin      | VOID / BATAL      | Guru Disiplin / PK HEM / Pengetua |
|                     | (NO DELETE)       | (Mesti sebut sebab void)          |
| eskalasi_kes        | State Tracking    | Strictly System Managed           |
| tindakan_disiplin   | IMMUTABLE         | Strict Insert Only                |
| sejarah_status_kes  | IMMUTABLE         | Strict Insert Only                |
| notifikasi          | User Owned        | Recipient Read / Soft Dismiss     |
| aktiviti_log        | IMMUTABLE         | System Insert Only                |
| ai_prompt_history   | IMMUTABLE         | System Insert Only (5-Yr Retention)|
+---------------------+-------------------+-----------------------------------+
```

## 4. Multi-School Unique Constraint Security
- Medan `no_kp` pada jadual `murid` diikat secara `UNIQUE(sekolah_id, no_kp)` untuk menjamin tiada konflik data murid merentasi sekolah berbeza.

## 5. Keselamatan Muat Naik Fail (File Upload Hardening)
1. **Validasi Server-Side**:
   - MIME Types dibenarkan: `image/jpeg`, `image/png`, `application/pdf`.
   - Saiz Maksimum: 5MB per fail.
2. **Penyimpanan Terlindung**:
   - Fail disimpan dalam `storage/app/private/lampiran_disiplin/...` (Di luar direktori public web root).
3. **Capaian Download Terkawal**:
   - Akses menerusi `LampiranDisiplinController@download` yang dilindungi oleh `RekodDisiplinPolicy@view`.
