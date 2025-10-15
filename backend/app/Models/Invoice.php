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

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_name',
        'client_id',
        'total',
        'balance',
        'status',
        'slot_id',
        'employee_id',
        'provider_id',
        'month_paid'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function slot()
    {
        return $this->belongsTo(CalendarSlots::class, 'slot_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItems::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayments::class);
    }
}
