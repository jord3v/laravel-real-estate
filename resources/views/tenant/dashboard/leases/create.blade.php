@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Novo Contrato') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <form action="{{ route('leases.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @include('tenant.dashboard.leases._form', ['lease' => $lease ?? null])
            </form>
        </div>
    </div>
</div>
@endsection