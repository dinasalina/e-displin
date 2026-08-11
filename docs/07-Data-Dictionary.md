# 07 - Data Dictionary: e-Disiplin (SDMS)

Dokumen Kamus Data ini mentakrifkan struktur jadual pangkalan data MySQL 8 bagi sistem e-Disiplin (Dikemaskini Fasa 2.1). Semua jadual menggunakan enjin **InnoDB**, charset **`utf8mb4`**, dan collation **`utf8mb4_unicode_ci`**.

---

## 1. Jadual: `sekolah`
Mengurus maklumat profil sekolah (Soft Delete).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `kod_sekolah` | `VARCHAR(20)` | Unique, Not Null | Kod rasmi sekolah (contoh: ABA1001) |
| `nama_sekolah` | `VARCHAR(255)` | Not Null | Nama rasmi sekolah |
| `kod_ppd` | `VARCHAR(20)` | Nullable | Kod PPD rujukan (Future Expansion) |
| `kod_jpn` | `VARCHAR(20)` | Nullable | Kod JPN rujukan (Future Expansion) |
| `jenis_sekolah` | `ENUM('RENDAH', 'MENENGAH')` | Not Null | Kategori sekolah |
| `telefon` | `VARCHAR(20)` | Nullable | No. telefon sekolah |
| `emel` | `VARCHAR(100)` | Nullable | Emel rasmi sekolah |
| `alamat` | `TEXT` | Nullable | Alamat fizikal sekolah |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

---

## 2. Jadual: `tahun_akademik`
Mengurus sesi akademik sekolah.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `nama_tahun` | `VARCHAR(50)` | Not Null | Contoh: "2025/2026" |
| `tarikh_mula` | `DATE` | Not Null | Tarikh mula sesi |
| `tarikh_tamat` | `DATE` | Not Null | Tarikh tamat sesi |
| `is_aktif` | `BOOLEAN` | Default: `false`, Index | Penanda sesi akademik aktif semasa |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |

---

## 3. Jadual: `pengguna`
Mengurus akaun pengguna/guru/pentadbir (Soft Delete). Menggunakan Spatie Permission (`HasRoles`).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `nama` | `VARCHAR(255)` | Not Null | Nama penuh pengguna |
| `no_kp` | `VARCHAR(20)` | Index, Not Null | No. Kad Pengenalan / NIK |
| `email` | `VARCHAR(255)` | Unique, Not Null | Alamat emel log masuk |
| `password` | `VARCHAR(255)` | Not Null | Kata laluan ter-hash |
| `jawatan` | `VARCHAR(100)` | Nullable | Jawatan rasmi sekolah |
| `status_aktif` | `BOOLEAN` | Default: `true` | Status akaun aktif |
| `remember_token` | `VARCHAR(100)` | Nullable | Token sesi |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

---

## 4. Jadual: `kelas`
Mengurus senarai kelas (Soft Delete). *Nota: Rujukan `guru_kelas_id` dibuang, digantikan oleh entiti `kelas_guru`*.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `tahun_akademik_id` | `BIGINT UNSIGNED` | Foreign Key (`tahun_akademik.id`), Index | Rujukan sesi akademik |
| `nama_kelas` | `VARCHAR(50)` | Not Null | Contoh: "5 Cemerlang" |
| `tingkatan_darjah` | `TINYINT UNSIGNED` | Not Null | Darjah 1-6 atau Tingkatan 1-6 |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

---

## 5. Jadual: `kelas_guru` (BAHARU)
Mengurus penugasan dan sejarah guru kelas.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `kelas_id` | `BIGINT UNSIGNED` | Foreign Key (`kelas.id`), Index | Rujukan kelas |
| `pengguna_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Rujukan pengguna/guru |
| `tahun_akademik_id` | `BIGINT UNSIGNED` | Foreign Key (`tahun_akademik.id`), Index | Rujukan sesi akademik |
| `peranan` | `VARCHAR(50)` | Default: `'GURU_UTAMA'` | Peranan guru kelas (GURU_UTAMA / GURU_PENDAMPING) |
| `tarikh_mula` | `DATE` | Not Null | Tarikh mula penugasan |
| `tarikh_tamat` | `DATE` | Nullable | Tarikh tamat penugasan |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |

---

## 6. Jadual: `murid`
Mengurus rekod profil murid sekolah (Soft Delete). *Nota: DILARANG mengandungi `kelas_id`*.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `nisn_nis` | `VARCHAR(30)` | Index, Nullable | No. Induk / Matrik Murid |
| `no_kp` | `VARCHAR(20)` | Not Null | No. Kad Pengenalan / MyKid |
| `nama_penuh` | `VARCHAR(255)` | Not Null, Index | Nama Penuh Murid |
| `jantina` | `ENUM('LELAKI', 'PEREMPUAN')` | Not Null | Jantina murid |
| `tarikh_lahir` | `DATE` | Not Null | Tarikh lahir |
| `status_murid` | `ENUM('AKTIF', 'ALUMNI', 'PINDAH', 'GANTUNG', 'BUANG')` | Default: `'AKTIF'`, Index | Status keberadaan murid |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

> **Unique Constraint Multi-School**: `UNIQUE KEY unique_sekolah_murid_nokp (sekolah_id, no_kp)`

---

## 7. Jadual: `penjaga`
Mengurus maklumat ibu bapa / penjaga (Soft Delete).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `nama_penuh` | `VARCHAR(255)` | Not Null | Nama Ibu Bapa / Penjaga |
| `no_kp` | `VARCHAR(20)` | Index, Not Null | No. Kad Pengenalan Penjaga |
| `no_telefon` | `VARCHAR(20)` | Not Null | No. Telefon Utama |
| `email` | `VARCHAR(255)` | Nullable | Alamat emel penjaga |
| `hubungkait` | `VARCHAR(50)` | Not Null | Contoh: Bapa, Ibu, Penjaga Sah |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

---

## 8. Jadual: `murid_penjaga`
Jadual ikatan pivot antara Murid dan Penjaga.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `murid_id` | `BIGINT UNSIGNED` | Foreign Key (`murid.id`), Index | Rujukan murid |
| `penjaga_id` | `BIGINT UNSIGNED` | Foreign Key (`penjaga.id`), Index | Rujukan penjaga |
| `is_penjaga_utama` | `BOOLEAN` | Default: `true` | Penanda penjaga utama |
| `created_at` | `TIMESTAMP` | Nullable | Masa pendaftaran |

---

## 9. Jadual: `sejarah_kelas_murid`
Single Source of Truth penempatan murid ke kelas bagi setiap tahun akademik.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `murid_id` | `BIGINT UNSIGNED` | Foreign Key (`murid.id`), Index | Rujukan murid |
| `kelas_id` | `BIGINT UNSIGNED` | Foreign Key (`kelas.id`), Index | Rujukan kelas |
| `tahun_akademik_id` | `BIGINT UNSIGNED` | Foreign Key (`tahun_akademik.id`), Index | Rujukan sesi akademik |
| `created_at` | `TIMESTAMP` | Nullable | Masa pendaftaran |

---

## 10. Jadual: `kategori_disiplin`
Mengurus senarai jenis dan pengelasan kes salah laku (Soft Delete).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `kod_kategori` | `VARCHAR(30)` | Not Null | Contoh: "PONTENG", "BULI" |
| `nama_kategori` | `VARCHAR(255)` | Not Null | Nama penuh kategori salah laku |
| `tahap_default` | `ENUM('RINGAN', 'SEDERHANA', 'BERAT')` | Not Null | Tahap graviti laluan |
| `penerangan` | `TEXT` | Nullable | Perincian kategori kes |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |
| `deleted_at` | `TIMESTAMP` | Nullable, Index | Masa soft delete |

---

## 11. Jadual: `rekod_disiplin`
Jadual teras pengurusan kes salah laku (**NO Delete / Void Mechanism**).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `no_kes` | `VARCHAR(50)` | Unique, Not Null, Index | No. rujukan kes (contoh: KES/2026/08/0001) |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `murid_id` | `BIGINT UNSIGNED` | Foreign Key (`murid.id`), Index | Rujukan murid yang dilaporkan |
| `pelapor_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Rujukan guru yang melaporkan |
| `kategori_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`kategori_disiplin.id`), Index | Kategori salah laku |
| `tahap_kes` | `ENUM('RINGAN', 'SEDERHANA', 'BERAT')` | Not Null, Index | Tahap graviti kes |
| `status_kes` | `ENUM('DILAPORKAN', 'DALAM_SEMAKAN', 'DALAM_TINDAKAN', 'MENUNGGU_KELULUSAN', 'DITUTUP')` | Default: `'DILAPORKAN'`, Index | Status terkini kes |
| `tarikh_kejadian` | `DATETIME` | Not Null, Index | Tarikh & masa salah laku berlaku |
| `lokasi_kejadian` | `VARCHAR(255)` | Not Null | Lokasi kejadian di sekolah |
| `keterangan_kes` | `LONGTEXT` | Not Null | Huraian kronologi lengkap kejadian |
| `ringkasan_ai` | `TEXT` | Nullable | Ringkasan kronologi kes yang dijana oleh AI |
| `is_void` | `BOOLEAN` | Default: `false`, Index | Penanda pembatalan rasmi kes |
| `void_reason` | `TEXT` | Nullable | Alasan rasmi pembatalan kes |
| `voided_by` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Nullable | Pengguna yang membatalkan kes |
| `voided_at` | `DATETIME` | Nullable | Tarikh & masa pembatalan |
| `tarikh_ditutup` | `DATETIME` | Nullable | Tarikh & masa kes ditutup rasmi |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |

---

## 12. Jadual: `eskalasi_kes` (BAHARU)
Mengurus tugasan, alur eskalasi, dan keputusan kelulusan berurutan (Sequential Approval) bagi kes BERAT.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `rekod_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`rekod_disiplin.id`), Index | Rujukan kes disiplin |
| `ditugaskan_oleh_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pegawai yang menyerahkan eskalasi |
| `penerima_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pegawai penerima tugasan (PK HEM / Pengetua) |
| `jenis_eskalasi` | `VARCHAR(50)` | Not Null | Contoh: 'SEMAKAN_PK_HEM', 'PENGESAHAN_PENGETUA' |
| `status` | `VARCHAR(50)` | Default: `'MENUNGGU'` | Status eskalasi ('MENUNGGU', 'DILULUSKAN', 'DITOLAK') |
| `catatan` | `TEXT` | Nullable | Catatan/arahan semasa penugasan |
| `keputusan` | `VARCHAR(100)` | Nullable | Keputusan rasmi pegawai |
| `catatan_keputusan` | `TEXT` | Nullable | Ulasan/justifikasi keputusan |
| `ditugaskan_pada` | `DATETIME` | Not Null | Tarikh & masa penugasan dibuat |
| `diputuskan_pada` | `DATETIME` | Nullable | Tarikh & masa keputusan dibuat |
| `created_at` | `TIMESTAMP` | Nullable | Masa rekod dicipta |
| `updated_at` | `TIMESTAMP` | Nullable | Masa rekod dikemaskini |

---

## 13. Jadual: `tindakan_disiplin`
Mengurus keputusan hukuman / intervensi kes (**IMMUTABLE**).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `rekod_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`rekod_disiplin.id`), Index | Rujukan kes |
| `tetap_oleh_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pengguna yang menetapkan tindakan |
| `jenis_tindakan` | `VARCHAR(100)` | Not Null | Contoh: Amaran Lisan, Kaunseling, Gantung Sekolah |
| `keterangan_tindakan` | `TEXT` | Not Null | Perincian tindakan / syarat intervensi |
| `tarikh_mula` | `DATE` | Nullable | Tarikh mula hukuman (jika ada) |
| `tarikh_tamat` | `DATE` | Nullable | Tarikh tamat hukuman (jika ada) |
| `created_at` | `TIMESTAMP` | Nullable | Tarikh rekod tindakan dibuat |

---

## 14. Jadual: `lampiran_disiplin`
Mengurus muat naik fail bukti kes disiplin.

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `rekod_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`rekod_disiplin.id`), Index | Rujukan kes |
| `nama_fail_asal` | `VARCHAR(255)` | Not Null | Nama asal fail yang dimuat naik |
| `path_fail` | `VARCHAR(255)` | Not Null | Laluan fizikal fail dalam storage teras |
| `mime_type` | `VARCHAR(100)` | Not Null | Jenis fail (image/png, application/pdf) |
| `saiz_bytes` | `BIGINT UNSIGNED` | Not Null | Saiz fail dalam bytes |
| `muat_naik_oleh_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pengguna yang memuat naik fail |
| `created_at` | `TIMESTAMP` | Nullable | Tarikh muat naik |

---

## 15. Jadual: `sejarah_status_kes`
Mengurus pergerakan jejak alur kerja kes (**IMMUTABLE**).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `rekod_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`rekod_disiplin.id`), Index | Rujukan kes |
| `dikemaskini_oleh_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pengguna yang menukar status |
| `status_asal` | `VARCHAR(50)` | Nullable | Status sebelum perubahan |
| `status_baharu` | `VARCHAR(50)` | Not Null | Status selepas perubahan |
| `nota_perubahan` | `TEXT` | Nullable | Catatan/alasan perubahan status |
| `created_at` | `TIMESTAMP` | Nullable | Tarikh perubahan status |

---

## 16. Jadual: `notifikasi`
Mengurus notifikasi dalaman (In-App Notification).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `uuid` | `CHAR(36)` | Unique, Not Null | Pengenal awam |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `penerima_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pengguna yang menerima notifikasi |
| `tajuk` | `VARCHAR(255)` | Not Null | Tajuk notifikasi |
| `mesej` | `TEXT` | Not Null | Kandungan pesanan notifikasi |
| `url_tindakan` | `VARCHAR(255)` | Nullable | Pautan pantas ke URL kes berkaitan |
| `is_dibaca` | `BOOLEAN` | Default: `false`, Index | Penanda notifikasi dibaca (unread status) |
| `created_at` | `TIMESTAMP` | Nullable | Tarikh notifikasi dicipta |

---

## 17. Jadual: `aktiviti_log`
Mengurus rekod jejak audit sistem (**IMMUTABLE**).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Nullable, Index | Rujukan sekolah |
| `pengguna_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Nullable, Index | Pengguna yang melakukan tindakan |
| `jenis_aktiviti` | `VARCHAR(100)` | Not Null, Index | Contoh: "CREATE_KES", "VOID_KES", "LOGIN" |
| `penerangan` | `TEXT` | Not Null | Huraian aktiviti |
| `ip_address` | `VARCHAR(45)` | Nullable | Alamat IP pengguna |
| `user_agent` | `TEXT` | Nullable | Maklumat pelayar pengguna |
| `data_lama` | `JSON` | Nullable | Data sebelum dikemaskini |
| `data_baharu` | `JSON` | Nullable | Data selepas dikemaskini |
| `created_at` | `TIMESTAMP` | Nullable | Masa aktiviti berlaku |

---

## 18. Jadual: `ai_prompt_history`
Mengurus rekod audit panggilan AI (**IMMUTABLE**).

| Nama Kolum | Jenis Data | Syarat / Indeks | Penerangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Primary Key, Auto Increment | Kunci utama dalaman |
| `sekolah_id` | `BIGINT UNSIGNED` | Foreign Key (`sekolah.id`), Index | Rujukan sekolah |
| `pengguna_id` | `BIGINT UNSIGNED` | Foreign Key (`pengguna.id`), Index | Pengguna yang memicu AI |
| `rekod_disiplin_id` | `BIGINT UNSIGNED` | Foreign Key (`rekod_disiplin.id`), Nullable, Index | Rujukan kes (jika ada) |
| `provider` | `VARCHAR(50)` | Not Null | Provider AI (contoh: "openai") |
| `model` | `VARCHAR(50)` | Not Null | Model AI (snapshot dari `config('ai.default_model')`) |
| `prompt_text` | `LONGTEXT` | Not Null | Kandungan prompt (Anonim tanpa PII) |
| `response_text` | `LONGTEXT` | Not Null | Kandungan balasan daripada AI |
| `tokens_input` | `INT UNSIGNED` | Default: `0` | Bilangan token input |
| `tokens_output` | `INT UNSIGNED` | Default: `0` | Bilangan token output |
| `latency_ms` | `INT UNSIGNED` | Default: `0` | Masa tindak balas dalam milisaat |
| `created_at` | `TIMESTAMP` | Nullable | Masa transaksi AI |

---

## 19. Jadual Pakej Rasmi: Spatie Laravel Permission
Mengurus peranan dan kebenaran rasmi (`spatie/laravel-permission` v6).
1. `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
2. `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
3. `model_has_roles` (`role_id`, `model_type`, `model_id`)
4. `model_has_permissions` (`permission_id`, `model_type`, `model_id`)
5. `role_has_permissions` (`permission_id`, `role_id`)
