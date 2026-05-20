@php
@endphp
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-2">Itinerário da Viagem</h2>
    <p><strong>Nome:</strong> {{ $viagem->nome }}</p>
    <p><strong>Data:</strong> {{ $viagem->data_ida }} até {{ $viagem->data_volta }}</p>
    <hr class="my-4">
    <h3 class="font-semibold text-lg mb-2">Itinerário e Passeios</h3>
    @foreach($viagem->itinerarios as $itinerario)
        <div class="mb-2">
            <strong>{{ $itinerario->data }}</strong>
            <ul class="ml-4 list-disc">
                @foreach($itinerario->passeios as $passeio)
                    <li>
                        {{ $passeio->nome }}
                        @if($passeio->amigos && count($passeio->amigos))
                            <ul class="ml-4 list-square text-sm">
                                @foreach($passeio->amigos as $amigo)
                                    <li>{{ $amigo->nome }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
