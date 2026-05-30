@php
@endphp
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-2">Itinerário da Viagem</h2>
    <p><strong>Nome:</strong> {{ $viagem->nome }}</p>
    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}
        <span style="color: #888; font-size: 0.95em;">({{ \Carbon\Carbon::parse($viagem->data_ida)->locale('pt_BR')->isoFormat('dddd') }})</span>
        até
        {{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}
        <span style="color: #888; font-size: 0.95em;">({{ \Carbon\Carbon::parse($viagem->data_volta)->locale('pt_BR')->isoFormat('dddd') }})</span>
    </p>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Itinerário e Passeios</h3>
    @foreach($viagem->itinerarios as $itinerario)
        <div class="mb-2">
            <div style="background:#e3f2fd; color:#1565c0; padding:6px 12px; border-radius:6px; font-weight:bold; margin-bottom:4px;">
                {{ \Carbon\Carbon::parse($itinerario->data)->format('d/m/Y') }}
                <span style="color: #888; font-size: 0.95em;">({{ \Carbon\Carbon::parse($itinerario->data)->locale('pt_BR')->isoFormat('dddd') }})</span>
            </div>
            <ul class="ml-4 list-disc">
                @foreach($itinerario->passeios as $passeio)
                    <li class="mb-2 pb-2">
                        {{ $passeio->nome }}
                        @if($passeio->pessoas && count($passeio->pessoas))
                            <ul class="ml-4 list-square text-sm">
                                @foreach($passeio->pessoas as $pessoa)
                                    <li>{{ $pessoa->nome }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <hr class="my-2" style="border-top:1px solid #bbb;">
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
