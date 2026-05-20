@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Checklist da Viagem" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 w-full">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="GET" action="{{ route('checklist.index') }}" class="mb-4">
                <label for="viagem_id" class="block text-xs font-medium text-gray-600 mb-1">Viagem</label>
                <select name="viagem_id" id="viagem_id" class="form-control w-full" onchange="this.form.submit()">
                    @foreach($viagens as $viagem)
                        <option value="{{ $viagem->id }}" {{ $viagemId == $viagem->id ? 'selected' : '' }}>{{ $viagem->nome }}</option>
                    @endforeach
                </select>
            </form>
            <form method="POST" action="{{ route('checklist.store') }}" class="flex gap-2 mb-6">
                @csrf
                <input type="hidden" name="viagem_id" value="{{ $viagemId }}">
                <input type="text" name="tarefa" class="form-control w-full" placeholder="Nova tarefa" required />
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Adicionar</button>
            </form>
            <div class="p-4 border-t border-gray-100 sm:p-6">
                <table id="checklist-table" class="table-auto w-full border">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Concluído</th>
                            <th class="px-4 py-2">Tarefa</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checklists as $item)
                            <tr>
                                <td class="border px-4 py-2 text-center">
                                    <form method="POST" action="{{ route('checklist.update', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="tarefa" value="{{ $item->tarefa }}">
                                        <input type="hidden" name="concluido" value="0">
                                        <input type="checkbox" name="concluido" value="1" onchange="this.form.submit()" {{ $item->concluido ? 'checked' : '' }}>
                                    </form>
                                </td>
                                <td class="border px-4 py-2">{{ $item->tarefa }}</td>
                                <td class="border px-4 py-2">
                                    <form method="POST" action="{{ route('checklist.destroy', $item->id) }}" onsubmit="return confirm('Remover tarefa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Remover">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">Nenhuma tarefa cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
