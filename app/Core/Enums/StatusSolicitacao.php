<?php

namespace App\Core\Enums;

enum StatusSolicitacao: string
{
    case Pendente = 'pendente';
    case Aprovada = 'aprovada';
    case Devolvida = 'devolvida';
}