<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioViagemController extends Controller
{
    public function index(Request $request)
    {
        $viagens = Viagem::where('user_id', auth()->id())->orderBy('data_ida', 'desc')->get();
        $viagemSelecionada = null;
        if ($request->viagem_id) {
            $viagemSelecionada = Viagem::with([
                'pessoas',
                'itinerarios.passeios.amigos',
                'amigos.presentes',
                'amigos.passeios',
                'malas.itens',
                'malas.presentes'
            ])->find($request->viagem_id);
        }
        return view('pages.relatorio.index', compact('viagens', 'viagemSelecionada'));
    }

    public function pdf(Request $request)
    {
        $viagem = Viagem::with([
            'pessoas',
            'itinerarios.passeios.amigos',
            'amigos.presentes',
            'amigos.passeios',
            'malas.itens',
            'malas.presentes'
        ])->findOrFail($request->viagem_id);
        $pdf = Pdf::loadView('pages.relatorio.viagem-conteudo', compact('viagem'));
        return $pdf->download('relatorio-viagem-'.$viagem->id.'.pdf');
    }

    public function itinerarioPdf(Request $request)
    {
        $viagem = Viagem::with([
            'itinerarios.passeios'
        ])->findOrFail($request->viagem_id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.relatorio.viagem-itinerario', compact('viagem'));
        return $pdf->download('itinerario-viagem-'.$viagem->id.'.pdf');
    }
}
