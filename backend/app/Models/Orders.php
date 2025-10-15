<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'business_id',
        'status',
        'scheduled_at',
        'total_price',
        'payment_method',
        'notes',
    ];

    // Relación con el usuario que creó la orden
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el negocio asociado a la orden
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
