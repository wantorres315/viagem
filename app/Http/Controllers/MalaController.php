<?php

namespace App\Http\Controllers;

use App\Models\Mala;

use Illuminate\Http\Request;

class MalaController extends Controller
{
    public function index()
    {
        // Exibe a lista de viagens do usuário
        $malas = auth()->user()->viagens()->malas()->latest()->get(); 
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
    /*
    |--------------------------------------------------------------------------
    | MALA
    |--------------------------------------------------------------------------
    */

    $mala = Mala::with('itens')->findOrFail($malaId);

    /*
    |--------------------------------------------------------------------------
    | VALIDAR
    |--------------------------------------------------------------------------
    */

    $data = $request->validate([

        'mala' => 'required|string|max:255',

        'tracking' => 'nullable|string|max:255',

        'peso' => 'nullable|numeric',

        'viagem' => 'required|exists:viagens,id',

        'itens' => 'nullable|array',

        'itens.*.id' => 'nullable',

        'itens.*.item' => 'required|string|max:255',

        'itens.*.pessoa_id' => 'required|exists:pessoas,id',

        'itens.*.na_mala' => 'nullable',

    ]);

    /*
    |--------------------------------------------------------------------------
    | UPDATE MALA
    |--------------------------------------------------------------------------
    */

    $mala->descricao = $data['mala'];

    $mala->track = $data['tracking'] ?? null;

    $mala->peso = $data['peso'] ?? null;

    $mala->viagem_id = $data['viagem'];

    $mala->save();

    /*
    |--------------------------------------------------------------------------
    | IDS MANTIDOS
    |--------------------------------------------------------------------------
    */

    $idsMantidos = [];

    /*
    |--------------------------------------------------------------------------
    | ITENS
    |--------------------------------------------------------------------------
    */

    if (!empty($data['itens'])) {

        foreach ($data['itens'] as $itemData) {

            /*
            |--------------------------------------------------------------------------
            | BOOLEAN
            |--------------------------------------------------------------------------
            */

            $naMala = false;

            if (
                isset($itemData['na_mala']) &&
                (
                    $itemData['na_mala'] == 1 ||
                    $itemData['na_mala'] === '1' ||
                    $itemData['na_mala'] === true
                )
            ) {

                $naMala = true;
            }

            /*
            |--------------------------------------------------------------------------
            | DADOS
            |--------------------------------------------------------------------------
            */

            $dados = [

                'item' => $itemData['item'],

                'pessoa_id' => $itemData['pessoa_id'],

                'na_mala' => $naMala,

            ];

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            if (!empty($itemData['id'])) {

                $item = $mala->itens()
                    ->find($itemData['id']);

                if ($item) {

                    $item->update($dados);

                    $idsMantidos[] = $item->id;
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | CREATE
                |--------------------------------------------------------------------------
                */

                $novo = $mala->itens()->create($dados);

                $idsMantidos[] = $novo->id;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE EXCLUÍDOS
    |--------------------------------------------------------------------------
    */

    if (!empty($idsMantidos)) {

        $mala->itens()
            ->whereNotIn('id', $idsMantidos)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('mala.edit', $mala->id)
        ->with('success', 'Mala atualizada com sucesso!');
}
    
}