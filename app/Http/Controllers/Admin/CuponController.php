<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;

class CuponController extends Controller
{
    public function index()
    {
        $cupones = Cupon::with(['empresa', 'eventos'])
            ->where('estado', 1)
            ->orderBy('fecha_fin', 'asc')
            ->get();

        return view('admin.cupones.index', compact('cupones'));
    }
}
