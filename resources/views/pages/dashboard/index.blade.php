@extends('layouts.app')

@section('content')

<script>
    window.chartLabels = @json($labels ?? []);
    window.chartValores = @json($valores ?? []);
    window.budget = {{ $budget ?? 0 }};
    window.gastoTotal = {{ $gastoTotal ?? 0 }};
    window.percentualGasto =
        {{ ($budget ?? 0) > 0 ? round(($gastoTotal / $budget) * 100, 2) : 0 }};
</script>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-4 md:gap-6">

    {{-- ESQUERDA --}}
    <div class="xl:col-span-7 space-y-6">

        {{-- MÉTRICAS --}}
        <div class="w-full overflow-hidden">
            @include('components.ecommerce.ecommerce-metrics')
        </div>

        {{-- GRÁFICO --}}
        <div class="w-full overflow-hidden">
            @include('components.ecommerce.monthly-sale')
        </div>

    </div>

    {{-- DIREITA --}}
    <div class="xl:col-span-5">

        <div class="w-full overflow-hidden">
            @include('components.ecommerce.monthly-target')
        </div>

    </div>

    {{-- SEM VIAGEM --}}
    @if(empty($labels) || count($labels) === 0)

        <div class="col-span-1 xl:col-span-12">

            <div class="
                rounded-2xl border border-yellow-200
                bg-yellow-50 p-5 sm:p-6 text-center
            ">

                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                    Nenhuma viagem futura cadastrada
                </h3>

                <p class="text-yellow-700 text-sm sm:text-base">
                    Cadastre uma nova viagem para visualizar os dados do dashboard.
                </p>

            </div>

        </div>

    @endif

    {{-- CHECKLIST --}}
    <div class="col-span-1 xl:col-span-12">

        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6">

            <div class="
                flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-2 mb-4
            ">

                <h3 class="text-lg font-semibold text-gray-800">
                    Checklist da Última Viagem
                </h3>

                @if(!empty($checklistUltimaViagem) && count($checklistUltimaViagem) > 0)

                    <span class="text-sm text-gray-500">
                        {{ count($checklistUltimaViagem) }} itens
                    </span>

                @endif

            </div>

            {{-- EXISTE CHECKLIST --}}
            @if(!empty($checklistUltimaViagem) && count($checklistUltimaViagem) > 0)

                {{-- MOBILE --}}
                <div class="block md:hidden space-y-3">

                    @foreach($checklistUltimaViagem as $item)

                        <div class="
                            border rounded-xl p-4
                            flex items-start gap-3
                            {{ $item->concluido
                                ? 'bg-green-50 border-green-200'
                                : 'bg-gray-50'
                            }}
                        ">

                            <input
                                type="checkbox"
                                disabled
                                class="mt-1"
                                {{ $item->concluido ? 'checked' : '' }}
                            >

                            <div class="
                                text-sm
                                {{ $item->concluido
                                    ? 'line-through text-gray-400'
                                    : 'text-gray-800'
                                }}
                            ">

                                {{ $item->tarefa }}

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- DESKTOP --}}
                <div class="hidden md:block">

                    <ul class="space-y-2">

                        @foreach($checklistUltimaViagem as $item)

                            <li class="
                                flex items-center gap-3
                                rounded-lg px-3 py-2
                                {{ $item->concluido
                                    ? 'bg-green-50'
                                    : 'bg-gray-50'
                                }}
                            ">

                                <input
                                    type="checkbox"
                                    disabled
                                    {{ $item->concluido ? 'checked' : '' }}
                                >

                                <span class="
                                    text-sm
                                    {{ $item->concluido
                                        ? 'line-through text-gray-400'
                                        : 'text-gray-700'
                                    }}
                                ">

                                    {{ $item->tarefa }}

                                </span>

                            </li>

                        @endforeach

                    </ul>

                </div>

            @else

                {{-- VAZIO --}}
                <div class="text-center py-8">

                    <p class="text-gray-500 text-sm sm:text-base">
                        Nenhum item de checklist cadastrado para a última viagem.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection