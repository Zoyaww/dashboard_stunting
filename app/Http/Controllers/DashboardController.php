<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('user')) {
            return redirect('/login');
        }

        return view('dashboard');
    }
}

