@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Meus Amigos" />

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mt-8">

    {{-- HEADER --}}
    <div class="px-4 sm:px-6 py-5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Meus Amigos
        </h3>

        <a
            href="{{ route('amigo.create') }}"
            class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-3 text-sm font-medium text-white transition-colors text-center"
        >
            Novo Amigo
        </a>

    </div>

    {{-- BODY --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">

        {{-- MOBILE --}}
        <div class="block lg:hidden space-y-4">

            @forelse($amigos as $amigo)

                <div class="border rounded-xl p-4 bg-white shadow-sm">

                    {{-- TOPO --}}
                    <div class="flex justify-between items-start gap-3 mb-3">

                        <div>

                            <div class="font-bold text-gray-800 text-lg">
                                {{ $amigo->nome }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                📍 {{ $amigo->cidade ?: 'Cidade não informada' }}
                            </div>

                        </div>

                    </div>

                    {{-- AÇÕES --}}
                    <div class="flex gap-2">

                        {{-- EDITAR --}}
                        <a
                            href="{{ route('amigo.edit', $amigo->id) }}"
                            class="flex-1 btn btn-sm btn-warning text-center"
                            title="Editar"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- EXCLUIR --}}
                        <form
                            action="{{ route('amigo.destroy', $amigo->id) }}"
                            method="POST"
                            class="flex-1"
                            onsubmit="return confirm('Tem certeza que deseja deletar este amigo?');"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-danger w-full"
                                title="Deletar"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="text-center py-6 text-gray-500">
                    Nenhum amigo cadastrado.
                </div>

            @endforelse

        </div>

        {{-- DESKTOP --}}
        <div class="hidden lg:block overflow-x-auto">

            <table
                id="amigos-table"
                class="table-auto w-full border min-w-[700px]"
            >

                <thead>

                    <tr class="bg-gray-100">

                        <th class="px-4 py-3 text-left">
                            Nome
                        </th>

                        <th class="px-4 py-3 text-left">
                            Cidade
                        </th>

                        <th class="px-4 py-3 text-center">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($amigos as $amigo)

                        <tr>

                            <td class="border px-4 py-3">
                                {{ $amigo->nome }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ $amigo->cidade ?: '-' }}
                            </td>

                            <td class="border px-4 py-3">

                                <div class="flex justify-center gap-2">

                                    {{-- EDITAR --}}
                                    <a
                                        href="{{ route('amigo.edit', $amigo->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Editar"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- EXCLUIR --}}
                                    <form
                                        action="{{ route('amigo.destroy', $amigo->id) }}"
                                        method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja deletar este amigo?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Deletar"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center py-6 text-gray-500">
                                Nenhum amigo cadastrado.
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

    if ($('#amigos-table tbody tr').length > 1) {

        $('#amigos-table').DataTable({

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