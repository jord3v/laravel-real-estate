@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Leads') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Lead') }}
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
                @forelse ($customers as $customer)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        {{-- Ícone e Nome --}}
                        <div class="col-6 col-md-3 d-flex align-items-center">
                            <span class="avatar avatar-md rounded-circle bg-light-lt me-3 d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <div class="flex-fill">
                                <span class="fw-bold d-block">{{ $customer->name }}</span>
                                <span class="text-muted d-block">{{ $customer->company_name ?? ucfirst($customer->type) }}</span>
                            </div>
                        </div>

                        {{-- Contatos --}}
                        <div class="col-4 d-none d-md-block text-truncate">
                            <div class="text-muted">
                                {{ $customer->email ?? '-' }}
                            </div>
                            <div class="text-muted mt-1">
                                {{ $customer->phone ?? '-' }}
                            </div>
                        </div>
                        
                        {{-- Tipo de Documento --}}
                        <div class="col-auto d-none d-lg-block text-center">
                            <span class="badge bg-secondary-lt">{{ strtoupper($customer->document_type) ?? 'N/A' }}</span>
                        </div>

                        {{-- Botões de Ação --}}
                        <div class="col-6 col-md-3 ms-auto d-flex justify-content-end align-items-center">
                            <div class="btn-list flex-nowrap">
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm">{{ __('Editar') }}</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este Lead?') }}');" class="d-none d-md-inline-block">
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
                            {{ __('Nenhum Lead encontrado.') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection