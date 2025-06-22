<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;

class GradeStudent extends Model
{
    protected $table = 'grade_students';

    // Mass assignable fields
    protected $fillable = [
        'student_id',
        'class_id',
        'course_id',
        'teacher_id',
        'assignment_score',
        'mid_exam_score',
        'final_exam_score',
        'final_score',
        'is_pass',
    ];

    // Casts
    protected $casts = [
        'assignment_score' => 'decimal:2',
        'mid_exam_score'   => 'decimal:2',
        'final_exam_score' => 'decimal:2',
        'final_score'      => 'decimal:2',
        'is_pass'          => 'boolean',
    ];

    // Optional relationships
    public function student()
    {
        return $this->belongsTo(\App\Models\Admin\Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Admin\Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Admin\Course::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(\App\Models\Admin\SchoolClass::class, 'class_id');
    }
}
