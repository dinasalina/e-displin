# 01 - Project Vision: e-Disiplin (SDMS)

## 1. Visi Utama
Menjadi platform pengurusan rekod disiplin murid digital yang paling selamat, telus, dan cekap di Malaysia, membolehkan pihak sekolah mengurus kes disiplin secara sistematik, adil, dan berteraskan analisis data pintar.

## 2. Objektif Strategik
1. **Digitalisasi Penuh Rekod Disiplin**: Menggantikan fail manual dan borang kertas dengan rekod digital terpusat yang disokong oleh kawalan keselamatan peranan.
2. **Standardisasi Alur Kerja Kes Berperingkat**: Memastikan setiap salah laku murid melalui alur kerja kelulusan yang sah (Laporkan → Semakan → Tindakan → Eskalasi Sequential → Tutup) secara berstruktur.
3. **Penglibatan AI yang Selamat**: Memanfaatkan keupayaan AI untuk membantu meringkaskan kes dan mengesan corak disiplin tanpa membelakangkan peranan manusia sebagai pembuat keputusan rasmi.
4. **Kesediaan Berskala Enterprise**: Membina asas teknologi yang mampu menampung pengembangan daripada pengurusan sekolah tunggal (*Single School*) kepada pengurusan berpusat peringkat Daerah (PPD) dan Negeri (JPN).

## 3. Sasaran Pengguna & Peranan Utama
- **Pentadbir Sekolah**: Mengurus data master sekolah (tahun akademik, pengguna/guru, kelas, murid, penjaga) dan tetapan konfigurasi sekolah. Tidak menguruskan kes disiplin secara automatik.
- **Guru Akademik / Guru Subjek**: Merekodkan laporan awal salah laku disiplin murid secara pantas dan tepat.
- **Guru Kelas**: Memantau rekod dan sejarah kes disiplin murid di bawah kelas jagaan masing-masing.
- **Guru Disiplin**: Menguruskan kes harian, menyemak laporan kes daripada guru, menetapkan tindakan ringan/sederhana, mengesahkan pembatalan (`Void`), serta mengurus eskalasi.
- **PK HEM (Penolong Kanan Hal Ehwal Murid)**: Menguruskan eskalasi kes berat (Kelulusan Peringkat Pertama), menetapkan intervensi, dan mengesahkan tindakan disiplin.
- **Pengetua / Guru Besar**: Pentadbir tertinggi sekolah yang menerima eskalasi kes kritikal (Pengesahan Akhir Hukuman Berat) serta melihat dashboard analisis AI.

## 4. Nilai Teras Sistem (Core Values)
- **Data Integrity & Traceability**: Setiap perubahan status, penugasan eskalasi (`eskalasi_kes`), dan tindakan mempunyai jejak audit (*audit trail*) yang tidak boleh diubah.
- **Single Source of Truth**: Sejarah kelas murid diuruskan menerusi `sejarah_kelas_murid` dan guru kelas menerusi `kelas_guru`.
- **Human-Centric AI**: AI berkhidmat untuk mempercepatkan analisis manusia, bukan menggantikan pertimbangan profesional pendidik.
- **Strict Tenant Isolation**: Data sekolah terasing secara ketat untuk menjamin kerahsiaan rekod murid.
