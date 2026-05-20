@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Minha Viagem" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 w-full">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ isset($viagem) ? route('minha-viagem.update', $viagem->id) : route('minha-viagem.store') }}">
                @csrf
                @if(isset($viagem))
                    @method('PUT')
                @endif
                <x-form.form-elements.input label="Nome da Viagem" name="nome" :value="old('nome', $viagem->nome ?? null)" required />
                <x-form.form-elements.date-input label="Data de Ida" name="data_ida" :value="old('data_ida', $viagem->data_ida ?? null)" required />
                <x-form.form-elements.date-input label="Data de Volta" name="data_volta" :value="old('data_volta', $viagem->data_volta ?? null)" required />
                <x-form.form-elements.input label="Budget" name="budget" type="number" step="0.01" :value="old('budget', $viagem->budget ?? null)" />
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">{{ isset($viagem) ? 'Atualizar Viagem' : 'Salvar Viagem' }}</button>
            </form>
        </div>
    </div>
@endsection
