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
        $viagem = auth()->user()->viagens()->with(['itinerarios.passeios'])->findOrFail($viagemId);
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
            foreach ($request->input('pessoas') as $pessoaData) {
                if (!empty($pessoaData['nome']) && !empty($pessoaData['idade'])) {
                    $viagem->pessoas()->create([
                        'nome' => $pessoaData['nome'],
                        'idade' => $pessoaData['idade'],
                    ]);
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
        $viagem->destinos()->delete();
        if ($request->has('pessoas')) {
            foreach ($request->input('pessoas') as $pessoaData) {
                if (!empty($pessoaData['nome']) && !empty($pessoaData['idade'])) {
                    $viagem->pessoas()->create([
                        'nome' => $pessoaData['nome'],
                        'idade' => $pessoaData['idade'],
                    ]);
                }
            }
        }
        // Salvar destinos
        if ($request->has('destinos')) {
            foreach ($request->input('destinos') as $destinoData) {
                if (!empty($destinoData['nome']) && !empty($destinoData['data'])) {
                    $vooId = null;
                    if (!empty($destinoData['voo']['numero_voo']) || !empty($destinoData['voo']['ida_volta']) || !empty($destinoData['voo']['assento'])) {
                        $voo = \App\Models\Voo::create([
                            'numero_voo' => $destinoData['voo']['numero_voo'] ?? null,
                            'ida_volta' => $destinoData['voo']['ida_volta'] ?? null,
                            'assento' => $destinoData['voo']['assento'] ?? null,
                        ]);
                        $vooId = $voo->id;
                    }
                    $viagem->destinos()->create([
                        'nome' => $destinoData['nome'],
                        'data' => $destinoData['data'],
                        'voo_id' => $vooId,
                    ]);
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
        return redirect()->route('minha-viagem.index')->with('success', 'Viagem atualizada com sucesso!');
    }

    public function destroy($viagemId)
    {
        $viagem = auth()->user()->viagens()->findOrFail($viagemId);
        $viagem->delete();
        return redirect()->route('minha-viagem.index')->with('success', 'Viagem deletada com sucesso!');
    }
}