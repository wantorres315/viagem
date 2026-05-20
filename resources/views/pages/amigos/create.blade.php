@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Novo Amigo" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 w-full">
            <form method="POST" action="{{ route('amigo.store') }}">
                @csrf
                <x-form.form-elements.input label="Nome do Amigo" name="nome" required />
                <x-form.form-elements.input label="Cidade" name="cidade" />
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Viagem</label>
                    <select name="viagem_id" class="form-control w-full" required>
                        <option value="">Selecione a viagem</option>
                        @foreach($viagens as $viagem)
                            <option value="{{ $viagem->id }}">{{ $viagem->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Salvar Amigo</button>
            </form>
        </div>
    </div>
@endsection
