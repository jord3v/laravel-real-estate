<div class="row mb-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">{{ __('Cliente') }}</label>
            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                <option value="">{{ __('Selecione um cliente') }}</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id', $attendance->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">{{ __('Imóvel') }}</label>
            <select name="property_id" class="form-select @error('property_id') is-invalid @enderror">
                <option value="">{{ __('Selecione um imóvel (opcional)') }}</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}" {{ old('property_id', $attendance->property_id ?? '') == $property->id ? 'selected' : '' }}>
                        {{ $property->code }}
                    </option>
                @endforeach
            </select>
            @error('property_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Status do Atendimento') }}</label>
    <select name="status" id="select-status" class="form-select @error('status') is-invalid @enderror" required>
        @if(isset($attendance) && $attendance->status)
            <option value="{{ old('status', $attendance->status) }}" selected>{{ old('status', $attendance->status) }}</option>
        @else
            <option value="">{{ __('Selecione ou adicione um status') }}</option>
        @endif
            <option value="Novo Contato" {{ old('status') == 'Novo Contato' ? 'selected' : '' }}>Novo Contato</option>
            <option value="Em Contato" {{ old('status') == 'Em Contato' ? 'selected' : '' }}>Em Contato</option>
            <option value="Visita Agendada" {{ old('status') == 'Visita Agendada' ? 'selected' : '' }}>Visita Agendada</option>
            <option value="Proposta Enviada" {{ old('status') == 'Proposta Enviada' ? 'selected' : '' }}>Proposta Enviada</option>
            <option value="Fechado" {{ old('status') == 'Fechado' ? 'selected' : '' }}>Fechado</option>
            <option value="Perdido" {{ old('status') == 'Perdido' ? 'selected' : '' }}>Perdido</option>
            <option value="Retornar Contato" {{ old('status') == 'Retornar Contato' ? 'selected' : '' }}>Retornar Contato</option>
        @foreach($statuses as $status)
            @if(old('status') !== $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endif
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Data do Atendimento') }}</label>
    <input type="date" name="attended_at" class="form-control @error('attended_at') is-invalid @enderror" value="{{ old('attended_at', \Carbon\Carbon::parse($attendance->attended_at ?? now())->format('Y-m-d')) }}">
    @error('attended_at')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Notas') }}</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes', $attendance->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">{{ __('Salvar Atendimento') }}</button>
</div>