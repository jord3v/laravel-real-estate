@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Registrar Novo Pagamento') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" class="needs-validation" novalidate>
                    @include('tenant.dashboard.payments._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection