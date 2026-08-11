# ADR-004: Multi-School Ready Database & Scope Architecture Architecture

## Status
Diluluskan (Approved)

## Tarikh
2026-08-10

## Konteks & Latar Belakang
Pada Fasa 1, sistem e-Disiplin difokuskan untuk kegunaan *single-school deployment* (satu sekolah sahaja). Walau bagaimanapun, sasaran jangka panjang sistem adalah untuk berkembang ke peringkat Pejabat Pendidikan Daerah (PPD) dan Jabatan Pendidikan Negeri (JPN).

## Keputusan yang Dibuat

1. **Seni Bina Pangkalan Data (Multi-Tenant Ready via Multi-School Scope)**:
   - Semua entiti domain utama (`pengguna`, `kelas`, `murid`, `rekod_disiplin`, `kategori_disiplin`) mesti mempunyai Foreign Key `sekolah_id` yang merujuk kepada jadual `sekolah`.
   - Menggunakan pendekatan *Shared Database, Single Schema* dengan pengasingan baris (*Row-Level Tenant Isolation*).

2. **Kebenaran Kebolehaksesan (Authorization Boundary Fasa 1)**:
   - Pengguna sekolah (Guru, Guru Kelas, Guru Disiplin, PK HEM, Pengetua) disekat secara ketat melalui Laravel Eloquent Global Scopes / Policy hanya untuk membaca dan mengurus data yang mempunyai `sekolah_id` pengguna tersebut.

3. **Perkembangan Masa Hadapan (Future Expansion Boundary - PPD / JPN)**:
   - **TIDAK BINA** sebarang UI, Dashboard, atau Workflow khusus untuk PPD/JPN dalam Fasa 1.
   - **TIDAK BINA** peranan (roles) PPD/JPN dalam Fasa 1.
   - Jadual `sekolah` disediakan dengan kolum rujukan hirarki masa depan (`kod_ppd`, `kod_jpn`, `kod_sekolah`) supaya apabila modul PPD/JPN dibangunkan di fasa seterusnya, tiada *breaking changes* pada struktur pangkalan data.

## Kesan & Implikasi (Consequences)

### Kesan Positif
- Mengelakkan kerja merombak semula (*refactoring*) skema pangkalan data apabila sistem ditingkatkan ke peringkat daerah atau negeri.
- Memastikan pemisahan data (*data isolation*) yang kukuh dari hari pertama pembangunan.

### Kesan Negatif / Kekangan
- Setiap query Eloquent perlu sentiasa mengambil kira konteks `sekolah_id`.
- Pembina kod wajib menggunakan Policy / Scope untuk mengelakkan isu kebocoran data antarsekolah (*cross-tenant data leak*).
