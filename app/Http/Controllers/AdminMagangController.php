<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMagangController extends Controller
{
    public function kegiatan()
    {
        return view('admin.magang.kegiatan.index');
    }
}
