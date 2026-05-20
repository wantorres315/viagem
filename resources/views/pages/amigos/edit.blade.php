@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Amigo" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 w-full">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('amigo.update', $amigo->id) }}">
                @csrf
                @method('PUT')
                <x-form.form-elements.input label="Nome do Amigo" name="nome" :value="$amigo->nome" required />
                <x-form.form-elements.input label="Cidade" name="cidade" :value="$amigo->cidade" />

                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-gift text-brand-500 mr-2"></i>
                        <span class="text-base font-semibold text-gray-700">Presentes</span>
                    </div>
                    <div id="presentes-list" class="space-y-3">
                        @foreach($amigo->presentes as $i => $presente)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-row items-end gap-4 presente-item relative shadow-sm">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Descrição</label>
                                    <input type="text" name="presentes[{{ $i }}][descricao]" class="form-control w-full" placeholder="Descrição do presente" value="{{ $presente->presente }}" required />
                                </div>
                                <div class="w-48">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Mala</label>
                                    <select name="presentes[{{ $i }}][mala_id]" class="form-control w-full" required>
                                        <option value="">Selecione a mala</option>
                                        @foreach($malas as $mala)
                                            <option value="{{ $mala->id }}" {{ isset($presente->mala_id) && $presente->mala_id == $mala->id ? 'selected' : '' }}>{{ $mala->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-presente ml-2" title="Remover presente">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" id="add-presente">
                        <i class="bi bi-plus"></i> Adicionar Presente
                    </button>
                </div>

                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-people text-brand-500 mr-2"></i>
                        <span class="text-base font-semibold text-gray-700">Passeios com este amigo</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($passeios as $passeio)
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="passeios[]" value="{{ $passeio->id }}" {{ $amigo->passeios->contains($passeio->id) ? 'checked' : '' }} class="form-checkbox rounded text-brand-500 focus:ring-brand-500" />
                                <span>{{ $passeio->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                    <small class="text-xs text-gray-500">Marque os passeios deste amigo.</small>
                </div>

                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Salvar Amigo</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Presentes dinâmicos
        let presentesList = document.getElementById('presentes-list');
        let addPresenteBtn = document.getElementById('add-presente');
        let presenteIndex = presentesList.querySelectorAll('.presente-item').length;
        addPresenteBtn.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-row items-end gap-4 presente-item relative shadow-sm';
            div.innerHTML = `
                <div class=\"flex-1\">
                    <label class=\"block text-xs font-medium text-gray-600 mb-1\">Descrição</label>
                    <input type=\"text\" name=\"presentes[${presenteIndex}][descricao]\" class=\"form-control w-full\" placeholder=\"Descrição do presente\" required />
                </div>
                <div class=\"w-48\">
                    <label class=\"block text-xs font-medium text-gray-600 mb-1\">Mala</label>
                    <select name=\"presentes[${presenteIndex}][mala_id]\" class=\"form-control w-full\" required>
                        <option value=\"\">Selecione a mala</option>
                        @foreach($malas as $mala)
                            <option value=\"{{ $mala->id }}\">{{ $mala->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <button type=\"button\" class=\"btn btn-danger btn-sm remove-presente ml-2\" title=\"Remover presente\"><i class=\"bi bi-x-lg\"></i></button>
            `;
            presentesList.appendChild(div);
            presenteIndex++;
        });
        presentesList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-presente') || e.target.closest('.remove-presente')) {
                e.target.closest('.presente-item').remove();
            }
        });
    });
</script>

            @endpush
      
