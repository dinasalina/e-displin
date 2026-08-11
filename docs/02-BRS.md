# 02 - Business Requirements Specification (BRS)

## 1. Pengenalan
Dokumen Business Requirements Specification (BRS) ini mendefinisikan keperluan perniagaan tahap tinggi bagi Sistem Rekod Disiplin Murid Digital (e-Disiplin / SDMS).

## 2. Skop Perniagaan Fasa 1 (Dikemaskini Fasa 2.1)

### 2.1 Skop Yang Dimerangkumi (In-Scope Fasa 1)
1. **Pengurusan Entiti Asas Sekolah**: Pengurusan profil sekolah, tahun akademik, kelas (`kelas`), penugasan guru kelas (`kelas_guru`), pengguna, murid (`murid`), dan penjaga (`penjaga`).
2. **Penempatan Kelas & Sejarah**: Sejarah penempatan murid dalam kelas diuruskan strictly menggunakan `sejarah_kelas_murid` (Single Source of Truth).
3. **Pengurusan Kategori Salah Laku**: Pengelasan kes mengikut kategori serta Tahap Kes (`RINGAN`, `SEDERHANA`, `BERAT`).
4. **Pengurusan Rekod & Eskalasi Sequential Disiplin**:
   - Pelaporan kes awal oleh Guru.
   - Semakan kes oleh Guru Disiplin.
   - Penetapan tindakan disiplin (Amaran, Sesi Kaunseling, dll.).
   - **Eskalasi Sequential Kes BERAT**: Rekod kes dialirkan menerusi jadual `eskalasi_kes` bagi Kelulusan Peringkat Pertama oleh PK HEM, diikuti Pengesahan Akhir oleh Pengetua/Guru Besar sebelum status ditukar kepada `DITUTUP`.
   - Mekanisme pembatalan rekod tersilap melalui alur kerja **Void/Batal** (strictly NO delete).
5. **Notifikasi Dalaman (In-App Notifications)**: Notifikasi status kes, tugasan eskalasi, dan tindakan di dalam jadual `notifikasi`.
6. **Pembantu Analisis AI**: Meringkaskan laporan kes dan menganalisis trend kes sekolah secara anonim melalui Laravel AI SDK & konfigurasi model dinamik (`config/ai.php`).
7. **Audit Trail & Spatie Permission Integration**: Jejak log aktiviti sistem yang kekal (*immutable*) dan kawalan peranan menggunakan `spatie/laravel-permission`.

### 2.2 Skop Yang Dikecualikan (Out-of-Scope Fasa 1)
1. **Sistem Merit / Demerit / Points**: Tiada modul pengiraan mata merit atau mata demotivation dalam Fasa 1.
2. **UI & Workflow PPD / JPN**: Sistem beroperasi di peringkat sekolah sahaja (Future Expansion ready).
3. **Saluran Notifikasi Luaran**: Tiada penghantaran notifikasi melalui SMS, WhatsApp API, atau E-mel dalam Fasa 1.
4. **Auto-Deletion Kes**: Kes disiplin kekal sebagai sejarah rekod murid dan tidak dipadam secara automatik.

## 3. Keperluan Perniagaan Utama (Business Requirements - BR)

### BR-01: Alur Kerja Disiplin & Sequential Escalation
Sistem mesti memastikan kes disiplin ber-tahap `BERAT` mengikut alur kelulusan berurutan (Sequential Approval):
- **Langkah 1**: Guru Disiplin menyemak kes dan menukar status kepada `MENUNGGU_KELULUSAN`.
- **Langkah 2**: PK HEM menerima tugasan eskalasi (`eskalasi_kes`) dan memberikan Kelulusan Peringkat Pertama.
- **Langkah 3**: Pengetua/Guru Besar menerima tugasan eskalasi (`eskalasi_kes`) dan memberikan Pengesahan Akhir.
- **Langkah 4**: Kes ditukar status kepada `DITUTUP`.

### BR-02: Kebenaran Akses Pentadbir vs Pengurusan Disiplin
- **Pentadbir Sekolah**: Berhak mengurus kelas, murid, penjaga, pengguna sekolah, tahun akademik, dan konfigurasi sekolah. Pentadbir Sekolah **TIDAK** mempunyai kuasa automatik untuk mengurus kes disiplin kecuali jika diberikan kebenaran khusus.
- **Guru Disiplin / PK HEM / Pengetua**: Berkuasa menguruskan semakan, tindakan, eskalasi, dan pembatalan kes disiplin.

### BR-03: Single Source of Truth bagi Penempatan Murid & Guru Kelas
- Penempatan murid dalam kelas ditentukan melalui jadual `sejarah_kelas_murid` berdasarkan tahun akademik aktif.
- Penugasan guru kelas diuruskan menerusi jadual `kelas_guru` untuk menyokong sejarah dan pertukaran guru kelas.

### BR-04: Integriti & Pembatalan Rekod (Void Rules)
Setiap rekod kes disiplin tidak boleh dipadam. Jika berlaku kesilapan, kes mesti dibatalkan (`is_void = true`) dengan alasan rasmi yang direkodkan dalam jejak audit.

### BR-05: Transparensi AI & Dynamic Configuration Layer
Panggilan AI mengguna pakai konfigurasi dinamik (`config/ai.php`). Prompt dihantar secara anonim, balasan disemak tanpa PII murid, dan transaksi direkodkan secara *immutable* di dalam `ai_prompt_history`.
