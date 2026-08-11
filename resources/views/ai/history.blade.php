@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
            <span>Audit & Keselamatan</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">AI Prompt Audit History</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white tracking-tight">Log Sejarah Audit AI (Immutable)</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Rekod kekal transaksi panggilan AI, prompt redaksi PII, snapshot model, dan token usage.</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="p-4">Masa & Pengguna</th>
                        <th class="p-4">Model & Provider</th>
                        <th class="p-4">Prompt (Redacted PII)</th>
                        <th class="p-4">Response AI Snapshot</th>
                        <th class="p-4 text-right">Tokens & Latensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                    @forelse($historyList as $item)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $item->pengguna->nama ?? 'Sistem' }}</div>
                                <div class="text-[11px] text-slate-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i:s A') }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 rounded-full font-mono text-[11px] font-bold">
                                    {{ $item->provider }} ({{ $item->model }})
                                </span>
                            </td>
                            <td class="p-4 text-xs text-slate-700 dark:text-slate-300 max-w-xs truncate">
                                {{ $item->prompt_text }}
                            </td>
                            <td class="p-4 text-xs text-slate-600 dark:text-slate-400 max-w-xs truncate font-mono">
                                {{ Str::limit($item->response_text, 100) }}
                            </td>
                            <td class="p-4 text-right font-mono text-xs">
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $item->tokens_input + $item->tokens_output }} tokens</div>
                                <div class="text-[10px] text-slate-400">{{ $item->latency_ms }} ms</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">Tiada sejarah transaksi AI dijumpai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700/60">
            {{ $historyList->links() }}
        </div>
    </div>
</div>
@endsection
