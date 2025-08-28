@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Atendimentos') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Atendimento') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($attendances as $attendance)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar avatar-1 rounded-circle">
                                JM <small class="badge bg-danger text-white">{{ $attendance->history_count }}</small>
                            </span>
                        </div>
                        
                        {{-- Informações principais: Código, Status e Endereço --}}
                        <div class="col text-truncate">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fw-bold me-2">{{ $attendance->customer->name }}</span>
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
                            <span class="badge {{ $statusColors[$attendance->status] ?? 'bg-secondary' }}-lt">{{ $attendance->status }}</span>
                            </div>
                            <div class="text-muted text-truncate">
                                {{$attendance->updated_at->diffForHumans()}} - <small>{{ str()->limit($attendance->notes, 50) ?? '-' }}</small>
                            </div>
                        </div>
                        
                        {{-- Composição e Dimensões (visível em telas grandes, oculta em pequenas) --}}
                        <div class="col-auto text-muted d-none d-md-block">
                            <div class="d-flex flex-wrap gap-2">
                                @if(isset($attendance->compositions['bedrooms']))
                                    <span>{{ $attendance->compositions['bedrooms'] }} Quartos</span>
                                @endif
                                @if(isset($attendance->compositions['bathrooms']))
                                    <span>{{ $attendance->compositions['bathrooms'] }} Banheiros</span>
                                @endif
                                @if(isset($attendance->compositions['parking_spots']))
                                    <span>{{ $attendance->compositions['parking_spots'] }} Vagas</span>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @if(isset($attendance->dimensions['usable_area']))
                                    <span>Área Útil: {{ $attendance->dimensions['usable_area'] }} m²</span>
                                @endif
                                @if(isset($attendance->dimensions['total_area']))
                                    <span>Área Total: {{ $attendance->dimensions['total_area'] }} m²</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Preços (visível em telas grandes, oculta em pequenas) --}}
                        <div class="col-auto text-muted d-none d-lg-block">
                            <div class="d-flex flex-column gap-1">
                                @if(isset($attendance->business_options['sale']['price']))
                                    <div class="text-truncate">Venda: <strong>R$ {{ number_format($attendance->business_options['sale']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                                @if(isset($attendance->business_options['rental']['price']))
                                    <div class="text-truncate">Locação: <strong>R$ {{ number_format($attendance->business_options['rental']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                                @if(isset($attendance->business_options['season']['price']))
                                    <div class="text-truncate">Temporada: <strong>R$ {{ number_format($attendance->business_options['season']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Botões de ação (sempre visíveis) --}}
                        <div class="col-auto ms-auto d-flex flex-wrap flex-sm-nowrap gap-2">
                            <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-sm">{{ __('Histórico') }}</a>
                            <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm">{{ __('Atualizar') }}</a>
                            <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este atendimento?') }}');" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100">{{ __('Excluir') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="list-group-item">
                        <div class="text-center text-muted py-5">
                            {{ __('Nenhum imóvel encontrado.') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

{{--@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Atendimentos') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Atendimento') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($attendances as $attendance)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        {{-- Avatar e Informações Principais 
                        <div class="col-6 col-md-3 d-flex align-items-center">
                            <span class="avatar avatar-md rounded bg-light-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 21v-2a4 4 0 0 0 -4 -4h-4a4 4 0 0 0 -4 4v2" /><circle cx="8" cy="7" r="4" /><path d="M17 11h4v4h-4z" /><path d="M17 11v-4a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v4" /></svg>
                            </span>
                            <div class="flex-fill">
                                <span class="fw-bold d-block">{{ $attendance->customer->name }}</span>
                                <span class="text-muted d-block">{{ $attendance->property->code ?? 'Sem imóvel' }}</span>
                            </div>
                        </div>

                        {{-- Notas e Andamentos 
                        <div class="col-12 col-md-4 d-none d-md-block text-truncate">
                            <div class="text-muted">
                                {{ Str::limit($attendance->notes, 50) ?? '-' }}
                            </div>
                            <div class="text-muted mt-1">
                                <span class="badge bg-secondary-lt me-2">{{ $attendance->history_count }} andamentos</span>
                            </div>
                        </div>
                        
                        {{-- Status e Data 
                        <div class="col-auto d-none d-lg-block text-center">
                            
                            <br>
                            <small class="text-muted mt-1">{{ \Carbon\Carbon::parse($attendance->attended_at)->format('d/m/Y') ?? 'N/A' }}</small>
                        </div>

                        {{-- Botões de Ação 
                        <div class="col-6 col-md-3 ms-auto d-flex justify-content-end align-items-center">
                            <div class="btn-list flex-nowrap">
                                
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="list-group-item">
                        <div class="text-center text-muted py-5">
                            {{ __('Nenhum atendimento encontrado.') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection--}}