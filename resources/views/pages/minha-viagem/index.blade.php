@extends('layouts.app')

@section('content')

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- HEADER --}}
    <div class="px-4 sm:px-6 py-5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Minhas Viagens
        </h3>

        <a
            href="{{ route('minha-viagem.create') }}"
            class="bg-brand-500 hover:bg-brand-600 rounded-lg px-4 py-3 text-sm font-medium text-white transition-colors text-center"
        >
            Nova Viagem
        </a>

    </div>

    {{-- BODY --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">

        {{-- MOBILE --}}
        <div class="block lg:hidden space-y-4">

            @forelse($viagens as $viagem)

                <div class="border rounded-xl p-4 shadow-sm bg-white">

                    {{-- TOPO --}}
                    <div class="flex justify-between items-start mb-3">

                        <div>

                            <div class="font-bold text-gray-800 text-lg">
                                {{ $viagem->nome }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">

                                📅
                                {{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}
                                →
                                {{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}

                            </div>

                        </div>

                    </div>

                    {{-- INFOS --}}
                    <div class="space-y-1 text-sm text-gray-700 mb-4">

                        <div>
                            👥 <strong>Pessoas:</strong>
                            {{ $viagem->pessoas->count() }}
                        </div>

                        <div>
                            💰 <strong>Budget:</strong>

                            @if($viagem->budget)

                                R$ {{ number_format($viagem->budget, 2, ',', '.') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                    {{-- AÇÕES --}}
                    <div class="flex gap-2">

                        {{-- EDITAR --}}
                        <a
                            href="{{ route('minha-viagem.edit', $viagem->id) }}"
                            class="flex-1 btn btn-sm btn-warning text-center"
                            title="Editar"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- EXCLUIR --}}
                        <form
                            action="{{ route('minha-viagem.destroy', $viagem->id) }}"
                            method="POST"
                            class="flex-1"
                            onsubmit="return confirm('Tem certeza que deseja deletar esta viagem?');"
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
                    Nenhuma viagem cadastrada.
                </div>

            @endforelse

        </div>

        {{-- DESKTOP --}}
        <div class="hidden lg:block overflow-x-auto">

            <table id="viagens-table" class="table-auto w-full border min-w-[900px]">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="px-4 py-3 text-left">
                            Nome
                        </th>

                        <th class="px-4 py-3 text-left">
                            Data de Ida
                        </th>

                        <th class="px-4 py-3 text-left">
                            Data de Volta
                        </th>

                        <th class="px-4 py-3 text-left">
                            Qtd. Pessoas
                        </th>

                        <th class="px-4 py-3 text-left">
                            Budget (R$)
                        </th>

                        <th class="px-4 py-3 text-center">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($viagens as $viagem)

                        <tr>

                            <td class="border px-4 py-3">
                                {{ $viagem->nome }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ $viagem->pessoas->count() }}
                            </td>

                            <td class="border px-4 py-3">

                                @if($viagem->budget)

                                    {{ number_format($viagem->budget, 2, ',', '.') }}

                                @else

                                    -

                                @endif

                            </td>

                            <td class="border px-4 py-3">

                                <div class="flex justify-center gap-2">

                                    {{-- EDITAR --}}
                                    <a
                                        href="{{ route('minha-viagem.edit', $viagem->id) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Editar"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- EXCLUIR --}}
                                    <form
                                        action="{{ route('minha-viagem.destroy', $viagem->id) }}"
                                        method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja deletar esta viagem?');"
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

                            <td colspan="6" class="text-center py-6 text-gray-500">
                                Nenhuma viagem cadastrada.
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

    if ($('#viagens-table tbody tr').length > 1) {

        $('#viagens-table').DataTable({

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