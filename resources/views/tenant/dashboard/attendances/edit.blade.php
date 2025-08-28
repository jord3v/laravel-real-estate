@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Editar Atendimento') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            {{-- Coluna do Formulário --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('attendances.update', $attendance) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('tenant.dashboard.attendances._form', ['attendance' => $attendance])
                        </form>
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