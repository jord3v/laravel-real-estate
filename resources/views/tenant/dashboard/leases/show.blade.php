@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Detalhes do Contrato') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('leases.edit', $lease) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        {{ __('Editar Contrato') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            {{-- Card de Detalhes do Contrato --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Detalhes do Contrato de Locação') }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>{{ __('Imóvel:') }}</strong> {{ $lease->property->code ?? 'N/A' }}</p>
                        <p><strong>{{ __('Locador:') }}</strong> {{ $lease->lessor->name ?? 'N/A' }}</p>
                        <p><strong>{{ __('Locatário:') }}</strong> {{ $lease->lessee->name ?? 'N/A' }}</p>
                        <hr>
                        <p><strong>{{ __('Valor do Aluguel:') }}</strong> R$ {{ number_format($lease->rent_amount, 2, ',', '.') }}</p>
                        <p><strong>{{ __('Prazo:') }}</strong> {{ $lease->term_months }} meses</p>
                        <p><strong>{{ __('Início:') }}</strong> {{ $lease->start_date->format('d/m/Y') }}</p>
                        <p><strong>{{ __('Término:') }}</strong> {{ $lease->end_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Card de Pagamentos --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Pagamentos do Contrato') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Descrição') }}</th>
                                    <th>{{ __('Valor do Boleto') }}</th>
                                    <th>{{ __('Valor Pago') }}</th>
                                    <th>{{ __('Vencimento') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="w-1">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lease->payments as $payment)
                                <tr>
                                    <td>{{ $payment->description }}</td>
                                    <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($payment->paid_amount)
                                            R$ {{ number_format($payment->paid_amount, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'warning' }}-lt">{{ ucfirst($payment->status) }}</span></td>
                                    <td>
                                        @if ($payment->status !== 'paid')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#receive-payment-modal" data-payment-id="{{ $payment->id }}" data-payment-amount="{{ $payment->amount }}" data-payment-date="{{ $payment->payment_date->format('Y-m-d') }}">
                                                {{ __('Receber') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        {{ __('Nenhum pagamento encontrado para este contrato.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Modal para Receber Pagamento --}}
<div class="modal modal-blur fade" id="receive-payment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="receive-payment-form" action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Receber Pagamento') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Valor a Receber') }}</label>
                        <p class="form-control-plaintext" id="modal-amount-due"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Valor Recebido') }}</label>
                        <input type="number" step="0.01" name="paid_amount" id="modal-paid-amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Data do Pagamento') }}</label>
                        <input type="date" name="paid_at" id="modal-paid-at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Forma de Pagamento') }}</label>
                        <select name="payment_method" id="modal-payment-method" class="form-select" required>
                            <option value="">{{ __('Selecione a forma de pagamento') }}</option>
                            <option value="pix">Pix</option>
                            <option value="boleto">Boleto</option>
                            <option value="cartao_credito">Cartão de Crédito</option>
                            <option value="cartao_debito">Cartão de Débito</option>
                            <option value="transferencia">Transferência Bancária</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Código da Transação/Observação') }}</label>
                        <input type="text" name="transaction_code" id="modal-transaction-code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Observações') }}</label>
                        <textarea name="notes" id="modal-notes" class="form-control" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="amount" id="modal-hidden-amount">
                    <input type="hidden" name="status" value="paid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Confirmar Recebimento') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const receivePaymentModal = document.getElementById('receive-payment-modal');
        const receivePaymentForm = document.getElementById('receive-payment-form');
        const modalAmountDue = document.getElementById('modal-amount-due');
        const modalPaidAmount = document.getElementById('modal-paid-amount');
        const modalPaidAt = document.getElementById('modal-paid-at');
        const modalHiddenAmount = document.getElementById('modal-hidden-amount');

        receivePaymentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const paymentId = button.getAttribute('data-payment-id');
            const paymentAmount = button.getAttribute('data-payment-amount');

            receivePaymentForm.action = `/dashboard/leases/{{ $lease->id }}/payments/${paymentId}/receive`;
            
            modalAmountDue.textContent = `R$ ${parseFloat(paymentAmount).toFixed(2).replace('.', ',')}`;
            modalPaidAmount.value = paymentAmount;
            modalPaidAt.value = '{{ now()->format('Y-m-d') }}';
            modalHiddenAmount.value = paymentAmount;
        });
    });
</script>
@endpush
@endsection