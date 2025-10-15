<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'client_id',
        'description',
        'price',
        'quantity',
        'total_price',
        'servicio_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
