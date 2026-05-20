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
            <li>{{ $pessoa->nome }} ({{ $pessoa->idade }} anos)</li>
        @endforeach
    </ul>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Presentes</h3>
    <ul class="ml-4">
        @foreach($viagem->amigos as $amigo)
            @if($amigo->presentes->count())
                <li class="mb-2"><span class="font-semibold">{{ $amigo->nome }}</span> 
                    <ul class="ml-4 list-disc">
                        @foreach($amigo->presentes as $presente)
                            <li>{{ $presente->presente }}</li>
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Malas</h3>
    <ul class="ml-4">
        @foreach($viagem->malas as $mala)
            <li class="font-semibold">{{ $mala->descricao }}
                <ul class="ml-4 list-disc">
                    @foreach($mala->itens as $item)
                        <li>{{ $item->nome }}</li>
                    @endforeach
                    @foreach($mala->presentes as $presente)
                        <li>Presente: {{ $presente->presente }} -&gt; {{ $presente->amigo->nome ?? '-' }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
