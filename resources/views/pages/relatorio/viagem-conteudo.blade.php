@php
// Exemplo de estrutura, ajuste conforme os relacionamentos reais
@endphp

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
        {{ $viagem->data_ida }}
        até
        {{ $viagem->data_volta }}
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
            </div>

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
                                        style="
                                            max-width:200px;
                                            max-height:200px;
                                            border:2px solid #666;
                                            border-radius:10px;
                                            margin-top:6px;
                                        "
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
                    class="ml-4 list-disc"
                    style="
                        border-bottom:1px solid #eee;
                        padding-bottom:10px;
                        margin-bottom:10px;
                    "
                >

                    {{-- ITENS --}}
                    @foreach($mala->itens as $item)

                        <li>
                            {{ $item->nome }}
                        </li>

                    @endforeach

                    {{-- PRESENTES --}}
                    @foreach($mala->presentes as $presente)

                        <li>

                            {{ $presente->amigo->nome ?? '-' }}
                            -
                            {{ $presente->presente }}

                            @if($presente->entregue)

                                <span style="color:green;">
                                    (entregue)
                                </span>

                            @endif

                        </li>

                    @endforeach

                </ul>

            </li>

        @endforeach

    </ul>

</div>