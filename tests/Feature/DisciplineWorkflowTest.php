<?php

use App\Actions\Disiplin\LaporKesAction;
use App\Actions\Disiplin\VoidRekodDisiplinAction;
use App\Enums\TahapKesEnum;
use App\Models\KategoriDisiplin;
use App\Models\Murid;
use App\Models\Sekolah;
use App\Models\User;
use App\Services\EskalasiKesService;
use Database\Seeders\KategoriDisiplinSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SekolahSeeder;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->seed([
        RoleAndPermissionSeeder::class,
        SekolahSeeder::class,
        KategoriDisiplinSeeder::class,
    ]);
});

test('guru can report a new discipline case', function () {
    $sekolah = Sekolah::first();
    $murid = Murid::create([
        'uuid' => (string) Str::uuid(),
        'sekolah_id' => $sekolah->id,
        'nama_penuh' => 'Ahmad Danial',
        'no_kp' => '120505101234',
        'tarikh_lahir' => '2012-05-05',
        'jantina' => 'LELAKI',
        'status_murid' => 'AKTIF',
    ]);
    $kategori = KategoriDisiplin::first();

    $user = User::factory()->create(['sekolah_id' => $sekolah->id]);
    $user->givePermissionTo('disiplin.lapor');

    $response = $this->actingAs($user)->post(route('disiplin.lapor.store'), [
        'sekolah_id' => $sekolah->id,
        'murid_id' => $murid->id,
        'kategori_disiplin_id' => $kategori->id,
        'tarikh_kejadian' => now()->format('Y-m-d H:i:s'),
        'lokasi_kejadian' => 'Bilik Darjah',
        'keterangan_kes' => 'Murid bising semasa waktu pengajaran.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('rekod_disiplin', [
        'murid_id' => $murid->id,
        'pelapor_id' => $user->id,
    ]);
});

test('guru disiplin can update status and record tindakan disiplin', function () {
    $sekolah = Sekolah::first();
    $murid = Murid::create([
        'uuid' => (string) Str::uuid(),
        'sekolah_id' => $sekolah->id,
        'nama_penuh' => 'Ahmad Faiz',
        'no_kp' => '120202105555',
        'tarikh_lahir' => '2012-02-02',
        'jantina' => 'LELAKI',
        'status_murid' => 'AKTIF',
    ]);
    $kategori = KategoriDisiplin::first();
    $pelapor = User::factory()->create(['sekolah_id' => $sekolah->id]);

    $laporAction = app(LaporKesAction::class);
    $rekod = $laporAction->execute($pelapor, [
        'sekolah_id' => $sekolah->id,
        'murid_id' => $murid->id,
        'kategori_disiplin_id' => $kategori->id,
        'tarikh_kejadian' => now()->format('Y-m-d H:i:s'),
        'keterangan_kes' => 'Ujian kes amaran',
    ]);

    $guruDisiplin = User::factory()->create(['sekolah_id' => $sekolah->id]);
    $guruDisiplin->givePermissionTo('disiplin.semak');

    $response = $this->actingAs($guruDisiplin)->post(route('disiplin.tindakan.update', $rekod), [
        'status_kes' => 'DALAM_TINDAKAN',
        'jenis_tindakan' => 'Amaran Lisan Kali Pertama',
        'keterangan_tindakan' => 'Sesi kaunseling & amaran lisan diberikan.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tindakan_disiplin', [
        'rekod_disiplin_id' => $rekod->id,
        'jenis_tindakan' => 'Amaran Lisan Kali Pertama',
    ]);
});

test('sequential approval flow for heavy discipline case', function () {
    $sekolah = Sekolah::first();
    $murid = Murid::create([
        'uuid' => (string) Str::uuid(),
        'sekolah_id' => $sekolah->id,
        'nama_penuh' => 'Siti Nur Aisyah',
        'no_kp' => '110303104321',
        'tarikh_lahir' => '2011-03-03',
        'jantina' => 'PEREMPUAN',
        'status_murid' => 'AKTIF',
    ]);
    $kategori = KategoriDisiplin::where('tahap_default', 'BERAT')->first() ?? KategoriDisiplin::first();

    $guruDisiplin = User::factory()->create(['sekolah_id' => $sekolah->id]);
    $guruDisiplin->assignRole('Guru Disiplin');

    $pkHem = User::factory()->create(['sekolah_id' => $sekolah->id]);
    $pkHem->assignRole('PK HEM');

    $pengetua = User::factory()->create(['sekolah_id' => $sekolah->id]);
    $pengetua->assignRole('Pengetua');

    // 1. Lapor kes berat
    $laporAction = app(LaporKesAction::class);
    $rekod = $laporAction->execute($guruDisiplin, [
        'sekolah_id' => $sekolah->id,
        'murid_id' => $murid->id,
        'kategori_disiplin_id' => $kategori->id,
        'tahap_kes' => TahapKesEnum::BERAT,
        'tarikh_kejadian' => now()->format('Y-m-d H:i:s'),
        'keterangan_kes' => 'Kes buli berat di asrama.',
    ]);

    expect($rekod->status_kes->value ?? $rekod->status_kes)->toBe('MENUNGGU_KELULUSAN');

    // 2. Guru Disiplin eskalasi ke PK HEM
    $eskalasiService = app(EskalasiKesService::class);
    $eskalasi1 = $eskalasiService->hantarKePkhem($rekod, $guruDisiplin, 'Mohon kelulusan PK HEM');

    expect($eskalasi1->jenis_eskalasi)->toBe('SEMAKAN_PK_HEM');
    expect($eskalasi1->status)->toBe('MENUNGGU');

    // 3. PK HEM Luluskan -> Cipta Peringkat 2 Pengetua
    $eskalasi2 = $eskalasiService->kelulusanPkhem($eskalasi1, $pkHem, 'LULUS', 'Disokong gantung sekolah');

    expect($eskalasi2->jenis_eskalasi)->toBe('PENGESAHAN_PENGETUA');
    expect($eskalasi2->status)->toBe('MENUNGGU');

    // 4. Pengetua Sahkan -> Kes DITUTUP
    $eskalasiFinal = $eskalasiService->pengesahanPengetua($eskalasi2, $pengetua, 'LULUS', 'Diluluskan gantung sekolah 14 hari');

    $rekod->refresh();
    expect($rekod->status_kes->value ?? $rekod->status_kes)->toBe('DITUTUP');
});

test('authorized user can void a discipline record', function () {
    $sekolah = Sekolah::first();
    $murid = Murid::create([
        'uuid' => (string) Str::uuid(),
        'sekolah_id' => $sekolah->id,
        'nama_penuh' => 'Muhammad Amir',
        'no_kp' => '100101109999',
        'tarikh_lahir' => '2010-01-01',
        'jantina' => 'LELAKI',
        'status_murid' => 'AKTIF',
    ]);
    $kategori = KategoriDisiplin::first();
    $pelapor = User::factory()->create(['sekolah_id' => $sekolah->id]);

    $laporAction = app(LaporKesAction::class);
    $rekod = $laporAction->execute($pelapor, [
        'sekolah_id' => $sekolah->id,
        'murid_id' => $murid->id,
        'kategori_disiplin_id' => $kategori->id,
        'tarikh_kejadian' => now()->format('Y-m-d H:i:s'),
        'keterangan_kes' => 'Ujian kes tersilap',
    ]);

    $user = User::factory()->create();
    $user->givePermissionTo('disiplin.void');

    $action = app(VoidRekodDisiplinAction::class);
    $action->execute($user, $rekod, 'Kes dilaporkan tersilap ID murid');

    $rekod->refresh();
    expect($rekod->is_void)->toBeTrue();
    expect($rekod->void_reason)->toBe('Kes dilaporkan tersilap ID murid');
});
