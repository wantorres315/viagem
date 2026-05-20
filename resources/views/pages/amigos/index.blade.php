@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Meus Amigos" />
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mt-8">
        <div class="px-6 py-5 flex justify-between items-center">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Meus Amigos
            </h3>
            <a href="{{ route('amigo.create') }}" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Novo Amigo</a>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
            <table id="amigos-table" class="table-auto w-full border">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">Cidade</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($amigos as $amigo)
                        <tr>
                            <td class="border px-4 py-2">{{ $amigo->nome }}</td>
                            <td class="border px-4 py-2">{{ $amigo->cidade }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('amigo.edit', $amigo->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('amigo.destroy', $amigo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja deletar este amigo?');">
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
                            <td colspan="3" class="text-center py-4">Nenhum amigo cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#amigos-table tbody tr').length > 1 || $('#amigos-table tbody tr td').length > 1 || !$('#amigos-table tbody tr td').hasClass('text-center')) {
            $('#amigos-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
                }
            });
        }
    });
</script>
@endpush
@endsection
