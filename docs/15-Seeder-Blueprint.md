# 15 - Seeder Blueprint: e-Disiplin (SDMS)

## 1. Pengenalan
Dokumen Seeder Blueprint ini mentakrifkan pelan pengisian data awal (*Database Seeding*) bagi sistem e-Disiplin menggunakan pakej rasmi `spatie/laravel-permission` (Dikemaskini Fasa 2.1).

## 2. Urutan Pelaksanaan Database Seeders

```text
database/seeders/
├── DatabaseSeeder.php
├── RoleAndPermissionSeeder.php
├── SekolahSeeder.php
├── KategoriDisiplinSeeder.php
├── DemoUserSeeder.php
└── DemoDisciplineRecordSeeder.php
```

## 3. Perincian Pengisian Data Awal

### 3.1 `RoleAndPermissionSeeder.php`
Daftarkan senarai peranan (Roles) dan kebenaran (Permissions) mengikut URS & Security Design Fasa 2.1:
- **Roles**:
  - `Super Admin`
  - `Pentadbir Sekolah`
  - `Guru`
  - `Guru Kelas`
  - `Guru Disiplin`
  - `PK HEM`
  - `Pengetua`
- **Permissions**:
  - **Master Data**: `sekolah.urus`, `pengguna.urus`, `kelas.urus`, `murid.urus`, `penjaga.urus`.
  - **Disiplin**: `disiplin.lapor`, `disiplin.lihat.sendiri`, `disiplin.lihat.kelas`, `disiplin.lihat.sekolah`, `disiplin.semak`, `disiplin.tindakan.ringan`, `disiplin.eskalasi.pkhem`, `disiplin.eskalasi.pengetua`, `disiplin.void`.

### 3.2 Tugasan Permission Mengikut Role
- **Pentadbir Sekolah**: `sekolah.urus`, `pengguna.urus`, `kelas.urus`, `murid.urus`, `penjaga.urus`. (*Strictly no discipline permissions unless custom granted*).
- **Guru**: `disiplin.lapor`, `disiplin.lihat.sendiri`.
- **Guru Kelas**: `disiplin.lapor`, `disiplin.lihat.sendiri`, `disiplin.lihat.kelas`.
- **Guru Disiplin**: `disiplin.lapor`, `disiplin.lihat.sekolah`, `disiplin.semak`, `disiplin.tindakan.ringan`, `disiplin.void`.
- **PK HEM**: `disiplin.lapor`, `disiplin.lihat.sekolah`, `disiplin.semak`, `disiplin.eskalasi.pkhem`, `disiplin.void`.
- **Pengetua**: `disiplin.lapor`, `disiplin.lihat.sekolah`, `disiplin.eskalasi.pengetua`, `disiplin.void`.

### 3.3 `DemoUserSeeder.php`
Menjana akaun pengguna demo bagi setiap peranan untuk pengujian:
- `admin@edisiplin.test` (`Super Admin`)
- `pentadbir@skseribintang.edu.my` (`Pentadbir Sekolah`)
- `gurudisiplin@skseribintang.edu.my` (`Guru Disiplin`)
- `pkhem@skseribintang.edu.my` (`PK HEM`)
- `pengetua@skseribintang.edu.my` (`Pengetua`)
- `guru1@skseribintang.edu.my` (`Guru`)
