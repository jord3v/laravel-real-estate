@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Controle de Contratos') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('leases.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Contrato') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('Imóvel') }}</th>
                            <th>{{ __('Inquilino') }}</th>
                            <th>{{ __('Proprietário') }}</th>
                            <th>{{ __('Período') }}</th>
                            <th>{{ __('Valor do Aluguel') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leases as $lease)
                        <tr>
                            <td>{{ $lease->property->address }}</td>
                            <td>{{ $lease->renter->name }}</td>
                            <td>{{ $lease->owner->name }}</td>
                            <td>{{ $lease->start_date }} a {{ $lease->end_date }}</td>
                            <td>R$ {{ number_format($lease->rent_amount, 2, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $lease->status == 'active' ? 'success' : 'danger' }}-lt">{{ ucfirst($lease->status) }}</span></td>
                            <td>
                                {{-- Ações (editar, deletar, etc.) --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection