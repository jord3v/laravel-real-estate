@extends('layouts.guest')

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">{{ __('Comece seu Teste Gratuito de 7 Dias') }}</h2>
                    <p class="text-center text-muted mb-4">{{ __('Preencha os dados abaixo para criar sua conta e acessar a plataforma.') }}</p>

                    <form method="POST" action="{{ route('trial.register') }}" autocomplete="off" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('Seu Nome Completo') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Digite seu nome') }}" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Nome da Imobiliária') }}</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" placeholder="{{ __('Ex: Imobiliária Brasil') }}" name="company_name" value="{{ old('company_name') }}" required>
                            @error('company_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Endereço de E-mail') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="seu@email.com" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Telefone') }}</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" placeholder="{{ __('(XX) 9XXXX-XXXX') }}" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Senha') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Crie uma senha') }}" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Confirme a Senha') }}</label>
                            <input type="password" class="form-control" placeholder="{{ __('Repita a senha') }}" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Começar Teste Gratuito') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center text-muted mt-3">
                {{ __('Já possui uma conta?') }} <a href="{{ route('login') }}" tabindex="-1">{{ __('Faça Login') }}</a>
            </div>
        </div>
    </div>
@endsection