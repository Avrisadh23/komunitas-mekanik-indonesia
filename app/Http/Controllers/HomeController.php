<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function produk()
    {
        return view('pages.produk');
    }

    public function komunitas()
    {
        return view('pages.komunitas');
    }

    public function bengkel()
    {
        return view('pages.bengkel');
    }
}