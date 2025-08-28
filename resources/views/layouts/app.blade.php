<!doctype html>
<html lang="pt-br">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
      <meta http-equiv="X-UA-Compatible" content="ie=edge" />
      <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
      <link href="{{ mix('css/app.css') }}" rel="stylesheet"/>
      <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
      <style>
    /* public/css/attendance_history.css */

.post-it-card {
    background-color: #ffc; /* Cor amarela suave de post-it */
    border: 1px solid #e0e0e0;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    margin-bottom: 1rem;
    padding: 1rem;
    position: relative;
    /* Adiciona uma leve rotação para o efeito post-it */
    transform: rotate(calc(var(--rotation-deg) * 1deg));
    transition: transform 0.2s ease-in-out;
    border-radius: 4px; /* Bordas levemente arredondadas */
    /* Detalhe de sombra para simular a dobra do papel */
    filter: drop-shadow(0px 5px 3px rgba(0, 0, 0, 0.1));
}

.post-it-card:hover {
    transform: rotate(0deg) scale(1.02); /* Animação ao passar o mouse */
    z-index: 10;
}

.post-it-card .status-change {
    font-size: 0.9em;
    color: #6c757d; /* Cor secundária para o texto */
}

.post-it-card .status-new {
    font-weight: bold;
    color: #212529; /* Cor principal para o status */
}

.post-it-card .timestamp {
    font-size: 0.8em;
    color: #888;
    margin-top: 0.5rem;
    display: block;
}

.post-it-card .notes-change {
    margin-top: 1rem;
    padding-top: 0.5rem;
    border-top: 1px dashed #ccc; /* Separador para as notas */
    font-size: 0.85em;
}

.post-it-card .notes-change small {
    display: block;
    color: #6c757d;
    margin-bottom: 0.2rem;
}

.post-it-card .notes-change strong {
    color: #343a40;
}
</style>
      @stack('styles')
      <style>
         @import url("https://rsms.me/inter/inter.css");
      </style>
   </head>
   <body>
      <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
      @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new TomSelect("#select-status", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            },
            plugins: {
                remove_button: {
                    title: 'Remover'
                }
            },
            // Adicione esta linha para o tema
            theme: 'bootstrap5' 
        });
    });
</script>
@endpush
      <div class="page">
         <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
               <button
                  class="navbar-toggler"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#sidebar-menu"
                  aria-controls="sidebar-menu"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                  >
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="navbar-brand navbar-brand-autodark">
                  <a href="." aria-label="Tabler"
                     >
                  <img src="./static/logo.svg" class="navbar-brand-image" alt="Tabler" />
                  </a>
               </div>
               <div class="navbar-nav flex-row d-lg-none">
                  <div class="nav-item dropdown">
                     <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"> </span>
                        <div class="d-none d-xl-block ps-2">
                           <div>{{ auth()->user()->name }}</div>
                           
                        </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="" class="dropdown-item">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="" class="dropdown-item">Logout</a>
                     </div>
                  </div>
               </div>
               <div class="collapse navbar-collapse" id="sidebar-menu">
                  <ul class="navbar-nav pt-lg-3">
    {{-- Home/Dashboard --}}
    <li class="nav-item @if(request()->routeIs('dashboard')) active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M13.45 11.55l2.05 -2.05" /><path d="M6.4 20a9 9 0 1 1 11.2 0z" /></svg>
            </span>
            <span class="nav-link-title"> {{ __('Início') }} </span>
        </a>
    </li>

    {{-- Imóveis --}}
    <li class="nav-item @if(request()->routeIs('properties.*')) active @endif">
        <a class="nav-link" href="{{ route('properties.index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
            </span>
            <span class="nav-link-title"> {{ __('Imóveis') }} </span>
        </a>
    </li>

    {{-- Contratos --}}
    <li class="nav-item @if(request()->routeIs('leases.*')) active @endif">
        <a class="nav-link" href="{{ route('leases.index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-contract"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 21h-2a3 3 0 0 1 -3 -3v-1h5.5" /><path d="M17 8.5v-3.5a2 2 0 1 1 2 2h-2" /><path d="M19 3h-11a3 3 0 0 0 -3 3v11" /><path d="M9 7h4" /><path d="M9 11h4" /><path d="M18.42 12.61a2.1 2.1 0 0 1 2.97 2.97l-6.39 6.42h-3v-3z" /></svg>
            </span>
            <span class="nav-link-title"> {{ __('Contratos') }} </span>
        </a>
    </li>
    
    {{-- Atendimento (Novo Item) --}}
    <li class="nav-item @if(request()->routeIs('attendances.*')) active @endif">
        <a class="nav-link" href="{{ route('attendances.index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-headset"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 14v-3a8 8 0 1 1 16 0v3" /><path d="M18 19c0 1.657 -2.686 3 -6 3" /><path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" /><path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z" /></svg>
            </span>
            <span class="nav-link-title"> {{ __('Atendimento') }} </span>
        </a>
    </li>

    {{-- Menu Dropdown de Pessoas --}}
    @php
        $isPeopleActive = request()->routeIs('customers.*') || request()->routeIs('payments.*');
    @endphp
    <li class="nav-item dropdown @if($isPeopleActive) active @endif">
        <a class="nav-link dropdown-toggle" href="#navbar-pessoas" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="{{ $isPeopleActive ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
            </span>
            <span class="nav-link-title">{{ __('Pessoas') }}</span>
        </a>
        <div class="dropdown-menu @if($isPeopleActive) show @endif">
            <a class="dropdown-item @if(request()->routeIs('customers.*')) active @endif" href="{{ route('customers.index') }}">
                {{ __('Clientes') }}
            </a>
            <a class="dropdown-item @if(request()->routeIs('payments.*')) active @endif" href="{{ route('payments.index') }}">
                {{ __('Pagamentos') }}
            </a>
        </div>
    </li>
</ul>
               </div>
            </div>
         </aside>
         <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
            <div class="container-xl">
               <button
                  class="navbar-toggler"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#navbar-menu"
                  aria-controls="navbar-menu"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                  >
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="navbar-nav flex-row order-md-last">
                  <div class="d-none d-md-flex">
                     <div class="nav-item">
                        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                           <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-1"
                              >
                              <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                           </svg>
                        </a>
                        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                           <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-1"
                              >
                              <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                              <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                           </svg>
                        </a>
                     </div>
                     <div class="nav-item dropdown d-none d-md-flex">
                        <a
                           href="#"
                           class="nav-link px-0"
                           data-bs-toggle="dropdown"
                           tabindex="-1"
                           aria-label="Show notifications"
                           data-bs-auto-close="outside"
                           aria-expanded="false"
                           >
                           <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-1"
                              >
                              <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                              <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                           </svg>
                           <span class="badge bg-red"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                           <div class="card">
                              <div class="card-header d-flex">
                                 <h3 class="card-title">Notificações</h3>
                                 <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                              </div>
                              <div class="list-group list-group-flush list-group-hoverable">
                                 <div class="list-group-item">
                                    <div class="row align-items-center">
                                       <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                                       <div class="col text-truncate">
                                          <a href="#" class="text-body d-block">Exemplo 1</a>
                                          <div class="d-block text-secondary text-truncate mt-n1">Alterar tags HTML obsoletas para classes de decoração de texto (#29604)</div>
                                       </div>
                                       <div class="col-auto">
                                          <a href="#" class="list-group-item-actions">
                                             <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon text-muted icon-2"
                                                >
                                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                             </svg>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="card-body">
                                 <div class="row">
                                    <div class="col">
                                       <a href="#" class="btn btn-2 w-100"> Arquivar tudo </a>
                                    </div>
                                    <div class="col">
                                       <a href="#" class="btn btn-2 w-100"> Marcar tudo como lido </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="nav-item dropdown">
                     <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"> </span>
                        <div class="d-none d-xl-block ps-2">
                           <div>{{ auth()->user()->name }}</div>
                           {{auth()->user()->roles()->get()->pluck('name')->join(', ')}}
                        </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="" class="dropdown-item">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                           @csrf
                        </form>
                     </div>
                  </div>
               </div>
               <div class="collapse navbar-collapse" id="navbar-menu">
                  remover  
               </div>
            </div>
         </header>
         <div class="page-wrapper">
            @yield('content')
            <footer class="footer footer-transparent d-print-none">
               <div class="container-xl">
                  <div class="row text-center align-items-center flex-row-reverse">
                     <div class="col-lg-auto ms-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                           <li class="list-inline-item"><a href="https://docs.tabler.io" target="_blank" class="link-secondary" rel="noopener">Documentation</a></li>
                           <li class="list-inline-item"><a href="./license.html" class="link-secondary">License</a></li>
                           <li class="list-inline-item">
                              <a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary" rel="noopener">Source code</a>
                           </li>
                           <li class="list-inline-item">
                              <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary" rel="noopener">
                                 <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon text-pink icon-inline icon-4"
                                    >
                                    <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                 </svg>
                                 Sponsor
                              </a>
                           </li>
                        </ul>
                     </div>
                     <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                           <li class="list-inline-item">
                              Copyright &copy; 2025
                              <a href="." class="link-secondary">Tabler</a>. All rights reserved.
                           </li>
                           <li class="list-inline-item">
                              <a href="./changelog.html" class="link-secondary" rel="noopener"> v1.4.0 </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </footer>
         </div>
      </div>
      <script src="{{ mix('js/app.js') }}" defer></script>
      @stack('scripts')
   </body>
</html>