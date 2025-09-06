@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Novo Atendimento') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('attendances.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @include('tenant.dashboard.attendances._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection