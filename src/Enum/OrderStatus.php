<?php

namespace App\Enum;

enum OrderStatus: string
{
    case EnPreparation = 'en_preparation';
    case Expediee = 'expediee';
    case Livree = 'livree';
    case Annulee = 'annulee';
}
