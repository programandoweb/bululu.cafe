<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'unit',
        'is_active',
        'contact_phone',
        'contact_email',
        'whatsapp_link',
        'location',
        'category_id', // Make sure this is in your migration
        'image',       // Make sure this is in your migration
        'allow_comments', // Make sure this is in your migration
        'allow_location', // Make sure this is in your migration
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'allow_comments' => 'boolean',
        'allow_location' => 'boolean',
        'price' => 'decimal:2', // Useful for ensuring decimal type
    ];
}