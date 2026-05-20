@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Minhas Malas" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 w-full">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action=" {{route('mala.store') }}">
                @csrf
                <div class="relative z-20 bg-transparent mb-2">
                    <select name="viagem"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                        <option value="">Selecione</option>
                        @foreach ($viagens as $viagem)
                            <option value="{{ $viagem->id }}" {{ (isset($viagem) && $viagem->id == $viagem->id) ? 'selected' : '' }}>
                                {{ $viagem->nome }}
                            </option>
                        @endforeach
                    </select>
                       
                    <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <x-form.form-elements.input label="Nome da Mala" name="mala"  required />
                <x-form.form-elements.input label="Peso" name="peso"  />
                <x-form.form-elements.input label="tracking" name="tracking"  />
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">Salvar Mala</button>
            </form>
        </div>
    </div>
@endsection
