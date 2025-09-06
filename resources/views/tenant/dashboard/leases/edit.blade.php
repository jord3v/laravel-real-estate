@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Editar Contrato') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <form action="{{ route('leases.update', $lease) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                @include('tenant.dashboard.leases._form', ['lease' => $lease])
            </form>
        </div>
    </div>
</div>
@endsection