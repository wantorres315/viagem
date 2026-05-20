@extends('layouts.app')

@section('content')
 <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Card Header -->
    <div class="px-6 py-5 flex justify-between items-center">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Minhas Viagens
        </h3>
        <a href="{{ route('minha-viagem.create') }}" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Nova Viagem</a>
    </div>

    <!-- Card Body -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
        <table id="viagens-table" class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2">Descricao</th>
                    <th class="px-4 py-2">Viagem</th>
                    <th class="px-4 py-2">Quantidade de Itens</th>
                    <th class="px-4 py-2">Peso</th>
                    <th class="px-4 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($malas as $mala)
                    <tr>
                        <td class="border px-4 py-2">{{ $viagem->nome }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($viagem->data_ida)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($viagem->data_volta)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ $viagem->pessoas->count() }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('minha-viagem.edit', $viagem->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('minha-viagem.destroy', $viagem->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja deletar esta viagem?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Deletar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Nenhuma viagem cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#viagens-table tbody tr').length > 1 || $('#viagens-table tbody tr td').length > 1 || !$('#viagens-table tbody tr td').hasClass('text-center')) {
            $('#viagens-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
                }
            });
        }
    });
</script>
@endpush
@endsection
