# 13 - Laravel Architecture & Code Structure Blueprint

## 1. Pengenalan
Dokumen Laravel Architecture Blueprint mendefinisikan penyusunan direktori kod sumber Laravel 13 bagi sistem e-Disiplin untuk memastikan pematuhan kepada prinsip **Thin Controller, Fat Service/Action**, reusability, dan Spatie Laravel Permission integration (Dikemaskini Fasa 2.1).

## 2. Struktur Direktori Kod App (`app/`)

```text
app/
├── Actions/
│   ├── Disiplin/
│   │   ├── LaporKesAction.php
│   │   ├── KemaskiniStatusKesAction.php
│   │   ├── TetapkanTindakanAction.php
│   │   └── VoidRekodDisiplinAction.php
│   └── AI/
│       ├── AnonymizeKesPromptAction.php
│       └── JanaRingkasanKesAiAction.php
├── Enums/
│   ├── TahapKesEnum.php             // RINGAN, SEDERHANA, BERAT
│   ├── StatusKesEnum.php            // DILAPORKAN, DALAM_SEMAKAN, DALAM_TINDAKAN, MENUNGGU_KELULUSAN, DITUTUP
│   ├── StatusMuridEnum.php          // AKTIF, ALUMNI, PINDAH, GANTUNG, BUANG
│   └── JenisSekolahEnum.php         // RENDAH, MENENGAH
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── RekodDisiplinController.php
│   │   ├── EskalasiKesController.php
│   │   ├── TindakanDisiplinController.php
│   │   ├── LampiranDisiplinController.php
│   │   ├── PenggunaController.php
│   │   ├── MuridController.php
│   │   ├── PenjagaController.php
│   │   ├── KelasGuruController.php
│   │   └── AiInsightController.php
│   └── Requests/
│       ├── Disiplin/
│       │   ├── LaporKesRequest.php
│       │   ├── UrutEskalasiRequest.php
│       │   ├── TetapkanTindakanRequest.php
│       │   └── VoidRekodRequest.php
│       └── Master/
│           ├── UrusPenggunaRequest.php
│           ├── UrusMuridRequest.php
│           └── UrusKelasGuruRequest.php
├── Models/
│   ├── Sekolah.php
│   ├── TahunAkademik.php
│   ├── Pengguna.php                 // Uses Spatie\Permission\Traits\HasRoles
│   ├── Kelas.php
│   ├── KelasGuru.php                // BAHARU
│   ├── Murid.php
│   ├── Penjaga.php
│   ├── KategoriDisiplin.php
│   ├── RekodDisiplin.php
│   ├── EskalasiKes.php              // BAHARU
│   ├── TindakanDisiplin.php
│   ├── LampiranDisiplin.php
│   ├── SejarahStatusKes.php
│   ├── Notifikasi.php
│   ├── AktivitiLog.php
│   └── AiPromptHistory.php
├── Policies/
│   ├── RekodDisiplinPolicy.php
│   ├── EskalasiKesPolicy.php
│   ├── MuridPolicy.php
│   ├── PenggunaPolicy.php
│   └── SekolahPolicy.php
└── Services/
    ├── EskalasiKesService.php
    ├── KelasGuruService.php
    ├── AiDisciplineService.php
    ├── AuditLogService.php
    └── InAppNotificationService.php
```

## 3. Configuration & Model Spatie Integration

### 3.1 Model Pengguna (`app/Models/Pengguna.php`)
```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Pengguna extends Authenticatable
{
    use SoftDeletes, HasRoles;

    protected $table = 'pengguna';
    
    // Custom guard default
    protected $guard_name = 'web';
}
```

### 3.2 Dynamic AI Configuration (`config/ai.php`)
```php
return [
    'default_provider' => env('AI_PROVIDER', 'openai'),
    'default_model' => env('AI_MODEL', 'gpt-4o-mini'),
    'retention_years' => 5,
];
```
