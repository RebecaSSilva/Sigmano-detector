<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnaAnalise extends Model
{
    protected $fillable = ['sequencia', 'resultado'];

    protected $casts = [
        'resultado' => 'integer',
    ];
}
