@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <h2 class="page-title">
               {{ __('Dashboard Inicial') }}
            </h2>
         </div>
      </div>
   </div>
</div>
<div class="page-body">
   <div class="container-xl">
      <div class="row row-deck row-cards">
         <div class="col-8">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">{{ __('Visão Geral do SaaS') }}</h3>
               </div>
               <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                     <div>
                        <h1 class="display-3 me-2">{{ count($tenants) }}</h1>
                        <div class="text-muted">{{ __('Total de Softwares (ERPs)') }}</div>
                     </div>
                  </div>
                  <p class="text-muted">
                     {{ __('Aqui você pode ver uma visão geral de todos os softwares e seus respectivos dados.') }}
                  </p>
               </div>
            </div>
         </div>
         <div class="col-4">
            <div class="card card-sm mb-4">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <h3 class="card-title mb-1">Atualizar Banco de Dados</h3>
                        <p class="text-muted mb-0">Execute as migrações pendentes para todos os tenants.</p>
                     </div>
                     <form action="{{ route('dashboard.run.tenant.migrate') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja executar a migração? Isso pode causar alterações no banco de dados.');">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" />
                              <path d="M12 3v9h9" />
                           </svg>
                           Rodar Migração
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row row-deck row-cards mt-4">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">{{ __('Detalhes por Software') }}</h3>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-vcenter card-table">
                        <thead>
                           <tr>
                              <th>{{ __('Software (ERP)') }}</th>
                              <th>{{ __('Usuários') }}</th>
                              <th>{{ __('Contratos') }}</th>
                              <th>{{ __('Imóveis') }}</th>
                              <th>Ação</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($tenants as $tenant)
                           <tr>
                              <td>{{ $tenant['name'] }}</td>
                              <td>{{ $tenant['name'] }}</td>
                              <td>
                                 <span class="badge bg-green-lt">0</span>
                              </td>
                              <td>
                                 <span class="badge bg-azure-lt">0</span>
                              </td>
                              <td>
                                 <div class="btn-list flex-nowrap">
                                    <a href="{{route('dashboard.impersonate', [$tenant->id, 1])}}" class="btn btn-sm">Acessar</a>
                                 </div>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection