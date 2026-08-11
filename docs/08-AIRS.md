# 08 - AI Requirements Specification (AIRS)

## 1. Pengenalan
Dokumen AI Requirements Specification (AIRS) mendefinisikan batasan etika, seni bina teknikal, polisi privasi, konfigurasi model dinamik, dan skema audit bagi keupayaan Pembantu AI di dalam sistem e-Disiplin (Dikemaskini Fasa 2.1).

## 2. Prinsip Etika & Sempadan Automasi AI

### 2.1 Boundary Autonomi AI (Strict Human-in-the-Loop)
- AI **DILARANG SEPENUHNYA** daripada membuat sebarang keputusan disiplin secara autonomi.
- AI **DILARANG SEPENUHNYA** daripada menukar `status_kes`, `tahap_kes`, atau meluluskan eskalasi.
- AI beraksi sebagai pembantu analitik untuk:
  1. Meringkaskan laporan kronologi kejadian guru yang panjang (`Ringkasan Kronologi Kes`).
  2. Menganalisis trend disiplin bulanan mengikut masa/lokasi secara anonim (`Insight Trend Disiplin`).
  3. Mencadangkan panduan langkah intervensi kaunseling (bukan hukuman) untuk dinilai oleh Guru Disiplin / PK HEM / Pengetua.

## 3. Dynamic Model Configuration Layer
- Model AI **TIDAK DI-HARDCODE** sebagai peratuan perniagaan (Business Rule).
- Penggunaan model AI diuruskan menerusi `config/ai.php` (`config('ai.default_model')`).
- Kolum `ai_prompt_history.model` menyimpan *snapshot* nama model sebenar yang dipanggil semasa transaksi berlaku.

## 4. Polisi Privasi, Anonymization & Retensi Data

### 4.1 Redaksi PII Murid (Anonymization)
Sebelum sebarang kandungan teks dihantar kepada OpenAI API melalui Laravel AI SDK, kelas `AiDisciplineService` **WAJIB** menapis dan menderak (redact) PII murid:

```
[ Data Mentah Kes ]
  ├── Nama Murid: "Ahmad Bin Abdullah" ────────► [TOKEN: MURID_A]
  ├── No. MyKad: "120304-10-1234"      ────────► [REDACTED]
  ├── Nama Penjaga: "Abdullah Bin Omar"────────► [REDACTED]
  └── Teks Kejadian: "Ahmad bergaduh..." ──────► "[MURID_A] bergaduh..."
```

Maklum balas daripada AI (*Response*) juga disemak oleh kelas sanitizer untuk memastikan tiada sebarang PII terluar atau bocor.

### 4.2 Kawalan Hak Akses Sejarah AI (Audit Access)
Sejarah audit AI (`ai_prompt_history`) bersifat **IMMUTABLE** dan capaian dibatasi seperti berikut:
- **Super Admin**: Membaca semua sejarah AI sistem untuk tujuan audit keselamatan.
- **Pentadbir Sekolah**: Membaca sejarah AI sekolah untuk pemantauan penggunaan kuota/token.
- **Guru Disiplin / PK HEM / Pengetua**: Membaca sejarah AI yang berkaitan dengan kes disiplin sekolah jagaan.

### 4.3 Polisi Retensi Data (Retention Policy)
- Rekod dalam `ai_prompt_history` disimpan selama **5 tahun** mengikut kitaran tempoh persekolahan murid sebelum diarkibkan secara automatik ke dalam storan jangka panjang.

## 5. Seni Bina Teknikal AI Integration

```
[ Blade View / User Interface ]
             │
             ▼ (Post Request)
[ AiInsightController ]
             │
             ▼
[ AiDisciplineService ]
             │
             ├─► 1. Anonymize Data (Murid Sanitizer)
             ├─► 2. Dapatkan config('ai.default_model')
             ├─► 3. Panggil OpenAI API via Laravel AI SDK
             ├─► 4. Check Response PII Safety
             ├─► 5. Rakam Audit Transaction (AiPromptHistoryRepository)
             │
             ▼
[ Pangkalan Data: ai_prompt_history (IMMUTABLE) ]
```
