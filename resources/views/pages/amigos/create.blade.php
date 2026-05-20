@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Novo Amigo" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 w-full">
            <form method="POST" action="{{ route('amigo.store') }}">
                @csrf
                <x-form.form-elements.input label="Nome do Amigo" name="nome" required />
                <x-form.form-elements.input label="Cidade" name="cidade" />
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Salvar Amigo</button>
            </form>
        </div>
    </div>
@endsection
