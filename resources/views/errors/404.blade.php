@extends('layouts.app')

@section('title', 'Página não encontrada')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-1">404</h1>
    <h2 class="mb-4">Página não encontrada</h2>
    <p class="lead mb-4">O endereço que você tentou acessar não existe ou o tenant não foi identificado.</p>
    <a href="/" class="btn btn-primary">Voltar para o início</a>
</div>
@endsection
