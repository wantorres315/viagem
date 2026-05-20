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

    public function create()
    {
        $viagens = auth()->user()->viagens()->latest()->get();
        return view('pages.malas.create', compact('viagens'));
    }

    public function store(Request $request)
    {
        // Validação básica
        $data = $request->validate([
            'mala' => 'required|string|max:255',
            'tracking' => 'nullable|string|max:255',
            'peso' => 'nullable|numeric',
            'viagem' => 'required|exists:viagens,id',
        ]);

        // Salva a mala para o usuário autenticado
        $mala = new Mala();
        $mala->descricao = $data['mala'];
        $mala->track = $data['tracking'] ?? null;
        $mala->peso = $data['peso'] ?? null;
        $mala->viagem_id = $data['viagem'];
        $mala->save();

        return redirect()->route('mala.index')->with('success', 'Mala cadastrada com sucesso!');
    }

    public function edit($malaId)
    {
        $mala = Mala::findOrFail($malaId);
        $viagens = auth()->user()->viagens()->latest()->get();
        return view('pages.malas.edit', compact('mala', 'viagens'));
    }

    public function update(Request $request, $malaId)
    {
        $mala = Mala::findOrFail($malaId);
        $data = $request->validate([
            'mala' => 'required|string|max:255',
            'tracking' => 'nullable|string|max:255',
            'peso' => 'nullable|numeric',
            'viagem' => 'required|exists:viagens,id',
            'itens' => 'nullable|array',
            'itens.*.item' => 'required|string|max:255',
            'itens.*.pessoa_id' => 'required|exists:pessoas,id',
        ]);

        $mala->descricao = $data['mala'];
        $mala->track = $data['tracking'] ?? null;
        $mala->peso = $data['peso'] ?? null;
        $mala->viagem_id = $data['viagem'];
        $mala->save();

        // Atualiza os itens da mala
        $mala->itens()->delete();
        if (!empty($data['itens'])) {
            foreach ($data['itens'] as $itemData) {
                $mala->itens()->create([
                    'item' => $itemData['item'],
                    'pessoa_id' => $itemData['pessoa_id'],
                ]);
            }
        }

        return redirect()->route('mala.edit', $mala->id)->with('success', 'Mala atualizada com sucesso!');
    }
    
}