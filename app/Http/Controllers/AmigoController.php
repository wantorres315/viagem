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
        $viagens = \App\Models\Viagem::all();
        return view('pages.amigos.create', compact('viagens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'viagem_id' => 'required|exists:viagens,id',
        ]);
        $amigo = Amigo::create($data);
        return redirect()->route('amigo.index')->with('success', 'Amigo criado!');
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
        $malas = collect();
        if ($viagem) {
            // Buscar todos os passeios dos itinerários da viagem
            $passeios = $viagem->itinerarios->flatMap(function($itinerario) {
                return $itinerario->passeios;
            });
            $malas = $viagem->malas;
        }
        return view('pages.amigos.edit', compact('amigo', 'passeios', 'malas'));
    }

   public function update(Request $request, $amigoId)
{
    $amigo = Amigo::findOrFail($amigoId);

    /*
    |--------------------------------------------------------------------------
    | VALIDAR
    |--------------------------------------------------------------------------
    */

    $data = $request->validate([
        'nome' => 'required|string|max:255',
        'cidade' => 'nullable|string|max:255',
    ]);

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR AMIGO
    |--------------------------------------------------------------------------
    */

    $amigo->update($data);

    /*
    |--------------------------------------------------------------------------
    | SINCRONIZAR PASSEIOS
    |--------------------------------------------------------------------------
    */

    $passeiosSelecionados = $request->input('passeios', []);

    $amigo->passeios()->sync($passeiosSelecionados);

    /*
    |--------------------------------------------------------------------------
    | SINCRONIZAR PRESENTES
    |--------------------------------------------------------------------------
    */

    $presentesInput = $request->input('presentes', []);

    $idsMantidos = [];

    foreach ($presentesInput as $i => $presenteData) {

        /*
        |--------------------------------------------------------------------------
        | CHECKBOX ENTREGUE
        |--------------------------------------------------------------------------
        |
        | Se checkbox vier marcado:
        | entregue = true
        |
        | Se checkbox NÃO vier:
        | entregue = false
        |
        */

        $entregue = isset($presenteData['entregue']);

        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR PRESENTE EXISTENTE
        |--------------------------------------------------------------------------
        */

        if (isset($presenteData['id'])) {

            $presente = $amigo->presentes()
                ->find($presenteData['id']);

            if ($presente) {

                $presente->update([

                    'presente' => $presenteData['descricao'],

                    'mala_id' => $presenteData['mala_id'],

                    'entregue' => $entregue,

                ]);

                $idsMantidos[] = $presente->id;
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | CRIAR NOVO PRESENTE
            |--------------------------------------------------------------------------
            */

            $novo = $amigo->presentes()->create([

                'presente' => $presenteData['descricao'],

                'mala_id' => $presenteData['mala_id'],

                'entregue' => $entregue,

            ]);

            $idsMantidos[] = $novo->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVER PRESENTES EXCLUÍDOS
    |--------------------------------------------------------------------------
    */

    $amigo->presentes()
        ->whereNotIn('id', $idsMantidos)
        ->delete();

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('amigo.edit', $amigo->id)
        ->with('success', 'Amigo atualizado!');
}

    public function destroy($amigoId)
    {
        $amigo = Amigo::findOrFail($amigoId);
        $amigo->delete();
        return redirect()->route('amigo.index')->with('success', 'Amigo deletado!');
    }
}
