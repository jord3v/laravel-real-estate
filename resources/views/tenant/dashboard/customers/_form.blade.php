@csrf
@if(isset($customer))
    @method('PUT')
@endif

<div class="mb-3">
    <label class="form-label">{{ __('Nome Completo') }}</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Endereço de E-mail') }}</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Telefone') }}</label>
    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone ?? '') }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Tipo') }}</label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
        <option value="owner" {{ old('type', $customer->type ?? '') == 'owner' ? 'selected' : '' }}>{{ __('Proprietário') }}</option>
        <option value="renter" {{ old('type', $customer->type ?? '') == 'renter' ? 'selected' : '' }}>{{ __('Inquilino') }}</option>
        <option value="agent" {{ old('type', $customer->type ?? '') == 'agent' ? 'selected' : '' }}>{{ __('Corretor') }}</option>
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-footer">
    <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
</div>