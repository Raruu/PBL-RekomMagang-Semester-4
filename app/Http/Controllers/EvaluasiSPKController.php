<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluasiSPKController extends Controller
{
    public function index()
    {
        return view('admin.spk.edit-bobot');
    }
}
