<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Servicios;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'name',
        'barcode',
        'brand',
        'measure_unit',
        'measure_quantity',
        'short_description',
        'long_description',
        'category_name',
        'stock_control',
        'stock_current',
        'stock_alert_level',
        'stock_reorder_amount',
        'stock_notifications_enabled',
        'model',
        'color',
        'sku',
        'stock',
        'min_stock',
        'price',
        'provider_id',
    ];

    /**
     * Servicio principal al que pertenece este producto
     */
    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }
}
