<?php

namespace App\Services;

use App\Models\AiPromptHistory;
use App\Models\Pengguna;
use App\Models\RekodDisiplin;

class AiDisciplineService
{
    /**
     * Menapis dan memadamkan PII (Personally Identifiable Information) murid.
     */
    public function redactPii(string $text, ?string $namaMurid = null, ?string $noKp = null): string
    {
        if ($namaMurid) {
            $text = str_replace($namaMurid, '[MURID_A]', $text);
        }

        if ($noKp) {
            $text = str_replace($noKp, '[REDACTED_MYKAD]', $text);
        }

        // Tapis sebarang format No. IC 12 digit (e.g. 080101145555)
        $text = preg_replace('/\b\d{6}-\d{2}-\d{4}\b|\b\d{12}\b/', '[REDACTED_MYKAD]', $text);

        // Tapis nombor telefon
        $text = preg_replace('/\b01\d{8,9}\b/', '[REDACTED_PHONE]', $text);

        return $text;
    }

    /**
     * Menjana Analisis Predictive Insight & Cadangan Intervensi Kaunseling AI.
     */
    public function generateCaseInsight(Pengguna $user, ?RekodDisiplin $rekod = null, ?string $customContext = null): array
    {
        $startTime = microtime(true);

        $rawPrompt = $rekod
            ? sprintf('Kronologi Kes: %s. Kategori: %s. Tahap: %s.', $rekod->keterangan_kes, $rekod->kategoriDisiplin->nama_kategori ?? '-', $rekod->tahap_kes->value ?? $rekod->tahap_kes)
            : ($customContext ?? 'Analisis am trend disiplin sekolah.');

        // Redaksi PII
        $sanitizedPrompt = $this->redactPii(
            $rawPrompt,
            $rekod?->murid?->nama_penuh,
            $rekod?->murid?->no_kp
        );

        $modelName = config('ai.default_model', 'gpt-4o-mini');

        // Simulasi Enjin AI Berprediktif (Strict Human-in-the-Loop Interventions)
        $insightResult = [
            'ringkasan_eksekutif' => 'Analisis gelagat murid menunjukkan corak kecenderungan emosi tidak stabil atau salah faham sosial.',
            'syor_intervensi_kaunseling' => [
                'Sesi Bimbingan & Kaunseling Individu (Modul Pengurusan Kemarahan)',
                'Pertemuan bersama Penjaga bagi penyelarasan persekitaran rumah',
                'Program Sahabat Sebaya & Mentoring Akademik',
            ],
            'tahap_risiko_berulang' => 'SEDERHANA',
            'catatan_etika' => 'Syor ini bersifat panduan sokongan kaunseling sahaja. Keputusan rasmi disiplin kekal 100% di tangan Pengetua / PK HEM / Guru Disiplin.',
        ];

        $responseText = json_encode($insightResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

        // Merekod sejarah audit AI (IMMUTABLE)
        AiPromptHistory::create([
            'sekolah_id' => $user->sekolah_id ?? $rekod?->sekolah_id ?? 1,
            'pengguna_id' => $user->id,
            'rekod_disiplin_id' => $rekod?->id,
            'provider' => 'OpenAI',
            'model' => $modelName,
            'prompt_text' => $sanitizedPrompt,
            'response_text' => $responseText,
            'tokens_input' => strlen($sanitizedPrompt),
            'tokens_output' => strlen($responseText),
            'latency_ms' => $latencyMs,
        ]);

        return $insightResult;
    }
}
