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
                <div class="mb-8">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-list-task text-brand-500 mr-2"></i>
                        <span class="text-base font-semibold text-gray-700">Itinerários</span>
                    </div>
                    <div id="itinerarios-list" class="space-y-3">
                        @php
                            $itinerariosOld = old('itinerarios', isset($viagem) && isset($viagem->itinerarios) ? $viagem->itinerarios->toArray() : []);
                        @endphp
                        @foreach($itinerariosOld as $i => $itinerario)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col gap-4 itinerario-item relative shadow-sm">
                                <div class="flex flex-col md:flex-row md:items-end gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nome</label>
                                        <input type="text" name="itinerarios[{{ $i }}][nome]" class="form-control w-full" placeholder="Nome do itinerário" value="{{ $itinerario['nome'] ?? '' }}" required />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Descrição</label>
                                        <input type="text" name="itinerarios[{{ $i }}][descricao]" class="form-control w-full" placeholder="Descrição" value="{{ $itinerario['descricao'] ?? '' }}" />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Data</label>
                                        <input type="date" name="itinerarios[{{ $i }}][data]" class="form-control w-full" placeholder="Data" value="{{ $itinerario['data'] ?? '' }}" />
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-itinerario md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto" title="Remover itinerário">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <!-- Passeios dentro do itinerário -->
                                <div class="mt-4">
                                    <div class="flex items-center mb-2">
                                        <i class="bi bi-map text-brand-500 mr-2"></i>
                                        <span class="text-sm font-semibold text-gray-700">Passeios</span>
                                    </div>
                                    <div class="passeios-list space-y-3">
                                        @php
                                            $passeios = $itinerario['passeios'] ?? [];
                                        @endphp
                                        @foreach($passeios as $j => $passeio)
                                            <div class="rounded-lg border border-gray-200 bg-white p-3 flex flex-col md:flex-row md:items-end gap-2 passeio-item relative">
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nome do Passeio</label>
                                                    <input type="text" name="itinerarios[{{ $i }}][passeios][{{ $j }}][nome]" class="form-control w-full" placeholder="Nome do passeio" value="{{ $passeio['nome'] ?? '' }}" required />
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Observação</label>
                                                    <input type="text" name="itinerarios[{{ $i }}][passeios][{{ $j }}][observacao]" class="form-control w-full" placeholder="Observação" value="{{ $passeio['observacao'] ?? '' }}" />
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Valor Adulto (R$)</label>
                                                    <input type="number" step="0.01" min="0" name="itinerarios[{{ $i }}][passeios][{{ $j }}][valor_adulto]" class="form-control w-full" placeholder="Valor adulto" value="{{ $passeio['valor_adulto'] ?? '' }}" required />
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Valor Criança (R$)</label>
                                                    <input type="number" step="0.01" min="0" name="itinerarios[{{ $i }}][passeios][{{ $j }}][valor_crianca]" class="form-control w-full" placeholder="Valor criança" value="{{ $passeio['valor_crianca'] ?? '' }}" required />
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm remove-passeio md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto" title="Remover passeio">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-passeio" data-itinerario="{{ $i }}">
                                        <i class="bi bi-plus"></i> Adicionar Passeio
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" id="add-itinerario">
                        <i class="bi bi-plus"></i> Adicionar Itinerário
                    </button>
                </div>
                    <div class="flex items-center mb-2">
                        <i class="bi bi-people-fill text-brand-500 mr-2"></i>
                        <span class="text-base font-semibold text-gray-700">Pessoas</span>
                    </div>
                    <div id="pessoas-list" class="space-y-3">
                        @php
                            $pessoasOld = old('pessoas', isset($viagem) ? $viagem->pessoas->toArray() : []);
                        @endphp
                        @foreach($pessoasOld as $i => $pessoa)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col md:flex-row md:items-end gap-4 pessoa-item relative shadow-sm">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nome</label>
                                    <input type="text" name="pessoas[{{ $i }}][nome]" class="form-control w-full" placeholder="Nome" value="{{ $pessoa['nome'] ?? '' }}" required />
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Idade</label>
                                    <input type="number" name="pessoas[{{ $i }}][idade]" class="form-control w-full" placeholder="Idade" value="{{ $pessoa['idade'] ?? '' }}" min="0" required />
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-pessoa md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto" title="Remover pessoa">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" id="add-pessoa">
                        <i class="bi bi-person-plus"></i> Adicionar Pessoa
                    </button>
                <!-- Destinos agora dentro do card principal -->
                <div class="mb-8">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-geo-alt-fill text-brand-500 mr-2"></i>
                        <span class="text-base font-semibold text-gray-700">Destinos</span>
                    </div>
                    <div id="destinos-list" class="space-y-3">
                        @php
                            $destinosOld = old('destinos', isset($viagem) ? $viagem->destinos->toArray() : []);
                        @endphp
                        @foreach($destinosOld as $i => $destino)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col md:flex-row md:items-end gap-4 destino-item relative shadow-sm">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nome</label>
                                    <input type="text" name="destinos[{{ $i }}][nome]" class="form-control w-full" placeholder="Nome do destino" value="{{ $destino['nome'] ?? '' }}" required />
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Data</label>
                                    <input type="date" name="destinos[{{ $i }}][data]" class="form-control w-full" placeholder="Data" value="{{ $destino['data'] ?? '' }}" required />
                                </div>
                                <div class="flex-1 flex flex-col gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm add-voo mb-2" data-index="{{ $i }}">
                                        <i class="bi bi-airplane"></i> {{ !empty($destino['voo']) ? 'Remover Voo' : 'Adicionar Voo' }}
                                    </button>
                                    <div class="voo-fields" style="display:{{ !empty($destino['voo']) ? 'block' : 'none' }};">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Número do Voo</label>
                                        <input type="text" name="destinos[{{ $i }}][voo][numero_voo]" class="form-control w-full mb-2" placeholder="Número do voo" value="{{ $destino['voo']['numero_voo'] ?? '' }}" />
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Ida/Volta</label>
                                        <div class="relative z-20 bg-transparent mb-2">
                                            <select name="destinos[{{ $i }}][voo][ida_volta]"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                                <option value="">Selecione</option>
                                                <option value="1" {{ (isset($destino['voo']['ida_volta']) && $destino['voo']['ida_volta'] == 1) ? 'selected' : '' }}>Ida</option>
                                                <option value="2" {{ (isset($destino['voo']['ida_volta']) && $destino['voo']['ida_volta'] == 2) ? 'selected' : '' }}>Volta</option>
                                            </select>
                                            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700">
                                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Assento</label>
                                        <input type="text" name="destinos[{{ $i }}][voo][assento]" class="form-control w-full" placeholder="Assento" value="{{ $destino['voo']['assento'] ?? '' }}" />
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-destino md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto" title="Remover destino">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" id="add-destino">
                        <i class="bi bi-geo-alt"></i> Adicionar Destino
                    </button>
                </div>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 rounded-lg p-3 text-sm font-medium text-white transition-colors">{{ isset($viagem) ? 'Atualizar Viagem' : 'Salvar Viagem' }}</button>
            @push('scripts')
               <script>
                // Itinerarios
                let itinerariosList = document.getElementById('itinerarios-list');
                let addItinerarioBtn = document.getElementById('add-itinerario');
                let itinerarioIndex = itinerariosList.querySelectorAll('.itinerario-item').length;
                addItinerarioBtn.addEventListener('click', function() {
                    const div = document.createElement('div');
                    div.className = 'rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col gap-4 itinerario-item relative shadow-sm';
                    div.innerHTML = `
                        <div class=\"flex flex-col md:flex-row md:items-end gap-4\">
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Nome</label>
                                <input type=\"text\" name=\"itinerarios[${itinerarioIndex}][nome]\" class=\"form-control w-full\" placeholder=\"Nome do itinerário\" required />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Descrição</label>
                                <input type=\"text\" name=\"itinerarios[${itinerarioIndex}][descricao]\" class=\"form-control w-full\" placeholder=\"Descrição\" />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Data</label>
                                <input type=\"date\" name=\"itinerarios[${itinerarioIndex}][data]\" class=\"form-control w-full\" placeholder=\"Data\" />
                            </div>
                            <button type=\"button\" class=\"btn btn-danger btn-sm remove-itinerario md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto\" title=\"Remover itinerário\"><i class=\"bi bi-x-lg\"></i></button>
                        </div>
                        <!-- Passeios dentro do itinerário -->
                        <div class=\"mt-4\">
                            <div class=\"flex items-center mb-2\">
                                <i class=\"bi bi-map text-brand-500 mr-2\"></i>
                                <span class=\"text-sm font-semibold text-gray-700\">Passeios</span>
                            </div>
                            <div class=\"passeios-list space-y-3\"></div>
                            <button type=\"button\" class=\"btn btn-outline-primary btn-sm mt-2 add-passeio\" data-itinerario=\"${itinerarioIndex}\"><i class=\"bi bi-plus\"></i> Adicionar Passeio</button>
                        </div>
                    `;
                    itinerariosList.appendChild(div);
                    itinerarioIndex++;
                });
                itinerariosList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-itinerario') || e.target.closest('.remove-itinerario')) {
                        e.target.closest('.itinerario-item').remove();
                    }
                });

                // Passeios dinâmicos
                itinerariosList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('add-passeio')) {
                        const itinerarioDiv = e.target.closest('.itinerario-item');
                        const passeiosList = itinerarioDiv.querySelector('.passeios-list');
                        let passeioIndex = passeiosList.querySelectorAll('.passeio-item').length;
                        const itinerarioIdx = e.target.getAttribute('data-itinerario');
                        const passeioDiv = document.createElement('div');
                        passeioDiv.className = 'rounded-lg border border-gray-200 bg-white p-3 flex flex-col md:flex-row md:items-end gap-2 passeio-item relative';
                        passeioDiv.innerHTML = `
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Nome do Passeio</label>
                                <input type=\"text\" name=\"itinerarios[${itinerarioIdx}][passeios][${passeioIndex}][nome]\" class=\"form-control w-full\" placeholder=\"Nome do passeio\" required />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Valor Adulto (R$)</label>
                                <input type=\"number\" step=\"0.01\" min=\"0\" name=\"itinerarios[${itinerarioIdx}][passeios][${passeioIndex}][valor_adulto]\" class=\"form-control w-full\" placeholder=\"Valor adulto\" required />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Valor Criança (R$)</label>
                                <input type=\"number\" step=\"0.01\" min=\"0\" name=\"itinerarios[${itinerarioIdx}][passeios][${passeioIndex}][valor_crianca]\" class=\"form-control w-full\" placeholder=\"Valor criança\" required />
                            </div>
                            <button type=\"button\" class=\"btn btn-danger btn-sm remove-passeio md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto\" title=\"Remover passeio\"><i class=\"bi bi-x-lg\"></i></button>
                        `;
                        passeiosList.appendChild(passeioDiv);
                    }
                    if (e.target.classList.contains('remove-passeio') || e.target.closest('.remove-passeio')) {
                        e.target.closest('.passeio-item').remove();
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    // Pessoas
                    let pessoasList = document.getElementById('pessoas-list');
                    let addPessoaBtn = document.getElementById('add-pessoa');
                    let pessoaIndex = pessoasList.querySelectorAll('.pessoa-item').length;
                    addPessoaBtn.addEventListener('click', function() {
                        const div = document.createElement('div');
                        div.className = 'rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col md:flex-row md:items-end gap-2 pessoa-item relative';
                        div.innerHTML = `
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Nome</label>
                                <input type=\"text\" name=\"pessoas[${pessoaIndex}][nome]\" class=\"form-control w-full\" placeholder=\"Nome\" required />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Idade</label>
                                <input type=\"number\" name=\"pessoas[${pessoaIndex}][idade]\" class=\"form-control w-full\" placeholder=\"Idade\" min=\"0\" required />
                            </div>
                            <button type=\"button\" class=\"btn btn-danger btn-sm remove-pessoa md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto\" title=\"Remover pessoa\"><i class=\"bi bi-x-lg\"></i></button>
                        `;
                        pessoasList.appendChild(div);
                        pessoaIndex++;
                    });
                    pessoasList.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-pessoa') || e.target.closest('.remove-pessoa')) {
                            e.target.closest('.pessoa-item').remove();
                        }
                    });

                    // Destinos
                    let destinosList = document.getElementById('destinos-list');
                    let addDestinoBtn = document.getElementById('add-destino');
                    let destinoIndex = destinosList.querySelectorAll('.destino-item').length;
                    addDestinoBtn.addEventListener('click', function() {
                        const div = document.createElement('div');
                        div.className = 'rounded-xl border border-gray-200 bg-gray-50 p-4 flex flex-col md:flex-row md:items-end gap-2 destino-item relative';
                        div.innerHTML = `
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Nome</label>
                                <input type=\"text\" name=\"destinos[${destinoIndex}][nome]\" class=\"form-control w-full\" placeholder=\"Nome do destino\" required />
                            </div>
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Data</label>
                                <input type=\"date\" name=\"destinos[${destinoIndex}][data]\" class=\"form-control w-full\" placeholder=\"Data\" required />
                            </div>
                            <div class=\"flex-1 flex flex-col gap-2\">
                                <button type=\"button\" class=\"btn btn-outline-secondary btn-sm add-voo mb-2\" data-index=\"${destinoIndex}\"><i class=\"bi bi-airplane\"></i> Adicionar Voo</button>
                                <div class=\"voo-fields\" style=\"display:none;\">
                                    <label class=\"block text-xs font-medium text-gray-600 mb-1\">Número do Voo</label>
                                    <input type=\"text\" name=\"destinos[${destinoIndex}][voo][numero_voo]\" class=\"form-control w-full mb-2\" placeholder=\"Número do voo\" />
                                    <label class=\"mb-1.5 block text-sm font-medium text-gray-700\">Ida/Volta</label>
                                    <div class=\"relative z-20 bg-transparent mb-2\">
                                        <select name=\"destinos[${destinoIndex}][voo][ida_volta]\"
                                            class=\"dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden\">
                                            <option value=\"\">Selecione</option>
                                            <option value=\"1\">Ida</option>
                                            <option value=\"2\">Volta</option>
                                        </select>
                                        <span class=\"pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700\">
                                            <svg class=\"stroke-current\" width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                                <path d=\"M4.79175 7.396L10.0001 12.6043L15.2084 7.396\" stroke=\"\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" />
                                            </svg>
                                        </span>
                                    </div>
                                    <label class=\"block text-xs font-medium text-gray-600 mb-1\">Assento</label>
                                    <input type=\"text\" name=\"destinos[${destinoIndex}][voo][assento]\" class=\"form-control w-full\" placeholder=\"Assento\" />
                                </div>
                            </div>
                            <button type=\"button\" class=\"btn btn-danger btn-sm remove-destino md:mb-0 md:ml-2 mt-2 md:mt-0 self-end md:self-auto\" title=\"Remover destino\"><i class=\"bi bi-x-lg\"></i></button>
                        `;
                                            // Exibir/esconder campos de voo
                                            destinosList.addEventListener('click', function(e) {
                                                if (e.target.classList.contains('add-voo')) {
                                                    const parent = e.target.closest('.flex-1');
                                                    const vooFields = parent.querySelector('.voo-fields');
                                                    if (vooFields.style.display === 'none') {
                                                        vooFields.style.display = 'block';
                                                        e.target.innerHTML = '<i class="bi bi-airplane"></i> Remover Voo';
                                                    } else {
                                                        vooFields.style.display = 'none';
                                                        e.target.innerHTML = '<i class="bi bi-airplane"></i> Adicionar Voo';
                                                        // Limpa os campos
                                                        vooFields.querySelectorAll('input').forEach(input => input.value = '');
                                                    }
                                                }
                                            });
                        destinosList.appendChild(div);
                        destinoIndex++;
                    });
                    destinosList.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-destino') || e.target.closest('.remove-destino')) {
                            e.target.closest('.destino-item').remove();
                        }
                    });
                });
            </script>
            @endpush
            </form>
        </div>
    </div>
@endsection
