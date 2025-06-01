<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'title',
        'content',
        'is_active',
    ];

    // Cast is_active ke boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
