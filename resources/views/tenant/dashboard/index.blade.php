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
        {{-- Widget de Funil com Barra de Progresso --}}
        <div class="card card-stacked mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Funil de Atendimento') }}</h3>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    {{ __('Atendimentos totais') }}: <strong>{{ $totalAttendances }}</strong>
                </p>
                <div class="progress progress-separated mb-3">
                    @php
                        $statusColors = [
                            'Novo Contato' => 'bg-primary',      // Cor azul para o primeiro contato
                            'Em Contato' => 'bg-info',           // Cor azul claro para o contato em andamento
                            'Visita Agendada' => 'bg-azure',     // Cor mais suave para o agendamento
                            'Proposta Enviada' => 'bg-warning',  // Cor de alerta para propostas
                            'Fechado' => 'bg-success',           // Cor verde para sucesso
                            'Perdido' => 'bg-danger',          // Cor vermelha para negócios perdidos
                            'Retornar Contato' => 'bg-orange',  // Cor laranja para follow-up
                        ];
                    @endphp

                    @foreach ($funnelData as $status => $count)
                        @php
                            $percentage = $totalAttendances > 0 ? ($count / $totalAttendances) * 100 : 0;
                            $color = $statusColors[$status] ?? 'bg-secondary';
                        @endphp
                        <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ round($percentage) }}%" aria-label="{{ ucfirst($status) }}"></div>
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
        
        {{-- Seção de Estatísticas Gerais (Cards) --}}
        <div class="row row-deck row-cards mb-4">
            {{-- Card de Imóveis --}}
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $totalProperties }} {{ __('Imóveis') }}
                                </div>
                                <div class="text-muted">
                                    {{ __('Total na carteira') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Card de Contratos Ativos --}}
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="7" x2="10" y2="7" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="12" y1="16" x2="12" y2="16.01" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $activeLeases }} {{ __('Contratos Ativos') }}
                                </div>
                                <div class="text-muted">
                                    {{ $totalLeases }} {{ __('total de contratos') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card de Inquilinos --}}
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-azure text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $totalRenters }} {{ __('Inquilinos') }}
                                </div>
                                <div class="text-muted">
                                    {{ __('Total de pessoas cadastradas') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card de Outras Informações --}}
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-yellow text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ __('Métricas Adicionais') }}
                                </div>
                                <div class="text-muted">
                                    {{ __('Total de itens publicados') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seção de Atividade Recente --}}
        <div class="row row-deck row-cards">
            {{-- Card de Últimos Imóveis --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Últimos Imóveis Adicionados') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Endereço') }}</th>
                                    <th>{{ __('Proprietário') }}</th>
                                    <th>{{ __('Valor') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestProperties as $property)
                                <tr>
                                    <td>
                                        @if(is_array($property->address))
                                            {{ $property->address['street'] ?? '-' }}<br>
                                            <span class="text-muted">{{ ($property->address['city'] ?? '-') . ' - ' . ($property->address['state'] ?? '-') }}</span>
                                        @else
                                            {{ $property->address ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $property->owner->name }}</td>
                                    <td>
                                        @if(isset($property->business_options['sale']['price']))
                                            R$ {{ number_format($property->business_options['sale']['price'], 2, ',', '.') }} (Venda)
                                        @elseif(isset($property->business_options['rental']['price']))
                                            R$ {{ number_format($property->business_options['rental']['price'], 2, ',', '.') }} (Locação)
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Card de Últimos Pagamentos --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Últimos Pagamentos Recebidos') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Inquilino') }}</th>
                                    <th>{{ __('Valor') }}</th>
                                    <th>{{ __('Data') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestPayments as $payment)
                                <tr>
                                    <td>{{ $payment->lease->renter->name }}</td>
                                    <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                    <td>{{ $payment->payment_date}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection