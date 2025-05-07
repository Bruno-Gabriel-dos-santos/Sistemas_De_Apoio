<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterfacesDeHiperprocessamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'capa',
        'titulo',
        'descricao',
        'conteudo',
        'data',
        'tag',
        'autor',
        'user_id',
    ];

    protected $table = 'interfaces_de_hiperprocessamento';
}
