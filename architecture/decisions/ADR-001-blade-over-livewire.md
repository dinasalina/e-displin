# ADR-001: Blade Rendering dan Alpine.js over Livewire / SPA Frameworks

## Status
Diluluskan (Approved)

## Tarikh
2026-08-10

## Konteks & Latar Belakang
Sistem e-Disiplin (SDMS) ialah sistem pengurusan rekod disiplin murid berperingkat enterprise. Dalam pemilihan teknologi frontend, pasukan perlu memutuskan antara pendekatan rendering tradisional Blade dengan Alpine.js berbanding kerangka kerja reaktif seperti Laravel Livewire, Vue.js, React, atau Inertia.js.

## Faktor Keputusan (Decision Drivers)
1. **Kebolehselenggaraan (Maintainability)**: Kemudahan penyelenggaraan jangka panjang oleh pasukan pembangunan awam/sekolah tanpa dependency frontend build toolchain yang kompleks.
2. **Prestasi & Kecekapan Server**: Mengurangkan beban websocket/server state synchronization yang tinggi dalam persekitaran Livewire apabila bilangan pengguna serentak meningkat.
3. **Kesederhanaan Seni Bina (Architecture Simplicity)**: Mengelakkan kebergantungan ketat (tight coupling) antara komponen reaktif server-state dengan Blade views.
4. **Tahap Keselamatan (Security Boundary)**: Memastikan kelakuan UI dan validasi kekal jelas antara client-side enhancement dan server-side execution.

## Keputusan yang Dibuat
Memutuskan untuk menggunakan **Laravel Blade Templates** sebagai enjin rendering utama frontend, digabungkan dengan **Tailwind CSS 4** untuk reka bentuk visual dan **Alpine.js** untuk interaktiviti lightweight di bahagian client.

**Teknologi yang DILARANG:**
- Laravel Livewire
- Vue.js
- React.js
- Inertia.js

## Kesan & Implikasi (Consequences)

### Kesan Positif
- Enjin rendering Blade adalah sangat stabil, laju, dan disokong secara natif oleh Laravel.
- Alpine.js memberikan keupayaan interaktif client-side (seperti modal, dropdown, tab, toggle) tanpa membebankan server.
- Tiada isu synchronization state server-client yang kerap berlaku dalam Livewire.
- Memudahkan ujian UI dan integrasi komponen standard.

### Kesan Negatif / Kekangan
- Untuk interaksi yang memerlukan kemaskini dinamik tanpa reload halaman (seperti carian dinamik atau notifikasi masa-nyata), panggilan AJAX/Fetch API bertema Alpine.js perlu ditulis secara manual.
- Memerlukan disiplin tinggi dalam penyusunan fail Blade partials dan komponen Tailwind CSS supaya kod UI tidak bersepah.

## Kawalan Pelaksanaan (Implementation Guidelines)
1. Semua form submission mesti melalui *Form Request Validation* standard Laravel.
2. Penggunaan Alpine.js terhad kepada manipulasi UI tempatan (DOM manipulation, modal toggle, form field dependency).
3. Panggilan API dinamik (contoh: carian murid async) mesti melalui REST endpoints Laravel yang dipanggil menggunakan Alpine `fetch()`.
