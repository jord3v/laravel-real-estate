@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Painel de Controle') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#tab-overview" role="tab" aria-selected="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M13.45 11.55l2.05 -2.05"/><path d="M6.4 20a9 9 0 1 1 11.2 0z"/></svg>
                            {{ __('Visão Geral') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-funnel" role="tab" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-funnel"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.387 3h15.226a1 1 0 0 1 .948 1.316l-5.105 15.316a2 2 0 0 1 -1.898 1.368h-3.116a2 2 0 0 1 -1.898 -1.368l-5.104 -15.316a1 1 0 0 1 .947 -1.316"/><path d="M5 9h14"/><path d="M7 15h10"/></svg>
                            {{ __('Funil de Atendimento') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-cashflow" role="tab" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>
                            {{ __('Fluxo de Caixa') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-recent" role="tab" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0 -18 0"/><path d="M12 7v5l3 3"/></svg>
                            {{ __('Atividades Recentes') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    
                    {{-- 1ª Aba: Visão Geral --}}
                    <div class="tab-pane active show" id="tab-overview" role="tabpanel">
                        <div class="row row-deck row-cards mb-4">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M13.45 11.55l2.05 -2.05"/><path d="M6.4 20a9 9 0 1 1 11.2 0z"/></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">{{ $totalProperties }} {{ __('Imóveis') }}</div>
                                                <div class="text-muted">{{ __('Total na carteira') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-green text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-contract"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 21h-2a3 3 0 0 1 -3 -3v-1h5.5"/><path d="M17 8.5v-3.5a2 2 0 1 1 2 2h-2"/><path d="M19 3h-11a3 3 0 0 0 -3 3v11"/><path d="M9 7h4"/><path d="M9 11h4"/><path d="M18.42 12.61a2.1 2.1 0 0 1 2.97 2.97l-6.39 6.42h-3v-3z"/></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">{{ $totalLeases }} {{ __('Contratos') }}</div>
                                                <div class="text-muted">{{ $activeLeases }} {{ __('ativos') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-azure text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-headset"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 14v-3a8 8 0 1 1 16 0v3"/><path d="M18 19c0 1.657 -2.686 3 -6 3"/><path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"/><path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"/></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">{{ $totalAttendances }} {{ __('Atendimentos') }}</div>
                                                <div class="text-muted">{{ $totalLeads }} {{ __('novos leads') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-yellow text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">{{ $totalPayments }} {{ __('Pagamentos') }}</div>
                                                <div class="text-muted">{{ $totalPaidPayments }} {{ __('pagos') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 2: Funil de Atendimento --}}
                    <div class="tab-pane" id="tab-funnel" role="tabpanel">
                        <div class="card card-stacked mb-4">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Progresso do Funil') }}</h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">
                                    {{ __('Atendimentos totais') }}: <strong>{{ $totalAttendances }}</strong>
                                </p>
                                <div class="progress progress-separated mb-3">
                                    @php
                                        $statusColors = [
                                            'Novo Contato' => 'bg-primary',
                                            'Em Contato' => 'bg-info',
                                            'Visita Agendada' => 'bg-azure',
                                            'Proposta Enviada' => 'bg-warning',
                                            'Fechado' => 'bg-success',
                                            'Perdido' => 'bg-danger',
                                            'Retornar Contato' => 'bg-orange',
                                        ];
                                    @endphp
                                    @foreach ($funnelData as $status => $count)
                                        @php
                                            $percentage = $totalAttendances > 0 ? ($count / $totalAttendances) * 100 : 0;
                                            $color = $statusColors[$status] ?? 'bg-secondary';
                                        @endphp
                                        <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ round($percentage) }}%" aria-label="{{ ucfirst($status) }}" data-bs-toggle="tooltip" title="{{ ucfirst($status) }}: {{ $count }}"></div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    @foreach ($funnelData as $status => $count)
                                        @php
                                            $color = $statusColors[$status] ?? 'bg-secondary';
                                        @endphp
                                        <div class="col-auto d-flex align-items-center pe-2">
                                            <span class="legend me-2 {{ $color }}"></span>
                                            <span>{{ ucfirst($status) }}</span>
                                            <span class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-secondary">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 3: Fluxo de Caixa --}}
                    <div class="tab-pane" id="tab-cashflow" role="tabpanel">
                        <div class="row row-deck row-cards mb-4">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-primary text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 20v-8m0 -8v8m-4 4h8" /><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    R$ {{ number_format($balance, 2, ',', '.') }}
                                                </div>
                                                <div class="text-muted">
                                                    {{ __('Saldo Atual') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-green text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17v-13m0 12l-3 -3m3 3l3 -3" /><path d="M5 21h14" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    R$ {{ number_format($totalReceivable, 2, ',', '.') }}
                                                </div>
                                                <div class="text-muted">
                                                    {{ __('Total a Receber') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-red text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 4v13m0 -12l-3 3m3 -3l3 3" /><path d="M5 21h14" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    R$ {{ number_format($totalPayable, 2, ',', '.') }}
                                                </div>
                                                <div class="text-muted">
                                                    {{ __('Total a Pagar') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="bg-yellow text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 12v-6m-2 4l2 2l2 -2" /></svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    {{ round($defaultRate, 2) }}%
                                                </div>
                                                <div class="text-muted">
                                                    {{ __('Taxa de Inadimplência') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-deck row-cards mb-4">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Fluxo de Caixa Mensal') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 250px;"><canvas id="cashFlowChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Distribuição de Despesas') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 250px;"><canvas id="expenseDistributionChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 2: Funil de Atendimento --}}
                    <div class="tab-pane" id="tab-funnel" role="tabpanel">
                        <div class="card card-stacked mb-4">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Progresso do Funil') }}</h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">
                                    {{ __('Atendimentos totais') }}: <strong>{{ $totalAttendances }}</strong>
                                </p>
                                <div class="progress progress-separated mb-3">
                                    @php
                                        $statusColors = [
                                            'Novo Contato' => 'bg-primary',
                                            'Em Contato' => 'bg-info',
                                            'Visita Agendada' => 'bg-azure',
                                            'Proposta Enviada' => 'bg-warning',
                                            'Fechado' => 'bg-success',
                                            'Perdido' => 'bg-danger',
                                            'Retornar Contato' => 'bg-orange',
                                        ];
                                    @endphp
                                    @foreach ($funnelData as $status => $count)
                                        @php
                                            $percentage = $totalAttendances > 0 ? ($count / $totalAttendances) * 100 : 0;
                                            $color = $statusColors[$status] ?? 'bg-secondary';
                                        @endphp
                                        <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ round($percentage) }}%" aria-label="{{ ucfirst($status) }}" data-bs-toggle="tooltip" title="{{ ucfirst($status) }}: {{ $count }}"></div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    @foreach ($funnelData as $status => $count)
                                        @php
                                            $color = $statusColors[$status] ?? 'bg-secondary';
                                        @endphp
                                        <div class="col-auto d-flex align-items-center pe-2">
                                            <span class="legend me-2 {{ $color }}"></span>
                                            <span>{{ ucfirst($status) }}</span>
                                            <span class="d-none d-md-inline d-lg-none d-xxl-inline ms-2 text-secondary">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 3: Fluxo de Caixa --}}
                    <div class="tab-pane" id="tab-cashflow" role="tabpanel">
                        <div class="row row-deck row-cards mb-4">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Fluxo de Caixa Mensal') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 250px;"><canvas id="cashFlowChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Distribuição de Despesas') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 250px;"><canvas id="expenseDistributionChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aba 4: Atividades Recentes --}}
                    <div class="tab-pane" id="tab-recent" role="tabpanel">
                        <div class="row row-deck row-cards">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Vencimentos Próximos') }}</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Data Venc.') }}</th>
                                                    <th>{{ __('Valor') }}</th>
                                                    <th>{{ __('Tipo') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($upcomingPayments as $payment)
                                                    <tr>
                                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                        <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                                        <td><span class="badge bg-{{ $payment->type == 'income' ? 'success' : 'danger' }}-lt">{{ ucfirst($payment->type) }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            {{ __('Nenhum pagamento próximo.') }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Ranking Financeiro (Top 5)') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-lg-6">
                                                <strong>{{ __('Maiores Receitas') }}</strong>
                                                <ul class="list-group list-group-flush list-group-sm">
                                                    @forelse($topIncomes as $income)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>{{ $income->category }}</span>
                                                            <span class="badge bg-success-lt">R$ {{ number_format($income->amount, 2, ',', '.') }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item text-center text-muted">
                                                            {{ __('Nenhuma receita encontrada.') }}
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <strong>{{ __('Maiores Despesas') }}</strong>
                                                <ul class="list-group list-group-flush list-group-sm">
                                                    @forelse($topExpenses as $expense)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>{{ $expense->category }}</span>
                                                            <span class="badge bg-danger-lt">R$ {{ number_format($expense->amount, 2, ',', '.') }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item text-center text-muted">
                                                            {{ __('Nenhuma despesa encontrada.') }}
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Gráfico de Barras: Fluxo de Caixa Mensal
        const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
        const cashFlowLabels = @json($cashFlowLabels);
        const incomeValues = @json($incomeValues);
        const expenseValues = @json($expenseValues);

        new Chart(cashFlowCtx, {
            type: 'bar',
            data: {
                labels: cashFlowLabels,
                datasets: [
                    {
                        label: 'Entradas (R$)',
                        data: incomeValues,
                        backgroundColor: 'rgba(52, 211, 153, 0.6)',
                        borderColor: 'rgba(52, 211, 153, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'Saídas (R$)',
                        data: expenseValues,
                        backgroundColor: 'rgba(251, 113, 133, 0.6)',
                        borderColor: 'rgba(251, 113, 133, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        beginAtZero: true,
                        stacked: false
                    }
                }
            }
        });

        // Gráfico de Pizza: Distribuição de Despesas
        const expenseDistributionCtx = document.getElementById('expenseDistributionChart').getContext('2d');
        const pieLabels = @json($pieChartLabels);
        const pieData = @json($pieChartData);

        new Chart(expenseDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [
                    {
                        data: pieData,
                        backgroundColor: [
                            '#fd7e14', '#206bc4', '#42b882', '#f76707', '#d63939', '#6610f2',
                        ],
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw) {
                                    label += `R$ ${parseFloat(context.raw).toFixed(2).replace('.', ',')}`;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
@endpush
@endsection