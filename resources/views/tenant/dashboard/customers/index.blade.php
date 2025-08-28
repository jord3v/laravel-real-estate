@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Gestão de Pessoas') }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                        {{ __('Nova Pessoa') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>{{ __('Nome') }}</th>
                            <th>{{ __('E-mail') }}</th>
                            <th>{{ __('Telefone') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $person)
                        <tr>
                            <td>{{ $person->name }}</td>
                            <td>{{ $person->email }}</td>
                            <td>{{ $person->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $person->type == 'owner' ? 'green' : 'azure' }}-lt">{{ ucfirst($person->type) }}</span>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('customers.edit', $person) }}" class="btn btn-sm">{{ __('Editar') }}</a>
                                    <form action="{{ route('customers.destroy', $person) }}" method="POST" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta pessoa?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Excluir') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection