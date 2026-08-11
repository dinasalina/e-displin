# 10 - Workflow & State Transition Specification

## 1. Pengenalan
Dokumen ini mementaskan alur kerja perniagaan (Business Workflow) dan Matriks Peralihan Status (State Transition Matrix) bagi pengurusan kes disiplin murid di dalam e-Disiplin (Dikemaskini Fasa 2.1).

## 2. Carta Alir Alur Kerja Utama Kes Disiplin

### 2.1 Alur Kerja Kes Biasa (Tahap RINGAN / SEDERHANA)

```
[ Guru / Pelapor ]
       │
       ▼ Lapor Kes Salah Laku
[ Status Kes: DILAPORKAN ]
       │
       ▼ (Notifikasi kepada Guru Disiplin)
[ Guru Disiplin ]
       │
       ├─► Semak & Kemaskini Status ──────► [ Status Kes: DALAM_SEMAKAN ]
       │
       ├─► Tetapkan Tindakan / Hukuman ──► [ Status Kes: DALAM_TINDAKAN ]
       │
       └─► Sahkan Tindakan Selesai ────────► [ Status Kes: DITUTUP ]
```

### 2.2 Alur Kerja Sequential Approval Kes Berat (`eskalasi_kes`)

```
[ Guru / Pelapor ]
       │
       ▼ Lapor Kes Berat
[ Status Kes: DILAPORKAN ]
       │
       ▼
[ Guru Disiplin ]
       │
       ▼ Semak & Eskalasi Kes
[ Status Kes: MENUNGGU_KELULUSAN ]
       ├─► Insert eskalasi_kes (Peringkat 1: jenis_eskalasi = 'SEMAKAN_PK_HEM')
       │
       ▼ (Notifikasi kepada PK HEM)
[ PK HEM ]
       │
       ▼ Kelulusan Peringkat Pertama
       ├─► Update eskalasi_kes (Peringkat 1: status = 'DILULUSKAN')
       ├─► Insert eskalasi_kes (Peringkat 2: jenis_eskalasi = 'PENGESAHAN_PENGETUA')
       │
       ▼ (Notifikasi kepada Pengetua / Guru Besar)
[ Pengetua / Guru Besar ]
       │
       ▼ Pengesahan Akhir Hukuman
       ├─► Update eskalasi_kes (Peringkat 2: status = 'DILULUSKAN')
       │
       ▼
[ Status Kes: DITUTUP ]
```

## 3. State Transition Matrix (Matriks Peralihan Status Kes Fasa 2.1)

| Status Asal | Status Baharu Dibenarkan | Peranan Dibenarkan | Syarat / Trigger & Tracking |
| :--- | :--- | :--- | :--- |
| *(Tiada)* | `DILAPORKAN` | Guru / Pelapor | Pendaftaran laporan kes baharu. |
| `DILAPORKAN` | `DALAM_SEMAKAN` | Guru Disiplin | Guru Disiplin memulakan semakan kes. |
| `DALAM_SEMAKAN` | `DALAM_TINDAKAN` | Guru Disiplin | Tindakan disiplin ringan/sederhana ditetapkan. |
| `DALAM_SEMAKAN` | `MENUNGGU_KELULUSAN` | Guru Disiplin | Kes `BERAT` dialirkan. Rekod `eskalasi_kes` dicipta untuk PK HEM. |
| `MENUNGGU_KELULUSAN` | `MENUNGGU_KELULUSAN` | PK HEM | Kelulusan Peringkat 1. Rekod `eskalasi_kes` dicipta untuk Pengetua. |
| `MENUNGGU_KELULUSAN` | `DITUTUP` | Pengetua / Guru Besar | Pengesahan Akhir Pengetua direkodkan dalam `eskalasi_kes`. |
| `DALAM_TINDAKAN` | `DITUTUP` | Guru Disiplin | Tindakan kes ringan/sederhana selesai. |
| *Sejarah Status* | `VOID (is_void=true)` | Guru Disiplin / PK HEM / Pengetua | Pembatalan kes tersilap beserta sebab rasmi (`void_reason`). |

## 4. Keperluan Jejak Peralihan & Audit
Setiap pertukaran status mendaftarkan rekod **IMMUTABLE** ke `sejarah_status_kes`, manakala keputusan eskalasi direkodkan dalam `eskalasi_kes` (`ditugaskan_oleh_id`, `penerima_id`, `jenis_eskalasi`, `catatan_keputusan`, `diputuskan_pada`).
