<?php

namespace App\Http\Controllers\Disiplin;

use App\Actions\Disiplin\VoidRekodDisiplinAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Disiplin\VoidKesRequest;
use App\Models\RekodDisiplin;
use Illuminate\Http\RedirectResponse;

class VoidKesController extends Controller
{
    public function store(VoidKesRequest $request, RekodDisiplin $disiplin, VoidRekodDisiplinAction $action): RedirectResponse
    {
        $this->authorize('void', $disiplin);

        $action->execute($request->user(), $disiplin, $request->void_reason);

        return back()->with('success', 'Rekod disiplin ini telah berjaya dibatalkan (VOID).');
    }
}
