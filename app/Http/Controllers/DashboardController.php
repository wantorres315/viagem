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
        $user = auth()->user();
        $totalViagens = $user->viagens()->count();
        $totalPessoas = 0;  
        $totalAmigos =0;
        $totalMalas = 0;
        $totalPasseios = 0;
        $totalPresentes = 0;
        $totalChecklist = 0;
        $totalChecklistConcluido = 0;
        foreach ($user->viagens as $viagem) {
            $totalPessoas += $viagem->pessoas()->count();
            $totalAmigos += $viagem->amigos()->count();
            $totalMalas += $viagem->malas()->count();
            $totalPasseios += $viagem->itinerarios()->withCount('passeios')->get()->sum('passeios_count');
            $totalPresentes += $viagem->amigos()->withCount('presentes')->get()->sum('presentes_count');
            $totalChecklist += $viagem->checklist()->count();
            $totalChecklistConcluido += $viagem->checklist()->where('concluido', true)->count();
        }
       

        // Buscar a próxima viagem do usuário
        $viagemAtual = Viagem::where('user_id', auth()->id())
            ->where('data_ida', '>=', now()->toDateString())
            ->orderBy('data_ida')
            ->with(['itinerarios.passeios.pessoas', 'pessoas'])
            ->first();

        $labels = [];
        $valores = [];
        $numAdultos = 0;
        $numCriancas = 0;
        $gastoTotal = 0;
        $budget = 0;
        $restante = 0;

        if ($viagemAtual) {
            $pessoas = $viagemAtual->pessoas;
            $numAdultos = $pessoas->where('idade', '>=', 12)->count();
            $numCriancas = $pessoas->where('idade', '<', 12)->count();

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
        }

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
