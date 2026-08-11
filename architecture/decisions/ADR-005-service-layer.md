# ADR-005: Service Layer & Single Responsibility Actions Architecture

## Status
Diluluskan (Approved)

## Tarikh
2026-08-10

## Konteks & Latar Belakang
Untuk memastikan kod e-Disiplin boleh diuji (*testable*), selamat, dan mudah diselenggara (*maintainable*), struktur Controller perlulah mengikut prinsip *Thin Controller, Fat Service/Action*. Controller tidak boleh mengandungi logika perniagaan yang kompleks, panggilan API terus, atau query database berantai.

## Keputusan yang Dibuat

1. **Struktur Abstraksi**:
   - **Form Requests (`app/Http/Requests`)**: Bertanggungjawab memvalidasi input HTTP daripada pengguna dan memeriksa kebenaran asas (*authorization*).
   - **Controllers (`app/Http/Controllers`)**: Bertindak sebagai *router & coordinator* sahaja. Menerima Form Request, memanggil Action/Service, dan memulangkan Blade View atau JSON response (Thin Controller < 100 baris).
   - **Actions / Services (`app/Services` / `app/Actions`)**: Mengandungi semua logika perniagaan (*business logic*), pengurusan transaksi database (`DB::transaction`), pemicuan notifikasi, dan rakaman audit log.
   - **Policies (`app/Policies`)**: Menguruskan kawalan capaian berasaskan peranan (*RBAC*) dan konteks `sekolah_id`.
   - **Eloquent Models (`app/Models`)**: Berfungsi sebagai peta data, perhubungan entiti (*relationships*), local scopes, dan casts sahaja.

2. **Dilarang**:
   - Menulis logic transaksi pangkalan data terus dalam Controller.
   - Menulis pemicuan OpenAI API terus dalam Controller.
   - Menulis validation rules terus dalam Controller.

## Kesan & Implikasi (Consequences)

### Kesan Positif
- Memudahkan ujian Pest PHP / Unit Testing secara terasing tanpa memulakan HTTP request penuh.
- Reusability tinggi — logika perniagaan yang sama boleh dipanggil oleh Web Controller, CLI Artisan Command, atau Scheduled Jobs.
- Mengelakkan masalah "Fat Controller" yang sukar diselenggara apabila projek berkembang.

### Kesan Negatif / Kekangan
- Bilangan fail PHP meningkat (perlu mencipta Action/Service class bagi setiap modul perniagaan utama).
- Memerlukan pemahaman yang konsisten oleh semua pembina kod mengenai penetapan lapisan (*layer responsibility*).
