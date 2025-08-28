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
        <div class="card">
            <div class="row g-0">
                {{-- Coluna de Navegação (list-group) --}}
                <div class="col-12 col-md-3 border-end">
                    <div class="card-body">
                        <h4 class="subheader">{{ __('Etapas do Cadastro') }}</h4>
                        <div class="list-group list-group-transparent">
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active">{{ __('1. Básico') }}</a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center disabled">{{ __('2. Características') }}</a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center disabled">{{ __('3. Negociação') }}</a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center disabled">{{ __('4. Finalizar') }}</a>
                        </div>
                    </div>
                </div>
                
                {{-- Coluna de Conteúdo do Formulário --}}
                <div class="col-12 col-md-9 d-flex flex-column">
                    <div class="card-body">
                        {{-- O conteúdo do passo será injetado aqui --}}
                        @yield('form_content')
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <a href="#" class="btn btn-1"> {{ __('Cancelar') }} </a>
                            <a href="#" class="btn btn-primary btn-2"> {{ __('Próximo') }} </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection