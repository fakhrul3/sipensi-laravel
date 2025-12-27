<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InkubatorController extends Controller
{
    public function index()
    {
        return view('inkubator.index');
    }
}
