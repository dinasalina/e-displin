# ADR-003: AI Assistance & Human-in-the-Loop Decision Authority Boundary

## Status
Diluluskan (Approved - Dikemaskini Fasa 2.1)

## Tarikh
2026-08-10

## Konteks & Latar Belakang
Sistem e-Disiplin menggunakan integrasi AI (Laravel AI SDK) untuk membantu pihak sekolah dalam pengurusan disiplin murid. Disebabkan oleh implikasi undang-undang, etika, dan emosi terhadap rekod murid, batasan yang sangat jelas perlu ditetapkan berhubung dengan keupayaan dan автоnomy AI serta penetapan model dinamik.

## Keputusan yang Dibuat

1. **AI ADALAH PEMBANTU (ASSISTANT), BUKAN PEMBUAT KEPUTUSAN (DECISION MAKER)**:
   - AI TIDAK BOLEH menukar status kes secara automatik.
   - AI TIDAK BOLEH mengenakan hukuman atau tindakan disiplin secara autonomi.
   - AI TIDAK BOLEH meluluskan atau menutup kes disiplin.

2. **Dibenarkan untuk AI**:
   - Meringkaskan kronologi kes yang panjang berdasarkan laporan guru.
   - Menganalisis trend disiplin sekolah/kelas (contoh: kes ponteng meningkat pada hari Jumaat).
   - Mencadangkan langkah intervensi kaunseling/bimbingan (bukan hukuman) sebagai panduan kepada Guru Disiplin/PK HEM.
   - Menyediakan draf awal laporan statistik disiplin.

3. **Konfigurasi AI Dinamik (Configuration Layer)**:
   - Model AI TIDAK DARSING/HARDCODED sebagai business rule.
   - Penggunaan model diuruskan melalui `config/ai.php` (contoh: `config('ai.default_model')`).
   - `ai_prompt_history.model` kekal menyimpan *snapshot* nama model sebenar yang dipanggil semasa transaksi berlaku.

4. **Integriti Audit AI & Retensi (`ai_prompt_history`)**:
   - Setiap transaksi promosi/pemanggilan AI wajib direkodkan dalam jadual `ai_prompt_history` yang bersifat **IMMUTABLE**.
   - Maklumat direkodkan: `sekolah_id`, `pengguna_id`, `rekod_disiplin_id`, `provider`, `model`, `prompt_text`, `response_text`, `tokens_input`, `tokens_output`, `latency_ms`, dan `created_at`.
   - Polisi retensi sejarah AI: Rekod disimpan selama 5 tahun mengikut kitaran persekolahan murid sebelum diarkibkan.

5. **Kerahsiaan & Minimasi Data (Data Minimization)**:
   - DILARANG menghantar maklumat peribadi sensitif (PII) murid (seperti No. Kad Pengenalan, nama penuh, alamat rumah, maklumat ibu bapa) kepada AI Provider.
   - Teks prompt wajib di-anonymize menggunakan token pengganti (contoh: `[MURID_A]`).
   - Maklum balas AI (Response) juga disemak supaya tidak mengandungi PII murid.

## Kesan & Implikasi (Consequences)

### Kesan Positif
- Mematuhi etika keselamatan data murid dan undang-undang privasi (PDPA / Akta Perlindungan Data Peribadi).
- Fleksibiliti menukar AI Provider/Model di peringkat aplikasi (`config/ai.php`) tanpa menjejaskan logic perniagaan.
- Memastikan akauntabiliti penuh kekal di tangan Pentadbir Sekolah/Guru.

### Kesan Negatif / Kekangan
- Pengguna manusia perlu membuat tindakan manual untuk menyetujui atau menolak cadangan AI.
- Memerlukan lapisan khusus (Dedicated AI Service) untuk mengurus anonymization data dan rakaman audit trail.
