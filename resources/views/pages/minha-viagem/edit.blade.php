@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Minha Viagem" />
    <div class="mt-8">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 w-full">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{ isset($viagem) ? route('minha-viagem.update', $viagem->id) : route('minha-viagem.store') }}">
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
                                           <label class="block text-xs font-medium text-gray-600 mb-1">Itinerário</label>
                                           <div class="w-full px-3 py-2 bg-gray-100 rounded border border-gray-200 text-gray-700">
                                               {{ $itinerario['nome'] ?? '' }}
                                               <input type="hidden" name="itinerarios[{{ $i }}][nome]" value="{{ $itinerario['nome'] ?? '' }}">
                                               @if(!empty($itinerario['data']))
                                                   @php
                                                       $data = $itinerario['data'];
                                                       $dataFormatada = \Carbon\Carbon::parse($data)->format('d/m/Y');
                                                       $dias = [
                                                           'Monday'    => 'Segunda-feira',
                                                           'Tuesday'   => 'Terça-feira',
                                                           'Wednesday' => 'Quarta-feira',
                                                           'Thursday'  => 'Quinta-feira',
                                                           'Friday'    => 'Sexta-feira',
                                                           'Saturday'  => 'Sábado',
                                                           'Sunday'    => 'Domingo',
                                                       ];
                                                       $diaSemana = $dias[\Carbon\Carbon::parse($data)->format('l')] ?? '';
                                                   @endphp
                                                   <span class="text-xs text-gray-500">Data: {{ $dataFormatada }} ({{ $diaSemana }})</span>
                                               @endif
                                           </div>
                                           
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Descrição</label>
                                        <input type="text" name="itinerarios[{{ $i }}][descricao]" class="form-control w-full" placeholder="Descrição" value="{{ $itinerario['descricao'] ?? '' }}" />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Data</label>
                                        <input type="date" name="itinerarios[{{ $i }}][data]" class="form-control w-full" placeholder="Data" value="{{ $itinerario['data'] ?? '' }}" />
                                    </div>
                                    <!-- Botão de remover itinerário removido para impedir exclusão -->
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
                    <!-- Botão de adicionar itinerário removido para impedir inclusão após criação -->
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
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Documentos</label>
                                    <div class="documentos-list space-y-2">
                                        @php
                                            $documentos = $pessoa['documentos'] ?? ($pessoa['id'] ?? false ? (isset($pessoa['documentos']) ? $pessoa['documentos'] : (isset($pessoa['id']) && isset($viagem) ? ($viagem->pessoas->where('id', $pessoa['id'])->first()->documentos->toArray() ?? []) : [])) : []);
                                        @endphp
                                        @foreach($documentos as $d => $doc)
                                            <div class="flex gap-2 mb-1 items-center">
                                                <input type="text" name="pessoas[{{ $i }}][documentos][{{ $d }}][tipo]" class="form-control w-1/3" placeholder="Tipo (ex: RG)" value="{{ $doc['tipo'] ?? '' }}" />
                                                <input type="file" name="pessoas[{{ $i }}][documentos][{{ $d }}][foto]" class="form-control w-2/3" accept="image/*" />
                                                @if(!empty($doc['foto']))
                                                    <a href="{{ asset('storage/' . $doc['foto']) }}" target="_blank" title="Ver foto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-6 w-6 text-blue-600 hover:text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15l-5-5a2 2 0 00-2.828 0l-5 5" />
                                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-danger btn-xs remove-documento">&times;</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-xs add-documento mt-1">Adicionar Documento</button>
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
                            $destinosOld = old('destinos');
                            if (!$destinosOld && isset($viagem)) {
                                $destinosOld = $viagem->destinos->map(function($destino) {
                                    $arr = $destino->toArray();
                                    $arr['id'] = $destino->id;
                                    if ($destino->voo) {
                                        $arr['voo'] = [
                                            'id' => $destino->voo->id ?? '',
                                            'numero_voo' => $destino->voo->numero_voo ?? '',
                                            'ida_volta' => $destino->voo->ida_volta ?? '',
                                            'assento' => $destino->voo->assento ?? '',
                                        ];
                                    }
                                    return $arr;
                                })->toArray();
                            }
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
                // Função de adicionar itinerário removida para impedir inclusão após criação
                // Função de remoção de itinerário removida para impedir exclusão após criação

                // Passeios dinâmicos
                itinerariosList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('add-passeio')) {
                        const itinerarioDiv = e.target.closest('.itinerario-item');
                        const passeiosList = itinerarioDiv.querySelector('.passeios-list');
                        let passeioIndex = passeiosList.querySelectorAll('.passeio-item').length;
                        const itinerarioIdx = e.target.getAttribute('data-itinerario');
                        // Garante que o campo hidden do nome existe e está preenchido
                        let nomeLabel = itinerarioDiv.querySelector('[data-itinerario-nome]');
                        let nomeHidden = itinerarioDiv.querySelector(`input[name="itinerarios[${itinerarioIdx}][nome]"]`);
                        // Se o label estiver vazio, tenta buscar o valor do input hidden já existente
                        let nomeValor = nomeLabel && nomeLabel.textContent.trim() ? nomeLabel.textContent.trim() : (nomeHidden ? nomeHidden.value : '');
                        if (!nomeHidden) {
                            nomeHidden = document.createElement('input');
                            nomeHidden.type = 'hidden';
                            nomeHidden.name = `itinerarios[${itinerarioIdx}][nome]`;
                            nomeHidden.value = nomeValor;
                            nomeLabel && nomeLabel.insertAdjacentElement('afterend', nomeHidden);
                        } else {
                            nomeHidden.value = nomeValor;
                        }
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
                            <div class=\"flex-1\">
                                <label class=\"block text-xs font-medium text-gray-600 mb-1\">Documentos</label>
                                <div class=\"documentos-list space-y-2\"></div>
                                <button type=\"button\" class=\"btn btn-outline-primary btn-xs add-documento mt-1\">Adicionar Documento</button>
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
                        if (e.target.classList.contains('add-documento')) {
                            const pessoaDiv = e.target.closest('.pessoa-item');
                            const documentosList = pessoaDiv.querySelector('.documentos-list');
                            let docIndex = documentosList.querySelectorAll('input[name*="[tipo]"]').length;
                            const docDiv = document.createElement('div');
                            docDiv.className = 'flex gap-2 mb-1 items-center';
                            docDiv.innerHTML = `
                                <input type=\"text\" name=\"pessoas[${Array.from(pessoasList.children).indexOf(pessoaDiv)}][documentos][${docIndex}][tipo]\" class=\"form-control w-1/3\" placeholder=\"Tipo (ex: RG)\" />
                                <input type=\"file\" name=\"pessoas[${Array.from(pessoasList.children).indexOf(pessoaDiv)}][documentos][${docIndex}][foto]\" class=\"form-control w-2/3\" accept=\"image/*\" />
                                <button type=\"button\" class=\"btn btn-danger btn-xs remove-documento\">&times;</button>
                            `;
                            documentosList.appendChild(docDiv);
                        }
                        if (e.target.classList.contains('remove-documento')) {
                            e.target.closest('div.flex').remove();
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
