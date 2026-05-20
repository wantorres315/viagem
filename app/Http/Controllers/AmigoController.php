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
        // Find the viagem via pessoa (assuming amigo is linked to pessoa)
        $viagem = null;
        if (method_exists($amigo, 'pessoa') && $amigo->pessoa) {
            $viagem = $amigo->pessoa->viagem;
        } elseif (isset($amigo->viagem_id)) {
            $viagem = \App\Models\Viagem::find($amigo->viagem_id);
        }

        $passeios = collect();
        if ($viagem) {
            $passeios = \App\Models\Passeio::whereIn('itinerario_id', $viagem->itinerarios->pluck('id'))->get();
        }
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
