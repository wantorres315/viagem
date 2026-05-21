@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Relatório: Presentes por Evento" />
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">Presentes por Evento</h2>
        <form method="GET" action="">
            <div class="mb-4">
                <label for="viagem_id" class="block text-sm font-medium text-gray-700 mb-1">Selecione a Viagem</label>
                <select name="viagem_id" id="viagem_id" class="form-control w-full max-w-xs" onchange="this.form.submit()">
                    <option value="">Selecione...</option>
                    @foreach($viagens as $v)
                        <option value="{{ $v->id }}" @if(request('viagem_id') == $v->id) selected @endif>{{ $v->nome }} ({{ $v->data_ida }})</option>
                    @endforeach
                </select>
            </div>
        </form>
        @if($viagem)
            <div class="mt-6">
                <h3 class="font-semibold text-lg mb-2">Presentes por Evento/Passeio</h3>
                @foreach($viagem->itinerarios as $itinerario)
                    <div class="mb-3">
                        <div class="font-bold text-blue-700 mb-1">{{ \Carbon\Carbon::parse($itinerario->data)->format('d/m/Y') }} - {{ $itinerario->nome }}</div>
                        @foreach($itinerario->passeios as $passeio)
                            <div class="ml-4 mb-2">
                                <div class="font-semibold">Passeio: {{ $passeio->nome }}</div>
                                <ul class="ml-4 list-disc">
                                    @foreach($passeio->amigos as $amigo)
                                        @if($amigo->presentes->count())
                                            <li>{{ $amigo->nome }}
                                                <ul class="ml-4 list-square">
                                                    @foreach($amigo->presentes as $presente)
                                                        <li>{{ $presente->presente }}</li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
