@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Configurações do Sistema') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <form action="{{ route('tenant.dashboard.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-0">
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader">{{ __('Etapas da Configuração') }}</h4>
                            <div id="form-nav" class="list-group list-group-transparent">
                                <a href="#step-1" class="list-group-item list-group-item-action d-flex align-items-center active" data-step="0">{{ __('1. Dados Básicos') }}</a>
                                <a href="#step-2" class="list-group-item list-group-item-action d-flex align-items-center" data-step="1">{{ __('2. Contato') }}</a>
                                <a href="#step-3" class="list-group-item list-group-item-action d-flex align-items-center" data-step="2">{{ __('3. Endereço e Horários') }}</a>
                                <a href="#step-4" class="list-group-item list-group-item-action d-flex align-items-center" data-step="3">{{ __('4. Modelo de Site') }}</a>
                                <a href="#step-5" class="list-group-item list-group-item-action d-flex align-items-center" data-step="4">{{ __('5. Confirmação') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <div class="card-body">
                            <div class="tab-content" id="form-steps">
                                <div class="tab-pane active" id="step-1" role="tabpanel">
                                    <h3 class="card-title">{{ __('Dados Básicos') }}</h3>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tenant->name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $tenant->email) }}" required>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step-2" role="tabpanel">
                                    <h3 class="card-title">{{ __('Contato') }}</h3>
                                    <div class="mb-3">
                                        <label class="form-label">Telefone(s)</label>
                                        <div id="phones-list">
                                            @php
                                                $phones = old('phones', isset($tenant->phones) ? $tenant->phones : []);
                                                if (!is_array($phones)) $phones = [$phones];
                                            @endphp
                                            @foreach($phones as $idx => $phone)
                                                <div class="input-group mb-2 phone-group">
                                                    <input type="text" name="phones[]" class="form-control" value="{{ is_array($phone) ? $phone['number'] ?? '' : $phone }}" placeholder="(99) 99999-9999">
                                                    <span class="input-group-text">
                                                        <input type="checkbox" name="phones_whatsapp[]" value="{{ $idx }}" {{ (is_array($phone) && !empty($phone['whatsapp'])) ? 'checked' : '' }}> WhatsApp
                                                    </span>
                                                    <button type="button" class="btn btn-outline-danger remove-phone">&times;</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-outline-primary" id="add-phone">Adicionar Telefone</button>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Redes Sociais</label>
                                        <input type="text" name="social[facebook]" class="form-control mb-2" value="{{ old('social.facebook', $tenant->social['facebook'] ?? '') }}" placeholder="Facebook">
                                        <input type="text" name="social[instagram]" class="form-control mb-2" value="{{ old('social.instagram', $tenant->social['instagram'] ?? '') }}" placeholder="Instagram">
                                        <input type="text" name="social[linkedin]" class="form-control mb-2" value="{{ old('social.linkedin', $tenant->social['linkedin'] ?? '') }}" placeholder="LinkedIn">
                                        <input type="text" name="social[youtube]" class="form-control mb-2" value="{{ old('social.youtube', $tenant->social['youtube'] ?? '') }}" placeholder="YouTube">
                                    </div>
                                </div>
                                <div class="tab-pane" id="step-3" role="tabpanel">
                                    <h3 class="card-title">{{ __('Endereço e Horários de Atendimento') }}</h3>
                                    <div class="mb-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input type="text" name="address[cep]" id="cep" class="form-control" value="{{ old('address.cep', $tenant->address['cep'] ?? '') }}" placeholder="Digite o CEP">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="address" class="form-label">Endereço</label>
                                            <input type="text" name="address[street]" id="address" class="form-control" value="{{ old('address.street', $tenant->address['street'] ?? '') }}" placeholder="Rua">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="number" class="form-label">Número</label>
                                            <input type="text" name="address[number]" id="number" class="form-control" value="{{ old('address.number', $tenant->address['number'] ?? '') }}" placeholder="Número">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label for="neighborhood" class="form-label">Bairro</label>
                                            <input type="text" name="address[neighborhood]" id="neighborhood" class="form-control" value="{{ old('address.neighborhood', $tenant->address['neighborhood'] ?? '') }}" placeholder="Bairro">
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="city" class="form-label">Cidade</label>
                                            <input type="text" name="address[city]" id="city" class="form-control" value="{{ old('address.city', $tenant->address['city'] ?? '') }}" placeholder="Cidade">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="state" class="form-label">Estado</label>
                                            <input type="text" name="address[state]" id="state" class="form-control" value="{{ old('address.state', $tenant->address['state'] ?? '') }}" placeholder="UF">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Horários de Atendimento</label>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Dia</th>
                                                        <th>Atende?</th>
                                                        <th>Horário Inicial</th>
                                                        <th>Horário Final</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $weekdays = [
                                                            'monday' => 'Segunda',
                                                            'tuesday' => 'Terça',
                                                            'wednesday' => 'Quarta',
                                                            'thursday' => 'Quinta',
                                                            'friday' => 'Sexta',
                                                            'saturday' => 'Sábado',
                                                            'sunday' => 'Domingo',
                                                        ];
                                                        $hours = old('business_hours', $tenant->business_hours ?? []);
                                                    @endphp
                                                    @foreach($weekdays as $key => $label)
                                                    <tr>
                                                        <td>{{ $label }}</td>
                                                        <td>
                                                            <input type="checkbox" name="business_hours[{{ $key }}][enabled]" value="1" class="weekday-check" data-day="{{ $key }}" {{ (isset($hours[$key]['enabled']) && $hours[$key]['enabled']) ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="time" name="business_hours[{{ $key }}][start]" class="form-control weekday-start" data-day="{{ $key }}" value="{{ $hours[$key]['start'] ?? '' }}" {{ (isset($hours[$key]['enabled']) && $hours[$key]['enabled']) ? '' : 'disabled' }}>
                                                        </td>
                                                        <td>
                                                            <input type="time" name="business_hours[{{ $key }}][end]" class="form-control weekday-end" data-day="{{ $key }}" value="{{ $hours[$key]['end'] ?? '' }}" {{ (isset($hours[$key]['enabled']) && $hours[$key]['enabled']) ? '' : 'disabled' }}>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step-4" role="tabpanel">
                                    <h3 class="card-title">{{ __('Modelo de Site') }}</h3>
                                    <div class="mb-3">
                                        <label class="form-label">Selecione o modelo de site</label>
                                        <div class="row" id="theme-cards">
                                            @foreach($themes as $theme)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card h-100 {{ old('theme', $tenant->theme ?? '') == $theme['name'] ? 'border-primary' : '' }}">
                                                        <img src="{{ $theme['thumbnail'] }}" class="card-img-top" alt="{{ $theme['name'] }}">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $theme['name'] }}</h5>
                                                            <p class="card-text">{{ $theme['description'] }}</p>
                                                            <a href="{{ $theme['preview'] }}" target="_blank" class="btn btn-outline-secondary btn-sm mb-2">Preview</a>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="theme" id="theme-{{ $theme['name'] }}" value="{{ $theme['name'] }}"
                                                                    {{ old('theme', $tenant->theme ?? '') == $theme['name'] ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="theme-{{ $theme['name'] }}">
                                                                    Selecionar
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="maintenance_mode">Modo manutenção</label>
                                            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $tenant->maintenance_mode ? true : false) ? 'checked' : '' }}>
                                            <span>Marque para ativar o modo manutenção do site.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step-5" role="tabpanel">
                                    <h3 class="card-title">{{ __('Confirmação') }}</h3>
                                    <p>Revise os dados antes de salvar.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent mt-auto">
                            <div class="btn-list justify-content-end">
                                <button type="button" class="btn btn-secondary" id="prev-step" style="display:none;">Anterior</button>
                                <div>
                                    <button type="button" class="btn btn-primary" id="next-step">Próximo</button>
                                    <button type="submit" class="btn btn-success" id="save-btn" style="display:none;">Salvar</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary" id="cancel-btn" style="display:none;">Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    initStepNavigation('form-nav', 'form-steps', 'prev-step', 'next-step', 'save-btn', 'cancel-btn');
    initCepLookup('cep', 'address', 'neighborhood', 'city', 'state');
        // Telefones dinâmicos
        const phonesList = document.getElementById('phones-list');
        const addPhoneBtn = document.getElementById('add-phone');
        if (addPhoneBtn && phonesList) {
            addPhoneBtn.addEventListener('click', function () {
                const idx = phonesList.querySelectorAll('.phone-group').length;
                const group = document.createElement('div');
                group.className = 'input-group mb-2 phone-group';
                group.innerHTML = `<input type="text" name="phones[]" class="form-control" placeholder="(99) 99999-9999">
                    <span class="input-group-text">
                        <input type="checkbox" name="phones_whatsapp[]" value="${idx}"> WhatsApp
                    </span>
                    <button type="button" class="btn btn-outline-danger remove-phone">&times;</button>`;
                phonesList.appendChild(group);
            });
            phonesList.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-phone')) {
                    e.target.parentElement.remove();
                }
            });
        }
        // Habilita/desabilita horários conforme checkbox
        document.querySelectorAll('.weekday-check').forEach(function(check) {
            check.addEventListener('change', function() {
                const day = this.getAttribute('data-day');
                const startInput = document.querySelector('input.weekday-start[data-day="' + day + '"]');
                const endInput = document.querySelector('input.weekday-end[data-day="' + day + '"]');
                if (this.checked) {
                    startInput.removeAttribute('disabled');
                    endInput.removeAttribute('disabled');
                } else {
                    startInput.setAttribute('disabled', 'disabled');
                    endInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });
</script>
@endpush
@endsection