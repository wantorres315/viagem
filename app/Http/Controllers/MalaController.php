<?php

namespace App\Http\Controllers;

use App\Models\Mala;

use Illuminate\Http\Request;

class MalaController extends Controller
{
    public function index()
    {
        // Exibe a lista de viagens do usuário
        $malas = Mala::all();
        return view('pages.malas.index', compact('malas'));
    }
    
}