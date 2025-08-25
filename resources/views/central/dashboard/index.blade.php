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
         <div class="col-12">
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
                              <td><a href="{{route('dashboard.impersonate', [$tenant->id, 1])}}">acessar</a></td>
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