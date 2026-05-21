<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Passeio;

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
            'pessoas.documentos',
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'pages.relatorio.viagem-itinerario',
            compact('viagem')
        )->setOptions([
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download(
            'itinerario-viagem-'.$viagem->id.'.pdf'
        );
    }

    public function presentesPorEvento(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | LISTA DE VIAGENS
    |--------------------------------------------------------------------------
    */

    $viagens = Viagem::where('user_id', auth()->id())
        ->orderBy('data_ida', 'desc')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | VARIÁVEIS
    |--------------------------------------------------------------------------
    */

    $viagem = null;

    $passeioSelecionado = null;

    /*
    |--------------------------------------------------------------------------
    | CARREGA VIAGEM
    |--------------------------------------------------------------------------
    */

    if ($request->filled('viagem_id')) {

        $viagem = Viagem::with([
            'itinerarios.passeios'
        ])
        ->where('user_id', auth()->id())
        ->find($request->viagem_id);
    }

    /*
    |--------------------------------------------------------------------------
    | CARREGA PASSEIO
    |--------------------------------------------------------------------------
    |
    | Apenas presentes NÃO entregues
    |
    */

    if ($request->filled('passeio_id')) {

        $passeioSelecionado = Passeio::with([

            'amigos.presentes' => function ($query) {

                $query->where('entregue', false);

            },

            'amigos.presentes.mala'

        ])->find($request->passeio_id);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'pages.relatorio.presentes-por-evento',
        compact(
            'viagens',
            'viagem',
            'passeioSelecionado'
        )
    );
}
}
