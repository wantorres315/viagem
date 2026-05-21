@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Relatório de Viagem</h2>
    <form method="GET" action="{{ route('relatorio.viagem') }}">
        <label for="viagem_id" class="block mb-2 font-medium">Selecione a viagem:</label>
        <select name="viagem_id" id="viagem_id" class="form-control w-full mb-4" required>
            @foreach($viagens as $viagem)
                <option value="{{ $viagem->id }}" {{ request('viagem_id') == $viagem->id ? 'selected' : '' }}>{{ $viagem->nome }} ({{ $viagem->data_ida }})</option>
            @endforeach
        </select>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded">Gerar Relatório</button>
    </form>
    @if(isset($viagemSelecionada))
        <div class="mt-8 flex flex-col gap-2">
            <div class="flex gap-2 mb-4">
                <a href="{{ route('relatorio.viagem.pdf', ['viagem_id' => $viagemSelecionada->id]) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Imprimir em PDF</a>
                <a href="{{ route('relatorio.viagem.itinerario.pdf', ['viagem_id' => $viagemSelecionada->id]) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Imprimir só Itinerário</a>
            </div>
            @include('pages.relatorio.viagem-conteudotela', ['viagem' => $viagemSelecionada])
        </div>
    @endif
</div>
@endsection
