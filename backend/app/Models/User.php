<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'customer_group_id',
        'name',
        'company_name',
        'image',
        'cover',
        'email',
        'email_verified_at',
        'password',
        'user_type',
        'identification_number',
        'identification_type',
        'phone_number',
        'address',
        'tax_no',
        'city',
        'state',
        'postal_code',
        'country',
        'confirmation_code',
        'status',
        'schedule',
        'whatsapp_link',
        'description',
        'gallery',
        'categories',
        'eventsToday',
        'promotions',
        'payment_day',
        // 🔹 Campos de tracking
        'marketing_source',
        'first_touch_at',
        'marketing_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'first_touch_at'    => 'datetime',
            'password'          => 'hashed',
            'schedule'          => 'array',
            'gallery'           => 'array',
            'categories'        => 'array',
            'eventsToday'       => 'array',
            'promotions'        => 'array',
            'marketing_data'    => 'array',
        ];
    }

    // 🔹 JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // 🔹 Relaciones
    public function characteristics()
    {
        return $this->hasMany(UserCharacteristics::class, 'user_id');
    }

    public function properties()
    {
        return $this->hasMany(PropertiesRelUser::class, 'properties_id');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'customer_id')
            ->with(['approver', 'customer'])
            ->orderBy('id', 'DESC');
    }

    public function credit()
    {
        return $this->hasOne(Credit::class, 'customer_id')
            ->selectRaw("customer_id, SUM(amount) as amount")
            ->groupBy('customer_id');
    }

    public function user_credit()
    {
        return $this->hasOne(Credit::class, 'customer_id')
            ->selectRaw("customer_id, SUM(amount) as amount")
            ->groupBy('customer_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'customer_id')->orderBy('id', 'DESC');
    }

    public function paids()
    {
        return $this->hasMany(SalesPayments::class, 'customer_id')->orderBy('id', 'DESC');
    }

    public function userMembership()
    {
        return $this->hasOne(UserMembership::class, 'user_id');
    }

    public function activeMembership()
    {
        return $this->hasOne(UserMembership::class, 'user_id')
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->with('membership');
    }

    public function tags()
    {
        return $this->belongsToMany(
            \App\Models\MasterTable::class,
            'user_tags',
            'user_id',
            'tag_id'
        )->where('master_tables.grupo', 'tags');
    }

    public function gallery()
    {
        return $this->hasMany(UserGalery::class, 'user_id');
    }

    public function events()
    {
        return $this->hasMany(\App\Models\Events::class, 'user_id')
            ->where('type', 'event')
            ->orderBy('fecha_evento', 'desc');
    }

    public function promotions()
    {
        return $this->hasMany(\App\Models\Events::class, 'user_id')
            ->where('type', 'promotion')
            ->orderBy('fecha_evento', 'desc');
    }
}
