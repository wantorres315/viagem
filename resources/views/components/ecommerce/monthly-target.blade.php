<div class="rounded-2xl border border-gray-200 bg-gray-100">
    <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 sm:px-6 sm:pt-6">
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Budget da Viagem
                </h3>
                
            </div>
            <!-- Dropdown Menu -->
            <x-common.dropdown-menu />
            <!-- End Dropdown Menu -->

        </div>
        <div class="relative max-h-[195px]">
            {{-- Chart --}}
            <div id="chartTwo" class="h-full"></div>
            <span class="absolute left-1/2 top-[85%] -translate-x-1/2 -translate-y-[85%] rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600">+10%</span>
        </div>
        <p class="mx-auto mt-1.5 w-full max-w-[380px] text-center text-sm text-gray-500 sm:text-base">
            Voce ja gastou {{ number_format($gastoTotal ?? 0, 2, ',', '.') }} na viagem, ainda faltam {{ number_format($restante ?? 0, 2, ',', '.') }} para atingir seu orçamento de {{ number_format($budget ?? 0, 2, ',', '.') }}.
        </p>
    </div>

    <div class="flex items-center justify-center gap-5 px-6 py-3.5 sm:gap-8 sm:py-5">
        <div>
            <p class="mb-1 text-center text-theme-xs text-gray-500 sm:text-sm">
                Orçamento
            </p>
            <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 sm:text-lg">
                R$ {{ number_format($budget ?? 0, 2, ',', '.') }}
            </p>
        </div>

        <div class="h-7 w-px bg-gray-200"></div>

        <div>
            <p class="mb-1 text-center text-theme-xs text-gray-500 sm:text-sm">
                Gastos
            </p>
            <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 sm:text-lg">
                R$ {{ number_format($gastoTotal ?? 0, 2, ',', '.') }}
            </p>
                <div class="h-7 w-px bg-gray-200"></div>

                <div>
                    <p class="mb-1 text-center text-theme-xs text-gray-500 sm:text-sm">
                        Restante
                    </p>
                    <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 sm:text-lg">
                        R$ {{ number_format($restante ?? 0, 2, ',', '.') }}
                    </p>
                </div>
        </div>

        <div class="h-7 w-px bg-gray-200"></div>

        
    </div>
</div>

