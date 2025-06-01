<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminGradeController extends Controller
{
    public function index()
    {
        return view('admin.grade');
    }
}
