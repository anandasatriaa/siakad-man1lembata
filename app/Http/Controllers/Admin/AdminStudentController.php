<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Models\Admin\Classroom;

class AdminStudentController extends Controller
{
    public function index()
    {
        return view('admin.student');
    }

    // Metode lain seperti store, edit, update, destroy akan ditambahkan nanti
}
