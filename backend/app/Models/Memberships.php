<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memberships extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'price',
        'features',
        'icon',
        'options',
        'is_current',
    ];

    protected $casts = [
        'features'   => 'array',
        'options'    => 'array',
        'is_current' => 'boolean',
    ];
}
