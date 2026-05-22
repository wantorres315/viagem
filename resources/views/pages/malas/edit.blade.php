@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Minhas Malas" />

<div class="mt-8">

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4 sm:p-6 w-full">

        {{-- ALERTA --}}
        @if(session('success'))

            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>

        @endif

        <form method="POST" action="{{ route('mala.update', $mala->id) }}">

            @csrf
            @method('PUT')

            {{-- VIAGEM --}}
            <div class="relative z-20 bg-transparent mb-4">

                <select
                    name="viagem"
                    class="
                        dark:bg-dark-900 shadow-theme-xs
                        focus:border-brand-300 focus:ring-brand-500/10
                        h-11 w-full appearance-none rounded-lg
                        border border-gray-300 bg-transparent
                        px-4 py-2.5 pr-11 text-sm text-gray-800
                        placeholder:text-gray-400
                        focus:ring-3 focus:outline-hidden
                    "
                >

                    <option value="">
                        Selecione
                    </option>

                    @foreach ($viagens as $viagem)

                        <option
                            value="{{ $viagem->id }}"
                            {{ ($mala->viagem_id == $viagem->id) ? 'selected' : '' }}
                        >
                            {{ $viagem->nome }}
                        </option>

                    @endforeach

                </select>

                <span class="
                    pointer-events-none absolute top-1/2 right-4
                    z-30 -translate-y-1/2 text-gray-700
                ">

                    <svg
                        class="stroke-current"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >

                        <path
                            d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                            stroke=""
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                    </svg>

                </span>

            </div>

            {{-- CAMPOS --}}
            <x-form.form-elements.input
                label="Nome da Mala"
                name="mala"
                :value="$mala->descricao"
                required
            />

            <x-form.form-elements.input
                label="Peso"
                name="peso"
                :value="$mala->peso"
            />

            <x-form.form-elements.input
                label="Tracking"
                name="tracking"
                :value="$mala->track"
            />

            {{-- ITENS --}}
            <div class="mb-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">

                    <div class="flex items-center">

                        <i class="bi bi-list-task text-brand-500 mr-2"></i>

                        <span class="text-base font-semibold text-gray-700">
                            Itens da Mala
                        </span>

                    </div>

                    @php
                        $totalItens = $mala->itens->count();

                        $itensNaMala = $mala->itens
                            ->where('na_mala', true)
                            ->count();

                        $percentual = $totalItens > 0
                            ? round(($itensNaMala / $totalItens) * 100)
                            : 0;
                    @endphp

                    <div class="text-sm text-gray-600">

                        {{ $itensNaMala }}/{{ $totalItens }}
                        itens na mala

                    </div>

                </div>

                {{-- BARRA --}}
                <div class="w-full bg-gray-200 rounded-full h-3 mb-5 overflow-hidden">

                    <div
                        class="bg-green-500 h-3 rounded-full transition-all duration-300"
                        style="width: {{ $percentual }}%"
                    ></div>

                </div>

                <div id="itens-mala-list" class="space-y-3">

                    @php
                        $pessoas = $mala->viagem
                            ? $mala->viagem->pessoas
                            : collect();
                    @endphp

                    @foreach($mala->itens as $i => $item)

                        <div class="
                            rounded-xl border
                            {{ $item->na_mala
                                ? 'border-green-200 bg-green-50'
                                : 'border-gray-200 bg-gray-50'
                            }}
                            p-4
                            flex flex-col xl:flex-row xl:items-end
                            gap-4 item-mala-item relative shadow-sm
                        ">

                            {{-- ID --}}
                            <input
                                type="hidden"
                                name="itens[{{ $i }}][id]"
                                value="{{ $item->id }}"
                            >

                            {{-- ITEM --}}
                            <div class="flex-1 w-full">

                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Item
                                </label>

                                <input
                                    type="text"
                                    name="itens[{{ $i }}][item]"
                                    class="form-control w-full"
                                    placeholder="Nome do item"
                                    value="{{ $item->item }}"
                                    required
                                />

                            </div>

                            {{-- PESSOA --}}
                            <div class="w-full xl:w-64">

                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Pessoa
                                </label>

                                <select
                                    name="itens[{{ $i }}][pessoa_id]"
                                    class="form-control w-full"
                                    required
                                >

                                    <option value="">
                                        Selecione
                                    </option>

                                    @foreach($pessoas as $pessoa)

                                        <option
                                            value="{{ $pessoa->id }}"
                                            {{ $item->pessoa_id == $pessoa->id ? 'selected' : '' }}
                                        >
                                            {{ $pessoa->nome }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- NA MALA --}}
                            <div class="w-full xl:w-auto">

                                <label class="inline-flex items-center space-x-2">

                                    {{-- HIDDEN --}}
                                    <input
                                        type="hidden"
                                        name="itens[{{ $i }}][na_mala]"
                                        value="0"
                                    >

                                    {{-- CHECKBOX --}}
                                    <input
                                        type="checkbox"
                                        name="itens[{{ $i }}][na_mala]"
                                        value="1"
                                        class="form-checkbox rounded text-green-600"
                                        {{ $item->na_mala ? 'checked' : '' }}
                                    />

                                    <span class="text-sm text-gray-700">
                                        Já coloquei
                                    </span>

                                </label>

                            </div>

                            {{-- REMOVER --}}
                            <div class="w-full xl:w-auto">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm remove-item-mala w-full xl:w-auto"
                                    title="Remover item"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- ADD ITEM --}}
                <button
                    type="button"
                    class="btn btn-primary btn-sm mt-3 w-full sm:w-auto"
                    id="add-item-mala"
                >
                    <i class="bi bi-plus"></i>
                    Adicionar Item
                </button>

            </div>

            {{-- SALVAR --}}
            <button
                type="submit"
                class="
                    bg-brand-500 hover:bg-brand-600
                    rounded-lg px-5 py-3 text-sm font-medium
                    text-white transition-colors
                    mt-6 w-full sm:w-auto
                "
            >
                Salvar Mala
            </button>

        </form>

        @push('scripts')

        <script>

        document.addEventListener('DOMContentLoaded', function() {

            let itensList = document.getElementById('itens-mala-list');

            let addItemBtn = document.getElementById('add-item-mala');

            let itemIndex = itensList.querySelectorAll('.item-mala-item').length;

            let pessoas = @json($pessoas);

            /*
            |--------------------------------------------------------------------------
            | ADD ITEM
            |--------------------------------------------------------------------------
            */

            addItemBtn.addEventListener('click', function() {

                const div = document.createElement('div');

                div.className = `
                    rounded-xl border border-gray-200
                    bg-gray-50 p-4
                    flex flex-col xl:flex-row xl:items-end
                    gap-4 item-mala-item relative shadow-sm
                `;

                let options = pessoas.map(function(pessoa) {

                    return `<option value="${pessoa.id}">${pessoa.nome}</option>`;

                }).join('');

                div.innerHTML = `

                    <div class="flex-1 w-full">

                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Item
                        </label>

                        <input
                            type="text"
                            name="itens[${itemIndex}][item]"
                            class="form-control w-full"
                            placeholder="Nome do item"
                            required
                        />

                    </div>

                    <div class="w-full xl:w-64">

                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Pessoa
                        </label>

                        <select
                            name="itens[${itemIndex}][pessoa_id]"
                            class="form-control w-full"
                            required
                        >

                            <option value="">
                                Selecione
                            </option>

                            ${options}

                        </select>

                    </div>

                    <div class="w-full xl:w-auto">

                        <label class="inline-flex items-center space-x-2">

                            <input
                                type="hidden"
                                name="itens[${itemIndex}][na_mala]"
                                value="0"
                            />

                            <input
                                type="checkbox"
                                name="itens[${itemIndex}][na_mala]"
                                value="1"
                                class="form-checkbox rounded text-green-600"
                            />

                            <span class="text-sm text-gray-700">
                                Já coloquei
                            </span>

                        </label>

                    </div>

                    <div class="w-full xl:w-auto">

                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-item-mala w-full xl:w-auto"
                            title="Remover item"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>

                    </div>

                `;

                itensList.appendChild(div);

                itemIndex++;

            });

            /*
            |--------------------------------------------------------------------------
            | REMOVER ITEM
            |--------------------------------------------------------------------------
            */

            itensList.addEventListener('click', function(e) {

                if (
                    e.target.classList.contains('remove-item-mala') ||
                    e.target.closest('.remove-item-mala')
                ) {

                    e.target.closest('.item-mala-item').remove();

                }

            });

        });

        </script>

        @endpush

    </div>

</div>

@endsection