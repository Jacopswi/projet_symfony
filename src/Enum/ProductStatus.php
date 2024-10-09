<?php

namespace App\Enum;

enum ProductStatus: string
{
    case Disponible = 'disponible';
    case EnRupture = 'en_rupture';
    case EnPrecommande = 'en_precommande';
}
