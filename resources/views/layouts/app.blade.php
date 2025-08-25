<!doctype html>
<html lang="pt-br">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
      <meta http-equiv="X-UA-Compatible" content="ie=edge" />
      <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
      <link href="{{ mix('css/app.css') }}" rel="stylesheet"/>
      <style>
         @import url("https://rsms.me/inter/inter.css");
      </style>
   </head>
   <body>
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
                     <li class="nav-item">
                        <a class="nav-link" href="./">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                 <path d="M13.45 11.55l2.05 -2.05" />
                                 <path d="M6.4 20a9 9 0 1 1 11.2 0z" />
                              </svg>
                           </span>
                           <span class="nav-link-title"> Home </span>
                        </a>
                     </li>
                     <li class="nav-item dropdown">
                        <a
                           class="nav-link dropdown-toggle"
                           href="#navbar-base"
                           data-bs-toggle="dropdown"
                           data-bs-auto-close="false"
                           role="button"
                           aria-expanded="false"
                           >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building-skyscraper">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M3 21l18 0" />
                                 <path d="M5 21v-14l8 -4v18" />
                                 <path d="M19 21v-10l-6 -4" />
                                 <path d="M9 9l0 .01" />
                                 <path d="M9 12l0 .01" />
                                 <path d="M9 15l0 .01" />
                                 <path d="M9 18l0 .01" />
                              </svg>
                           </span>
                           <span class="nav-link-title"> Interface </span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns">
                              <div class="dropdown-menu-column">
                                 <a class="dropdown-item" href="./accordion.html">
                                 Accordion
                                 <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                                 </a>
                                 <a class="dropdown-item" href="./alerts.html"> Alerts </a>
                                 <div class="dropend">
                                    <a
                                       class="dropdown-item dropdown-toggle"
                                       href="#sidebar-authentication"
                                       data-bs-toggle="dropdown"
                                       data-bs-auto-close="false"
                                       role="button"
                                       aria-expanded="false"
                                       >
                                    Authentication
                                    </a>
                                    <div class="dropdown-menu">
                                       <a href="" class="dropdown-item"> Sign in </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
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
   </body>
</html>