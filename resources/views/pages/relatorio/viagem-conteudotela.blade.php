@php
// Exemplo de estrutura, ajuste conforme os relacionamentos reais
@endphp
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-2">Dados da Viagem</h2>
    <p><strong>Nome:</strong> {{ $viagem->nome }}</p>
    <p><strong>Data:</strong> {{ $viagem->data_ida }} até {{ $viagem->data_volta }}</p>
    <p><strong>Orçamento:</strong> R$ {{ number_format($viagem->budget, 2, ',', '.') }}</p>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Itinerário e Passeios</h3>
    @foreach($viagem->itinerarios as $itinerario)
        <div class="mb-2">
            <div style="background:#e3f2fd; color:#1565c0; padding:6px 12px; border-radius:6px; font-weight:bold; margin-bottom:4px;">
                {{ \Carbon\Carbon::parse($itinerario->data)->format('d/m/Y') }}
            </div>
            <ul class="ml-4 list-disc">
                @foreach($itinerario->passeios as $passeio)
                    <li class="mb-2 pb-2">
                        {{ $passeio->nome }} (Adulto: R$ {{ number_format($passeio->valor_adulto,2,',','.') }}, Criança: R$ {{ number_format($passeio->valor_crianca,2,',','.') }})
                        <ul class="ml-4 list-square text-sm">
                            @foreach($viagem->pessoas as $pessoa)
                                <li>
                                    {{ $pessoa->nome }}:
                                    @if($pessoa->idade >= 12)
                                        R$ {{ number_format($passeio->valor_adulto,2,',','.') }} (adulto)
                                    @else
                                        R$ {{ number_format($passeio->valor_crianca,2,',','.') }} (criança)
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if($passeio->amigos && $passeio->amigos->count())
                            <div class="mt-1 text-xs text-blue-700">
                                <strong>Amigos neste passeio:</strong>
                                {{ $passeio->amigos->pluck('nome')->join(', ') }}
                            </div>
                        @endif
                        <hr class="my-2" style="border-top:1px solid #bbb;">
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Pessoas da Viagem</h3>
    <ul class="ml-4 list-disc">
        @foreach($viagem->pessoas as $pessoa)
            <li style="margin-bottom: 10px;">
                <div><strong>{{ $pessoa->nome }}</strong> ({{ $pessoa->idade }} anos)</div>
                @if($pessoa->documentos && count($pessoa->documentos))
                    <div style="margin-left: 10px; margin-top: 2px;">
                        <span style="font-size: 0.95em; color: #444;">Documentos:</span>
                        @foreach($pessoa->documentos as $doc)
                            <div style="display: inline-block; margin-right: 12px; margin-bottom: 4px;">
                                <span style="font-size:0.9em;">{{ $doc->tipo }}</span>
                                @if($doc->foto)
                                    <br>
                                    <img src="{{ asset('storage/' . $doc->foto) }}" alt="Foto documento" style="max-width:200px; max-height:200px; border:2px solid #666; border-radius:10px; margin-top:6px;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Checklist</h3>
    <ul class="ml-4">
        @foreach($viagem->checklist as $checklist)
            <li><input type = "checkbox" class = "mr-2">{{ $checklist->tarefa }}</li>
        @endforeach
    </ul>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Malas</h3>
    <div class="ml-4">
        @foreach($viagem->malas as $mala)
            <div class="font-semibold">{{ $mala->descricao }}
                <div class="ml-4" style="border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px;">
                    @foreach($mala->itens as $item)
                        <div style="display:flex;align-items:center;gap:4px;">
                            @if($item->na_mala)
                                <span style="color:green;font-size:1.2em;">&#10003;</span>
                            @endif
                            <span>{{ $item->item }}</span>
                        </div>
                    @endforeach
                    @foreach($mala->presentes as $presente)
                        <div>{{ $presente->amigo->nome ?? '-' }} - {{ $presente->presente }}</div>
                    @endforeach
                    @if($mala->fotos && count($mala->fotos))
                        <div class="mt-2">
                            <table class="w-auto" style="border-collapse:collapse;">
                                <tbody>
                                @foreach($mala->fotos as $foto)
                                    <tr>
                                        <td class="px-2 py-2 text-center">
                                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto da mala" style="max-width:350px; max-height:350px; border-radius:10px; border:2px solid #666; margin:10px auto; display:block;">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
