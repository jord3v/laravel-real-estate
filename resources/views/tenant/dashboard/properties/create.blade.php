@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Cadastrar Novo Imóvel') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form id="property-form" action="{{ route('properties.store') }}" method="POST">
            @include('tenant.dashboard.properties._form', ['owners' => $owners])
        </form>
    </div>
</div>
@endsection