<?php

namespace App\Http\Controllers;

use App\Models\Amigo;
use Illuminate\Http\Request;

class AmigoController extends Controller
{
    public function index()
    {
        $amigos = Amigo::all();
        return view('pages.amigos.index', compact('amigos'));
    }

    public function create()
    {
        return view('pages.amigos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cidade' => 'nullable|string|max:255',
        ]);
        $amigo = Amigo::create($data);
        return redirect()->route('amigo.edit', $amigo->id)->with('success', 'Amigo criado!');
    }

    public function edit($amigoId)
    {
        $amigo = Amigo::findOrFail($amigoId);
        $passeios = \App\Models\Passeio::all();
        return view('pages.amigos.edit', compact('amigo', 'passeios'));
    }

    public function update(Request $request, $amigoId)
    {
        $amigo = Amigo::findOrFail($amigoId);
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cidade' => 'nullable|string|max:255',
            // presentes e passeios depois
        ]);
        $amigo->update($data);
        // salvar presentes e passeios depois
        return redirect()->route('amigo.edit', $amigo->id)->with('success', 'Amigo atualizado!');
    }
}
