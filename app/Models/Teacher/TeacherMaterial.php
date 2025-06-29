<?php

namespace App\Models\Teacher;

use App\Models\Admin\SchoolClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Admin\Course;
use App\Models\Admin\Teacher;

class TeacherMaterial extends Model
{
    use HasFactory;

    protected $table = 'teacher_materials';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'course_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Relasi ke User (guru)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // Relasi ke Kelas
    public function classroom()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Relasi ke Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
