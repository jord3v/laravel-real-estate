@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Imóveis') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('properties.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Novo Imóvel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        {{-- Seção de Filtros --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">Filtros</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('properties.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Imóvel</label>
                            <select name="type" class="form-select">
                                <option value="">Todos os tipos</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Finalidade</label>
                            <select name="purpose" class="form-select">
                                <option value="">Todas as finalidades</option>
                                <option value="residential" {{ request('purpose') == 'residential' ? 'selected' : '' }}>Residencial</option>
                                <option value="commercial" {{ request('purpose') == 'commercial' ? 'selected' : '' }}>Comercial</option>
                                <option value="others" {{ request('purpose') == 'others' ? 'selected' : '' }}>Outros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos os status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                            <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($properties as $property)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        {{-- Imagem do Imóvel --}}
                        <div class="col-auto">
                            @php
                                $mainMedia = null;
                                foreach ($property->getMedia('property') as $media) {
                                    if ($media->getCustomProperty('is_main') === true) {
                                        $mainMedia = $media;
                                        break;
                                    }
                                }
                                if (!$mainMedia) {
                                    $mainMedia = $property->getFirstMedia('property');
                                }
                            @endphp
                            
                            @if ($mainMedia)
                                <span class="avatar avatar-lg rounded" style="background-image: url({{ $mainMedia->getUrl('preview') }})"></span>
                            @else
                                <span class="avatar avatar-lg rounded bg-muted-lt d-flex align-items-center justify-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M15 8h.01"></path>
                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"></path>
                                        <path d="M3 16l4 -4c.928 -.893 2.072 -.893 3 0l5 5"></path>
                                        <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"></path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                        
                        {{-- Informações principais: Código, Tipo, Status e Endereço --}}
                        <div class="col text-truncate">
                            <div class="d-flex align-items-center mb-1">
                                <span class="fw-bold me-2">{{ $property->code }}</span>
                                @if($property->type)
                                    <span class="badge bg-info-lt me-2">{{ $property->type->name }}</span>
                                @endif
                                <span class="badge bg-{{ $property->status == 'published' ? 'success' : 'secondary' }}-lt">{{ ucfirst($property->status) }}</span>
                            </div>
                            <div class="text-muted text-truncate">
                                {{ is_array($property->address) ? ($property->address['street'] ?? '') . ', ' . ($property->address['city'] ?? '') . ' - ' . ($property->address['state'] ?? '') : ($property->address ?? '') . ', ' . ($property->city ?? '') . ' - ' . ($property->state ?? '') }}
                            </div>
                        </div>
                        
                        {{-- Composição e Dimensões (visível em telas grandes, oculta em pequenas) --}}
                        <div class="col-auto text-muted d-none d-md-block">
                            <div class="d-flex flex-wrap gap-2">
                                @if(isset($property->compositions['bedrooms']))
                                    <span>{{ $property->compositions['bedrooms'] }} Quartos</span>
                                @endif
                                @if(isset($property->compositions['bathrooms']))
                                    <span>{{ $property->compositions['bathrooms'] }} Banheiros</span>
                                @endif
                                @if(isset($property->compositions['car_spaces']))
                                    <span>{{ $property->compositions['car_spaces'] }} Vagas</span>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @if(isset($property->dimensions['usable_area']))
                                    <span>Área Útil: {{ $property->dimensions['usable_area'] }} m²</span>
                                @endif
                                @if(isset($property->dimensions['total_area']))
                                    <span>Área Total: {{ $property->dimensions['total_area'] }} m²</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Preços (visível em telas grandes, oculta em pequenas) --}}
                        <div class="col-auto text-muted d-none d-lg-block">
                            <div class="d-flex flex-column gap-1">
                                @if(isset($property->business_options['sale']['price']))
                                    <div class="text-truncate">Venda: <strong>R$ {{ number_format($property->business_options['sale']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                                @if(isset($property->business_options['rental']['price']))
                                    <div class="text-truncate">Locação: <strong>R$ {{ number_format($property->business_options['rental']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                                @if(isset($property->business_options['season']['price']))
                                    <div class="text-truncate">Temporada: <strong>R$ {{ number_format($property->business_options['season']['price'], 2, ',', '.') }}</strong></div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Botões de ação (sempre visíveis) --}}
                        <div class="col-auto ms-auto d-flex flex-wrap flex-sm-nowrap gap-2">
                            <form action="{{ route('properties.clone', $property) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja clonar este imóvel?') }}');">
                                @csrf
                                <button type="submit" class="btn btn-sm">{{ __('Clonar') }}</button>
                            </form>
                            <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm">{{ __('Editar') }}</a>
                            <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este imóvel?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Excluir') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="list-group-item">
                        <div class="text-center text-muted py-5">
                            {{ __('Nenhum imóvel encontrado.') }}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</div>
@endsection