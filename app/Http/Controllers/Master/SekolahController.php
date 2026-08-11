<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UrusSekolahRequest;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SekolahController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Sekolah::class);

        $sekolahList = Sekolah::latest()->paginate(10);

        return view('master.sekolah.index', compact('sekolahList'));
    }

    public function store(UrusSekolahRequest $request): RedirectResponse
    {
        Sekolah::create(array_merge($request->validated(), [
            'uuid' => (string) Str::uuid(),
        ]));

        return back()->with('success', 'Maklumat sekolah berjaya didaftarkan.');
    }

    public function update(UrusSekolahRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $sekolah->update($request->validated());

        return back()->with('success', 'Maklumat sekolah berjaya dikemaskini.');
    }

    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        $this->authorize('delete', $sekolah);

        $sekolah->delete();

        return back()->with('success', 'Sekolah berjaya dihapuskan.');
    }
}
