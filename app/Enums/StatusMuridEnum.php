<?php

namespace App\Enums;

enum StatusMuridEnum: string
{
    case AKTIF = 'AKTIF';
    case ALUMNI = 'ALUMNI';
    case PINDAH = 'PINDAH';
    case GANTUNG = 'GANTUNG';
    case BUANG = 'BUANG';
}
