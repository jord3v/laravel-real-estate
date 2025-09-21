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
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">
                                        {{ __('Tipo do Imóvel') }}
                                    </label>
                                    <a href="#" class="text-decoration-none form-label mb-0 text-primary" onclick="window.openTypesModal(event)">
                                        Gerenciar
                                    </a>
                                </div>
                                <select name="type_id" id="type-select" class="form-select @error('type_id') is-invalid @enderror">
                                    <option value="">{{ __('Selecione o tipo') }}</option>
                                    {{-- Tipos serão carregados via JavaScript --}}
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
                        <!-- Inputs de localização -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function showPropertyMap(lat, long) {
    console.log('Chamando showPropertyMap com:', lat, long);
    var mapContainer = document.getElementById('property-map');
    var notFoundBox = document.getElementById('location-not-found');
    if (!mapContainer || !notFoundBox) return;
    
    if (lat !== null && lat !== undefined && lat !== '' && long !== null && long !== undefined && long !== '' && !isNaN(lat) && !isNaN(long)) {
        notFoundBox.style.display = "none";
        mapContainer.style.display = "block";
        mapContainer.innerHTML = "";
        
        setTimeout(function() {
            if (window.propertyMapInstance) {
                window.propertyMapInstance.remove();
            }
            if (typeof L === 'undefined') {
                mapContainer.innerHTML = '<div style="color:red;text-align:center;padding:20px;">Erro: Leaflet.js não está carregado!</div>';
                console.error('Leaflet.js não está disponível. Certifique-se de incluir o CSS e JS do Leaflet no layout.');
                return;
            }
            try {
                window.propertyMapInstance = L.map('property-map').setView([lat, long], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(window.propertyMapInstance);
                L.marker([lat, long]).addTo(window.propertyMapInstance);
                
                // Verifica se o container do mapa está visível e força o redimensionamento
                const step2 = document.getElementById('step-2');
                if (step2 && step2.classList.contains('active')) {
                    setTimeout(function() {
                        window.propertyMapInstance.invalidateSize();
                        console.log('Mapa redimensionado após renderização');
                    }, 100);
                }
                
                console.log('Mapa renderizado!');
            } catch (e) {
                mapContainer.innerHTML = '<div style="color:red;text-align:center;padding:20px;">Erro ao renderizar mapa!</div>';
                console.error('Erro ao renderizar mapa:', e);
            }
        }, 100);
    } else {
        if (window.propertyMapInstance) {
            window.propertyMapInstance.off();
            window.propertyMapInstance.remove();
            window.propertyMapInstance = null;
        }
        // Remove o div do Leaflet se existir
        var leafletDiv = mapContainer.querySelector('.leaflet-container');
        if (leafletDiv) {
            leafletDiv.remove();
        }
        mapContainer.innerHTML = "";
        mapContainer.style.display = "none";
        notFoundBox.style.display = "block";
    }
}
function updateMapFromInputs() {
    console.log('Todos inputs address[lat]:', document.querySelectorAll('[name="address[lat]"]'));
    console.log('Todos inputs address[long]:', document.querySelectorAll('[name="address[long]"]'));
    const latInput = document.querySelector('[name="address[lat]"]');
    const longInput = document.querySelector('[name="address[long]"]');
    if (latInput && longInput) {
        const latVal = latInput.value.trim();
        const longVal = longInput.value.trim();
        console.log('Valores brutos lat/long:', latVal, typeof latVal, longVal, typeof longVal);
        const latNum = parseFloat(latVal);
        const longNum = parseFloat(longVal);
        console.log('Valores convertidos lat/long:', latNum, typeof latNum, longNum, typeof longNum);
        if (latVal !== '' && longVal !== '' && !isNaN(latNum) && !isNaN(longNum)) {
            showPropertyMap(latNum, longNum);
        } else {
            console.log('Lat/Long inválidos, mapa não será exibido:', latVal, longVal);
            showPropertyMap(null, null);
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.querySelector('[name="address[lat]"]');
    const longInput = document.querySelector('[name="address[long]"]');
    if (latInput && longInput) {
        latInput.addEventListener('change', updateMapFromInputs);
        longInput.addEventListener('change', updateMapFromInputs);
        updateMapFromInputs();
    }
    // Atualiza o mapa após preenchimento automático do CEP
    setTimeout(updateMapFromInputs, 500);
    
    // Observer para detectar quando a aba de localização fica visível
    const step2 = document.getElementById('step-2');
    if (step2) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (step2.classList.contains('active') && window.propertyMapInstance) {
                        setTimeout(function() {
                            window.propertyMapInstance.invalidateSize();
                            console.log('Mapa redimensionado pelo observer');
                        }, 250);
                    }
                }
            });
        });
        observer.observe(step2, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>
                        <h3 class="card-title">{{ __('Localização do Imóvel') }}</h3>
                        <div class="row">
                            <div class="col-2 mb-3">
                                <label class="form-label">{{ __('CEP') }}</label>
                                <input type="text" name="address[cep]" id="cep" class="form-control @error('address.cep') is-invalid @enderror" value="{{ old('address.cep', $property->address['cep'] ?? '') }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">{{ __('Endereço') }}</label>
                                <input type="text" name="address[street]" id="address" class="form-control @error('address.street') is-invalid @enderror" value="{{ old('address.street', $property->address['street'] ?? '') }}" required>
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">{{ __('Número') }}</label>
                                <input type="text" name="address[number]" class="form-control @error('address.number') is-invalid @enderror" value="{{ old('address.number', $property->address['number'] ?? '') }}">
                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">{{ __('Complemento') }}</label>
                                <input type="text" name="address[complement]" class="form-control @error('address.complement') is-invalid @enderror" value="{{ old('address.complement', $property->address['complement'] ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">{{ __('Bairro') }}</label>
                                <input type="text" name="address[neighborhood]" id="neighborhood" class="form-control @error('address.neighborhood') is-invalid @enderror" value="{{ old('address.neighborhood', $property->address['neighborhood'] ?? '') }}" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">{{ __('Cidade') }}</label>
                                <input type="text" name="address[city]" id="city" class="form-control @error('address.city') is-invalid @enderror" value="{{ old('address.city', $property->address['city'] ?? '') }}" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">{{ __('Estado') }}</label>
                                <input type="text" name="address[state]" id="state" class="form-control @error('address.state') is-invalid @enderror" value="{{ old('address.state', $property->address['state'] ?? '') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Mapa do Imóvel</label>
                                <input type="hidden" name="address[lat]" class="form-control @error('address.lat') is-invalid @enderror" value="{{ old('address.lat', $property->address['lat'] ?? '') }}">
                                <input type="hidden" name="address[long]" class="form-control @error('address.long') is-invalid @enderror" value="{{ old('address.long', $property->address['long'] ?? '') }}">
                                <div id="location-box" style="width: 100%; min-height: 350px; border-radius: 8px; border: 2px solid #007bff; background: #f8f9fa; margin-top: 16px; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <div id="property-map" style="width: 100%; height: 350px; border-radius: 8px; display: none;"></div>
                                    <div id="location-not-found" style="width: 100%; text-align: center; color: #007bff; font-weight: bold; font-size: 1.2em; position: absolute; left: 0; top: 50%; transform: translateY(-50%);">Localização não encontrada.</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="address[show_map]" id="show-map" value="1" {{ old('address.show_map', $property->address['show_map'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show-map">Mostrar mapa?</label>
                                </div>
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
                                <input type="number" name="compositions[car_spaces]" class="form-control @error('compositions.car_spaces') is-invalid @enderror" value="{{ old('compositions.car_spaces', $property->compositions['car_spaces'] ?? '') }}">
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
                        <!-- Campos adicionais fora do accordion -->
                        <hr>
                        <div class="row mt-4">
                            <div class="col-3 mb-3">
                                <label class="form-label">Valor condomínio (Mensal)</label>
                                <input type="number" step="0.01" name="business_options[condo_value]" class="form-control" value="{{ old('business_options.condo_value', $property->business_options['condo_value'] ?? '') }}">
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Venc. IPTU</label>
                                <select name="business_options[iptu_due]" class="form-select">
                                    <option value="free" {{ old('business_options.iptu_due', $property->business_options['iptu_due'] ?? '') == 'free' ? 'selected' : '' }}>{{ __('Isento') }}</option>
                                    <option value="weekly" {{ old('business_options.iptu_due', $property->business_options['iptu_due'] ?? '') == 'weekly' ? 'selected' : '' }}>{{ __('Semanal') }}</option>
                                    <option value="monthly" {{ old('business_options.iptu_due', $property->business_options['iptu_due'] ?? '') == 'monthly' ? 'selected' : '' }}>{{ __('Mensal') }}</option>
                                </select>
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Valor IPTU</label>
                                <input type="number" step="0.01" name="business_options[iptu_value]" class="form-control" value="{{ old('business_options.iptu_value', $property->business_options['iptu_value'] ?? '') }}">
                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Outras taxas</label>
                                <input type="text" name="business_options[other_fees]" class="form-control" value="{{ old('business_options.other_fees', $property->business_options['other_fees'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Passo 6: Descrição --}}
                    <div class="tab-pane" id="step-6" role="tabpanel">
                        <h3 class="card-title">{{ __('Descrição do Imóvel') }}</h3>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Descrição Completa do Imóvel') }}</label>
                            <textarea name="description" id="description-textarea" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $property->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <a class="btn btn-primary" id="generate-ai-description-btn">
                                <span id="ai-btn-text">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-openai"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.217 19.384a3.501 3.501 0 0 0 6.783 -1.217v-5.167l-6 -3.35" /><path d="M5.214 15.014a3.501 3.501 0 0 0 4.446 5.266l4.34 -2.534v-6.946" /><path d="M6 7.63c-1.391 -.236 -2.787 .395 -3.534 1.689a3.474 3.474 0 0 0 1.271 4.745l4.263 2.514l6 -3.348" /><path d="M12.783 4.616a3.501 3.501 0 0 0 -6.783 1.217v5.067l6 3.45" /><path d="M18.786 8.986a3.501 3.501 0 0 0 -4.446 -5.266l-4.34 2.534v6.946" /><path d="M18 16.302c1.391 .236 2.787 -.395 3.534 -1.689a3.474 3.474 0 0 0 -1.271 -4.745l-4.308 -2.514l-5.955 3.42" /></svg>
                                    {{ __('Gerar descrição com inteligência artificial') }}
                                </span>
                                <span id="ai-loading-spinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    {{ __('Gerando...') }}
                                </span>
                            </a>
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
        // ...existing code...
        // Inicialização da busca de CEP para os novos campos
        if (typeof initCepLookup === 'function') {
            initCepLookup('cep', 'address', 'neighborhood', 'city', 'state');
        }
        // ...existing code...
        // (restante do script permanece igual)
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

<script>
// Variáveis globais para gerenciamento de tipos - devem estar disponíveis imediatamente
window.currentEditingTypeId = null;

// Função para abrir o modal de tipos - definida no escopo global
window.openTypesModal = function(event) {
    // Se o evento existe, previne o comportamento padrão para evitar conflitos
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Carrega os tipos primeiro
    if (typeof window.loadTypes === 'function') {
        window.loadTypes();
    }
    
    // Usa a API nativa do DOM para abrir o modal
    const modalElement = document.getElementById('typesModal');
    
    // Limpa qualquer estado anterior
    if (typeof window.closeTypesModal === 'function') {
        window.closeTypesModal();
    }
    
    // Abre o modal
    setTimeout(() => {
        if (typeof bootstrap !== 'undefined') {
            // Usa Bootstrap se disponível
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback usando CSS puro
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            modalElement.setAttribute('aria-hidden', 'false');
            
            // Adiciona backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop';
            document.body.appendChild(backdrop);
            
            // Adiciona classe modal-open ao body
            document.body.classList.add('modal-open');
        }
    }, 100);
};

// Função para fechar o modal de tipos - definida no escopo global
window.closeTypesModal = function() {
    const modalElement = document.getElementById('typesModal');
    
    // Verifica se Bootstrap está disponível
    if (typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        } else {
            // Se não há instância, força o fechamento
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
        }
    } else {
        // Fallback usando classes CSS
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        modalElement.setAttribute('aria-hidden', 'true');
    }
    
    // Remove todos os backdrops possíveis (limpeza geral)
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Remove backdrop específico se existir
    const specificBackdrop = document.getElementById('modal-backdrop');
    if (specificBackdrop) {
        specificBackdrop.remove();
    }
    
    // Remove classe modal-open do body
    document.body.classList.remove('modal-open');
    
    // Remove estilos inline que podem estar interferindo
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
};
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('property-form');
        const generateAiButton = document.getElementById('generate-ai-description-btn');
        const aiBtnText = document.getElementById('ai-btn-text');
        const aiLoadingSpinner = document.getElementById('ai-loading-spinner');
        const descriptionTextarea = document.getElementById('description-textarea');

        if (generateAiButton && form && descriptionTextarea) {
            generateAiButton.addEventListener('click', async function () {
                aiBtnText.classList.add('d-none');
                aiLoadingSpinner.classList.remove('d-none');

                try {
                    const formData = new FormData(form);
                    formData.delete('_method');
                    formData.delete('campo2');
                    
                    const response = await fetch('{{ route("properties.generateDescriptionWithAi") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}",
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Erro na requisição: ' + response.statusText);
                    }

                    const data = await response.json();
                    if (data.description) {
                        descriptionTextarea.value = data.description;
                    } else {
                        alert('Nenhuma descrição gerada pela IA.');
                    }
                } catch (error) {
                    console.error('Erro ao gerar descrição:', error);
                    alert('Ocorreu um erro ao gerar a descrição. Tente novamente.');
                } finally {
                    aiBtnText.classList.remove('d-none');
                    aiLoadingSpinner.classList.add('d-none');
                }
            });
        }
    });
</script>
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
            
            // Se estivermos na aba de localização (index 1) e o mapa existe, redimensiona ele
            if (index === 1 && window.propertyMapInstance) {
                setTimeout(function() {
                    window.propertyMapInstance.invalidateSize();
                    console.log('Mapa redimensionado para a aba de localização');
                }, 200);
            }
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
                
                // Se clicou na aba de localização e o mapa existe, força o redimensionamento
                if (index === 1 && window.propertyMapInstance) {
                    setTimeout(function() {
                        window.propertyMapInstance.invalidateSize();
                        console.log('Mapa redimensionado ao clicar na aba de localização');
                    }, 300);
                }
            });
        });
        
        @if ($errors->any())
            const firstErrorTab = form.querySelector('.is-invalid').closest('.tab-pane');
            if (firstErrorTab) {
                const firstErrorStep = Array.from(steps).indexOf(firstErrorTab);
                currentStepIndex = firstErrorStep;
            }
        @endif
    

        showStep(currentStepIndex);
    });
</script>

<script>
// Variáveis globais para gerenciamento de tipos
let currentEditingTypeId = null;

// Função para abrir o modal de tipos
function openTypesModal(event) {
    // Se o evento existe, previne o comportamento padrão para evitar conflitos
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Carrega os tipos primeiro
    loadTypes();
    
    // Usa a API nativa do DOM para abrir o modal
    const modalElement = document.getElementById('typesModal');
    
    // Limpa qualquer estado anterior
    closeTypesModal();
    
    // Abre o modal
    setTimeout(() => {
        if (typeof bootstrap !== 'undefined') {
            // Usa Bootstrap se disponível
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback usando CSS puro
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            modalElement.setAttribute('aria-hidden', 'false');
            
            // Adiciona backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop';
            document.body.appendChild(backdrop);
            
            // Adiciona classe modal-open ao body
            document.body.classList.add('modal-open');
        }
    }, 100);
}

// Função para fechar o modal de tipos
function closeTypesModal() {
    const modalElement = document.getElementById('typesModal');
    
    // Verifica se Bootstrap está disponível
    if (typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        } else {
            // Se não há instância, força o fechamento
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
        }
    } else {
        // Fallback usando classes CSS
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        modalElement.setAttribute('aria-hidden', 'true');
    }
    
    // Remove todos os backdrops possíveis (limpeza geral)
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Remove backdrop específico se existir
    const specificBackdrop = document.getElementById('modal-backdrop');
    if (specificBackdrop) {
        specificBackdrop.remove();
    }
    
    // Remove classe modal-open do body
    document.body.classList.remove('modal-open');
    
    // Remove estilos inline que podem estar interferindo
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Função para carregar tipos via API
window.loadTypes = async function() {
    const loadingEl = document.getElementById('types-loading');
    const listEl = document.getElementById('types-list');
    
    loadingEl.style.display = 'block';
    listEl.style.display = 'none';
    
    try {
        const response = await fetch('/dashboard/types');
        const types = await response.json();
        
        renderTypesTable(types);
        updateTypeSelect(types);
        
        loadingEl.style.display = 'none';
        listEl.style.display = 'block';
    } catch (error) {
        console.error('Erro ao carregar tipos:', error);
        showAlert('Erro ao carregar tipos!', 'danger');
        loadingEl.style.display = 'none';
    }
}

// Função para renderizar tabela de tipos
window.renderTypesTable = function(types) {
    const tbody = document.getElementById('types-table-body');
    tbody.innerHTML = '';
    
    if (types.length === 0) {
        // Mostra mensagem quando não há tipos
        const row = document.createElement('tr');
        row.innerHTML = `
            <td colspan="4" class="text-center text-muted py-4">
                <em>Nenhum tipo cadastrado ainda.</em>
            </td>
        `;
        tbody.appendChild(row);
        return;
    }
    
    types.forEach(type => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${type.name}</td>
            <td>${type.description || '-'}</td>
            <td>
                <span class="badge bg-${type.active ? 'success-lt' : 'danger-lt'}">
                    ${type.active ? 'Ativo' : 'Inativo'}
                </span>
            </td>
            <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                    <button type="button" class="btn btn-sm" onclick="window.editType(${type.id})">
                        Editar
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="window.deleteType(${type.id})">
                        Excluir
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Função para atualizar o select de tipos no formulário principal
function updateTypeSelect(types) {
    const select = document.getElementById('type-select');
    const currentValue = select.value;
    
    // Valor do imóvel em edição (se houver)
    const editingTypeId = @json($property->type_id ?? null);
    
    // Limpa opções existentes (exceto a primeira)
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    // Adiciona novos tipos
    types.forEach(type => {
        if (type.active) {
            const option = document.createElement('option');
            option.value = type.id;
            option.textContent = type.name;
            
            // Marca como selecionado se for o tipo do imóvel em edição ou o valor atual
            if (editingTypeId && editingTypeId == type.id) {
                option.selected = true;
            } else if (currentValue == type.id) {
                option.selected = true;
            }
            
            select.appendChild(option);
        }
    });
}

// Função para editar tipo
window.editType = async function(typeId) {
    try {
        const response = await fetch(`/dashboard/types/${typeId}`);
        const type = await response.json();
        
        document.getElementById('type-name').value = type.name;
        document.getElementById('type-description').value = type.description || '';
        document.getElementById('type-active').checked = type.active;
        
        window.currentEditingTypeId = typeId;
        
        // Atualiza o botão do formulário
        const submitBtn = document.querySelector('#type-form button[type="submit"]');
        submitBtn.innerHTML = 'Atualizar Tipo';
        submitBtn.className = 'btn btn-primary';
        
    } catch (error) {
        console.error('Erro ao carregar tipo:', error);
        showAlert('Erro ao carregar dados do tipo!', 'danger');
    }
}

// Função para cancelar edição
window.cancelEditType = function() {
    window.currentEditingTypeId = null;
    document.getElementById('type-form').reset();
    document.getElementById('type-active').checked = true;
    
    // Restaura o botão do formulário
    const submitBtn = document.querySelector('#type-form button[type="submit"]');
    submitBtn.innerHTML = 'Adicionar Tipo';
    submitBtn.className = 'btn btn-primary';
}

// Função para excluir tipo
window.deleteType = async function(typeId) {
    if (!confirm('Tem certeza que deseja excluir este tipo?')) {
        return;
    }
    
    try {
        const response = await fetch(`/dashboard/types/${typeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}"
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            loadTypes(); // Recarrega a lista
        } else {
            showAlert(result.message, 'danger');
        }
    } catch (error) {
        console.error('Erro ao excluir tipo:', error);
        showAlert('Erro ao excluir tipo!', 'danger');
    }
}

// Função para mostrar alertas
function showAlert(message, type = 'info') {
    // Cria um toast usando o Tabler
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Adiciona o toast ao topo da página
    document.body.insertBefore(toast, document.body.firstChild);
    
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Carrega os tipos quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    // Carrega os tipos para popular o select inicial
    window.loadTypes();
    
    // Event listeners para o modal
    const modal = document.getElementById('typesModal');
    
    // Event listener para quando o modal Bootstrap for fechado
    modal.addEventListener('hidden.bs.modal', function() {
        // Força limpeza completa após o Bootstrap fechar o modal
        window.closeTypesModal();
    });
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            window.closeTypesModal();
        }
    });
    
    // Fechar modal clicando no backdrop
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            window.closeTypesModal();
        }
    });
    
    // Event listener para o formulário de tipo
    document.getElementById('type-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Formulário submetido!');
        
        const formData = new FormData(e.target);
        const data = {
            name: formData.get('name'),
            description: formData.get('description'),
            active: formData.has('active')
        };
        
        console.log('Dados a serem enviados:', data);
        
        try {
            let response;
            if (window.currentEditingTypeId) {
                console.log('Atualizando tipo existente:', window.currentEditingTypeId);
                // Atualizar tipo existente
                response = await fetch(`/dashboard/types/${window.currentEditingTypeId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });
            } else {
                console.log('Criando novo tipo');
                // Criar novo tipo
                response = await fetch('/dashboard/types', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });
            }
            
            console.log('Response status:', response.status);
            const result = await response.json();
            console.log('Response data:', result);
            
            if (result.success) {
                showAlert(result.message, 'success');
                window.cancelEditType(); // Limpa o formulário
                window.loadTypes(); // Recarrega a lista
            } else {
                showAlert(result.message || 'Erro ao processar solicitação!', 'danger');
            }
        } catch (error) {
            console.error('Erro ao salvar tipo:', error);
            showAlert('Erro ao salvar tipo!', 'danger');
        }
    });
});
</script>

{{-- Modal para gerenciar tipos de imóveis --}}
<div class="modal modal-blur fade" id="typesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Tipos de Imóveis</h5>
                <button type="button" class="btn-close" onclick="window.closeTypesModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Formulário para adicionar novo tipo --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Adicionar Novo Tipo</label>
                    <form id="type-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Nome do Tipo</label>
                                <input type="text" name="name" id="type-name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="active" id="type-active" checked>
                                    <label class="form-check-label" for="type-active">Ativo</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="description" id="type-description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3 text-end">
                            <button type="button" class="btn btn-secondary" onclick="window.cancelEditType()">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                Adicionar Tipo
                            </button>
                        </div>
                    </form>
                </div>

                <hr>

                {{-- Lista de tipos existentes --}}
                <div>
                    <label class="form-label fw-bold">Tipos Existentes</label>
                    <div id="types-loading" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                    <div id="types-list" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-sm table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Status</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="types-table-body">
                                    {{-- Tipos serão carregados via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endpush