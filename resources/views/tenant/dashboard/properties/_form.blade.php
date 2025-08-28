@csrf
@if(isset($property))
    @method('PUT')
@endif

<input type="hidden" name="status" id="form-status" value="draft">

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-title">{{ __('Erro de Validação!') }}</h4>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card">
    <div class="row g-0">
        <div class="col-12 col-md-3 border-end">
            <div class="card-body">
                <h4 class="subheader">{{ __('Etapas do Cadastro') }}</h4>
                <div id="form-nav" class="list-group list-group-transparent">
                    <a href="#step-1" class="list-group-item list-group-item-action d-flex align-items-center active" data-step="0">{{ __('1. Básico') }}</a>
                    <a href="#step-2" class="list-group-item list-group-item-action d-flex align-items-center" data-step="1">{{ __('2. Localização') }}</a>
                    <a href="#step-3" class="list-group-item list-group-item-action d-flex align-items-center" data-step="2">{{ __('3. Composições e Dimensões') }}</a>
                    <a href="#step-4" class="list-group-item list-group-item-action d-flex align-items-center" data-step="3">{{ __('4. Características') }}</a>
                    <a href="#step-5" class="list-group-item list-group-item-action d-flex align-items-center" data-step="4">{{ __('5. Negociação') }}</a>
                    <a href="#step-6" class="list-group-item list-group-item-action d-flex align-items-center" data-step="5">{{ __('6. Descrição') }}</a>
                    <a href="#step-7" class="list-group-item list-group-item-action d-flex align-items-center" data-step="6">{{ __('7. Mídia') }}</a>
                    <a href="#step-8" class="list-group-item list-group-item-action d-flex align-items-center" data-step="7">{{ __('8. Publicação') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 d-flex flex-column">
            <div class="card-body">
                <div class="tab-content" id="form-steps">

                    {{-- Passo 1: Básico --}}
                    <div class="tab-pane active" id="step-1" role="tabpanel">
                        <h3 class="card-title">{{ __('Informações Básicas') }}</h3>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Código do Imóvel') }}</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $property->code ?? '') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Tipo do Imóvel') }}</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="">{{ __('Selecione o tipo') }}</option>
                                    <option value="apartment" {{ old('type', $property->type ?? '') == 'apartment' ? 'selected' : '' }}>{{ __('Apartamento') }}</option>
                                    <option value="house" {{ old('type', $property->type ?? '') == 'house' ? 'selected' : '' }}>{{ __('Casa') }}</option>
                                    <option value="commercial" {{ old('type', $property->type ?? '') == 'commercial' ? 'selected' : '' }}>{{ __('Comercial') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Finalidade') }}</label>
                                <select name="purpose" class="form-select @error('purpose') is-invalid @enderror">
                                    <option value="">{{ __('Selecione a finalidade') }}</option>
                                    <option value="residential" {{ old('purpose', $property->purpose ?? '') == 'residential' ? 'selected' : '' }}>{{ __('Residencial') }}</option>
                                    <option value="commercial" {{ old('purpose', $property->purpose ?? '') == 'commercial' ? 'selected' : '' }}>{{ __('Comercial') }}</option>
                                    <option value="others" {{ old('purpose', $property->purpose ?? '') == 'others' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Proprietário') }}</label>
                            <select name="owner_id" class="form-select @error('owner_id') is-invalid @enderror" required>
                                <option value="">{{ __('Selecione um proprietário') }}</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', $property->owner_id ?? '') == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Passo 2: Localização --}}
                    <div class="tab-pane" id="step-2" role="tabpanel">
                        <h3 class="card-title">{{ __('Localização do Imóvel') }}</h3>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('CEP') }}</label>
                                <input type="text" name="address[cep]" id="cep" class="form-control @error('address.cep') is-invalid @enderror" value="{{ old('address.cep', $property->address['cep'] ?? '') }}" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">{{ __('Endereço') }}</label>
                                <input type="text" name="address[street]" id="address" class="form-control @error('address.street') is-invalid @enderror" value="{{ old('address.street', $property->address['street'] ?? '') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">{{ __('Cidade') }}</label>
                                <input type="text" name="address[city]" id="city" class="form-control @error('address.city') is-invalid @enderror" value="{{ old('address.city', $property->address['city'] ?? '') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ __('Estado') }}</label>
                                <input type="text" name="address[state]" id="state" class="form-control @error('address.state') is-invalid @enderror" value="{{ old('address.state', $property->address['state'] ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Passo 3: Composições e Dimensões --}}
                    <div class="tab-pane" id="step-3" role="tabpanel">
                        <h3 class="card-title">{{ __('Composições e Dimensões') }}</h3>
                        <div class="row g-3">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Quartos') }}</label>
                                <input type="number" name="compositions[bedrooms]" class="form-control @error('compositions.bedrooms') is-invalid @enderror" value="{{ old('compositions.bedrooms', $property->compositions['bedrooms'] ?? '') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Suítes') }}</label>
                                <input type="number" name="compositions[suites]" class="form-control @error('compositions.suites') is-invalid @enderror" value="{{ old('compositions.suites', $property->compositions['suites'] ?? '') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Banheiros') }}</label>
                                <input type="number" name="compositions[bathrooms]" class="form-control @error('compositions.bathrooms') is-invalid @enderror" value="{{ old('compositions.bathrooms', $property->compositions['bathrooms'] ?? '') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Salas') }}</label>
                                <input type="number" name="compositions[living_rooms]" class="form-control @error('compositions.living_rooms') is-invalid @enderror" value="{{ old('compositions.living_rooms', $property->compositions['living_rooms'] ?? '') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Vagas') }}</label>
                                <input type="number" name="compositions[parking_spots]" class="form-control @error('compositions.parking_spots') is-invalid @enderror" value="{{ old('compositions.parking_spots', $property->compositions['parking_spots'] ?? '') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Área Útil (em m²)') }}</label>
                                <input type="text" name="dimensions[usable_area]" class="form-control @error('dimensions.usable_area') is-invalid @enderror" value="{{ old('dimensions.usable_area', $property->dimensions['usable_area'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Área Total (em m²)') }}</label>
                                <input type="text" name="dimensions[total_area]" class="form-control @error('dimensions.total_area') is-invalid @enderror" value="{{ old('dimensions.total_area', $property->dimensions['total_area'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Passo 4: Características (em branco) --}}
                    <div class="tab-pane" id="step-4" role="tabpanel">
                        <h3 class="card-title">{{ __('Características do Imóvel') }}</h3>
                        {{-- Espaço em branco para futuras características --}}
                    </div>

                    {{-- Passo 5: Negociação --}}
                    <div class="tab-pane" id="step-5" role="tabpanel">
                        <h3 class="card-title">{{ __('Opções de Negociação') }}</h3>
                        <div class="accordion" id="accordion-business-options">
                            {{-- Acordeão Venda --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-sale">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-sale" aria-expanded="false" aria-controls="collapse-sale">
                                        {{ __('Venda') }}
                                    </button>
                                </h2>
                                <div id="collapse-sale" class="accordion-collapse collapse {{ old('business_options.sale.price', $property->business_options['sale']['price'] ?? '') ? 'show' : '' }}" aria-labelledby="heading-sale" data-bs-parent="#accordion-business-options">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Valor para Venda') }}</label>
                                            <input type="number" step="0.01" name="business_options[sale][price]" class="form-control @error('business_options.sale.price') is-invalid @enderror" value="{{ old('business_options.sale.price', $property->business_options['sale']['price'] ?? '') }}">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="business_options[sale][show_price]" id="show-price-sale" {{ old('business_options.sale.show_price', $property->business_options['sale']['show_price'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show-price-sale">{{ __('Mostrar preço no site?') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="business_options[sale][financing]" id="financing" {{ old('business_options.sale.financing', $property->business_options['sale']['financing'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="financing">{{ __('Aceita Financiamento') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="business_options[sale][trade_in]" id="trade-in" {{ old('business_options.sale.trade_in', $property->business_options['sale']['trade_in'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="trade-in">{{ __('Aceita Permuta') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Acordeão Locação --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-rental">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-rental" aria-expanded="false" aria-controls="collapse-rental">
                                        {{ __('Locação') }}
                                    </button>
                                </h2>
                                <div id="collapse-rental" class="accordion-collapse collapse {{ old('business_options.rental.price', $property->business_options['rental']['price'] ?? '') ? 'show' : '' }}" aria-labelledby="heading-rental" data-bs-parent="#accordion-business-options">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Valor para Locação') }}</label>
                                            <input type="number" step="0.01" name="business_options[rental][price]" class="form-control @error('business_options.rental.price') is-invalid @enderror" value="{{ old('business_options.rental.price', $property->business_options['rental']['price'] ?? '') }}">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="business_options[rental][show_price]" id="show-price-rental" {{ old('business_options.rental.show_price', $property->business_options['rental']['show_price'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show-price-rental">{{ __('Mostrar preço no site?') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="business_options[rental][deposit]" id="deposit" {{ old('business_options.rental.deposit', $property->business_options['rental']['deposit'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="deposit">{{ __('Exige Depósito') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Acordeão Temporada --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-season">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-season" aria-expanded="false" aria-controls="collapse-season">
                                        {{ __('Temporada') }}
                                    </button>
                                </h2>
                                <div id="collapse-season" class="accordion-collapse collapse {{ old('business_options.season.price', $property->business_options['season']['price'] ?? '') || old('business_options.season.period', $property->business_options['season']['period'] ?? '') || old('business_options.season.start_date', $property->business_options['season']['start_date'] ?? '') ? 'show' : '' }}" aria-labelledby="heading-season" data-bs-parent="#accordion-business-options">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Valor para Temporada') }}</label>
                                            <input type="number" step="0.01" name="business_options[season][price]" class="form-control @error('business_options.season.price') is-invalid @enderror" value="{{ old('business_options.season.price', $property->business_options['season']['price'] ?? '') }}">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="business_options[season][show_price]" id="show-price-season" {{ old('business_options.season.show_price', $property->business_options['season']['show_price'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show-price-season">{{ __('Mostrar preço no site?') }}</label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Período de Aluguel') }}</label>
                                            <select name="business_options[season][period]" class="form-select @error('business_options.season.period') is-invalid @enderror">
                                                <option value="">{{ __('Selecione o período') }}</option>
                                                <option value="daily" {{ old('business_options.season.period', $property->business_options['season']['period'] ?? '') == 'daily' ? 'selected' : '' }}>{{ __('Diária') }}</option>
                                                <option value="weekly" {{ old('business_options.season.period', $property->business_options['season']['period'] ?? '') == 'weekly' ? 'selected' : '' }}>{{ __('Semanal') }}</option>
                                                <option value="monthly" {{ old('business_options.season.period', $property->business_options['season']['period'] ?? '') == 'monthly' ? 'selected' : '' }}>{{ __('Mensal') }}</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Período Disponível') }}</label>
                                            <input type="text" id="season-date-range-input" class="form-control" value="{{ old('business_options.season.available_dates', $property->business_options['season']['available_dates'] ?? '') }}">
                                            <input type="hidden" name="business_options[season][start_date]" id="season-start-date-hidden" value="{{ old('business_options.season.start_date', $property->business_options['season']['start_date'] ?? '') }}">
                                            <input type="hidden" name="business_options[season][end_date]" id="season-end-date-hidden" value="{{ old('business_options.season.end_date', $property->business_options['season']['end_date'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Passo 6: Descrição --}}
                    <div class="tab-pane" id="step-6" role="tabpanel">
                        <h3 class="card-title">{{ __('Descrição do Imóvel') }}</h3>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Descrição Completa do Imóvel') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $property->description ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-openai"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.217 19.384a3.501 3.501 0 0 0 6.783 -1.217v-5.167l-6 -3.35" /><path d="M5.214 15.014a3.501 3.501 0 0 0 4.446 5.266l4.34 -2.534v-6.946" /><path d="M6 7.63c-1.391 -.236 -2.787 .395 -3.534 1.689a3.474 3.474 0 0 0 1.271 4.745l4.263 2.514l6 -3.348" /><path d="M12.783 4.616a3.501 3.501 0 0 0 -6.783 1.217v5.067l6 3.45" /><path d="M18.786 8.986a3.501 3.501 0 0 0 -4.446 -5.266l-4.34 2.534v6.946" /><path d="M18 16.302c1.391 .236 2.787 -.395 3.534 -1.689a3.474 3.474 0 0 0 -1.271 -4.745l-4.308 -2.514l-5.955 3.42" /></svg>
                                {{ __('Gerar com IA') }}
                            </button>
                        </div>
                    </div>

                    {{-- Passo 7: Mídia --}}
                    <div class="tab-pane" id="step-7" role="tabpanel">
                        <h3 class="card-title">{{ __('Mídia do Imóvel') }}</h3>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Fotos do Imóvel') }}</label>
                            @if(isset($property) && $property->id)
                                <div class="container my-4">
                                    <div class="mb-3">
                                        <a id="select-button" href="javascript:void(0)" class="btn btn-primary" role="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-upload"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg> Selecionar Imagens
                                        </a>
                                    </div>
                                    <div id="dropzone-area" class="dropzone-area border border-2 border-dashed border-secondary rounded">
                                        <div id="preview-grid" class="d-flex flex-wrap">
                                            <div class="dz-empty-message" style="display: none;">Nenhuma imagem carregada.</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mt-2">
                                    {{ __('Salve o imóvel para adicionar fotos e outros arquivos de mídia.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Passo 8: Publicação --}}
                    <div class="tab-pane" id="step-8" role="tabpanel">
                        <h3 class="card-title">{{ __('Opções de Publicação') }}</h3>

                        {{-- Publicação em Portais --}}
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">{{ __('Portais Imobiliários') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="publication[portals][]" value="zap" id="portal-zap" {{ in_array('zap', old('publication.portals', $property->publication['portals'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="portal-zap">{{ __('ZAP Imóveis') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="publication[portals][]" value="vivareal" id="portal-vivareal" {{ in_array('vivareal', old('publication.portals', $property->publication['portals'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="portal-vivareal">{{ __('Viva Real') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="publication[portals][]" value="olx" id="portal-olx" {{ in_array('olx', old('publication.portals', $property->publication['portals'] ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="portal-olx">{{ __('OLX') }}</label>
                            </div>
                        </div>

                        <hr>
                        
                        {{-- Publicação no Site --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="publication[my_site]" id="my-site" value="1" {{ old('publication.my_site', $property->publication['my_site'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="my-site">{{ __('Divulgar no meu site') }}</label>
                            </div>
                        </div>

                        <hr>
                        
                        {{-- Período de Divulgação --}}
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">{{ __('Período de Divulgação') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publication[period_type]" id="period-manual" value="manual" {{ old('publication.period_type', $property->publication['period_type'] ?? 'manual') == 'manual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="period-manual">{{ __('Remover manualmente') }}</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="publication[period_type]" id="period-range" value="range" {{ old('publication.period_type', $property->publication['period_type'] ?? '') == 'range' ? 'checked' : '' }}>
                                <label class="form-check-label" for="period-range">{{ __('Definir prazo inicial e final') }}</label>
                            </div>

                            <div class="row" id="date-range-fields" style="display: {{ old('publication.period_type', $property->publication['period_type'] ?? '') == 'range' ? 'flex' : 'none' }};">
                                <div class="col-12">
                                    <label class="form-label">{{ __('Prazo de Divulgação') }}</label>
                                    <input type="text" id="date-range-input" class="form-control" value="{{ old('publication.date_range', $property->publication['date_range'] ?? '') }}">
                                    <input type="hidden" name="publication[start_date]" id="start-date-hidden" value="{{ old('publication.start_date', $property->publication['start_date'] ?? '') }}">
                                    <input type="hidden" name="publication[end_date]" id="end-date-hidden" value="{{ old('publication.end_date', $property->publication['end_date'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer bg-transparent mt-auto">
                <div class="btn-list justify-content-end">
                    <button type="button" class="btn btn-secondary" id="prev-step" style="display: none;">{{ __('Anterior') }}</button>
                    <div>
                        <button type="submit" class="btn btn-outline-primary" name="status" value="draft" id="save-draft">{{ __('Salvar Rascunho') }}</button>
                        <button type="button" class="btn btn-primary" id="next-step">{{ __('Próximo') }}</button>
                        <button type="submit" class="btn btn-primary" name="status" value="published" id="save-publish" style="display: none;">{{ __('Salvar e Publicar') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formNav = document.getElementById('form-nav');
        const formSteps = document.getElementById('form-steps');
        const prevBtn = document.getElementById('prev-step');
        const nextBtn = document.getElementById('next-step');
        const savePublishBtn = document.getElementById('save-publish');

        let currentStep = 0;
        const steps = formSteps.querySelectorAll('.tab-pane');
        const navLinks = formNav.querySelectorAll('.list-group-item');
        const totalSteps = steps.length;

        function updateStep(newStep) {
            steps[currentStep].classList.remove('active');
            navLinks[currentStep].classList.remove('active');

            currentStep = newStep;
            steps[currentStep].classList.add('active');
            navLinks[currentStep].classList.add('active');

            prevBtn.style.display = currentStep === 0 ? 'none' : 'block';
            nextBtn.style.display = currentStep === totalSteps - 1 ? 'none' : 'block';
            savePublishBtn.style.display = currentStep === totalSteps - 1 ? 'block' : 'none';
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps - 1) {
                updateStep(currentStep + 1);
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                updateStep(currentStep - 1);
            }
        });

        navLinks.forEach((link, index) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                updateStep(index);
            });
        });

        // Lógica para mostrar/esconder o seletor de data de publicação
        const periodManual = document.getElementById('period-manual');
        const periodRange = document.getElementById('period-range');
        const dateRangeFields = document.getElementById('date-range-fields');

        function toggleDateRangeFields() {
            if (periodRange.checked) {
                dateRangeFields.style.display = 'flex';
            } else {
                dateRangeFields.style.display = 'none';
            }
        }

        periodManual.addEventListener('change', toggleDateRangeFields);
        periodRange.addEventListener('change', toggleDateRangeFields);
        toggleDateRangeFields(); // Garante o estado correto no carregamento da página

        // Inicialização do Litepicker para Publicação
        const publicationPicker = new Litepicker({ 
            element: document.getElementById('date-range-input'),
            singleMode: false,
            allowRepick: true,
            lang: 'pt-BR',
            startDate: document.getElementById('start-date-hidden').value,
            endDate: document.getElementById('end-date-hidden').value,
            setup: (picker) => {
                picker.on('selected', (start, end) => {
                    document.getElementById('start-date-hidden').value = start.format('YYYY-MM-DD');
                    document.getElementById('end-date-hidden').value = end.format('YYYY-MM-DD');
                });
            }
        });

        // Inicialização do Litepicker para Temporada
        const seasonPicker = new Litepicker({
            element: document.getElementById('season-date-range-input'),
            singleMode: false,
            allowRepick: true,
            lang: 'pt-BR',
            startDate: document.getElementById('season-start-date-hidden').value,
            endDate: document.getElementById('season-end-date-hidden').value,
            setup: (picker) => {
                picker.on('selected', (start, end) => {
                    document.getElementById('season-start-date-hidden').value = start.format('YYYY-MM-DD');
                    document.getElementById('season-end-date-hidden').value = end.format('YYYY-MM-DD');
                });
            }
        });

    });
</script>

{{-- Script para o seletor de data e a lógica de etapas --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.8/litepicker.js" integrity="sha512-SrC0hVGB4ZI7HrrHeOTPNQ0h9A7uJYoaB71vKSSBbk6ap1TOwPgTe7yFLtUxTijFmylT2/8cypZdefDCZQjQeQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formNav = document.getElementById('form-nav');
        const formSteps = document.getElementById('form-steps');
        const prevBtn = document.getElementById('prev-step');
        const nextBtn = document.getElementById('next-step');
        const savePublishBtn = document.getElementById('save-publish');

        let currentStep = 0;
        const steps = formSteps.querySelectorAll('.tab-pane');
        const navLinks = formNav.querySelectorAll('.list-group-item');
        const totalSteps = steps.length;

        function updateStep(newStep) {
            steps[currentStep].classList.remove('active');
            navLinks[currentStep].classList.remove('active');

            currentStep = newStep;
            steps[currentStep].classList.add('active');
            navLinks[currentStep].classList.add('active');

            prevBtn.style.display = currentStep === 0 ? 'none' : 'block';
            nextBtn.style.display = currentStep === totalSteps - 1 ? 'none' : 'block';
            savePublishBtn.style.display = currentStep === totalSteps - 1 ? 'block' : 'none';
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps - 1) {
                updateStep(currentStep + 1);
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                updateStep(currentStep - 1);
            }
        });

        navLinks.forEach((link, index) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                updateStep(index);
            });
        });

        // Lógica para mostrar/esconder o seletor de data
        const periodManual = document.getElementById('period-manual');
        const periodRange = document.getElementById('period-range');
        const dateRangeFields = document.getElementById('date-range-fields');

        function toggleDateRangeFields() {
            if (periodRange.checked) {
                dateRangeFields.style.display = 'flex';
            } else {
                dateRangeFields.style.display = 'none';
            }
        }

        periodManual.addEventListener('change', toggleDateRangeFields);
        periodRange.addEventListener('change', toggleDateRangeFields);
        toggleDateRangeFields(); // Garante o estado correto no carregamento da página
        
        // Inicialização do Litepicker
        const picker = new Litepicker({ 
            element: document.getElementById('date-range-input'),
            singleMode: false,
            allowRepick: true,
            lang: 'pt-BR',
            startDate: document.getElementById('start-date-hidden').value,
            endDate: document.getElementById('end-date-hidden').value,
            setup: (picker) => {
                picker.on('selected', (start, end) => {
                    document.getElementById('start-date-hidden').value = start.format('YYYY-MM-DD');
                    document.getElementById('end-date-hidden').value = end.format('YYYY-MM-DD');
                });
            }
        });

    });
</script>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('property-form');
        const formNav = document.getElementById('form-nav');
        const formSteps = document.getElementById('form-steps');
        
        if (!form || !formNav || !formSteps) {
            return;
        }

        const navItems = formNav.querySelectorAll('.list-group-item');
        const steps = formSteps.querySelectorAll('.tab-pane');
        const nextBtn = form.querySelector('#next-step');
        const prevBtn = form.querySelector('#prev-step');
        const savePublishBtn = form.querySelector('#save-publish');
        const saveDraftBtn = form.querySelector('#save-draft');
        
        let currentStepIndex = 0;

        function showStep(index) {
            navItems.forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });
            steps.forEach((step, i) => {
                step.classList.toggle('active', i === index);
            });
            
            prevBtn.style.display = index > 0 ? 'inline-block' : 'none';
            nextBtn.style.display = index < steps.length - 1 ? 'inline-block' : 'none';
            
            savePublishBtn.style.display = index === steps.length - 1 ? 'inline-block' : 'none';
            saveDraftBtn.style.display = index === steps.length - 1 ? 'inline-block' : 'none';
        }

        nextBtn.addEventListener('click', () => {
            if (currentStepIndex < steps.length - 1) {
                currentStepIndex++;
                showStep(currentStepIndex);
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStepIndex > 0) {
                currentStepIndex--;
                showStep(currentStepIndex);
            }
        });

        navItems.forEach((item, index) => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                currentStepIndex = index;
                showStep(currentStepIndex);
            });
        });
        
        @if ($errors->any())
            const firstErrorTab = form.querySelector('.is-invalid').closest('.tab-pane');
            if (firstErrorTab) {
                const firstErrorStep = Array.from(steps).indexOf(firstErrorTab);
                currentStepIndex = firstErrorStep;
            }
        @endif
    
        const cepInput = document.getElementById('cep');
        const addressInput = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');

        if (cepInput && addressInput && cityInput && stateInput) {
            cepInput.addEventListener('blur', function() {
                const cep = cepInput.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    addressInput.value = '...';
                    cityInput.value = '...';
                    stateInput.value = '...';
                    
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                addressInput.value = data.logradouro;
                                cityInput.value = data.localidade;
                                stateInput.value = data.uf;
                            } else {
                                alert('CEP não encontrado.');
                                addressInput.value = '';
                                cityInput.value = '';
                                stateInput.value = '';
                            }
                        })
                        .catch(() => {
                            alert('Erro ao buscar o CEP.');
                            addressInput.value = '';
                            cityInput.value = '';
                            stateInput.value = '';
                        });
                }
            });
        }

        showStep(currentStepIndex);
    });
</script>
@endpush