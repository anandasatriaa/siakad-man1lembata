<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\SchoolClass;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'email',
        'religion',
        'enrollment_year',
        'guardian_name',
        'guardian_phone',
        'photo',
        'status',
        'user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',  // atau 'datetime' jika di DB ada time‐stamp juga
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
