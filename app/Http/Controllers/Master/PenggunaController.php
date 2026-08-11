<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusPenggunaRequest;
use App\Models\Pengguna;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PenggunaController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Pengguna::class);

        $penggunaList = Pengguna::with(['sekolah', 'roles'])->latest()->paginate(10);
        $sekolahList = Sekolah::all();
        $roles = Role::all();

        return view('master.pengguna.index', compact('penggunaList', 'sekolahList', 'roles'));
    }

    public function create(): View
    {
        $this->authorize('create', Pengguna::class);

        $sekolahList = Sekolah::all();
        $roles = Role::all();

        return view('master.pengguna.create', compact('sekolahList', 'roles'));
    }

    public function store(UrusPenggunaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $roleName = $data['role'];
        unset($data['role']);

        $data['uuid'] = (string) Str::uuid();
        $data['password'] = Hash::make($data['password']);

        $pengguna = Pengguna::create($data);
        $pengguna->assignRole($roleName);

        return redirect()->route('master.pengguna.index')->with('success', 'Akaun pengguna berjaya didaftarkan.');
    }

    public function edit(Pengguna $pengguna): View
    {
        $this->authorize('update', $pengguna);

        $sekolahList = Sekolah::all();
        $roles = Role::all();

        return view('master.pengguna.edit', compact('pengguna', 'sekolahList', 'roles'));
    }

    public function update(UrusPenggunaRequest $request, Pengguna $pengguna): RedirectResponse
    {
        $data = $request->validated();
        $roleName = $data['role'];
        unset($data['role']);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $pengguna->update($data);
        $pengguna->syncRoles([$roleName]);

        return redirect()->route('master.pengguna.index')->with('success', 'Akaun pengguna berjaya dikemaskini.');
    }

    public function destroy(Pengguna $pengguna): RedirectResponse
    {
        $this->authorize('delete', $pengguna);

        $pengguna->delete();

        return back()->with('success', 'Akaun pengguna telah dinyahaktifkan (soft delete).');
    }
}
