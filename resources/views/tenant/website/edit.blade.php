@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('tenant.website.update') }}">
        @csrf
        @method('PUT')
        <!-- Outros campos do modelo do site -->

        <div class="form-group">
            <label for="maintenance_mode">Modo manutenção</label>
            <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $tenant->data['maintenance_mode'] ?? false) ? 'checked' : '' }}>
            <span>Marque para ativar o modo manutenção do site.</span>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
