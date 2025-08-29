<div class="row g-0">
    <div class="col-12 col-md-3 border-end">
        <div class="card-body">
            <h4 class="subheader">{{ __('Etapas do Contrato') }}</h4>
            <div id="form-nav" class="list-group list-group-transparent">
                <a href="#step-1" class="list-group-item list-group-item-action d-flex align-items-center active" data-step="0">{{ __('1. Partes e Imóvel') }}</a>
                <a href="#step-2" class="list-group-item list-group-item-action d-flex align-items-center" data-step="1">{{ __('2. Termos da Locação') }}</a>
                <a href="#step-3" class="list-group-item list-group-item-action d-flex align-items-center" data-step="2">{{ __('3. Cláusulas e Obrigações') }}</a>
                <a href="#step-4" class="list-group-item list-group-item-action d-flex align-items-center" data-step="3">{{ __('4. Garantia Locatícia') }}</a>
                <a href="#step-5" class="list-group-item list-group-item-action d-flex align-items-center" data-step="4">{{ __('5. Condições Finais') }}</a>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-9 d-flex flex-column">
        <div class="card-body">
            <div class="tab-content" id="form-steps">
                
                {{-- Passo 1: Partes e Imóvel --}}
                <div class="tab-pane active" id="step-1" role="tabpanel">
                    <h3 class="card-title">{{ __('Partes Envolvidas e Imóvel') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Locador(a)') }}</label>
                        <select name="lessor_id" class="form-select @error('lessor_id') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o locador') }}</option>
                            @foreach ($lessors as $lessor)
                                <option value="{{ $lessor->id }}" {{ old('lessor_id', $lease?->lessor_id) == $lessor->id ? 'selected' : '' }}>{{ $lessor->name }}</option>
                            @endforeach
                        </select>
                        @error('lessor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Locatário(a)') }}</label>
                        <select name="lessee_id" class="form-select @error('lessee_id') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o locatário') }}</option>
                            @foreach ($lessees as $lessee)
                                <option value="{{ $lessee->id }}" {{ old('lessee_id', $lease?->lessee_id) == $lessee->id ? 'selected' : '' }}>{{ $lessee->name }}</option>
                            @endforeach
                        </select>
                        @error('lessee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Imóvel') }}</label>
                        <select name="property_id" class="form-select @error('property_id') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o imóvel') }}</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}" {{ old('property_id', $lease?->property_id) == $property->id ? 'selected' : '' }}>{{ $property->code }} - {{ $property->address['street'] ?? $property->address }}</option>
                            @endforeach
                        </select>
                        @error('property_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Passo 2: Termos da Locação --}}
                <div class="tab-pane" id="step-2" role="tabpanel">
                    <h3 class="card-title">{{ __('Termos da Locação') }}</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Prazo de Locação (meses)') }}</label>
                            <input type="number" name="term_months" class="form-control @error('term_months') is-invalid @enderror" value="{{ old('term_months', $lease?->term_months ?? 30) }}">
                            @error('term_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Data de Início') }}</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $lease?->start_date?->format('Y-m-d') ?? '') }}">
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Valor do Aluguel') }}</label>
                            <input type="text" name="rent_amount" class="form-control @error('rent_amount') is-invalid @enderror" value="{{ old('rent_amount', $lease?->rent_amount ?? '') }}" placeholder="R$ 0.00">
                            @error('rent_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Dia de Vencimento do Aluguel') }}</label>
                            <input type="number" name="due_day" class="form-control @error('due_day') is-invalid @enderror" value="{{ old('due_day', $lease?->due_day ?? 5) }}">
                            @error('due_day')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Local de Pagamento') }}</label>
                        <input type="text" name="payment_place" class="form-control @error('payment_place') is-invalid @enderror" value="{{ old('payment_place', $lease?->payment_place ?? '') }}" placeholder="Ex: São Paulo - SP">
                        @error('payment_place')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Índice de Reajuste Anual') }}</label>
                        <input type="text" name="readjustment_index" class="form-control @error('readjustment_index') is-invalid @enderror" value="{{ old('readjustment_index', $lease?->readjustment_index ?? 'IGP-M') }}">
                        @error('readjustment_index')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Passo 3: Cláusulas e Obrigações --}}
                <div class="tab-pane" id="step-3" role="tabpanel">
                    <h3 class="card-title">{{ __('Cláusulas e Obrigações') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Destinação do Imóvel') }}</label>
                        <input type="text" name="use_destination" class="form-control @error('use_destination') is-invalid @enderror" value="{{ old('use_destination', $lease?->use_destination ?? 'Exclusivamente para residência do(a) LOCATÁRIO(A) e sua família') }}">
                        @error('use_destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Obrigações de Manutenção do Locatário') }}</label>
                        <textarea name="maintenance_obligations" class="form-control @error('maintenance_obligations') is-invalid @enderror" rows="3">{{ old('maintenance_obligations', $lease?->maintenance_obligations ?? 'Manter o imóvel limpo e bem cuidado, pequenos reparos.') }}</textarea>
                        @error('maintenance_obligations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="benfeitorias" id="benfeitorias" value="1" {{ old('benfeitorias', $lease?->benfeitorias ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="benfeitorias">{{ __('Permitir obras ou benfeitorias com anuência do Locador?') }}</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="monetary_correction" id="monetary_correction" value="1" {{ old('monetary_correction', $lease?->monetary_correction ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="monetary_correction">{{ __('Habilitar atualização monetária por atraso?') }}</label>
                    </div>
                </div>
                
                {{-- Passo 4: Garantia Locatícia --}}
                <div class="tab-pane" id="step-4" role="tabpanel">
                    <h3 class="card-title">{{ __('Garantia Locatícia') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Tipo de Garantia') }}</label>
                        <select name="guarantee_type" id="guarantee-type" class="form-select @error('guarantee_type') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione a garantia') }}</option>
                            <option value="fianca" {{ old('guarantee_type', $lease?->guarantee_type ?? '') == 'fianca' ? 'selected' : '' }}>Fiança</option>
                            <option value="caucao_dinheiro" {{ old('guarantee_type', $lease?->guarantee_type ?? '') == 'caucao_dinheiro' ? 'selected' : '' }}>Caução em Dinheiro</option>
                            <option value="seguro_fianca" {{ old('guarantee_type', $lease?->guarantee_type ?? '') == 'seguro_fianca' ? 'selected' : '' }}>Seguro-Fiança</option>
                            <option value="caucao_imobiliaria" {{ old('guarantee_type', $lease?->guarantee_type ?? '') == 'caucao_imobiliaria' ? 'selected' : '' }}>Caução Imobiliária</option>
                        </select>
                        @error('guarantee_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Seção Condicional para Fiador (Fiança) --}}
                    <div id="guarantor-details" class="card card-body mt-3 {{ old('guarantee_type', $lease?->guarantee_type ?? '') == 'fianca' ? '' : 'd-none' }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Fiador(a)') }}</label>
                            <select name="guarantor_id" class="form-select @error('guarantor_id') is-invalid @enderror">
                                <option value="">{{ __('Selecione o fiador') }}</option>
                                @foreach ($guarantors as $guarantor)
                                    <option value="{{ $guarantor->id }}" {{ old('guarantor_id', $lease?->guarantor_id) == $guarantor->id ? 'selected' : '' }}>{{ $guarantor->name }}</option>
                                @endforeach
                            </select>
                            @error('guarantor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Passo 5: Condições Finais --}}
                <div class="tab-pane" id="step-5" role="tabpanel">
                    <h3 class="card-title">{{ __('Condições Finais') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Honorários Advocatícios (%)') }}</label>
                        <input type="number" name="attorney_fees_percent" class="form-control @error('attorney_fees_percent') is-invalid @enderror" value="{{ old('attorney_fees_percent', $lease?->attorney_fees_percent ?? 20) }}">
                        @error('attorney_fees_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Foro Eleito') }}</label>
                        <input type="text" name="elected_forum" class="form-control @error('elected_forum') is-invalid @enderror" value="{{ old('elected_forum', $lease?->elected_forum ?? '') }}">
                        @error('elected_forum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Número de Vias') }}</label>
                        <input type="number" name="via_count" class="form-control @error('via_count') is-invalid @enderror" value="{{ old('via_count', $lease?->via_count ?? 3) }}">
                        @error('via_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent mt-auto">
            <div class="btn-list justify-content-end">
                <button type="button" class="btn btn-secondary" id="prev-step" style="display: none;">{{ __('Anterior') }}</button>
                <div>
                    <button type="button" class="btn btn-primary" id="next-step">{{ __('Próximo') }}</button>
                    <button type="submit" class="btn btn-primary" id="save-contract" style="display: none;">{{ __('Salvar Contrato') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formNav = document.getElementById('form-nav');
        const formSteps = document.getElementById('form-steps');
        const prevBtn = document.getElementById('prev-step');
        const nextBtn = document.getElementById('next-step');
        const saveBtn = document.getElementById('save-contract');

        let currentStep = 0;
        const navLinks = formNav.querySelectorAll('.list-group-item');
        const stepPanes = formSteps.querySelectorAll('.tab-pane');
        const totalSteps = stepPanes.length;

        function updateStep(newStep) {
            navLinks[currentStep].classList.remove('active');
            stepPanes[currentStep].classList.remove('active');

            currentStep = newStep;
            navLinks[currentStep].classList.add('active');
            stepPanes[currentStep].classList.add('active');

            prevBtn.style.display = currentStep > 0 ? 'block' : 'none';
            nextBtn.style.display = currentStep < totalSteps - 1 ? 'block' : 'none';
            saveBtn.style.display = currentStep === totalSteps - 1 ? 'block' : 'none';
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

        const guaranteeTypeSelect = document.getElementById('guarantee-type');
        const guarantorDetails = document.getElementById('guarantor-details');

        function toggleGuarantorDetails() {
            if (guaranteeTypeSelect.value === 'fianca') {
                guarantorDetails.classList.remove('d-none');
            } else {
                guarantorDetails.classList.add('d-none');
            }
        }
        
        guaranteeTypeSelect.addEventListener('change', toggleGuarantorDetails);
        toggleGuarantorDetails();
    });
</script>
@endpush