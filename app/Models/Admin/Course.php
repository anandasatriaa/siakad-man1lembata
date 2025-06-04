<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'grade', 'code'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'course_id');
    }
}
