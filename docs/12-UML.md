# 12 - UML Diagrams Specification

## 1. Pengenalan
Dokumen UML Diagrams ini menggambarkan struktur interaksi sistem e-Disiplin melalui Use Case Diagram dan Sequence Diagram (Dikemaskini Fasa 2.1).

## 2. Use Case Diagram (Notasi Mermaid)

```mermaid
graph TD
    subgraph Pengguna Sekolah
        P[Pentadbir Sekolah]
        G[Guru Subjek]
        GK[Guru Kelas]
        GD[Guru Disiplin]
        PK[PK HEM]
        PG[Pengetua / Guru Besar]
    end

    subgraph Modul e-Disiplin
        UC0(Urus Master: Pengguna/Kelas/Murid/Penjaga)
        UC1(Pelaporan Kes Salah Laku)
        UC2(Lihat Rekod Disiplin Kelas)
        UC3(Semak & Tetapkan Tindakan Kes)
        UC4(Eskalasi Kes Berat Peringkat 1: PK HEM)
        UC5(Pengesahan Akhir Kes Berat Peringkat 2: Pengetua)
        UC6(Void / Batal Rekod Kes)
        UC7(Jana Ringkasan AI)
    end

    P --> UC0
    G --> UC1
    GK --> UC1
    GK --> UC2
    GD --> UC1
    GD --> UC3
    GD --> UC4
    GD --> UC6
    GD --> UC7
    PK --> UC4
    PK --> UC6
    PK --> UC7
    PG --> UC5
    PG --> UC6
    PG --> UC7
```

## 3. Sequence Diagram: Sequential Escalation Kes BERAT (`eskalasi_kes`)

```mermaid
sequenceDiagram
    autonumber
    actor GD as Guru Disiplin
    actor PK as PK HEM
    actor PG as Pengetua / Guru Besar
    participant Controller as RekodDisiplinController
    participant Service as EskalasiKesService
    participant DB as MySQL Database
    participant Notif as InAppNotificationService

    GD->>Controller: Eskalasi Kes BERAT
    Controller->>Service: initiateEscalation(rekod, 'SEMAKAN_PK_HEM')
    Service->>DB: Save eskalasi_kes (Peringkat 1, status: MENUNGGU)
    Service->>DB: Update rekod_disiplin (status_kes: MENUNGGU_KELULUSAN)
    Service->>Notif: send(penerima: PK HEM, mesej: Tugasan Eskalasi Peringkat 1)
    
    Note over PK,Service: Peringkat 1: Semakan PK HEM
    PK->>Controller: Submit Kelulusan Peringkat 1
    Controller->>Service: approveStage1(eskalasi1, keputusan)
    Service->>DB: Update eskalasi_kes Peringkat 1 (status: DILULUSKAN)
    Service->>DB: Save eskalasi_kes Peringkat 2 (jenis: PENGESAHAN_PENGETUA)
    Service->>Notif: send(penerima: Pengetua, mesej: Tugasan Eskalasi Peringkat 2)

    Note over PG,Service: Peringkat 2: Pengesahan Akhir Pengetua
    PG->>Controller: Submit Pengesahan Akhir
    Controller->>Service: approveFinalStage(eskalasi2, keputusan)
    Service->>DB: Update eskalasi_kes Peringkat 2 (status: DILULUSKAN)
    Service->>DB: Update rekod_disiplin (status_kes: DITUTUP)
    Service->>Notif: send(penerima: Pelapor & GD, mesej: Kes Berat Ditutup)
```

## 4. Sequence Diagram: Pembatalan Kes Rasmi (Void Workflow)

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Guru Disiplin / PK HEM / Pengetua
    participant Controller as RekodDisiplinController
    participant Action as VoidRekodDisiplinAction
    participant DB as MySQL Database

    Admin->>Controller: Submit Form Void (rekod_uuid, void_reason)
    Controller->>Action: execute(rekodDisiplin, voidReason)
    
    rect rgb(255, 230, 230)
        Note over Action,DB: Strict Void Execution
        Action->>DB: Update rekod_disiplin: is_void=true, void_reason, voided_by, voided_at
        Action->>DB: Insert sejarah_status_kes (status_baharu: VOID)
        Action->>DB: Insert aktiviti_log (jenis_aktiviti: VOID_KES)
    end

    Action-->>Controller: Success Status
    Controller-->>Admin: Redirect + SweetAlert "Rekod Telah Dibatalkan"
```
