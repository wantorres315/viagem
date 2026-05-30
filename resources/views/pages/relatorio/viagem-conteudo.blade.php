@php
// Exemplo de estrutura, ajuste conforme os relacionamentos reais
@endphp
<style>
@media print {
  img.foto-pdf {
    max-width: 350px !important;
    max-height: 350px !important;
    border-radius: 10px !important;
    border: 2px solid #666 !important;
    margin: 10px auto !important;
    display: block !important;
    page-break-inside: avoid !important;
  }
}
</style>

<div class="bg-white p-6 rounded-xl shadow">

    {{-- DADOS DA VIAGEM --}}
    <h2 class="text-xl font-bold mb-2">
        Dados da Viagem
    </h2>

    <p>
        <strong>Nome:</strong>
        {{ $viagem->nome }}
    </p>

    <p>
        <strong>Data:</strong>
        {{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}
        <span style="color: #888; font-size: 0.95em;">
            ({{ \Carbon\Carbon::parse($viagem->data_ida)->locale('pt_BR')->isoFormat('dddd') }})
        </span>
        até
        {{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}
        <span style="color: #888; font-size: 0.95em;">
            ({{ \Carbon\Carbon::parse($viagem->data_volta)->locale('pt_BR')->isoFormat('dddd') }})
        </span>
    </p>

    <p>
        <strong>Orçamento:</strong>
        R$ {{ number_format($viagem->budget, 2, ',', '.') }}
    </p>

    <hr class="my-4">

    {{-- ITINERÁRIO --}}
    <h3 class="font-semibold text-lg mb-2">
        Itinerário e Passeios
    </h3>

    @foreach($viagem->itinerarios as $itinerario)


        <div class="mb-2">
            <div
                style="
                    background:#e3f2fd;
                    color:#1565c0;
                    padding:6px 12px;
                    border-radius:6px;
                    font-weight:bold;
                    margin-bottom:4px;
                "
            >
                {{ \Carbon\Carbon::parse($itinerario->data)->format('d/m/Y') }}
                <span style="color: #888; font-size: 0.95em;">
                    ({{ \Carbon\Carbon::parse($itinerario->data)->locale('pt_BR')->isoFormat('dddd') }})
                </span>
            </div>

            @if(count($itinerario->passeios))
                <ul class="ml-4 list-disc">
                    @foreach($itinerario->passeios as $passeio)
                        <li class="mb-2 pb-2">
                            {{ $passeio->nome }}
                            (
                            Adulto:
                            R$ {{ number_format($passeio->valor_adulto,2,',','.') }},
                            Criança:
                            R$ {{ number_format($passeio->valor_crianca,2,',','.') }}
                            )
                            {{-- PESSOAS --}}
                            <ul class="ml-4 list-square text-sm">
                                @foreach($viagem->pessoas as $pessoa)
                                    <li>
                                        {{ $pessoa->nome }}:
                                        @if($pessoa->idade >= 12)
                                            R$
                                            {{ number_format($passeio->valor_adulto,2,',','.') }}
                                            (adulto)
                                        @else
                                            R$
                                            {{ number_format($passeio->valor_crianca,2,',','.') }}
                                            (criança)
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            {{-- AMIGOS --}}
                            @if($passeio->amigos && $passeio->amigos->count())
                                <div class="mt-1 text-xs text-blue-700">
                                    <strong>
                                        Amigos neste passeio:
                                    </strong>
                                    {{ $passeio->amigos->pluck('nome')->join(', ') }}
                                </div>
                            @endif
                            <hr
                                class="my-2"
                                style="border-top:1px solid #bbb;"
                            >
                        </li>
                    @endforeach
                </ul>
            @else
                <div style="height: 80px; border: 1px dashed #bbb; margin: 10px 0 20px 0; border-radius: 6px; background: #f8fafc;">
                    @for($i=0;$i<4;$i++)
                        <div style="border-bottom:1px dotted #ccc; height:18px; margin:0 20px;"></div>
                    @endfor
                </div>
            @endif
        </div>

    @endforeach

    <hr class="my-4">

    {{-- PESSOAS --}}
    <h3 class="font-semibold text-lg mb-2">
        Pessoas da Viagem
    </h3>

    <ul class="ml-4 list-disc">

        @foreach($viagem->pessoas as $pessoa)

            <li style="margin-bottom: 10px;">

                <div>
                    <strong>{{ $pessoa->nome }}</strong>
                    ({{ $pessoa->idade }} anos)
                </div>

                {{-- DOCUMENTOS --}}
                @if($pessoa->documentos && count($pessoa->documentos))

                    <div
                        style="
                            margin-left: 10px;
                            margin-top: 2px;
                        "
                    >

                        <span
                            style="
                                font-size: 0.95em;
                                color: #444;
                            "
                        >
                            Documentos:
                        </span>

                        @foreach($pessoa->documentos as $doc)

                            <div
                                style="
                                    display: inline-block;
                                    margin-right: 12px;
                                    margin-bottom: 4px;
                                "
                            >

                                <span style="font-size:0.9em;">
                                    {{ $doc->tipo }}
                                </span>

                                {{-- FOTO --}}
                                @if($doc->foto)

                                    <br>

                                    <img
                                        src="{{ public_path('storage/' . $doc->foto) }}"
                                        alt="Foto documento"
                                        class="foto-pdf"
                                    >

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </li>

        @endforeach

    </ul>

    <hr class="my-4">

    {{-- CHECKLIST --}}
    <h3 class="font-semibold text-lg mb-2">
        Checklist
    </h3>

    <ul class="ml-4">

        @foreach($viagem->checklist as $checklist)

            <li>
                ☐ {{ $checklist->tarefa }}
            </li>

        @endforeach

    </ul>

    <hr class="my-4">

    {{-- MALAS --}}
    <h3 class="font-semibold text-lg mb-2">
        Malas
    </h3>

    <ul class="ml-4">

        @foreach($viagem->malas as $mala)

            <li class="font-semibold">
                {{ $mala->descricao }}
                <ul
                    class="ml-4"
                    style="border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px; list-style:none;"
                >
                    {{-- ITENS --}}
                    @foreach($mala->itens as $item)
                        <li style="display:flex;align-items:center;gap:4px;">
                            @if($item->na_mala)
                                <span style="color:green;font-size:1.2em;">&#10003;</span>
                            @endif
                            <span>{{ $item->item }}</span>
                        </li>
                    @endforeach
                    {{-- PRESENTES --}}
                    @foreach($mala->presentes as $presente)
                        <li>
                            {{ $presente->amigo->nome ?? '-' }} - {{ $presente->presente }}
                            @if($presente->entregue)
                                <span style="color:green;">(entregue)</span>
                            @endif
                        </li>
                    @endforeach
                    {{-- FOTOS --}}
                    @if($mala->fotos && count($mala->fotos))
                        <li class="mt-2" style="list-style:none;">
                            <table class="w-auto" style="border-collapse:collapse;">
                                <tbody>
                                @foreach($mala->fotos as $foto)
                                    <tr>
                                        <td class="px-2 py-2 text-center">
                                                <img src="{{ public_path('storage/' . $foto->caminho) }}" alt="Foto da mala" class="foto-pdf">
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </li>
                    @endif
                </ul>
            </li>

        @endforeach

    </ul>

</div>