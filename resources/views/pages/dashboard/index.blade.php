@extends('layouts.app')

@section('content')
  <script>
    window.chartLabels = @json($labels ?? []);
    window.chartValores = @json($valores ?? []);
    window.budget = {{ $budget ?? 0 }};
    window.gastoTotal = {{ $gastoTotal ?? 0 }};
    window.percentualGasto = {{ ($budget ?? 0) > 0 ? round(($gastoTotal / $budget) * 100, 2) : 0 }};
  </script>
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6 xl:col-span-7">
      @include('components.ecommerce.ecommerce-metrics')
      @include('components.ecommerce.monthly-sale')
    </div>
    <div class="col-span-12 xl:col-span-5">
      @include('components.ecommerce.monthly-target')
    </div>

    

    <div class="col-span-12 xl:col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Checklist da Última Viagem</h3>
          @if(!empty($checklistUltimaViagem) && count($checklistUltimaViagem) > 0)
            <ul class="space-y-2">
              @foreach($checklistUltimaViagem as $item)
                <li class="flex items-center gap-2">
                  <input type="checkbox" disabled {{ $item->concluido ? 'checked' : '' }}>
                  <span class="{{ $item->concluido ? 'line-through text-gray-400' : '' }}">{{ $item->tarefa }}</span>
                </li>
              @endforeach
            </ul>
          @else
            <p class="text-gray-500">Nenhum item de checklist cadastrado para a última viagem.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
