<?php

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('pentadbir sekolah can access master data sekolah page', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('sekolah.urus');

    $response = $this->actingAs($user)->get(route('master.sekolah.index'));

    $response->assertStatus(200);
});

test('guru without permission cannot access master data sekolah page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('master.sekolah.index'));

    $response->assertStatus(403);
});

test('pentadbir sekolah can register a new school', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('sekolah.urus');

    $response = $this->actingAs($user)->post(route('master.sekolah.store'), [
        'kod_sekolah' => 'TEST001',
        'nama_sekolah' => 'SK Test Impian',
        'jenis_sekolah' => 'RENDAH',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('sekolah', ['kod_sekolah' => 'TEST001']);
});
