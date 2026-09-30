<?php

namespace App\Enums;

enum CatStatus: string
{
    case Available = 'available';
    case Adopted = 'adopted';
    case NotAdoptable = 'not_adoptable';
}
