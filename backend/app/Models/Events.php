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

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'duracion',
        'fecha_evento',
        'descripcion',
        'portada',
        'galeria',
        'artistas',
        'categories',
        'type',                 // evento o promoción
        'status',               // estado opcional
        'publication_status',   // draft, published, cancelled
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
        'galeria'      => 'array',
        'artistas'     => 'array',
        'categories'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(EventItems::class, 'event_id')
            ->with(['servicio', 'event']);
    }

    public function servicios()
    {
        return $this->hasManyThrough(
            \App\Models\Servicios::class,
            \App\Models\EventItems::class,
            'event_id',     // FK en event_items
            'id',           // PK en servicios
            'id',           // PK en events
            'servicio_id'   // FK en event_items
        );
    }
}
