@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Controle de Pagamentos') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('payments.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Pagamento') }}
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
                            <th>{{ __('Contrato') }}</th>
                            <th>{{ __('Inquilino') }}</th>
                            <th>{{ __('Imóvel') }}</th>
                            <th>{{ __('Valor') }}</th>
                            <th>{{ __('Data do Pagamento') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        <tr>
                            <td>#{{ $payment->lease->id }}</td>
                            <td>{{ $payment->lease->renter->name }}</td>
                            <td>{{ $payment->lease->property->address }}</td>
                            <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td>{{ $payment->payment_date }}</td>
                            <td><span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'danger' }}-lt">{{ ucfirst($payment->status) }}</span></td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm">{{ __('Editar') }}</a>
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este pagamento?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Excluir') }}</button>
                                    </form>
                                </div>
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