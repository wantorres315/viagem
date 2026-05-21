@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Checklist da Viagem" />

<div class="mt-8">

    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 w-full">

        {{-- ALERTA --}}
        @if(session('success'))

            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>

        @endif

        {{-- SELECIONAR VIAGEM --}}
        <form
            method="GET"
            action="{{ route('checklist.index') }}"
            class="mb-4"
        >

            <label
                for="viagem_id"
                class="block text-xs font-medium text-gray-600 mb-1"
            >
                Viagem
            </label>

            <select
                name="viagem_id"
                id="viagem_id"
                class="form-control w-full"
                onchange="this.form.submit()"
            >

                @foreach($viagens as $viagem)

                    <option
                        value="{{ $viagem->id }}"
                        {{ $viagemId == $viagem->id ? 'selected' : '' }}
                    >
                        {{ $viagem->nome }}
                    </option>

                @endforeach

            </select>

        </form>

        {{-- NOVA TAREFA --}}
        <form
            method="POST"
            action="{{ route('checklist.store') }}"
            class="flex flex-col sm:flex-row gap-2 mb-6"
        >

            @csrf

            <input
                type="hidden"
                name="viagem_id"
                value="{{ $viagemId }}"
            >

            <input
                type="text"
                name="tarefa"
                class="form-control w-full"
                placeholder="Nova tarefa"
                required
            />

            <button
                type="submit"
                class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-3 text-sm font-medium text-white transition-colors whitespace-nowrap"
            >
                Adicionar
            </button>

        </form>

        {{-- MOBILE --}}
        <div class="block lg:hidden space-y-3">

            @forelse($checklists as $item)

                <div class="border rounded-xl p-4 bg-white shadow-sm">

                    <div class="flex items-start justify-between gap-3">

                        {{-- CHECKBOX --}}
                        <form
                            method="POST"
                            action="{{ route('checklist.update', $item->id) }}"
                            class="flex items-start gap-3 flex-1"
                        >

                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="tarefa"
                                value="{{ $item->tarefa }}"
                            >

                            <input
                                type="hidden"
                                name="concluido"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="concluido"
                                value="1"
                                onchange="this.form.submit()"
                                class="mt-1"
                                {{ $item->concluido ? 'checked' : '' }}
                            >

                            <div class="
                                text-sm
                                {{ $item->concluido ? 'line-through text-gray-400' : 'text-gray-800' }}
                            ">

                                {{ $item->tarefa }}

                            </div>

                        </form>

                        {{-- REMOVER --}}
                        <form
                            method="POST"
                            action="{{ route('checklist.destroy', $item->id) }}"
                            onsubmit="return confirm('Remover tarefa?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger btn-xs"
                                title="Remover"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="text-center py-6 text-gray-500">
                    Nenhuma tarefa cadastrada.
                </div>

            @endforelse

        </div>

        {{-- DESKTOP --}}
        <div class="hidden lg:block overflow-x-auto">

            <table
                id="checklist-table"
                class="table-auto w-full border min-w-[700px]"
            >

                <thead>

                    <tr class="bg-gray-100">

                        <th class="px-4 py-3">
                            Concluído
                        </th>

                        <th class="px-4 py-3 text-left">
                            Tarefa
                        </th>

                        <th class="px-4 py-3 text-center">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($checklists as $item)

                        <tr>

                            {{-- CHECKBOX --}}
                            <td class="border px-4 py-3 text-center">

                                <form
                                    method="POST"
                                    action="{{ route('checklist.update', $item->id) }}"
                                >

                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="tarefa"
                                        value="{{ $item->tarefa }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="concluido"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="concluido"
                                        value="1"
                                        onchange="this.form.submit()"
                                        {{ $item->concluido ? 'checked' : '' }}
                                    >

                                </form>

                            </td>

                            {{-- TAREFA --}}
                            <td class="
                                border px-4 py-3
                                {{ $item->concluido ? 'line-through text-gray-400' : '' }}
                            ">

                                {{ $item->tarefa }}

                            </td>

                            {{-- REMOVER --}}
                            <td class="border px-4 py-3 text-center">

                                <form
                                    method="POST"
                                    action="{{ route('checklist.destroy', $item->id) }}"
                                    onsubmit="return confirm('Remover tarefa?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-xs"
                                        title="Remover"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center py-6 text-gray-500">
                                Nenhuma tarefa cadastrada.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@push('scripts')

<script>

$(document).ready(function() {

    if ($('#checklist-table tbody tr').length > 1) {

        $('#checklist-table').DataTable({

            responsive: true,

            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            }

        });

    }

});

</script>

@endpush

@endsection