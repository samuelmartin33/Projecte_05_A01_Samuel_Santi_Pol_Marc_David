<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalEventos' => Evento::count(),
            'eventosActivos' => Evento::where('estado', 1)->count(),
        ]);
    }
}
