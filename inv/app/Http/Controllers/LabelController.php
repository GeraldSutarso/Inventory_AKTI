<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return view('label.index'); // Pastikan ada file resources/views/label/index.blade.php
    }
}
