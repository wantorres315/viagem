@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Relatório: Presentes por Passeio" />

<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-6">
        Relatório de Presentes
    </h2>

    <form method="GET" action="">

        {{-- VIAGEM --}}
        <div class="mb-4">

            <label
                for="viagem_id"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Selecione a Viagem
            </label>

            <select
                name="viagem_id"
                id="viagem_id"
                class="form-control w-full max-w-md"
                onchange="this.form.submit()"
            >
                <option value="">Selecione...</option>

                @foreach($viagens as $v)

                    <option
                        value="{{ $v->id }}"
                        @selected(request('viagem_id') == $v->id)
                    >
                        {{ $v->nome }}
                        ({{ \Carbon\Carbon::parse($v->data_ida)->format('d/m/Y') }})
                    </option>

                @endforeach

            </select>

        </div>

        {{-- PASSEIO --}}
        @if($viagem)

            <div class="mb-4">

                <label
                    for="passeio_id"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Selecione o Passeio
                </label>

                <select
                    name="passeio_id"
                    id="passeio_id"
                    class="form-control w-full max-w-md"
                    onchange="this.form.submit()"
                >
                    <option value="">Selecione...</option>

                    @foreach($viagem->itinerarios as $itinerario)

                        @foreach($itinerario->passeios as $passeio)

                            <option
                                value="{{ $passeio->id }}"
                                @selected(request('passeio_id') == $passeio->id)
                            >
                                {{ \Carbon\Carbon::parse($itinerario->data)->format('d/m/Y') }}
                                -
                                {{ $itinerario->nome }}
                                -
                                {{ $passeio->nome }}
                            </option>

                        @endforeach

                    @endforeach

                </select>

            </div>

        @endif

    </form>

    {{-- RELATÓRIO --}}
    @if($passeioSelecionado)

        <div class="mt-8">

            <div class="border-b pb-3 mb-6">

                <h3 class="text-2xl font-bold text-blue-700">
                    {{ $passeioSelecionado->nome }}
                </h3>

            </div>

            @if($passeioSelecionado->amigos->count())

                <div class="space-y-4">

                    @foreach($passeioSelecionado->amigos as $amigo)

                        @if($amigo->presentes->count())

                            <div class="border rounded-xl p-4 bg-gray-50">

                                {{-- NOME DO AMIGO --}}
                                <div class="font-bold text-lg text-gray-800 mb-3">
                                    {{ $amigo->nome }}
                                </div>

                                {{-- LISTA DE PRESENTES --}}
                                <ul class="space-y-3">

                                    @foreach($amigo->presentes as $presente)

                                        <li class="bg-white border rounded-lg p-4">

                                            {{-- PRESENTE --}}
                                            <div class="font-medium text-gray-800">
                                                🎁 {{ $presente->presente }}
                                            </div>

                                            {{-- MALA --}}
                                            @if($presente->mala)

                                                <div class="mt-2 text-sm text-blue-700">

                                                    🧳 Mala:
                                                    <strong>
                                                        {{ $presente->mala->descricao }}
                                                    </strong>

                                                </div>

                                            @else

                                                <div class="mt-2 text-sm text-red-500">
                                                    🧳 Sem mala definida
                                                </div>

                                            @endif

                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif

                    @endforeach

                </div>

            @else

                <div class="text-gray-500">
                    Nenhum amigo encontrado neste passeio.
                </div>

            @endif

        </div>

    @endif

</div>

@endsection