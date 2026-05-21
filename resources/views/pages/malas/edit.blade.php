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

                <div class="flex items-center mb-3">

                    <i class="bi bi-list-task text-brand-500 mr-2"></i>

                    <span class="text-base font-semibold text-gray-700">
                        Itens da Mala
                    </span>

                </div>

                <div id="itens-mala-list" class="space-y-3">

                    @php
                        $pessoas = $mala->viagem
                            ? $mala->viagem->pessoas
                            : collect();
                    @endphp

                    @foreach($mala->itens as $i => $item)

                        <div class="
                            rounded-xl border border-gray-200
                            bg-gray-50 p-4
                            flex flex-col lg:flex-row lg:items-end
                            gap-4 item-mala-item relative shadow-sm
                        ">

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
                            <div class="w-full lg:w-64">

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

                            {{-- REMOVER --}}
                            <div class="w-full lg:w-auto">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm remove-item-mala w-full lg:w-auto"
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

            {{-- PRESENTES --}}
            @if($mala->presentes && $mala->presentes->count())

                <div class="mt-8">

                    <div class="flex items-center mb-4">

                        <i class="bi bi-gift text-pink-500 mr-2"></i>

                        <span class="text-base font-semibold text-gray-700">
                            Presentes nesta Mala
                        </span>

                    </div>

                    {{-- MOBILE --}}
                    <div class="block lg:hidden space-y-3">

                        @foreach(
                            $mala->presentes->groupBy('amigo.nome')
                            as $nomeAmigo => $presentes
                        )

                            <div class="border rounded-xl p-4 bg-gray-50">

                                <div class="font-semibold text-gray-800 mb-3">
                                    {{ $nomeAmigo }}
                                </div>

                                <div class="flex flex-wrap gap-2">

                                    @foreach($presentes as $presente)

                                        <span class="
                                            px-2 py-1 rounded-lg text-xs font-medium
                                            {{ $presente->entregue
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-yellow-100 text-yellow-700'
                                            }}
                                        ">

                                            {{ $presente->presente }}

                                        </span>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>

                    {{-- DESKTOP --}}
                    <div class="hidden lg:block rounded-xl border border-gray-200 overflow-x-auto">

                        <table class="w-full text-sm min-w-[600px]">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">
                                        Pessoa
                                    </th>

                                    <th class="text-left px-4 py-3 font-semibold text-gray-700">
                                        Presentes
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach(
                                    $mala->presentes->groupBy('amigo.nome')
                                    as $nomeAmigo => $presentes
                                )

                                    <tr class="border-t">

                                        <td class="
                                            px-4 py-3 font-medium text-gray-800
                                            align-top whitespace-nowrap
                                        ">
                                            {{ $nomeAmigo }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-700">

                                            <div class="flex flex-wrap gap-2">

                                                @foreach($presentes as $presente)

                                                    <span class="
                                                        px-2 py-1 rounded-lg text-xs font-medium
                                                        {{ $presente->entregue
                                                            ? 'bg-green-100 text-green-700'
                                                            : 'bg-yellow-100 text-yellow-700'
                                                        }}
                                                    ">

                                                        {{ $presente->presente }}

                                                    </span>

                                                @endforeach

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

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
                    flex flex-col lg:flex-row lg:items-end
                    gap-4 item-mala-item relative shadow-sm
                `;

                let selectPessoa = `
                    <div class="w-full lg:w-64">

                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Pessoa
                        </label>

                        <select
                            name="itens[\${itemIndex}][pessoa_id]"
                            class="form-control w-full"
                            required
                        >

                            <option value="">
                                Selecione
                            </option>

                            \${pessoas.map(
                                p => `<option value="\${p.id}">\${p.nome}</option>`
                            ).join('')}

                        </select>

                    </div>
                `;

                div.innerHTML = `

                    <div class="flex-1 w-full">

                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Item
                        </label>

                        <input
                            type="text"
                            name="itens[\${itemIndex}][item]"
                            class="form-control w-full"
                            placeholder="Nome do item"
                            required
                        />

                    </div>

                    \${selectPessoa}

                    <div class="w-full lg:w-auto">

                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-item-mala w-full lg:w-auto"
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