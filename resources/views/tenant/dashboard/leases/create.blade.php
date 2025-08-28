@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Novo Contrato de Aluguel') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('leases.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Imóvel') }}</label>
                        <select name="property_id" class="form-select @error('property_id') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o imóvel') }}</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                    {{ $property->address }} ({{ $property->owner->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Inquilino') }}</label>
                        <select name="renter_id" class="form-select @error('renter_id') is-invalid @enderror" required>
                            <option value="">{{ __('Selecione o inquilino') }}</option>
                            @foreach ($renters as $renter)
                                <option value="{{ $renter->id }}" {{ old('renter_id') == $renter->id ? 'selected' : '' }}>
                                    {{ $renter->name }} ({{ $renter->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('renter_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Data de Início') }}</label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Data de Término') }}</label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Valor do Aluguel') }}</label>
                        <input type="number" step="0.01" name="rent_amount" class="form-control @error('rent_amount') is-invalid @enderror" value="{{ old('rent_amount') }}" required>
                        @error('rent_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Salvar Contrato') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection