<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminManajemenPeriode extends Controller
{
    public function index()
    {
        return view('admin.manajemen_periode.index');
    }
}
