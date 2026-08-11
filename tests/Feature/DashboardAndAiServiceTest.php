<?php

use App\Models\Murid;
use App\Models\Sekolah;
use App\Models\User;
use App\Services\AiDisciplineService;
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

test('dashboard displays dynamic statistics from database', function () {
    $sekolah = Sekolah::first();
    $user = User::factory()->create(['sekolah_id' => $sekolah->id]);

    Murid::create([
        'uuid' => (string) Str::uuid(),
        'sekolah_id' => $sekolah->id,
        'nama_penuh' => 'Ahmad Syukri',
        'no_kp' => '120101108888',
        'tarikh_lahir' => '2012-01-01',
        'jantina' => 'LELAKI',
        'status_murid' => 'AKTIF',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('jumlahMurid', 1);
});

test('ai service redacts PII from text', function () {
    $service = app(AiDisciplineService::class);

    $redacted = $service->redactPii('Ahmad Bin Ali dengan IC 120505-10-1234 dan Telefon 0123456789 bergaduh.', 'Ahmad Bin Ali', '120505-10-1234');

    expect($redacted)->not->toContain('Ahmad Bin Ali');
    expect($redacted)->not->toContain('120505-10-1234');
    expect($redacted)->not->toContain('0123456789');
    expect($redacted)->toContain('[MURID_A]');
    expect($redacted)->toContain('[REDACTED_MYKAD]');
    expect($redacted)->toContain('[REDACTED_PHONE]');
});

test('ai service logs audit trail in ai_prompt_history table', function () {
    $sekolah = Sekolah::first();
    $user = User::factory()->create(['sekolah_id' => $sekolah->id]);

    $service = app(AiDisciplineService::class);
    $result = $service->generateCaseInsight($user, null, 'Ujian prompt AI');

    expect($result)->toHaveKey('ringkasan_eksekutif');

    $this->assertDatabaseHas('ai_prompt_history', [
        'pengguna_id' => $user->id,
        'model' => config('ai.default_model'),
    ]);
});

test('user can trigger ai generate route and view ai audit history', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('ai.generate'), [
        'context' => 'Ujian AI Insight',
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('status', 'success');

    $historyResponse = $this->actingAs($user)->get(route('ai.history'));
    $historyResponse->assertStatus(200);
});
