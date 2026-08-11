# 05 - Business Rules Specification

## 1. Pengenalan
Dokumen Business Rules Specification ini menetapkan peraturan perniagaan (Business Rules - BRUL) yang wajib dipatuhi dalam pengkodan logic perniagaan e-Disiplin (Dikemaskini Fasa 2.1).

## 2. Peraturan Perniagaan Teras (Business Rules - BRUL)

### BRUL-01: Peraturan Pendaftaran Rekod Disiplin
1. Mana-mana Guru yang berdaftar di sekolah berhak melaporkan kes salah laku murid.
2. Rekod kes baharu secara automatik menerima status awal `DILAPORKAN`.
3. Rekod kes mesti ditanda dengan salah satu daripada tiga tahap kes graviti:
   - `RINGAN`
   - `SEDERHANA`
   - `BERAT`
4. Tarikh kejadian tidak boleh melepasi tarikh/masa semasa (*future date is forbidden*).

### BRUL-02: Peraturan Sequential Approval Kes BERAT
1. Status kes yang sah terdiri daripada Enum berikut: `DILAPORKAN`, `DALAM_SEMAKAN`, `DALAM_TINDAKAN`, `MENUNGGU_KELULUSAN`, `DITUTUP`.
2. Kes `RINGAN` dan `SEDERHANA` boleh ditutup secara terus oleh Guru Disiplin selepas tindakan ditetapkan.
3. Kes `BERAT` WAJIB melepasi kelulusan berurutan (Sequential Approval) dua peringkat:
   - **Peringkat 1 (PK HEM)**: Guru Disiplin menukar status kes kepada `MENUNGGU_KELULUSAN` dan mencipta rekod `eskalasi_kes` untuk PK HEM. PK HEM memasukkan keputusan kelulusan.
   - **Peringkat 2 (Pengetua/Guru Besar)**: Kes dialirkan kepada Pengetua/Guru Besar menerusi rekod `eskalasi_kes` baharu untuk Pengesahan Akhir Hukuman.
   - **Penutupan Kes**: Selepas Pengesahan Akhir Pengetua/Guru Besar direkodkan, status kes dikemaskini secara automatik kepada `DITUTUP`.

### BRUL-03: Peraturan Pembatalan Rekod Kes (Void Rules)
1. Rekod kes disiplin rasmi **DILARANG DIPADAM** dari pangkalan data (Strictly NO Delete).
2. Jika berlaku laporan palsu atau kesilapan fakta, kes mesti dibatalkan melalui alur kerja `Void`.
3. Pembatalan kes hanya boleh dilakukan oleh Guru Disiplin, PK HEM, atau Pengetua/Guru Besar dengan memasukkan `void_reason` (minimum 10 aksara).
4. Apabila kes dibatalkan (`is_void = true`), rekod disembunyikan daripada statistik kes aktif tetapi kekal dalam jejak audit.

### BRUL-04: Peraturan Single Source of Truth (Kelas & Guru Kelas)
1. Penempatan kelas murid diuruskan STRICTLY melalui jadual `sejarah_kelas_murid` berdasarkan tahun akademik aktif. Medan `kelas_id` DILARANG ditambah pada jadual `murid`.
2. Penugasan guru kelas diuruskan STRICTLY melalui jadual `kelas_guru` (`kelas_id`, `pengguna_id`, `tahun_akademik_id`, `tarikh_mula`, `tarikh_tamat`). Medan `guru_kelas_id` DILARANG diletakkan pada `kelas`.

### BRUL-05: Peraturan Batasan Peranan Pentadbir Sekolah
1. `Pentadbir Sekolah` mempunyai hak penuh untuk mengurus kelas, murid, penjaga, pengguna sekolah, tahun akademik, dan konfigurasi sekolah.
2. `Pentadbir Sekolah` **TIDAK** mempunyai kebenaran automatik untuk mengurus, menyemak, meluluskan, atau membatalkan kes disiplin.

### BRUL-06: Peraturan AI Configuration, Privacy & Retention
1. Model AI dikawal melalui lapisan konfigurasi `config/ai.php` (`config('ai.default_model')`). Model tidak di-hardcode sebagai business rule.
2. Teks prompt wajib di-anonymize daripada sebarang PII murid (Nama, MyKad, Alamat, Ibu Bapa) sebelum dihantar kepada AI Provider.
3. Sejarah pemicuan AI direkodkan secara *immutable* di dalam `ai_prompt_history` dan disimpan selama 5 tahun sebelum diarkibkan.

### BRUL-07: Peraturan In-App Notification System (`notifikasi`)
1. Notifikasi dihantar secara automatik menerusi jadual `notifikasi` bagi tugasan kes baharu, penugasan eskalasi (`eskalasi_kes`), dan penutupan kes.
2. Notifikasi menyokong penandaan *mark as read*, *mark all as read*, dan kiraan *unread count badge*. Channel luaran (SMS/Email/WhatsApp) tidak diaktifkan pada Fasa 1.
