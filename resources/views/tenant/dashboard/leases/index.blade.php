@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Contratos de Locação') }}</h2>
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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($leases as $lease)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        {{-- Identificador e Partes --}}
                        <div class="col-6 col-md-3 d-flex align-items-center">
                            <span class="avatar avatar-lg rounded bg-light-lt me-3 d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="7" x2="10" y2="7" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="12" y1="16" x2="12" y2="16.01" /></svg>
                            </span>
                            <div class="flex-fill">
                                <span class="fw-bold d-block">{{ $lease->property->code ?? 'N/A' }}</span>
                                <span class="text-muted d-block mt-1">Locador: {{ $lease->lessor->name ?? 'N/A' }}</span>
                                <span class="text-muted d-block">Locatário: {{ $lease->lessee->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        {{-- Datas e Valor do Aluguel --}}
                        <div class="col-12 col-md-4 d-none d-md-block text-truncate">
                            <div class="text-muted">
                                <strong>{{ __('Aluguel:') }}</strong> R$ {{ number_format($lease->rent_amount, 2, ',', '.') }}
                            </div>
                            <div class="text-muted mt-1">
                                {{ __('Início:') }} {{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }} |
                                {{ __('Término:') }} {{ \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') }}
                            </div>
                        </div>

                        {{-- Status e Tipo --}}
                        <div class="col-auto text-center d-none d-lg-block">
                            <span class="badge bg-{{ $lease->status == 'active' ? 'success' : 'secondary' }}-lt">{{ ucfirst($lease->status) }}</span>
                            <br>
                            <small class="text-muted mt-1">{{ $lease->contract_type }}</small>
                        </div>
                        
                        {{-- Botões de Ação --}}
                        <div class="col-auto ms-auto d-flex flex-wrap flex-md-nowrap gap-2">
                            <div class="btn-list flex-nowrap">
                                @if ($lease->payments->isEmpty())
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#payment-preview-modal" data-lease-id="{{ $lease->id }}">
                                        {{ __('Gerar Boletos') }}
                                    </button>
                                @else
                                    <a href="{{ route('leases.show', $lease) }}" class="btn btn-sm btn-outline-info">{{ __('Ver Boletos') }}</a>
                                @endif
                                
                                <a href="{{ route('leases.edit', $lease) }}" class="btn btn-sm">{{ __('Editar') }}</a>
                                <form action="{{ route('leases.destroy', $lease) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este contrato?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Excluir') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="list-group-item">
                        <div class="text-center text-muted py-5">
                            {{ __('Nenhum contrato encontrado.') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $leases->links() }}
            </div>
        </div>
    </div>
</div>
{{-- Modal de Pré-visualização de Pagamentos --}}
<div class="modal modal-blur fade" id="payment-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Pré-visualização de Pagamentos') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payment-generation-form" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Confirme os valores e datas de vencimento. Você pode editá-los manualmente antes de gerar.</p>
                    <div class="list-group list-group-flush" id="payment-preview-body">
                        {{-- Os dados serão preenchidos via JavaScript --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <div class="d-flex align-items-center me-3">
                        <button id="prev-page-btn" class="btn btn-icon btn-sm me-2" type="button" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                        </button>
                        <span id="page-info" class="text-muted">Página 1 de 1</span>
                        <button id="next-page-btn" class="btn btn-icon btn-sm ms-2" type="button" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </button>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Gerar e Salvar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('payment-preview-modal');
        const modalBody = document.getElementById('payment-preview-body');
        const form = document.getElementById('payment-generation-form');
        const prevPageBtn = document.getElementById('prev-page-btn');
        const nextPageBtn = document.getElementById('next-page-btn');
        const pageInfo = document.getElementById('page-info');

        let allPayments = [];
        let currentPage = 0;
        const itemsPerPage = 12;

        function renderPage() {
            const start = currentPage * itemsPerPage;
            const end = start + itemsPerPage;
            const paymentsForPage = allPayments.slice(start, end);

            modalBody.innerHTML = '';
            paymentsForPage.forEach((payment, index) => {
                const monthNumber = start + index + 1;
                const formattedAmount = parseFloat(payment.amount).toFixed(2);
                const formattedPaymentDate = new Date(payment.payment_date).toISOString().split('T')[0];
                const formattedReferenceDate = new Date(payment.reference_date).toISOString().split('T')[0];

                const listItem = `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 text-truncate">
                                <strong>${monthNumber}º Mês:</strong> ${payment.description}
                            </h5>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label mb-1 d-block d-lg-none">{{ __('Valor') }}</label>
                                <input type="number" step="0.01" name="payments[${start + index}][amount]" class="form-control form-control-sm" value="${formattedAmount}">
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1 d-block d-lg-none">{{ __('Vencimento') }}</label>
                                <input type="date" name="payments[${start + index}][payment_date]" class="form-control form-control-sm" value="${formattedPaymentDate}">
                            </div>
                            <input type="hidden" name="payments[${start + index}][status]" value="${payment.status}">
                            <input type="hidden" name="payments[${start + index}][type]" value="${payment.type}">
                            <input type="hidden" name="payments[${start + index}][category]" value="${payment.category}">
                            <input type="hidden" name="payments[${start + index}][description]" value="${payment.description}">
                            <input type="hidden" name="payments[${start + index}][reference_date]" value="${formattedReferenceDate}">
                        </div>
                    </div>
                `;
                modalBody.innerHTML += listItem;
            });
            
            const totalPages = Math.ceil(allPayments.length / itemsPerPage);
            pageInfo.textContent = `Página ${currentPage + 1} de ${totalPages}`;
            prevPageBtn.disabled = currentPage === 0;
            nextPageBtn.disabled = currentPage >= totalPages - 1;
        }

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const leaseId = button.getAttribute('data-lease-id');
            
            modalBody.innerHTML = '<div class="text-center text-muted py-5">Carregando...</div>';
            form.action = `/dashboard/leases/${leaseId}/payments/store-generated`;

            fetch(`/dashboard/leases/${leaseId}/payments/preview`)
                .then(response => response.json())
                .then(payments => {
                    allPayments = payments;
                    currentPage = 0;
                    renderPage();
                })
                .catch(error => {
                    console.error('Erro ao carregar pagamentos:', error);
                    modalBody.innerHTML = '<div class="text-center text-danger py-5">Erro ao carregar pagamentos.</div>';
                });
        });

        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 0) {
                currentPage--;
                renderPage();
            }
        });

        nextPageBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(allPayments.length / itemsPerPage);
            if (currentPage < totalPages - 1) {
                currentPage++;
                renderPage();
            }
        });
    });
</script>
@endpush
@endsection