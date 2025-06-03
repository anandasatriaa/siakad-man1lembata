<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'email',
        'religion',
        'enrollment_year',
        'photo',
        'status',
        'user_id'
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
