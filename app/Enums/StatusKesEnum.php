<?php

namespace App\Enums;

enum StatusKesEnum: string
{
    case DILAPORKAN = 'DILAPORKAN';
    case DALAM_SEMAKAN = 'DALAM_SEMAKAN';
    case DALAM_TINDAKAN = 'DALAM_TINDAKAN';
    case MENUNGGU_KELULUSAN = 'MENUNGGU_KELULUSAN';
    case DITUTUP = 'DITUTUP';
}
