<?php

namespace App\Http\Controllers;


use App\Models\Viagem;
use App\Models\Pessoa;
use App\Models\Amigo;
use App\Models\Mala;
use App\Models\Passeio;
use App\Models\ChecklistViagem;
use App\Models\Presente;

class DashboardController extends Controller
{
    public function index()
    {
        $totalViagens = Viagem::where("user_id", auth()->user()->id)->count();
        $totalPessoas = Pessoa::count();
        $totalAmigos = Amigo::count();
        $totalMalas = Mala::count();
        $totalPasseios = Passeio::count();
        $totalPresentes = Presente::count();
        $totalChecklist = ChecklistViagem::count();
        $totalChecklistConcluido = ChecklistViagem::where('concluido', true)->count();

        // Buscar a próxima viagem do usuário
        $viagemAtual = Viagem::where('user_id', auth()->id())
            ->where('data_ida', '>=', now()->toDateString())
            ->orderBy('data_ida')
            ->first();

        $labels = [];
        $valores = [];
        $pessoas = $viagemAtual->pessoas;
        $numAdultos = $pessoas->where('idade', '>=', 12)->count();
        $numCriancas = $pessoas->where('idade', '<', 12)->count();

        $gastoTotal = 0;
        foreach($viagemAtual->itinerarios as $itinerario) {
            $labels[] = $itinerario->data;
            $valorTotal = 0;
            foreach ($itinerario->passeios as $passeio) {
                $valorTotal += ($passeio->valor_adulto ?? 0) * $numAdultos;
                $valorTotal += ($passeio->valor_crianca ?? 0) * $numCriancas;
            }
            $valores[] = $valorTotal;
            $gastoTotal += $valorTotal;
        }

        $budget = $viagemAtual->budget ?? 0;
        $restante = $budget - $gastoTotal;

        // Última viagem do usuário (mais recente, já realizada ou futura)
        $ultimaViagem = Viagem::where('user_id', auth()->id())
            ->orderBy('data_ida', 'desc')
            ->first();

        $checklistUltimaViagem = [];
        if ($ultimaViagem) {
            $checklistUltimaViagem = ChecklistViagem::where('viagem_id', $ultimaViagem->id)->get();
        }

        return view('pages.dashboard.index', compact(
            'totalViagens',
            'totalPessoas',
            'totalAmigos',
            'totalMalas',
            'totalPasseios',
            'totalPresentes',
            'totalChecklist',
            'totalChecklistConcluido',
            'labels',
            'valores',
            'budget',
            'gastoTotal',
            'restante',
            'checklistUltimaViagem',
        ));
    }
}
