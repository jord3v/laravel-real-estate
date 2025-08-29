<div class="row g-0">
    <div class="col-12 col-md-3 border-end">
        <div class="card-body">
            <h4 class="subheader">{{ __('Etapas do Cadastro') }}</h4>
            <div id="form-nav" class="list-group list-group-transparent">
                <a href="#step-1" class="list-group-item list-group-item-action d-flex align-items-center active" data-step="0">{{ __('1. Dados Principais') }}</a>
                <a href="#step-2" class="list-group-item list-group-item-action d-flex align-items-center" data-step="1">{{ __('2. Documentação') }}</a>
                <a href="#step-3" class="list-group-item list-group-item-action d-flex align-items-center" data-step="2">{{ __('3. Detalhes Adicionais') }}</a>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-9 d-flex flex-column">
        <div class="card-body">
            <div class="tab-content" id="form-steps">
                
                {{-- Passo 1: Dados Principais --}}
                <div class="tab-pane active" id="step-1" role="tabpanel">
                    <h3 class="card-title">{{ __('Dados Principais') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Nome') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('E-mail') }}</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email ?? '') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Telefone') }}</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone ?? '') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Tipo de Cliente') }}</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o tipo') }}</option>
                            <option value="lessor" {{ old('type', $customer->type ?? '') == 'lessor' ? 'selected' : '' }}>{{ __('Locador') }}</option>
                            <option value="lessee" {{ old('type', $customer->type ?? '') == 'lessee' ? 'selected' : '' }}>{{ __('Locatário') }}</option>
                            <option value="guarantor" {{ old('type', $customer->type ?? '') == 'guarantor' ? 'selected' : '' }}>{{ __('Fiador') }}</option>
                            <option value="prospect" {{ old('type', $customer->type ?? '') == 'prospect' ? 'selected' : '' }}>{{ __('Prospect') }}</option>
                            <option value="supplier" {{ old('type', $customer->type ?? '') == 'supplier' ? 'selected' : '' }}>{{ __('Fornecedor') }}</option>
                            <option value="administrator" {{ old('type', $customer->type ?? '') == 'administrator' ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Passo 2: Documentação --}}
                <div class="tab-pane" id="step-2" role="tabpanel">
                    <h3 class="card-title">{{ __('Documentação') }}</h3>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Tipo de Pessoa') }}</label>
                        <select name="document_type" id="document-type-select" class="form-select @error('document_type') is-invalid @enderror" required>
                            <option value="pf" {{ old('document_type', $customer->document_type ?? 'pf') == 'pf' ? 'selected' : '' }}>{{ __('Pessoa Física (CPF)') }}</option>
                            <option value="pj" {{ old('document_type', $customer->document_type ?? 'pf') == 'pj' ? 'selected' : '' }}>{{ __('Pessoa Jurídica (CNPJ)') }}</option>
                        </select>
                        @error('document_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Campos para Pessoa Física --}}
                    <div id="pf-fields" class="row {{ old('document_type', $customer->document_type ?? 'pf') == 'pf' ? '' : 'd-none' }}">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('CPF') }}</label>
                            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf', $customer->cpf ?? '') }}">
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('RG') }}</label>
                            <input type="text" name="rg" class="form-control @error('rg') is-invalid @enderror" value="{{ old('rg', $customer->rg ?? '') }}">
                            @error('rg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Campos para Pessoa Jurídica --}}
                    <div id="pj-fields" class="row {{ old('document_type', $customer->document_type ?? 'pf') == 'pj' ? '' : 'd-none' }}">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Nome da Empresa') }}</label>
                            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $customer->company_name ?? '') }}">
                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('CNPJ') }}</label>
                            <input type="text" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj', $customer->cnpj ?? '') }}">
                            @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Passo 3: Detalhes Adicionais (PF) --}}
                <div class="tab-pane" id="step-3" role="tabpanel">
                    <h3 class="card-title">{{ __('Detalhes Adicionais') }}</h3>
                    <div id="pf-extra-fields" class="{{ old('document_type', $customer->document_type ?? 'pf') == 'pf' ? '' : 'd-none' }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Estado Civil') }}</label>
                            <input type="text" name="marital_status" class="form-control @error('marital_status') is-invalid @enderror" value="{{ old('marital_status', $customer->marital_status ?? '') }}">
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Profissão') }}</label>
                            <input type="text" name="profession" class="form-control @error('profession') is-invalid @enderror" value="{{ old('profession', $customer->profession ?? '') }}">
                            @error('profession')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Nacionalidade') }}</label>
                            <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality', $customer->nationality ?? '') }}">
                            @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <h4 class="mt-4">{{ __('Dados do Cônjuge') }}</h4>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Nome do Cônjuge') }}</label>
                            <input type="text" name="spouse_name" class="form-control @error('spouse_name') is-invalid @enderror" value="{{ old('spouse_name', $customer->spouse_name ?? '') }}">
                            @error('spouse_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-transparent mt-auto">
            <div class="btn-list justify-content-end">
                <button type="button" class="btn btn-secondary" id="prev-step" style="display: none;">{{ __('Anterior') }}</button>
                <div>
                    <button type="button" class="btn btn-primary" id="next-step">{{ __('Próximo') }}</button>
                    <button type="submit" class="btn btn-primary" id="save-customer" style="display: none;">{{ __('Salvar Cliente') }}</button>
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
        const saveBtn = document.getElementById('save-customer');

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

        // Lógica para mostrar/esconder campos de Pessoa Física/Jurídica
        const documentTypeSelect = document.getElementById('document-type-select');
        const pfFields = document.getElementById('pf-fields');
        const pjFields = document.getElementById('pj-fields');
        const pfExtraFields = document.getElementById('pf-extra-fields');

        function toggleDocumentFields() {
            if (documentTypeSelect.value === 'pj') {
                pfFields.classList.add('d-none');
                pfExtraFields.classList.add('d-none');
                pjFields.classList.remove('d-none');
            } else {
                pfFields.classList.remove('d-none');
                pfExtraFields.classList.remove('d-none');
                pjFields.classList.add('d-none');
            }
        }
        
        documentTypeSelect.addEventListener('change', toggleDocumentFields);
        toggleDocumentFields(); // Garante o estado correto no carregamento da página
    });
</script>
@endpush