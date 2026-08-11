# ADR-002: Dual Identifier Strategy (Auto-Increment Integer ID + Public UUID)

## Status
Diluluskan (Approved)

## Tarikh
2026-08-10

## Konteks & Latar Belakang
Dalam sistem enterprise seperti e-Disiplin, pengenalan rekod dalam pangkalan data perlu memenuhi dua keperluan penting:
1. Kecekapan prestasi pengindeksan pangkalan data (indexing, foreign key joins, dan storage layout B-Tree MySQL).
2. Keselamatan maklumat dan pencapaian endpoint (mencegah serangan *Insecure Direct Object Reference* / IDOR dan enum pencarian URL).

## Keputusan yang Dibuat
Mengguna pakai **Dual Identifier Strategy** bagi semua entiti teras pangkalan data MySQL 8:
- `id` (`BIGINT UNSIGNED AUTO_INCREMENT`): Digunakan strictly secara dalaman (*internal primary key*) untuk hubungan Foreign Key dan pengindeksan pangkalan data.
- `uuid` (`CHAR(36)` atau `BINARY(16)`): Digunakan sebagai pengenal awam (*public identifier*) dalam URL routing, API endpoints, Form Inputs, dan rujukan luaran.

## Keputusan Rasmi Penggunaan
1. **Dilarang mengeksport integer `id` kepada pengguna** melalui mana-mana URL, payload API, atau paparan UI.
2. Semua Laravel Route Model Binding hendaklah dikonfigurasikan menggunakan `uuid` (contoh: `getRouteKeyName()` memulangkan `'uuid'`).
3. Semua hubungan antarabangsa (Foreign Keys) dalam pangkalan data menggunakan `id` integer untuk mengekalkan saiz indeks pangkalan data yang minima dan prestasi *join* yang optimum.

## Kesan & Implikasi (Consequences)

### Kesan Positif
- **Keselamatan**: Mencegah penceroboh daripada meramal ID rekod seterusnya (contoh: `/kes/1001` vs `/kes/9f8a2b3c-4d5e...`).
- **Prestasi Database**: Mengekalkan saiz indeks clustered B-Tree MySQL yang kecil berbanding pengosongan skema jika UUID digunakan sebagai Clustered Primary Key.
- **Integriti Data**: Foreign key integer membolehkan operasi perbandingan integer dilakukan pada kelajuan perkakasan (*hardware speed*).

### Kesan Negatif / Kekangan
- Memerlukan tambahan satu kolum `uuid` bagi setiap jadual utama.
- Pembina kod perlu sentiasa peka untuk menterjemahkan UUID kepada Internal ID dalam Service Layer jika diperlukan, walaupun Route Model Binding Laravel mengurangkan beban ini.

## Polisi Kod (Coding Standard Rule)
```php
// Contoh Route Model Binding
public function show(RekodDisiplin $rekodDisiplin)
{
    // $rekodDisiplin diikat secara automatik melalui kolum 'uuid'
    return view('disiplin.show', compact('rekodDisiplin'));
}
```
