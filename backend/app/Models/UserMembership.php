<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    use HasFactory;

    protected $table = 'user_memberships';

    protected $fillable = [
        'user_id',
        'membership_id',
        'start_date',
        'end_date',
    ];

    // 🔹 Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔹 Relación con la membresía
    public function membership()
    {
        return $this->belongsTo(Memberships::class, 'membership_id');
    }

}
