<?php

namespace App\Http\Controllers;

use App\Models\FeedbackMahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
}
