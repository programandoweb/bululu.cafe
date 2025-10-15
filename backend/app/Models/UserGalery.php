<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGalery extends Model
{
    use HasFactory;

    protected $table = 'user_galeries';

    protected $fillable = [
        'user_id',
        'image',
        'description',
        'status',
    ];

    /**
     * Relación con el usuario dueño de la galería
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
