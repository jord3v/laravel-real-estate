<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($tenant->name ?? '') . ' | Encontre seu imóvel ideal')</title>
    <meta name="description" content="Sistema de busca de imóveis. Encontre casas, apartamentos e terrenos para compra ou aluguel.">
    <meta name="keywords" content="imóveis, sistema imobiliário, aluguel, venda, apartamento, casa, favoritos, comparar">
    <meta name="author" content="{{$tenant->name}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a5ca3;
            --primary-color-darker: #13467e;
            --secondary-color: #495057;
            --accent-color: #f79f1b;
            --accent-color-darker: #e08e12;
            --whatsapp-color: #25D366;
            --whatsapp-color-darker: #1EBE57;
            --light-bg-color: #f8f9fa;
            --surface-color: #ffffff;
            --text-color: #212529;
            --text-color-muted: #6c757d;
            --border-color: #dee2e6;
            --shadow-sm: rgba(0, 0, 0, 0.05);
            --shadow-md: rgba(0, 0, 0, 0.1);
            --body-font: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--body-font);
            background-color: var(--surface-color);
            color: var(--text-color);
            scroll-behavior: smooth;
        }

        body.modal-open {
            overflow: hidden;
        }

        @media (min-width: 992px) {
            html {
                scroll-padding-top: 90px;
            }
        }

        .header-main {
            background-color: var(--surface-color);
            box-shadow: 0 2px 15px var(--shadow-sm);
        }

        .navbar-brand .logo-text {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .navbar-brand .creci-text {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--text-color-muted);
            line-height: 1;
            margin-top: -5px;
        }

        .navbar .nav-link {
            font-weight: 500;
            color: var(--secondary-color);
            transition: color 0.2s ease;
            margin: 0 0.5rem;
            position: relative;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .navbar .nav-link:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        @media (min-width: 992px) {
            .navbar .nav-link:hover:after,
            .navbar .nav-link.active:after {
                width: 60%;
            }
        }

        .navbar .nav-link.active,
        .navbar .nav-link:hover {
            color: var(--primary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: var(--text-color);
            border-radius: 50px;
            padding: 0.5rem 1.5rem !important;
            transition: all 0.3s ease;
            border: none;
            font-weight: 600;
        }

        .btn-accent:hover {
            background-color: var(--accent-color-darker);
            color: var(--text-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px var(--shadow-md);
        }

        .social-icons-nav a {
            color: var(--text-color-muted);
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .social-icons-nav a:hover {
            color: var(--primary-color);
        }

        .section-padding {
            padding: 5rem 0;
        }

        .section-title {
            font-weight: 700;
            color: var(--text-color);
        }

        .hero-section {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.2)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            padding: 8rem 0;
            color: var(--surface-color);
        }

        #about-content p, #modal-description p {
            margin-bottom: 1rem;
        }

        .search-section {
            padding: 3rem 0;
            background-color: var(--light-bg-color);
            border-bottom: 1px solid var(--border-color);
        }

        .search-form-container {
            background-color: var(--surface-color);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 40px var(--shadow-sm);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(26, 92, 163, 0.15);
        }

        .filter-toggle-btn {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .filter-toggle-btn:hover {
            color: var(--primary-color);
        }

        .filter-pills-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .filter-pills-group .btn-outline-primary {
            border-radius: 50px;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            flex-grow: 1;
        }

        .btn-check:checked+.btn-outline-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--surface-color);
        }

        .controls-bar .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-color-muted);
        }
        .controls-bar .btn-outline-secondary:hover,
        .controls-bar .btn-outline-secondary.active {
            background-color: var(--primary-color);
            color: var(--surface-color);
            border-color: var(--primary-color);
        }
        #show-favorites-btn:hover,
        #show-favorites-btn.active {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
        }

        .property-card {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            background-color: var(--surface-color);
            box-shadow: 0 5px 25px var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .property-card .card-body {
            cursor: pointer;
        }

        .property-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px var(--shadow-md);
            border-color: var(--primary-color);
        }

        .property-card .img-container {
            position: relative;
        }
        .property-card .carousel-item img {
            height: 250px;
            width: 100%;
            object-fit: cover;
        }
        .property-card .carousel-control-prev,
        .property-card .carousel-control-next {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .property-card:hover .carousel-control-prev,
        .property-card:hover .carousel-control-next {
            opacity: 0.7;
        }

        .property-card .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .property-card-price {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
        }
        .property-purpose-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background-color: rgba(26, 92, 163, 0.9);
            color: var(--surface-color);
            padding: 0.3rem 0.7rem;
            font-size: 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            z-index: 2;
        }

        .card-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 3;
            display: flex;
            gap: 0.5rem;
        }

        .card-actions .btn-action {
            background-color: rgba(255, 255, 255, 0.85);
            color: var(--secondary-color);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(2px);
            cursor: pointer;
        }

        .card-actions .btn-action:hover {
            background-color: var(--surface-color);
            transform: scale(1.1);
        }
        .card-actions .btn-action.favorited {
            color: #e74c3c;
        }
        .card-actions .compare-checkbox {
            display: none;
        }
        .card-actions .compare-label.checked {
            color: var(--accent-color);
        }

        .property-features {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-color-muted);
        }
        .property-features span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #property-list-container.list-view .property-card {
            flex-direction: row;
        }
        #property-list-container.list-view .property-card .img-container {
            width: 40%;
            flex-shrink: 0;
        }
        #property-list-container.list-view .property-card .carousel,
        #property-list-container.list-view .property-card .carousel-item img {
            height: 100%;
        }

        .page-link {
            color: var(--primary-color);
            border-radius: 8px !important;
            margin: 0 4px;
            border-color: var(--border-color);
        }
        .page-link:hover {
            color: var(--primary-color-darker);
            background-color: var(--light-bg-color);
        }
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--surface-color);
            box-shadow: 0 4px 10px rgba(26, 92, 163, 0.2);
        }

        .footer a.footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a.footer-link:hover {
            color: var(--accent-color);
        }
        .footer a.footer-social-link {
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .footer a.footer-social-link:hover {
            color: var(--surface-color);
            transform: translateY(-3px);
        }

        .property-modal {
            display: none; position: fixed; z-index: 1055; left: 0; top: 0;
            width: 100%; height: 100%; overflow: auto;
            background-color: rgba(10, 25, 41, 0.7);
            backdrop-filter: blur(8px);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .property-modal.show { opacity: 1; }

        .property-modal-content {
            background-color: var(--light-bg-color);
            margin: 2% auto; padding: 0; border: 0; max-width: 1200px; width: 95%;
            border-radius: 1rem; position: relative;
            transform: translateY(-50px);
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            overflow: hidden;
        }
        .property-modal.show .property-modal-content { transform: translateY(0); }
        .property-modal-close {
            position: fixed; top: 20px; right: 30px; font-size: 2.5rem;
            color: var(--surface-color); text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            cursor: pointer; border: none; background: none; z-index: 1060;
            transition: transform 0.2s ease;
        }
        .property-modal-close:hover { transform: scale(1.1); }
        .modal-header-custom { padding: 1rem 1.5rem; background-color: var(--surface-color); border-bottom: 1px solid var(--border-color); }
        .modal-body-custom { padding: 2rem; }
        .modal-feature-item { font-size: 1.1rem; }
        .modal-feature-item i { color: var(--primary-color); }

        .btn-primary {
            background-color: var(--primary-color); border-color: var(--primary-color);
            border-radius: 8px; font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-color-darker); border-color: var(--primary-color-darker);
            transform: translateY(-2px); box-shadow: 0 4px 15px rgba(26, 92, 163, 0.2);
        }
        .btn-whatsapp {
            background-color: var(--whatsapp-color); border-color: var(--whatsapp-color);
            color: #fff; font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-whatsapp:hover {
            background-color: var(--whatsapp-color-darker); border-color: var(--whatsapp-color-darker);
            color: #fff; transform: translateY(-2px);
        }

        .compare-tray {
            position: fixed; bottom: -100px; left: 0; width: 100%; height: 70px;
            background-color: var(--text-color); color: var(--surface-color); z-index: 1040;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.2);
            transition: bottom 0.4s ease-in-out;
        }
        .compare-tray.show { bottom: 0; }
        .compare-tray .btn-outline-light { border-radius: 50px; }
        .compare-table .img-fluid { max-height: 120px; object-fit: cover; }

        #chat-widget-container{position:fixed;bottom:25px;right:25px;z-index:1050}
        .chat-bubble{width:60px;height:60px;background-color:var(--primary-color);color:var(--surface-color);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 5px 20px rgba(26,92,163,.3);transition:all .3s ease;position:relative}
        .chat-bubble:hover{transform:scale(1.1)}
        .chat-notification-badge{position:absolute;top:-2px;right:-2px;width:20px;height:20px;background-color:#e74c3c;color:white;border-radius:50%;font-size:12px;font-weight:700;display:none;justify-content:center;align-items:center;border:2px solid var(--surface-color)}
        .chat-window{width:350px;max-width:calc(100vw - 40px);height:80vh;max-height:550px;background-color:var(--surface-color);border-radius:15px;box-shadow:0 10px 40px rgba(0,0,0,.15);display:flex;flex-direction:column;overflow:hidden;position:absolute;bottom:80px;right:0;opacity:0;transform-origin:bottom right;transform:scale(.95);transition:opacity .3s ease,transform .3s ease;visibility:hidden}
        .chat-window.open{opacity:1;transform:scale(1);visibility:visible}
        .chat-header{background:var(--primary-color);color:var(--surface-color);padding:1rem;display:flex;align-items:center;gap:1rem;flex-shrink:0}

        .chat-avatar-wrapper{position:relative;flex-shrink:0}
        #chat-avatar{width:50px;height:50px;border-radius:50%;border:2px solid rgba(255,255,255,.5);object-fit:cover}
        .chat-status-online{position:absolute;bottom:2px;right:2px;width:12px;height:12px;background-color:var(--whatsapp-color);border-radius:50%;border:2px solid var(--primary-color)}

        .chat-attendant-info{flex-grow:1;font-weight:600}.chat-attendant-role{font-size:.8rem;opacity:.8;font-weight:400}
        .chat-control-btn{background:0 0;border:none;color:var(--surface-color);font-size:1rem;cursor:pointer;opacity:.8;transition:opacity .2s;padding:.25rem .5rem}
        .chat-control-btn:hover{opacity:1}
        .chat-messages{flex-grow:1;padding:1rem;overflow-y:auto;background-color:var(--light-bg-color);display:flex;flex-direction:column;gap:.75rem}

        .message-container{display:flex;width:100%;opacity:0;transform:translateY(10px);animation:fadeIn .4s ease forwards}
        .message-bubble{padding:.6rem 1rem;border-radius:18px;max-width:85%;font-size:.9rem;line-height:1.4;word-wrap:break-word}
        .message-container.attendant{justify-content:flex-start}
        .message-container.attendant .message-bubble{background-color:#e9ecef;color:var(--text-color);border-bottom-left-radius:4px}
        .message-container.user-choice{justify-content:flex-end}
        .message-container.user-choice .message-bubble{background-color:var(--primary-color);color:var(--surface-color);border-bottom-right-radius:4px}

        .message-container.interactive-area{padding:.5rem 0}
        .typing-indicator{display:flex;align-items:center;padding:0.6rem 1rem;}
        .typing-indicator span{height:8px;width:8px;margin:0 2px;background-color:#bdc3c7;border-radius:50%;animation:typing 1.4s infinite ease-in-out both}
        .typing-indicator span:nth-child(1){animation-delay:0s}
        .typing-indicator span:nth-child(2){animation-delay:.2s}
        .typing-indicator span:nth-child(3){animation-delay:.4s}
        @keyframes fadeIn{to{opacity:1;transform:translateY(0)}}
        @keyframes typing{0%,80%,100%{transform:scale(0)}40%{transform:scale(1.0)}}
        .chat-choices{display:flex;gap:.5rem;padding:.5rem;width:100%}
        .chat-choices .btn{flex:1;border-radius:20px;font-size:.9rem}

        #chat-form-container { padding: 1rem; overflow-y: auto; background-color: var(--light-bg-color); }
        #widget-form .form-label { font-size: 0.8rem; margin-bottom: 0.1rem; }
        #widget-form .form-control-sm { padding: 0.3rem 0.6rem; font-size: 0.9rem; }
        #widget-form .form-control-sm, #widget-form textarea { border-radius: 6px;}
        #widget-form .mb-2 { margin-bottom: 0.5rem !important; }
        #widget-form .btn { font-size: 0.9rem; padding: 0.5rem; }
    </style>
</head>
<body>
    <header class="header-main sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <div class="d-flex flex-column align-items-start">
                        <span class="logo-text"><i class="fa-solid fa-building-user me-2"></i><span id="nav-brand-name">{{$tenant->name}}</span></span>
                        <span class="creci-text">CRECI: <span id="nav-brand-creci">123456-J</span></span>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                            <li class="nav-item"><a class="nav-link" href="#inicio">Início</a></li>
                            <li class="nav-item"><a class="nav-link" href="#busca">Buscar</a></li>
                            <li class="nav-item"><a class="nav-link" href="#imoveis">Imóveis</a></li>
                            <li class="nav-item"><a class="nav-link" href="#sobre">A Empresa</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>
                        </ul>
                        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-3">
                           <a class="btn btn-accent btn-lg" href="#contato">Anuncie seu Imóvel</a>
                           <div class="d-none d-lg-flex social-icons-nav">
                              @if(!empty($tenant->social))
                                  @foreach($tenant->social as $key => $url)
                                      @if($url)
                                          <a href="{{$url}}" target="_blank"><i class="fa-brands fa-{{$key}}"></i></a>
                                      @endif
                                  @endforeach
                              @endif
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    

    @yield('content')

    <footer class="footer bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-uppercase fw-bold mb-4" id="footer-name">{{$tenant->name}}</h5>
                    <p>CRECI <span id="footer-creci">123456-J</span></p>
                    <p class="mb-0" id="footer-copyright-name">© 2024 {{$tenant->name}}. Todos os direitos reservados.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-uppercase fw-bold mb-4">Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#inicio" class="footer-link">Início</a></li>
                        <li class="mb-2"><a href="#imoveis" class="footer-link">Imóveis</a></li>
                        <li class="mb-2"><a href="#sobre" class="footer-link">A Empresa</a></li>
                        <li class="mb-2"><a href="#contato" class="footer-link">Contato</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase fw-bold mb-4">Contato</h5>
                    <ul class="list-unstyled">
                        @if(!empty($tenant->phones))
                            @foreach($tenant->phones as $phone)
                                @if(is_string($phone))
                                    <li class="mb-2"><a href="tel:{{$phone}}" class="footer-link"><i class="fa-solid fa-phone me-2"></i> {{$phone}}</a></li>
                                @elseif(is_array($phone) && isset($phone['number']))
                                    @php
                                        $whatsNumber = preg_replace('/\D/', '', $phone['number']);
                                    @endphp
                                    <li class="mb-2">
                                        @if(array_key_exists('whatsapp', $phone) && $phone['whatsapp'])
                                            <a href="https://wa.me/{{$whatsNumber}}" class="footer-link" target="_blank" rel="noopener" title="WhatsApp">
                                                <i class="fa-brands fa-whatsapp me-2 text-success"></i>{{$phone['number']}}
                                            </a>
                                        @else
                                            <a href="tel:{{$phone['number']}}" class="footer-link"><i class="fa-solid fa-phone me-2"></i> {{$phone['number']}}</a>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        @if(!empty($tenant->email))
                            <li class="mb-2"><a href="mailto:{{$tenant->email}}" class="footer-link"><i class="fa-solid fa-envelope me-2"></i> {{$tenant->email}}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-uppercase fw-bold mb-4">Siga-nos</h5>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        @if(!empty($tenant->social))
                            @foreach($tenant->social as $key => $url)
                                @if($url)
                                    <a href="{{$url}}" class="fs-4 footer-social-link" target="_blank"><i class="fa-brands fa-{{$key}}"></i></a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>


    

    <div id="compare-tray" class="compare-tray">
        <div class="container d-flex justify-content-between align-items-center h-100">
            <div class="compare-tray-info">
                <strong>Comparar Imóveis</strong>
                <span id="compare-count" class="ms-2"></span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button id="compare-now-btn" class="btn btn-accent" disabled>Comparar Agora</button>
                <button id="clear-compare-btn" class="btn btn-outline-light">&times; Limpar</button>
            </div>
        </div>
    </div>

    <div class="property-modal" id="compare-modal">
        <button class="property-modal-close" id="compare-modal-close-btn">&times;</button>
        <div class="property-modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title">Comparativo de Imóveis</h5>
            </div>
            <div class="modal-body-custom" id="compare-modal-body"></div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="contact-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"><i class="fa-solid fa-check-circle me-2"></i>Sua mensagem foi enviada com sucesso!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="compare-toast" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
             <div class="d-flex">
                <div class="toast-body"><i class="fa-solid fa-triangle-exclamation me-2"></i>Você pode comparar no máximo 4 imóveis.</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div id="chat-widget-container">
        <div id="chat-bubble" class="chat-bubble">
            <i class="fa-solid fa-comments fa-xl"></i>
            <span id="chat-notification-badge" class="chat-notification-badge">1</span>
        </div>
        <div id="chat-window" class="chat-window">
            <div class="chat-header">
                <div class="chat-avatar-wrapper">
                    <img id="chat-avatar" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=100&auto=format&fit=crop" alt="Atendente">
                    <span class="chat-status-online"></span>
                </div>
                <div class="chat-attendant-info">
                    <div id="chat-attendant-name" class="chat-attendant-name">Amanda Souza</div>
                    <div id="chat-attendant-role" class="chat-attendant-role">Corretora Online</div>
                </div>
                <button id="chat-restart-btn" class="chat-control-btn" title="Reiniciar conversa"><i class="fa-solid fa-rotate-right"></i></button>
                <button id="chat-close-btn" class="chat-control-btn" title="Fechar"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="chat-messages" class="chat-messages"></div>
            <div id="chat-form-container" class="chat-form-container" style="display: none;"></div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let propertiesData = [];
    let currentPage = 1, propertiesPerPage = 6, currentView = 'grid';
    let showingOnlyFavorites = false;
    // ...existing code...
    // Carrega os imóveis da API do tenant
    async function fetchPropertiesData() {
            try {
                let params;
                if (showingOnlyFavorites) {
                    // Busca favoritos do backend
                    const response = await fetch('/api/favorites/properties');
                    if (!response.ok) throw new Error('Erro ao buscar favoritos');
                    const data = await response.json();
                    propertiesData = Array.isArray(data) ? data : (data.data || []);
                    window.lastPage = 1;
                    window.lastTotal = propertiesData.length;
                    render();
                    return;
                }
                // Monta query string com filtros do formulário
                const propertyType = document.getElementById('property-type').value;
                const purpose = document.getElementById('purpose-select').value;
                const location = document.getElementById('location-code').value;
                const priceRange = (document.getElementById('price-range')?.value || '0-999999999').split('-');
                const minPrice = priceRange[0];
                const maxPrice = priceRange[1];
                const bedrooms = Array.from(document.querySelectorAll('input[name="bedrooms"]:checked')).map(cb => cb.value).join(',');
                const bathrooms = Array.from(document.querySelectorAll('input[name="bathrooms"]:checked')).map(cb => cb.value).join(',');
                const parking = Array.from(document.querySelectorAll('input[name="parking"]:checked')).map(cb => cb.value).join(',');
                const perPage = propertiesPerPage;
                const page = currentPage;

                params = new URLSearchParams({
                    type: propertyType,
                    purpose,
                    location,
                    minPrice,
                    maxPrice,
                    bedrooms,
                    bathrooms,
                    parking,
                    per_page: perPage,
                    page
                });
                const response = await fetch(`/api/properties?${params.toString()}`);
                if (!response.ok) throw new Error('Erro ao buscar imóveis');
                const data = await response.json();
                propertiesData = Array.isArray(data) ? data : (data.data || []);
                window.lastPage = data.last_page || 1;
                window.lastTotal = data.total || propertiesData.length;
                render();
            } catch (e) {
                console.error('Erro ao buscar imóveis:', e);
            }
        }
    // Removido chamada duplicada, agora só é chamada dentro do DOMContentLoaded
        // =================================================================
        // INÍCIO: LÓGICA PRINCIPAL DO SITE (REMOVIDO FETCH)
        // Paginação baseada no backend
        function setupPaginationBackend() {
            const paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = '';
            const pageCount = window.lastPage || 1;
            if (pageCount <= 1) return;
            const ul = document.createElement('ul');
            ul.className = 'pagination justify-content-center';
            const createPageItem = (text, pageNum, isDisabled = false, isActive = false) => {
                const li = document.createElement('li');
                li.className = `page-item ${isDisabled ? 'disabled' : ''} ${isActive ? 'active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#imoveis';
                a.innerHTML = text;
                if (!isDisabled) {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = pageNum;
                        fetchPropertiesData();
                        document.getElementById('imoveis').scrollIntoView({ behavior: 'smooth' });
                    });
                }
                li.appendChild(a);
                return li;
            };
            ul.appendChild(createPageItem('<i class="fa-solid fa-chevron-left"></i>', currentPage - 1, currentPage === 1));
            for (let i = 1; i <= pageCount; i++) ul.appendChild(createPageItem(i, i, false, i === currentPage));
            ul.appendChild(createPageItem('<i class="fa-solid fa-chevron-right"></i>', currentPage + 1, currentPage === pageCount));
            paginationControls.appendChild(ul);
        }
        // =================================================================
    let currentSort = { key: 'price', order: 'asc' };
    let favorites = [];
    let compareList = JSON.parse(localStorage.getItem('compareList')) || [];
    // ...existing code...
        const MAX_COMPARE = 4;
        const priceRanges = {
            Venda: [{ value: '0-999999999', text: 'Qualquer valor' }, { value: '0-500000', text: 'Até R$ 500.000' }, { value: '500001-1000000', text: 'R$ 500 mil - R$ 1 milhão' }, { value: '1000001-2000000', text: 'R$ 1 milhão - R$ 2 milhões' }, { value: '2000001-5000000', text: 'R$ 2 milhões - R$ 5 milhões' }, { value: '5000001-999999999', text: 'Acima de R$ 5 milhões' }],
            Aluguel: [{ value: '0-999999999', text: 'Qualquer valor' }, { value: '0-2000', text: 'Até R$ 2.000' }, { value: '2001-5000', text: 'R$ 2.000 - R$ 5.000' }, { value: '5001-10000', text: 'R$ 5.000 - R$ 10.000' }, { value: '10001-999999999', text: 'Acima de R$ 10.000' }],
            Temporada: [{ value: '0-999999999', text: 'Qualquer valor (diária)' }, { value: '0-500', text: 'Até R$ 500' }, { value: '501-1500', text: 'R$ 500 - R$ 1.500' }, { value: '1501-3000', text: 'R$ 1.500 - R$ 3.000' }, { value: '3001-999999999', text: 'Acima de R$ 3.000' }]
        };
        const formatFullCurrency = (value) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
        const applyPhoneMask = (event) => {
            let input = event.target;
            input.value = phoneMask(input.value);
        }
        const phoneMask = (value) => {
            if (!value) return "";
            value = value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, "($1) $2");
            value = value.replace(/(\d)(\d{4})$/, "$1-$2");
            return value;
        }

        function updatePriceFilterOptions() {
            const purpose = document.getElementById('purpose-select').value || 'Venda';
            const ranges = priceRanges[purpose] || priceRanges['Venda'];
            const priceRangeSelect = document.getElementById('price-range');
            priceRangeSelect.innerHTML = '';
            ranges.forEach(range => {
                const option = document.createElement('option');
                option.value = range.value;
                option.textContent = range.text;
                priceRangeSelect.appendChild(option);
            });
        }

        function applyFilters() {
            // Filtro agora é feito no backend, apenas retorna todos os imóveis carregados
            return propertiesData;
        }

        function sortProperties(properties) {
            const { key, order } = currentSort;
            if (!key) return properties;
            return [...properties].sort((a, b) => order === 'asc' ? a[key] - b[key] : b[key] - a[key]);
        }

        function displayProperties(page, properties) {
            const propertyList = document.getElementById('property-list');
            const noResultsDiv = document.getElementById('no-results');
            propertyList.innerHTML = '';
            noResultsDiv.style.display = properties.length === 0 ? 'block' : 'none';
            propertyList.className = currentView === 'grid' ? 'row g-4' : 'list-group';
            // Mostra todos os imóveis recebidos do backend (já paginados)
            for (const prop of properties) {
                // Detect current route from window.location.pathname
                let isHomePage = window.location.pathname === '/' || window.location.pathname === '/inicio';
                const colLgClass = isHomePage ? 'col-lg-3' : 'col-lg-4';
                const colClass = currentView === 'grid' ? `col-md-6 ${colLgClass} d-flex align-items-stretch` : 'mb-4';
                let imagesHTML = '';
                if (prop.imagens && prop.imagens.length > 0) {
                    imagesHTML = prop.imagens.map((img, index) => `<div class="carousel-item ${index === 0 ? 'active' : ''}"><img src="${img}" class="d-block w-100" alt="Foto ${index + 1} de ${prop.title}"></div>`).join('');
                } else {
                    imagesHTML = `<div class="carousel-item active"><img src="https://placehold.co/600x400/eee/ccc?text=Sem+Foto" class="d-block w-100" alt="Imagem não disponível"></div>`;
                }
                const carouselId = `carousel-prop-${prop.id}`;
                const isFavorited = favorites.includes(prop.id);
                const isCompared = compareList.includes(prop.id);
                let featuresHTML = '';
                if (prop.bedrooms > 0) featuresHTML += `<span><i class="fa-solid fa-bed"></i> ${prop.bedrooms}</span>`;
                if (prop.bathrooms > 0) featuresHTML += `<span><i class="fa-solid fa-bath"></i> ${prop.bathrooms}</span>`;
                if (prop.parking > 0) featuresHTML += `<span><i class="fa-solid fa-car"></i> ${prop.parking}</span>`;
                if (prop.area > 0) featuresHTML += `<span><i class="fa-solid fa-ruler-combined"></i> ${prop.area} m²</span>`;
                const propertyCardHTML = `<div class="${colClass}"><div class="card property-card"><div class="img-container"><div id="${carouselId}" class="carousel slide" data-bs-ride="false" data-bs-interval="false"><div class="carousel-inner">${imagesHTML}</div>${prop.imagens && prop.imagens.length > 1 ? `<button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button><button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>` : ''}</div><span class="property-purpose-badge">${prop.purpose}</span><div class="card-actions"><button class="btn-action btn-favorite ${isFavorited ? 'favorited' : ''}" data-property-id="${prop.id}" title="Adicionar aos favoritos"><i class="${isFavorited ? 'fa-solid' : 'fa-regular'} fa-heart"></i></button><label for="compare-${prop.id}" class="btn-action compare-label ${isCompared ? 'checked' : ''}" title="Comparar imóvel"><input class="compare-checkbox" type="checkbox" id="compare-${prop.id}" data-property-id="${prop.id}" ${isCompared ? 'checked' : ''}><i class="fa-solid fa-scale-balanced"></i></label></div></div><div class="card-body" data-property-id="${prop.id}"><h5 class="card-title text-truncate">${prop.title}</h5><p class="text-muted small">Cód: ${prop.code}</p><p class="text-muted small text-truncate"><i class="fa-solid fa-map-marker-alt me-1"></i> ${prop.location}</p><h6 class="property-card-price my-2">${formatFullCurrency(prop.price)}</h6><div class="property-features mt-auto">${featuresHTML}</div></div></div></div>`;
                propertyList.innerHTML += propertyCardHTML;
            }
        }

        function setupPagination(properties) {
            const paginationControls = document.getElementById('pagination-controls');
            paginationControls.innerHTML = '';
            const pageCount = Math.ceil(properties.length / propertiesPerPage);
            if (pageCount <= 1) return;
            const ul = document.createElement('ul');
            ul.className = 'pagination justify-content-center';
            const createPageItem = (text, pageNum, isDisabled = false, isActive = false) => {
                const li = document.createElement('li');
                li.className = `page-item ${isDisabled ? 'disabled' : ''} ${isActive ? 'active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#imoveis';
                a.innerHTML = text;
                if (!isDisabled) {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = pageNum;
                        render();
                        document.getElementById('imoveis').scrollIntoView({ behavior: 'smooth' });
                    });
                }
                li.appendChild(a);
                return li;
            };
            ul.appendChild(createPageItem('<i class="fa-solid fa-chevron-left"></i>', currentPage - 1, currentPage === 1));
            for (let i = 1; i <= pageCount; i++) ul.appendChild(createPageItem(i, i, false, i === currentPage));
            ul.appendChild(createPageItem('<i class="fa-solid fa-chevron-right"></i>', currentPage + 1, currentPage === pageCount));
            paginationControls.appendChild(ul);
        }

        function render() {
            const propertyCountSpan = document.getElementById('property-count');
            let filteredProperties = propertiesData;
            // Se modo favoritos, exibe só os imóveis retornados do backend
            if (showingOnlyFavorites) {
                filteredProperties = propertiesData;
                propertyCountSpan.textContent = `(${filteredProperties.length} favoritos)`;
                displayProperties(1, sortProperties(filteredProperties));
                document.getElementById('pagination-controls').innerHTML = '';
                return;
            }
            // Se modo comparação, busca imóveis do backend para os IDs em compareList
            if (compareList.length > 0 && window.showingCompareOnly) {
                fetch(`/api/properties?ids=${compareList.join(',')}`)
                    .then(res => res.json())
                    .then(data => {
                        filteredProperties = Array.isArray(data) ? data : (data.data || []);
                        propertyCountSpan.textContent = `(${filteredProperties.length} para comparar)`;
                        displayProperties(1, sortProperties(filteredProperties));
                        document.getElementById('pagination-controls').innerHTML = '';
                    });
                return;
            }
            filteredProperties = sortProperties(filteredProperties);
            propertyCountSpan.textContent = `(${window.lastTotal || 0} encontrados)`;
            displayProperties(currentPage, filteredProperties);
            setupPaginationBackend();
        }

        function updateActiveButtons() {
            const gridViewBtn = document.getElementById('grid-view-btn');
            const listViewBtn = document.getElementById('list-view-btn');
            const sortPriceAscBtn = document.getElementById('sort-price-asc');
            const sortPriceDescBtn = document.getElementById('sort-price-desc');
            const showFavoritesBtn = document.getElementById('show-favorites-btn');

            gridViewBtn.classList.toggle('active', currentView === 'grid');
            listViewBtn.classList.toggle('active', currentView === 'list');
            sortPriceAscBtn.classList.toggle('active', currentSort.key === 'price' && currentSort.order === 'asc');
            sortPriceDescBtn.classList.toggle('active', currentSort.key === 'price' && currentSort.order === 'desc');
            showFavoritesBtn.classList.toggle('active', showingOnlyFavorites);
        }

        function showCustomToast(message, type) {
            const toastElement = document.getElementById(`${type}-toast`);
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement);
                const toastBody = toastElement.querySelector('.toast-body');
                toastBody.innerHTML = `<i class="fa-solid fa-check-circle me-2"></i>${message}`;
                toast.show();
            }
        }

        function handleFormSubmit(formElement) {
            formElement.classList.add('was-validated');
            if (!formElement.checkValidity()) return;
            const submitButton = formElement.querySelector('[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Enviando...`;
            
            // Simulação de envio de formulário
            setTimeout(() => {
                showCustomToast('Sua mensagem foi enviada com sucesso!', 'contact');
                formElement.reset();
                formElement.classList.remove('was-validated');
                if (formElement.id === 'modal-contact-form') {
                    //closePropertyModal();
                }
                if (formElement.id === 'widget-form') {
                    document.getElementById('chat-form-container').style.display = 'none';
                    document.getElementById('chat-messages').style.display = 'flex';
                    addMessageToChat("✅ Sucesso! Seus dados foram enviados. Um corretor entrará em contato em breve.", 'attendant');
                }
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }, 1500);
        }
        
        function toggleFavorite(propertyId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ property_id: propertyId })
            })
            .then(res => res.json())
            .then(data => {
                favorites = data.favorites || [];
                // Se estiver mostrando favoritos, atualiza lista, senão só atualiza ícones
                if (showingOnlyFavorites) {
                    fetch('/api/favorites/properties')
                        .then(res => res.json())
                        .then(favData => {
                            propertiesData = Array.isArray(favData) ? favData : (favData.data || []);
                            render();
                        });
                } else {
                    render();
                }
            });
        }

        function toggleCompare(propertyId, isChecked) {
            const index = compareList.indexOf(propertyId);
            if (isChecked) {
                if (index === -1 && compareList.length < MAX_COMPARE) {
                    compareList.push(propertyId);
                } else if (compareList.length >= MAX_COMPARE) {
                    showCustomToast('Você pode comparar no máximo 4 imóveis.', 'compare');
                    document.getElementById(`compare-${propertyId}`).checked = false;
                }
            } else {
                if (index > -1) {
                    compareList.splice(index, 1);
                }
            }
            localStorage.setItem('compareList', JSON.stringify(compareList));
            updateCompareTray();
        }

        function updateCompareTray() {
            const compareTray = document.getElementById('compare-tray');
            const compareCountSpan = document.getElementById('compare-count');
            const compareNowBtn = document.getElementById('compare-now-btn');
            const count = compareList.length;
            if (count > 0) {
                compareCountSpan.textContent = `(${count}/${MAX_COMPARE} selecionados)`;
                compareNowBtn.disabled = count < 2;
                compareTray.classList.add('show');
            } else {
                compareTray.classList.remove('show');
            }
        }

        function clearCompareList() {
            compareList = [];
            localStorage.removeItem('compareList');
            updateCompareTray();
            render();
        }

        function openCompareModal() {
            const compareModal = document.getElementById('compare-modal');
            const compareModalBody = document.getElementById('compare-modal-body');
            const propertiesToCompare = propertiesData.filter(p => compareList.includes(p.id));
            if (propertiesToCompare.length < 2) return;
            const isMobile = window.innerWidth <= 767;
            let modalContentHTML = '';
            if (isMobile) {
                modalContentHTML += '<div class="compare-mobile-container"><div class="compare-mobile-header">';
                propertiesToCompare.forEach(prop => {
                    modalContentHTML += `<div class="compare-mobile-property"><img src="${prop.imagens && prop.imagens.length > 0 ? prop.imagens[0] : 'https://placehold.co/400x300'}" alt="${prop.title}"><h6>${prop.title}</h6><small class="text-muted">Cód: ${prop.code}</small></div>`;
                });
                modalContentHTML += '</div>';
                const createFeatureGroup = (header, key, formatter = val => val) => {
                    let groupHTML = `<div class="compare-feature-group"><h5 class="compare-feature-title">${header}</h5>`;
                    propertiesToCompare.forEach(prop => {
                        const value = prop[key];
                        const finalFormatter = key === 'area' ? v => `${v} m²` : formatter;
                        const displayValue = value > 0 || (typeof value === 'string' && value !== '') ? finalFormatter(value) : '-';
                        groupHTML += `<p><strong>${prop.title}:</strong> ${displayValue}</p>`;
                    });
                    groupHTML += `</div>`;
                    return groupHTML;
                };
                modalContentHTML += createFeatureGroup('Finalidade', 'purpose');
                modalContentHTML += createFeatureGroup('Preço', 'price', formatFullCurrency);
                modalContentHTML += createFeatureGroup('Tipo', 'type');
                modalContentHTML += createFeatureGroup('Localização', 'location');
                modalContentHTML += createFeatureGroup('Quartos', 'bedrooms', val => val);
                modalContentHTML += createFeatureGroup('Banheiros', 'bathrooms', val => val);
                modalContentHTML += createFeatureGroup('Vagas', 'parking', val => val);
                modalContentHTML += createFeatureGroup('Área (m²)', 'area', val => val);
                modalContentHTML += '</div>';
            } else {
                modalContentHTML = '<div class="table-responsive"><table class="table table-bordered compare-table align-middle text-center">';
                let headerHTML = '<thead><tr><th class="text-start">Característica</th>';
                propertiesToCompare.forEach(prop => {
                    headerHTML += `<th><img src="${prop.imagens && prop.imagens.length > 0 ? prop.imagens[0] : 'https://placehold.co/600x400'}" alt="${prop.title}" class="img-fluid rounded-3 mb-2"><h6>${prop.title}</h6><small class="text-muted">Cód: ${prop.code}</small></th>`;
                });
                headerHTML += '</tr></thead>';
                modalContentHTML += headerHTML;
                const createRow = (header, key, formatter = val => val) => {
                    let row = `<tr><td class="fw-bold text-start">${header}</td>`;
                    propertiesToCompare.forEach(prop => {
                        const value = prop[key];
                        const finalFormatter = key === 'area' ? v => `${v} m²` : formatter;
                        const displayValue = value > 0 || (typeof value === 'string' && value !== '') ? finalFormatter(value, prop) : '-';
                        row += `<td>${displayValue}</td>`;
                    });
                    row += `</tr>`;
                    return row;
                };
                let bodyHTML = '<tbody>';
                bodyHTML += createRow('Finalidade', 'purpose');
                bodyHTML += createRow('Preço', 'price', formatFullCurrency);
                bodyHTML += createRow('Tipo', 'type');
                bodyHTML += createRow('Localização', 'location');
                bodyHTML += createRow('Quartos', 'bedrooms', val => val);
                bodyHTML += createRow('Banheiros', 'bathrooms', val => val);
                bodyHTML += createRow('Vagas', 'parking', val => val);
                bodyHTML += createRow('Área', 'area');
                bodyHTML += '</tbody>';
                modalContentHTML += bodyHTML;
                modalContentHTML += '</table></div>';
            }
            compareModalBody.innerHTML = modalContentHTML;
            compareModal.style.display = 'block';
            setTimeout(() => compareModal.classList.add('show'), 10);
            document.body.classList.add('modal-open');
        }

        function closeCompareModal() {
            const compareModal = document.getElementById('compare-modal');
            compareModal.classList.remove('show');
            setTimeout(() => {
                compareModal.style.display = 'none';
                if (!document.querySelector('.property-modal.show')) {
                    document.body.classList.remove('modal-open');
                }
                // Ao fechar o modal, volta ao modo padrão
                window.showingCompareOnly = false;
                fetchPropertiesData();
            }, 300);
        }

        function initializeChatWidget() {
            const chatBubble = document.getElementById('chat-bubble');
            const chatWindow = document.getElementById('chat-window');
            const closeBtn = document.getElementById('chat-close-btn');
            const restartBtn = document.getElementById('chat-restart-btn');
            let isChatOpen = false, conversationStarted = false;

            function toggleChatWindow() {
                isChatOpen = !isChatOpen;
                chatWindow.classList.toggle('open');
                if (isChatOpen && !conversationStarted) {
                    startConversation();
                }
                if (isChatOpen) {
                    document.getElementById('chat-notification-badge').style.display = 'none';
                }
            }

            function restartConversation() {
                document.getElementById('chat-messages').innerHTML = '';
                document.getElementById('chat-form-container').innerHTML = '';
                document.getElementById('chat-form-container').style.display = 'none';
                document.getElementById('chat-messages').style.display = 'flex';
                conversationStarted = false;
                startConversation();
            }

            function addMessageToChat(text, sender = 'attendant') {
                const messagesContainer = document.getElementById('chat-messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message-container ${sender}`;
                const bubble = document.createElement('div');
                bubble.className = 'message-bubble';
                bubble.innerHTML = text;
                messageDiv.appendChild(bubble);
                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function showTyping(show = true) {
                const messagesContainer = document.getElementById('chat-messages');
                let typingDiv = messagesContainer.querySelector('.typing-indicator');
                if (show && !typingDiv) {
                    typingDiv = document.createElement('div');
                    typingDiv.className = 'message-container attendant';
                    typingDiv.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
                    messagesContainer.appendChild(typingDiv);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } else if (!show && typingDiv) {
                    typingDiv.remove();
                }
            }

            function startConversation() {
                conversationStarted = true;
                setTimeout(() => { showTyping(); }, 500);
                setTimeout(() => { showTyping(false); addMessageToChat(`Olá! 👋 Bem-vindo(a) ao nosso site!`); }, 2000);
                setTimeout(() => { showTyping(); }, 2500);
                setTimeout(() => { showTyping(false); addMessageToChat(`Eu sou seu assistente virtual e estou aqui para te ajudar a encontrar o imóvel ideal.`); }, 4500);
                setTimeout(() => { showTyping(); }, 5000);
                setTimeout(() => { showTyping(false); addMessageToChat(`Você gostaria de falar com um de nossos corretores especialistas?`); }, 7000);
                setTimeout(() => { showChoiceButtons(); }, 7500);
            }

            function showChoiceButtons() {
                const messagesContainer = document.getElementById('chat-messages');
                const choicesDiv = document.createElement('div');
                choicesDiv.className = 'message-container interactive-area';
                choicesDiv.innerHTML = `<div class="chat-choices"><button id="choice-yes" class="btn btn-primary">Sim, por favor</button><button id="choice-no" class="btn btn-outline-secondary">Não, obrigado</button></div>`;
                messagesContainer.appendChild(choicesDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                document.getElementById('choice-yes').addEventListener('click', handleChoiceYes);
                document.getElementById('choice-no').addEventListener('click', handleChoiceNo);
            }

            function handleChoiceYes() {
                document.querySelector('.interactive-area').remove();
                addMessageToChat("Sim, por favor", 'user-choice');
                setTimeout(() => { showTyping(); }, 500);
                setTimeout(() => { showTyping(false); addMessageToChat("Excelente escolha! Para agilizar, preciso de apenas alguns detalhes."); }, 1500);
                setTimeout(() => { showContactForm(); }, 2500);
            }

            function handleChoiceNo() {
                document.querySelector('.interactive-area').remove();
                addMessageToChat("Não, obrigado", 'user-choice');
                setTimeout(() => { showTyping(); }, 500);
                setTimeout(() => { showTyping(false); addMessageToChat("Entendido! Fique à vontade para explorar. Se mudar de ideia, é só clicar no ícone de reiniciar ali em cima. 😉"); }, 1500);
            }

            function showContactForm() {
                document.getElementById('chat-messages').style.display = 'none';
                const chatFormContainer = document.getElementById('chat-form-container');
                chatFormContainer.style.display = 'block';
                chatFormContainer.innerHTML = `<form id="widget-form" class="needs-validation h-100 d-flex flex-column" novalidate><h6 class="text-center mb-3">Informações para Contato</h6><div class="mb-2"><label class="form-label">Nome</label><input type="text" name="name" class="form-control form-control-sm" required></div><div class="mb-2"><label class="form-label">Telefone</label><input type="tel" id="widget-phone" name="phone" class="form-control form-control-sm" maxlength="15" required></div><div class="mb-2"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control form-control-sm" required></div><div class="mb-2"><label class="form-label">Que tipo de imóvel você procura? <span class="text-muted">(Opcional)</span></label><textarea name="message" class="form-control form-control-sm" rows="2"></textarea></div><div class="mt-auto"><button type="submit" class="btn btn-primary w-100">Enviar para Corretor</button></div></form>`;
                const widgetForm = document.getElementById('widget-form');
                widgetForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmit(this);
                });
                document.getElementById('widget-phone').addEventListener('input', applyPhoneMask);
            }

            chatBubble.addEventListener('click', toggleChatWindow);
            closeBtn.addEventListener('click', toggleChatWindow);
            restartBtn.addEventListener('click', restartConversation);
            if (!sessionStorage.getItem('chatWidgetFirstVisit')) {
                setTimeout(() => {
                    document.getElementById('chat-notification-badge').style.display = 'flex';
                    sessionStorage.setItem('chatWidgetFirstVisit', 'true');
                }, 5000);
            }
        }

        function initializeScrollSpy() {
            const sections = document.querySelectorAll('main > section[id]');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link[href^="#"]');
            if (sections.length === 0 || navLinks.length === 0) return;
            const observerOptions = { rootMargin: '-100px 0px -50% 0px', threshold: 0 };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const targetId = entry.target.getAttribute('id');
                        navLinks.forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === `#${targetId}`) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            }, observerOptions);
            sections.forEach(section => { observer.observe(section); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const propertyListContainer = document.getElementById('property-list-container');
            const propertyList = document.getElementById('property-list');
            const itemsPerPageSelect = document.getElementById('items-per-page-select');
            const sortPriceAscBtn = document.getElementById('sort-price-asc');
            const sortPriceDescBtn = document.getElementById('sort-price-desc');
            const purposeSelect = document.getElementById('purpose-select');
            const showFavoritesBtn = document.getElementById('show-favorites-btn');
            const compareNowBtn = document.getElementById('compare-now-btn');
            const clearCompareBtn = document.getElementById('clear-compare-btn');
            const compareModalCloseBtn = document.getElementById('compare-modal-close-btn');
            const compareModal = document.getElementById('compare-modal');
            //const showInterestFormBtn = document.getElementById('show-interest-form-btn');
            const backToDetailsBtn = document.getElementById('back-to-details-btn');
            const mainContactForm = document.getElementById('main-contact-form');
            //const modalContactForm = document.getElementById('modal-contact-form');
            const filterToggleButton = document.getElementById('filter-toggle-btn');
            const advancedFilters = document.getElementById('advanced-filters');
            //const modalCloseBtn = document.getElementById('modal-close-btn');
            const modal = document.getElementById('property-modal');
            const gridViewBtn = document.getElementById('grid-view-btn');
            const listViewBtn = document.getElementById('list-view-btn');

            updatePriceFilterOptions();
            updateActiveButtons();
            updateCompareTray();
            // Ao carregar, sempre inicia no modo padrão (não favoritos)
            showingOnlyFavorites = false;
            fetchPropertiesData();
            initializeScrollSpy();
            initializeChatWidget();

            form.addEventListener('input', () => { currentPage = 1; fetchPropertiesData(); });
            form.addEventListener('submit', (e) => { e.preventDefault(); currentPage = 1; fetchPropertiesData(); });
            if (itemsPerPageSelect) {
                itemsPerPageSelect.addEventListener('change', (e) => { propertiesPerPage = parseInt(e.target.value, 10); currentPage = 1; fetchPropertiesData(); });
            }
            purposeSelect.addEventListener('change', () => { updatePriceFilterOptions(); });
            gridViewBtn.addEventListener('click', () => { currentView = 'grid'; propertyListContainer.classList.remove('list-view'); updateActiveButtons(); render(); });
            listViewBtn.addEventListener('click', () => { currentView = 'list'; propertyListContainer.classList.add('list-view'); updateActiveButtons(); render(); });
            sortPriceAscBtn.addEventListener('click', () => { currentSort = { key: 'price', order: 'asc' }; currentPage = 1; updateActiveButtons(); render(); });
            sortPriceDescBtn.addEventListener('click', () => { currentSort = { key: 'price', order: 'desc' }; currentPage = 1; updateActiveButtons(); render(); });
            showFavoritesBtn.addEventListener('click', () => {
                showingOnlyFavorites = !showingOnlyFavorites;
                updateActiveButtons();
                currentPage = 1;
                if (showingOnlyFavorites) {
                    fetch('/api/favorites/properties')
                        .then(res => res.json())
                        .then(favData => {
                            favorites = Array.isArray(favData) ? favData.map(p => p.id) : (favData.data ? favData.data.map(p => p.id) : []);
                            propertiesData = Array.isArray(favData) ? favData : (favData.data || []);
                            render();
                        });
                } else {
                    fetchPropertiesData();
                }
            });
            clearCompareBtn.addEventListener('click', clearCompareList);
            // Removido botão e função Mostrar Comparação
            compareNowBtn.addEventListener('click', function() {
                if (compareList.length < 2 || compareList.length > 4) return;
                // Ativa modo comparação e busca do backend
                window.showingCompareOnly = true;
                fetch(`/api/properties?ids=${compareList.join(',')}`)
                    .then(res => res.json())
                    .then(data => {
                        // Exibe modal com os imóveis buscados
                        const propertiesToCompare = Array.isArray(data) ? data : (data.data || []);
                        showCompareModal(propertiesToCompare);
                    });
            });
        // Função para exibir o modal de comparação com dados do backend
        function showCompareModal(propertiesToCompare) {
            const compareModal = document.getElementById('compare-modal');
            const compareModalBody = document.getElementById('compare-modal-body');
            if (propertiesToCompare.length < 2) return;
            const isMobile = window.innerWidth <= 767;
            let modalContentHTML = '';
            if (isMobile) {
                modalContentHTML += '<div class="compare-mobile-container"><div class="compare-mobile-header">';
                propertiesToCompare.forEach(prop => {
                    modalContentHTML += `<div class="compare-mobile-property"><img src="${prop.imagens && prop.imagens.length > 0 ? prop.imagens[0] : 'https://placehold.co/400x300'}" alt="${prop.title}"><h6>${prop.title}</h6><small class="text-muted">Cód: ${prop.code}</small></div>`;
                });
                modalContentHTML += '</div>';
                const createFeatureGroup = (header, key, formatter = val => val) => {
                    let groupHTML = `<div class="compare-feature-group"><h5 class="compare-feature-title">${header}</h5>`;
                    propertiesToCompare.forEach(prop => {
                        const value = prop[key];
                        const finalFormatter = key === 'area' ? v => `${v} m²` : formatter;
                        const displayValue = value > 0 || (typeof value === 'string' && value !== '') ? finalFormatter(value) : '-';
                        groupHTML += `<p><strong>${prop.title}:</strong> ${displayValue}</p>`;
                    });
                    groupHTML += `</div>`;
                    return groupHTML;
                };
                modalContentHTML += createFeatureGroup('Finalidade', 'purpose');
                modalContentHTML += createFeatureGroup('Preço', 'price', formatFullCurrency);
                modalContentHTML += createFeatureGroup('Tipo', 'type');
                modalContentHTML += createFeatureGroup('Localização', 'location');
                modalContentHTML += createFeatureGroup('Quartos', 'bedrooms', val => val);
                modalContentHTML += createFeatureGroup('Banheiros', 'bathrooms', val => val);
                modalContentHTML += createFeatureGroup('Vagas', 'parking', val => val);
                modalContentHTML += createFeatureGroup('Área (m²)', 'area', val => val);
                modalContentHTML += '</div>';
            } else {
                modalContentHTML = '<div class="table-responsive"><table class="table table-bordered compare-table align-middle text-center">';
                let headerHTML = '<thead><tr><th class="text-start">Característica</th>';
                propertiesToCompare.forEach(prop => {
                    headerHTML += `<th><img src="${prop.imagens && prop.imagens.length > 0 ? prop.imagens[0] : 'https://placehold.co/600x400'}" alt="${prop.title}" class="img-fluid rounded-3 mb-2"><h6>${prop.title}</h6><small class="text-muted">Cód: ${prop.code}</small></th>`;
                });
                headerHTML += '</tr></thead>';
                modalContentHTML += headerHTML;
                const createRow = (header, key, formatter = val => val) => {
                    let row = `<tr><td class="fw-bold text-start">${header}</td>`;
                    propertiesToCompare.forEach(prop => {
                        const value = prop[key];
                        const finalFormatter = key === 'area' ? v => `${v} m²` : formatter;
                        const displayValue = value > 0 || (typeof value === 'string' && value !== '') ? finalFormatter(value, prop) : '-';
                        row += `<td>${displayValue}</td>`;
                    });
                    row += `</tr>`;
                    return row;
                };
                let bodyHTML = '<tbody>';
                bodyHTML += createRow('Finalidade', 'purpose');
                bodyHTML += createRow('Preço', 'price', formatFullCurrency);
                bodyHTML += createRow('Tipo', 'type');
                bodyHTML += createRow('Localização', 'location');
                bodyHTML += createRow('Quartos', 'bedrooms', val => val);
                bodyHTML += createRow('Banheiros', 'bathrooms', val => val);
                bodyHTML += createRow('Vagas', 'parking', val => val);
                bodyHTML += createRow('Área', 'area');
                bodyHTML += '</tbody>';
                modalContentHTML += bodyHTML;
                modalContentHTML += '</table></div>';
            }
            compareModalBody.innerHTML = modalContentHTML;
            compareModal.style.display = 'block';
            setTimeout(() => compareModal.classList.add('show'), 10);
            document.body.classList.add('modal-open');
        }
            compareModalCloseBtn.addEventListener('click', closeCompareModal);
            compareModal.addEventListener('click', (e) => { if (e.target === compareModal) closeCompareModal(); });
            propertyList.addEventListener('click', function(e) {
                const cardBody = e.target.closest('.card-body');
                const favoriteBtn = e.target.closest('.btn-favorite');
                const compareLabel = e.target.closest('.compare-label');
                if (favoriteBtn) {
                    e.stopPropagation();
                    const propertyId = parseInt(favoriteBtn.dataset.propertyId, 10);
                    toggleFavorite(propertyId);
                    return;
                }
                if (compareLabel) { e.stopPropagation(); return; }
            });
            propertyList.addEventListener('change', function(e) {
                if (e.target.classList.contains('compare-checkbox')) {
                    const propertyId = parseInt(e.target.dataset.propertyId, 10);
                    toggleCompare(propertyId, e.target.checked);
                    e.target.closest('.compare-label').classList.toggle('checked', e.target.checked);
                }
            });
            if (filterToggleButton) {
                advancedFilters.addEventListener('show.bs.collapse', () => { filterToggleButton.innerHTML = '<i class="fa-solid fa-chevron-up me-1"></i> Recolher Filtros'; });
                advancedFilters.addEventListener('hide.bs.collapse', () => { filterToggleButton.innerHTML = '<i class="fa-solid fa-sliders me-1"></i> Filtros Avançados'; });
            }
            //modalCloseBtn.addEventListener('click', () => { history.pushState("", document.title, window.location.pathname + window.location.search); closePropertyModal(); });
            //modal.addEventListener('click', function(e) { if (e.target === modal) { history.pushState("", document.title, window.location.pathname + window.location.search); closePropertyModal(); } });
    
    
    
            const offcanvasElement = document.getElementById('offcanvasNavbar');
            if (offcanvasElement) {
                const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                const navLinks = offcanvasElement.querySelectorAll('.nav-link');
                navLinks.forEach(link => { link.addEventListener('click', () => { bsOffcanvas.hide(); }); });
            }
            mainContactForm.addEventListener('submit', function(e) { e.preventDefault(); handleFormSubmit(this); });
    
            document.getElementById('contact-phone').addEventListener('input', applyPhoneMask);
        });
    </script>
</body>
</html>
