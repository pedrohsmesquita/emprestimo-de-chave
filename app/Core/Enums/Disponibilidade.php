<?php

namespace App\Core\Enums;

enum Disponibilidade: string
{
    case Disponivel = 'disponivel';
    case Emprestado = 'emprestado';
}