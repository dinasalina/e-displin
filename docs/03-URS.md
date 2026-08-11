# 03 - User Requirements Specification (URS)

## 1. Pengenalan
Dokumen User Requirements Specification (URS) ini menjelaskan keperluan dari sudut pandangan pengguna sistem e-Disiplin mengikut peranan pengguna yang ditetapkan.

## 2. Profil Peranan Pengguna & Matriks Kebenaran Akses (Dikemaskini Fasa 2.1)

| Peranan | Penerangan Kumpulan Pengguna | Skop Kebenaran Akses Utama |
| :--- | :--- | :--- |
| **Super Admin** | Pentadbir sistem teknikal teratas | Mengurus sekolah, akaun pentadbir sekolah, menyemak audit log sistem. |
| **Pentadbir Sekolah** | Staff ICT / Pentadbiran Sekolah | **Mengurus Data Master**: Urus kelas, urus murid, urus penjaga, urus pengguna sekolah, urus tahun akademik, urus konfigurasi sekolah. *Tiada kuasa automatik pengurusan disiplin*. |
| **Guru Subjek / Akademik** | Semua guru di sekolah | Merekodkan kes salah laku awal murid, melihat rekod kes yang dilaporkan sendiri. |
| **Guru Kelas** | Guru yang mengurus kelas spesifik (`kelas_guru`) | Melihat rekod disiplin murid di dalam kelas jagaan semasa. |
| **Guru Disiplin** | AJK Disiplin Sekolah | Melihat semua kes sekolah, menyemak kes, menetapkan tindakan disiplin ringan/sederhana, mencadangkan eskalasi kes berat, mengesahkan pembatalan (`Void`). |
| **PK HEM** | Penolong Kanan Hal Ehwal Murid | Melihat semua kes, menerima eskalasi kes berat (`eskalasi_kes`), memberikan Kelulusan Peringkat Pertama kes berat, mengesahkan pembatalan. |
| **Pengetua / Guru Besar** | Pentadbir tertinggi sekolah | Menerima eskalasi kes berat (`eskalasi_kes`), memberikan Pengesahan Akhir kes berat (gantung/buang sekolah), melihat dashboard AI. |

## 3. Keperluan Pengguna Mengikut Modul (User Requirements - UR)

### UR-01: Modul Pengurusan Master & Pentadbiran (Pentadbir Sekolah)
- Pentadbir Sekolah boleh mengurus akaun pengguna sekolah, murid, penjaga, tahun akademik, dan kelas.
- Pentadbir Sekolah boleh mentetapkan guru kelas menerusi jadual `kelas_guru` (merekod `tarikh_mula` & `tarikh_tamat`).
- Pentadbir Sekolah **tidak boleh** mengakses borang semakan atau penutupan kes disiplin kecuali diberikan kebenaran khusus (*specific permission*).

### UR-02: Modul Pelaporan Kes (Guru)
- Guru boleh membuat carian murid mengikut nama atau NIK/MyKad (diikat secara `UNIQUE(sekolah_id, no_kp)`).
- Guru boleh mengisi borang laporan kes: Tarikh/Masa kejadian, Lokasi, Kategori Salah Laku, Tahap Kes (Ringan/Sederhana/Berat), Keterangan Kejadian, dan memuat naik fail lampiran bukti.

### UR-03: Modul Semakan & Sequential Escalation (Guru Disiplin, PK HEM, Pengetua)
- **Semakan Kes Biasa**: Guru Disiplin menyemak kes, menetapkan tindakan, dan menutup kes `RINGAN`/`SEDERHANA`.
- **Sequential Escalation Kes BERAT**:
  1. Guru Disiplin menukar status kes kepada `MENUNGGU_KELULUSAN` dan menjana rekod `eskalasi_kes` (Peringkat 1: PK HEM).
  2. PK HEM menerima notifikasi, menyemak kronologi, dan memasukkan keputusan Kelulusan Peringkat Pertama dalam `eskalasi_kes`. Kes seterusnya dialirkan kepada Pengetua/Guru Besar.
  3. Pengetua/Guru Besar menyemak dan memasukkan keputusan Pengesahan Akhir dalam `eskalasi_kes`.
  4. Kes dikemaskini secara automatik kepada status `DITUTUP`.

### UR-04: Modul Pembatalan Kes Rasmi (Void)
- Guru Disiplin, PK HEM, atau Pengetua boleh memicu fungsi `Void` bagi kes yang tersilap dilaporkan dengan memasukkan sebab pembatalan rasmi (`void_reason`).

### UR-05: Modul Notifikasi In-App
- Pengguna menerima notifikasi dalaman menerusi jadual `notifikasi` mengikut kebenaran `sekolah_id`.
- Antara muka notifikasi menyediakan paparan *unread count badge*, fungsi *mark as read*, dan *mark all as read*.
