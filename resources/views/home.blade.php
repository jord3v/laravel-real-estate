@php
// Simulação de dados de Leads (ERPs)
$erps = [
[
'name' => 'ImobSystem Pro',
'users' => 125,
'contracts' => 450,
'properties' => 210
],
[
'name' => 'Agile Imóveis',
'users' => 80,
'contracts' => 300,
'properties' => 155
],
[
'name' => 'Solução Imobiliária',
'users' => 205,
'contracts' => 890,
'properties' => 540
],
[
'name' => 'ImobEasy',
'users' => 45,
'contracts' => 150,
'properties' => 95
],
];
@endphp
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
                        <h1 class="display-3 me-2">{{ count($erps) }}</h1>
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
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($erps as $erp)
                           <tr>
                              <td>{{ $erp['name'] }}</td>
                              <td>
                                 <span class="badge bg-primary-lt">{{ $erp['users'] }}</span>
                              </td>
                              <td>
                                 <span class="badge bg-green-lt">{{ $erp['contracts'] }}</span>
                              </td>
                              <td>
                                 <span class="badge bg-azure-lt">{{ $erp['properties'] }}</span>
                              </td>
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