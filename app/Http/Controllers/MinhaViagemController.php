<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MinhaViagemController extends Controller
{
    public function index()
    {
        // Exibe a lista de viagens do usuário
        $viagens = auth()->user()->viagens()->latest()->get();
        return view('pages.minha-viagem.index', compact('viagens'));
    }
    public function create()
    {
        return view('pages.minha-viagem.create');
    }

    public function edit($viagemId)
    {
        $viagem = auth()->user()->viagens()->with([
            'itinerarios.passeios',
            'destinos.voo',
            'pessoas.documentos',
        ])->findOrFail($viagemId);
        return view('pages.minha-viagem.edit', compact('viagem'));
    }

    public function store(Request $request)
    {
        // Validação básica
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'data_ida' => 'required|date',
            'data_volta' => 'required|date|after_or_equal:data_ida',
            'budget' => 'nullable|numeric|min:0',
        ]);

        // Salva a viagem para o usuário autenticado
        $viagem = $request->user()->viagens()->create($data);
        // Salvar pessoas
        if ($request->has('pessoas')) {
            foreach ($request->input('pessoas') as $idx => $pessoaData) {
                if (!empty($pessoaData['nome']) && !empty($pessoaData['idade'])) {
                    $pessoa = $viagem->pessoas()->create([
                        'nome' => $pessoaData['nome'],
                        'idade' => $pessoaData['idade'],
                    ]);
                    // Salvar documentos se enviados
                    if (isset($pessoaData['documentos']) && is_array($pessoaData['documentos'])) {
                    foreach ($pessoaData['documentos'] as $docIdx => $doc) {
                        
                            $foto = null;
                            // Trata upload da foto
                            if ($request->hasFile("pessoas.$idx.documentos.$docIdx.foto")) {
                                $fotoFile = $request->file("pessoas.$idx.documentos.$docIdx.foto");
                                $foto = $fotoFile->store('documentos', 'public');
                            }
                            Documento::create([
                                'tipo' => $doc['tipo'],
                                'foto' => $foto,
                                'viagem_id' => $viagem->id,
                                    'pessoa_id' => $pessoa->id,
                                ]);
                           
                        }
                    }
                }
            }
        }

        // Preencher itinerário automaticamente com dias da viagem
        $dataIda = new \DateTime($viagem->data_ida);
        $dataVolta = new \DateTime($viagem->data_volta);
        $interval = $dataIda->diff($dataVolta)->days;
        for ($i = 0; $i <= $interval; $i++) {
            $data = (clone $dataIda)->modify("+{$i} days")->format('Y-m-d');
            $viagem->itinerarios()->create([
                'nome' => 'Dia ' . ($i + 1),
                'data' => $data,
                'descricao' => null,
            ]);
        }
        // Ajusta o array de pessoas para manter compatibilidade
        $viagem->pessoas = $viagem->pessoas->map(function($p) { return $p->toArray(); });
        return view('pages.minha-viagem.create', compact('viagem'));
    }

    public function update(Request $request, $viagemId)
    {
        $viagem = auth()->user()->viagens()->findOrFail($viagemId);
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'data_ida' => 'required|date',
            'data_volta' => 'required|date|after_or_equal:data_ida',
            'budget' => 'nullable|numeric|min:0',
        ]);
        $viagem->update($data);
        // Atualizar pessoas
        $viagem->pessoas()->delete();
        // --- Destinos: atualizar, criar e remover corretamente ---
        $destinosInput = $request->input('destinos', []);
        $destinoIdsInput = collect($destinosInput)->pluck('id')->filter()->all();
        // Remover destinos que não estão mais no input
        $viagem->destinos()->whereNotIn('id', $destinoIdsInput)->each(function($destino) {
            if ($destino->voo) $destino->voo->delete();
            $destino->delete();
        });

        // Atualizar ou criar destinos
        foreach ($destinosInput as $destinoData) {
            if (empty($destinoData['nome']) || empty($destinoData['data'])) continue;
            $destino = null;
            if (!empty($destinoData['id'])) {
                $destino = $viagem->destinos()->where('id', $destinoData['id'])->first();
            }
            // Voo
            $vooData = $destinoData['voo'] ?? null;
            $vooId = null;
            if ($vooData && (!empty($vooData['numero_voo']) || !empty($vooData['ida_volta']) || !empty($vooData['assento']))) {
                if ($destino && $destino->voo) {
                    $destino->voo->update([
                        'numero_voo' => $vooData['numero_voo'] ?? null,
                        'ida_volta' => $vooData['ida_volta'] ?? null,
                        'assento' => $vooData['assento'] ?? null,
                    ]);
                    $vooId = $destino->voo->id;
                } else {
                    $voo = \App\Models\Voo::create([
                        'numero_voo' => $vooData['numero_voo'] ?? null,
                        'ida_volta' => $vooData['ida_volta'] ?? null,
                        'assento' => $vooData['assento'] ?? null,
                    ]);
                    $vooId = $voo->id;
                }
            } elseif ($destino && $destino->voo) {
                // Se não tem mais dados de voo, remover voo antigo
                $destino->voo->delete();
                $vooId = null;
            }
            $destinoPayload = [
                'nome' => $destinoData['nome'],
                'data' => $destinoData['data'],
                'voo_id' => $vooId,
            ];
            if ($destino) {
                $destino->update($destinoPayload);
            } else {
                $viagem->destinos()->create($destinoPayload);
            }
        }

        // Pessoas (depois dos destinos para manter ordem)
        if ($request->has('pessoas')) {
            foreach ($request->input('pessoas') as $idx => $pessoaData) {
                if (!empty($pessoaData['nome']) && !empty($pessoaData['idade'])) {
                    $pessoa = $viagem->pessoas()->create([
                        'nome' => $pessoaData['nome'],
                        'idade' => $pessoaData['idade'],
                    ]);
                    // Salvar documentos se enviados
                    if (isset($pessoaData['documentos']) && is_array($pessoaData['documentos'])) {
                        foreach ($pessoaData['documentos'] as $docIdx => $doc) {
                            if (!empty($doc['tipo'])) {
                                $foto = null;
                                // Trata upload da foto
                                if ($request->hasFile("pessoas.$idx.documentos.$docIdx.foto")) {
                                    $fotoFile = $request->file("pessoas.$idx.documentos.$docIdx.foto");
                                    $foto = $fotoFile->store('documentos', 'public');
                                }
                                $pessoa->documentos()->create([
                                    'tipo' => $doc['tipo'],
                                    'foto' => $foto,
                                    'viagem_id' => $viagem->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        // Salvar itinerários e passeios
        if ($request->has('itinerarios')) {
            // Remove itinerários antigos se necessário (opcional)
            $viagem->itinerarios()->delete();
            foreach ($request->input('itinerarios') as $itinerarioData) {
                // Cria o itinerário
                $itinerario = $viagem->itinerarios()->create([
                    'nome' => $itinerarioData['nome'] ?? null,
                    'descricao' => $itinerarioData['descricao'] ?? null,
                    'data' => $itinerarioData['data'] ?? null,
                ]);
                // Passeios
                if (!empty($itinerarioData['passeios'])) {
                    foreach ($itinerarioData['passeios'] as $passeioData) {
                        $passeio = $itinerario->passeios()->create([
                            'nome' => $passeioData['nome'] ?? null,
                            'valor_adulto' => $passeioData['valor_adulto'] ?? null,
                            'valor_crianca' => $passeioData['valor_crianca'] ?? null,
                        ]);
                        // Para cada pessoa da viagem, cria PasseioPessoa
                        foreach ($viagem->pessoas as $pessoa) {
                            $valor = null;
                            if (isset($pessoa->idade)) {
                                $valor = ($pessoa->idade >= 12)
                                    ? $passeio->valor_adulto
                                    : $passeio->valor_crianca;
                            }
                            \App\Models\PasseioPessoa::create([
                                'passeio_id' => $passeio->id,
                                'pessoa_id' => $pessoa->id,
                                'valor' => $valor,
                                'data' => $itinerario->data,
                            ]);
                        }
                    }
                }
            }
        }
        return redirect()->route('minha-viagem.edit', $viagemId)->with('success', 'Viagem atualizada com sucesso!');
    }

    public function destroy($viagemId)
    {
        $viagem = auth()->user()->viagens()->findOrFail($viagemId);
        $viagem->delete();
        return redirect()->route('minha-viagem.index')->with('success', 'Viagem deletada com sucesso!');
    }
}