<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grade_students';
    protected $fillable = [
        'student_id','class_id','course_id','teacher_id',
        'assignment_score','mid_exam_score','final_exam_score',
        'final_score','is_pass'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
