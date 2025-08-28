@csrf
@if(isset($payment))
    @method('PUT')
@endif

<div class="mb-3">
    <label class="form-label">{{ __('Contrato') }}</label>
    <select name="lease_id" class="form-select @error('lease_id') is-invalid @enderror" required>
        <option value="">{{ __('Selecione um contrato') }}</option>
        @foreach ($leases as $lease)
            <option value="{{ $lease->id }}" {{ old('lease_id', $payment->lease_id ?? '') == $lease->id ? 'selected' : '' }}>
                {{ $lease->property->address }} ({{ $lease->renter->name }})
            </option>
        @endforeach
    </select>
    @error('lease_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Valor') }}</label>
    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount ?? '') }}" required>
    @error('amount')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Data do Pagamento') }}</label>
    <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', $payment->payment_date ?? now()->toDateString()) }}" required>
    @error('payment_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Status do Pagamento') }}</label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="paid" {{ old('status', $payment->status ?? '') == 'paid' ? 'selected' : '' }}>{{ __('Pago') }}</option>
        <option value="overdue" {{ old('status', $payment->status ?? '') == 'overdue' ? 'selected' : '' }}>{{ __('Atrasado') }}</option>
        <option value="pending" {{ old('status', $payment->status ?? '') == 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-footer">
    <button type="submit" class="btn btn-primary">{{ __('Salvar Pagamento') }}</button>
</div>