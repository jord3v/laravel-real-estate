@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Detalhes do Atendimento') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        {{ __('Atualizar Atendimento') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            {{-- Coluna dos Detalhes do Atendimento --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ __('Informações do Atendimento') }}</h3>
                        <p><strong>{{ __('Cliente:') }}</strong> {{ $attendance->customer->name }}</p>
                        <p><strong>{{ __('Imóvel:') }}</strong> {{ $attendance->property->code ?? 'N/A' }}</p>
                        <p><strong>{{ __('Status Atual:') }}</strong> 
                            <span class="badge bg-secondary-lt">
                                {{ $attendance->status }}
                            </span>
                        </p>
                        <p><strong>{{ __('Data do Atendimento:') }}</strong> {{ \Carbon\Carbon::parse($attendance->attended_at)->format('d/m/Y') }}</p>
                        <p><strong>{{ __('Notas:') }}</strong> {{ $attendance->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Coluna do Histórico de Status (Post-its) --}}
            <div class="col-md-4">
                <h3 class="card-title mb-3">{{ __('Histórico de Status') }}</h3>
                <div class="history-container">
                    @foreach ($attendance->history->sortByDesc('created_at') as $index => $history)
                        @php
                            $rotation = ($index % 2 == 0) ? rand(-2, 2) : rand(-2, 2);
                        @endphp
                        <div class="post-it-card" style="--rotation-deg: {{ $rotation }};">
                            <div class="status-change">
                                @if ($history->old_status && $history->old_status !== $history->new_status)
                                    De <span class="text-muted">{{ ucfirst($history->old_status) }}</span> para
                                @endif
                                <span class="status-new">{{ ucfirst($history->new_status) }}</span>
                            </div>
                            <span class="timestamp">{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}</span>

                            @if ($history->old_notes !== $history->new_notes)
                                <div class="notes-change">
                                    <small>{{ __('Notas alteradas:') }}</small>
                                    @if($history->old_notes)
                                        <small class="text-muted">De: <s>{{ Str::limit($history->old_notes, 50) }}</s></small>
                                    @endif
                                    <strong>{{ Str::limit($history->new_notes, 50) }}</strong>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection