<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // Gallery Methods
    public function getGallery()
    {
        return response()->json(['status' => 'success']);
    }

    public function saveGallery(Request $request)
    {
        return response()->json(['status' => 'success']);
    }

    public function deleteGallery($id)
    {
        return response()->json(['status' => 'success']);
    }

    // Bengkel Methods
    public function getBengkel()
    {
        return response()->json(['status' => 'success']);
    }

    public function saveBengkel(Request $request)
    {
        return response()->json(['status' => 'success']);
    }

    public function deleteBengkel($id)
    {
        return response()->json(['status' => 'success']);
    }

    // Product Methods
    public function getProducts()
    {
        return response()->json(['status' => 'success']);
    }

    public function saveProduct(Request $request)
    {
        return response()->json(['status' => 'success']);
    }

    public function deleteProduct($id)
    {
        return response()->json(['status' => 'success']);
    }
}
