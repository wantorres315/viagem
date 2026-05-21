@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Editar Amigo" />

<div class="mt-8">

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 w-full">

        {{-- ALERTA --}}
        @if(session('success'))

            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>

        @endif

        <form method="POST" action="{{ route('amigo.update', $amigo->id) }}">

            @csrf
            @method('PUT')

            {{-- NOME --}}
            <x-form.form-elements.input
                label="Nome do Amigo"
                name="nome"
                :value="$amigo->nome"
                required
            />

            {{-- CIDADE --}}
            <x-form.form-elements.input
                label="Cidade"
                name="cidade"
                :value="$amigo->cidade"
            />

            {{-- PRESENTES --}}
            <div class="mb-6">

                <div class="flex items-center mb-2">

                    <i class="bi bi-gift text-brand-500 mr-2"></i>

                    <span class="text-base font-semibold text-gray-700">
                        Presentes
                    </span>

                </div>

                <div id="presentes-list" class="space-y-3">

                    @foreach($amigo->presentes as $i => $presente)

                        <div class="
                            rounded-xl border border-gray-200 bg-gray-50
                            p-4 flex flex-col lg:flex-row lg:items-end
                            gap-4 presente-item relative shadow-sm
                        ">

                            {{-- ID --}}
                            <input
                                type="hidden"
                                name="presentes[{{ $i }}][id]"
                                value="{{ $presente->id }}"
                            >

                            {{-- DESCRIÇÃO --}}
                            <div class="flex-1 w-full">

                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Descrição
                                </label>

                                <input
                                    type="text"
                                    name="presentes[{{ $i }}][descricao]"
                                    class="form-control w-full"
                                    placeholder="Descrição do presente"
                                    value="{{ $presente->presente }}"
                                    required
                                />

                            </div>

                            {{-- MALA --}}
                            <div class="w-full lg:w-56">

                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Mala
                                </label>

                                <select
                                    name="presentes[{{ $i }}][mala_id]"
                                    class="form-control w-full"
                                    required
                                >

                                    <option value="">
                                        Selecione a mala
                                    </option>

                                    @foreach($malas as $mala)

                                        <option
                                            value="{{ $mala->id }}"
                                            {{ $presente->mala_id == $mala->id ? 'selected' : '' }}
                                        >
                                            {{ $mala->descricao }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- ENTREGUE --}}
                            <div class="w-full lg:w-auto">

                                <label class="inline-flex items-center space-x-2">

                                    <input
                                        type="checkbox"
                                        name="presentes[{{ $i }}][entregue]"
                                        value="1"
                                        class="form-checkbox rounded text-green-600"
                                        {{ $presente->entregue ? 'checked' : '' }}
                                    />

                                    <span class="text-sm text-gray-700">
                                        Entregue
                                    </span>

                                </label>

                            </div>

                            {{-- REMOVER --}}
                            <div class="w-full lg:w-auto">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm remove-presente w-full lg:w-auto"
                                    title="Remover presente"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- ADD --}}
                <button
                    type="button"
                    class="btn btn-primary btn-sm mt-3 w-full sm:w-auto"
                    id="add-presente"
                >
                    <i class="bi bi-plus"></i>
                    Adicionar Presente
                </button>

            </div>

            {{-- PASSEIOS --}}
            <div class="mb-6">

                <div class="flex items-center mb-2">

                    <i class="bi bi-people text-brand-500 mr-2"></i>

                    <span class="text-base font-semibold text-gray-700">
                        Passeios com este amigo
                    </span>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">

                    @foreach($passeios as $passeio)

                        <label class="
                            inline-flex items-start gap-2
                            border rounded-lg p-3 bg-gray-50
                        ">

                            <input
                                type="checkbox"
                                name="passeios[]"
                                value="{{ $passeio->id }}"
                                {{ $amigo->passeios->contains($passeio->id) ? 'checked' : '' }}
                                class="form-checkbox rounded text-brand-500 focus:ring-brand-500 mt-1"
                            />

                            <span class="text-sm">
                                {{ $passeio->nome }}
                            </span>

                        </label>

                    @endforeach

                </div>

                <small class="text-xs text-gray-500 block mt-2">
                    Marque os passeios deste amigo.
                </small>

            </div>

            {{-- SALVAR --}}
            <button
                type="submit"
                class="
                    bg-brand-500 hover:bg-brand-600
                    rounded-lg px-5 py-3 text-sm font-medium
                    text-white transition-colors
                    w-full sm:w-auto
                "
            >
                Salvar Amigo
            </button>

        </form>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    let presentesList = document.getElementById('presentes-list');

    let addPresenteBtn = document.getElementById('add-presente');

    let presenteIndex = presentesList.querySelectorAll('.presente-item').length;

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR PRESENTE
    |--------------------------------------------------------------------------
    */

    addPresenteBtn.addEventListener('click', function() {

        const div = document.createElement('div');

        div.className = `
            rounded-xl border border-gray-200 bg-gray-50
            p-4 flex flex-col lg:flex-row lg:items-end
            gap-4 presente-item relative shadow-sm
        `;

        div.innerHTML = `

            <div class="flex-1 w-full">

                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Descrição
                </label>

                <input
                    type="text"
                    name="presentes[\${presenteIndex}][descricao]"
                    class="form-control w-full"
                    placeholder="Descrição do presente"
                    required
                />

            </div>

            <div class="w-full lg:w-56">

                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Mala
                </label>

                <select
                    name="presentes[\${presenteIndex}][mala_id]"
                    class="form-control w-full"
                    required
                >

                    <option value="">
                        Selecione a mala
                    </option>

                    @foreach($malas as $mala)

                        <option value="{{ $mala->id }}">
                            {{ $mala->descricao }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="w-full lg:w-auto">

                <label class="inline-flex items-center space-x-2">

                    <input
                        type="checkbox"
                        name="presentes[\${presenteIndex}][entregue]"
                        value="1"
                        class="form-checkbox rounded text-green-600"
                    />

                    <span class="text-sm text-gray-700">
                        Entregue
                    </span>

                </label>

            </div>

            <div class="w-full lg:w-auto">

                <button
                    type="button"
                    class="btn btn-danger btn-sm remove-presente w-full lg:w-auto"
                    title="Remover presente"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>

        `;

        presentesList.appendChild(div);

        presenteIndex++;

    });

    /*
    |--------------------------------------------------------------------------
    | REMOVER PRESENTE
    |--------------------------------------------------------------------------
    */

    presentesList.addEventListener('click', function(e) {

        if (
            e.target.classList.contains('remove-presente') ||
            e.target.closest('.remove-presente')
        ) {

            e.target.closest('.presente-item').remove();

        }

    });

});

</script>

@endpush