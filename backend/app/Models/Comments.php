<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'parent_id',
        'mensaje',
        'type',
        'status',
        'image',
        'module',
        'pathname',
        'json',
        'user_id',
    ];

    /**
     * 🔹 Relación con el usuario autor del comentario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 🔹 Relación con el comentario padre (si es una respuesta)
     */
    public function parent()
    {
        return $this->belongsTo(Comments::class, 'parent_id');
    }

    /**
     * 🔹 Relación con los comentarios hijos (respuestas)
     */
    public function children()
    {
        return $this->hasMany(Comments::class, 'parent_id');
    }

    /**
     * 🔹 Accesor para transformar el campo JSON automáticamente
     */
    protected $casts = [
        'json' => 'array',
    ];

    /**
     * 🔹 Scope para comentarios pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * 🔹 Scope para filtrar por tipo
     */
    public function scopeType($query, $type)
    {
        if (!empty($type)) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * 🔹 Scope para búsqueda general
     */
    public function scopeSearch($query, $term)
    {
        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('mensaje', 'like', "%{$term}%")
                  ->orWhere('module', 'like', "%{$term}%")
                  ->orWhere('pathname', 'like', "%{$term}%");
            });
        }
        return $query;
    }
}
