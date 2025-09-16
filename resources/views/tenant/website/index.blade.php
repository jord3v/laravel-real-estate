@section('title', 'Página Inicial | ' . $tenant->name)
@extends('tenant.website.layout')

@section('content')
<main>
        <section id="inicio" class="hero-section">
            <div class="container text-center">
                <h1 class="display-3 fw-bold mb-4" id="hero-title">Sua Chave Para a Felicidade</h1>
                <p class="lead" id="hero-subtitle">Encontre o imóvel dos seus sonhos de forma simples e segura.</p>
            </div>
        </section>

        <section id="busca" class="search-section">
            <div class="container">
                <form id="filter-form" class="search-form-container">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select" id="property-type">
                                <option value="">Tipo de Imóvel</option>
                                <option>Apartamento</option>
                                <option>Casa</option>
                                <option>Terreno</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select" id="purpose-select">
                                <option value="">Finalidade</option>
                                <option>Venda</option>
                                <option>Aluguel</option>
                                <option>Temporada</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="location-code" placeholder="Digite bairro, cidade ou código do imóvel">
                        </div>
                    </div>
                    <div class="collapse mt-4" id="advanced-filters">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="price-range" class="form-label small fw-medium">Faixa de Preço</label>
                                <select class="form-select" id="price-range"></select>
                            </div>
                            <div class="col-lg-8">
                                <label class="form-label small fw-medium">Detalhes do Imóvel</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="filter-pills-group">
                                            <input type="checkbox" class="btn-check" name="bedrooms[]" value="1" id="bed1"><label class="btn btn-outline-primary" for="bed1">1 Quarto</label>
                                            <input type="checkbox" class="btn-check" name="bedrooms[]" value="2" id="bed2"><label class="btn btn-outline-primary" for="bed2">2 Quartos</label>
                                            <input type="checkbox" class="btn-check" name="bedrooms[]" value="3" id="bed3"><label class="btn btn-outline-primary" for="bed3">3+ Quartos</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="filter-pills-group">
                                            <input type="checkbox" class="btn-check" name="bathrooms[]" value="1" id="bath1"><label class="btn btn-outline-primary" for="bath1">1 Banheiro</label>
                                            <input type="checkbox" class="btn-check" name="bathrooms[]" value="2" id="bath2"><label class="btn btn-outline-primary" for="bath2">2 Banheiros</label>
                                            <input type="checkbox" class="btn-check" name="bathrooms[]" value="3" id="bath3"><label class="btn btn-outline-primary" for="bath3">3+ Banheiros</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="filter-pills-group">
                                            <input type="checkbox" class="btn-check" name="parking[]" value="1" id="park1"><label class="btn btn-outline-primary" for="park1">1 Vaga</label>
                                            <input type="checkbox" class="btn-check" name="parking[]" value="2" id="park2"><label class="btn btn-outline-primary" for="park2">2 Vagas</label>
                                            <input type="checkbox" class="btn-check" name="parking[]" value="3" id="park3"><label class="btn btn-outline-primary" for="park3">3+ Vagas</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3 align-items-center">
                        <div class="col-lg-9 text-lg-start text-center">
                             <a class="filter-toggle-btn" data-bs-toggle="collapse" href="#advanced-filters" role="button" aria-expanded="false" aria-controls="advanced-filters" id="filter-toggle-btn">
                                <i class="fa-solid fa-sliders me-1"></i> Filtros Avançados
                            </a>
                        </div>
                        <div class="col-lg-3 text-lg-end text-center">
                            <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-search me-2"></i>Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section id="imoveis" class="section-padding">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
                    <h2 class="section-title mb-0">Imóveis <span id="property-count" class="fw-normal fs-6 text-muted"></span></h2>
                    <div class="d-flex flex-wrap align-items-center gap-3 controls-bar">
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="items-per-page-select" style="width: auto;">
                                <option value="6" selected>6 por pág.</option>
                                <option value="9">9 por pág.</option>
                                <option value="12">12 por pág.</option>
                            </select>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="show-favorites-btn" title="Mostrar favoritos"><i class="fa-solid fa-heart"></i></button>
                            <button type="button" class="btn btn-outline-secondary" id="sort-price-asc" title="Ordenar por menor preço"><i class="fa-solid fa-arrow-down-1-9"></i></button>
                            <button type="button" class="btn btn-outline-secondary" id="sort-price-desc" title="Ordenar por maior preço"><i class="fa-solid fa-arrow-up-9-1"></i></button>
                            <button type="button" class="btn btn-outline-secondary active" id="grid-view-btn"><i class="fa-solid fa-grip"></i></button>
                            <button type="button" class="btn btn-outline-secondary" id="list-view-btn"><i class="fa-solid fa-list"></i></button>
                        </div>
                    </div>
                </div>
                <div id="property-list-container">
                    <div id="property-list" class="row g-4"></div>
                </div>
                <div id="no-results" class="text-center" style="display: none;">
                    <i class="fa-solid fa-house-circle-xmark fa-3x text-muted mb-3"></i>
                    <p class="lead">Nenhum imóvel encontrado com os filtros selecionados.</p>
                </div>
                <nav id="pagination-controls" class="mt-5"></nav>
            </div>
        </section>

        <section id="sobre" class="section-padding bg-light">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <img id="about-image" src="https://images.unsplash.com/photo-1594944883163-5464522a4505?q=80&w=1500&auto=format&fit=crop" alt="Equipe da imobiliária" class="img-fluid rounded-4 shadow-lg">
                    </div>
                    <div class="col-lg-6">
                        <h2 class="section-title" id="about-title">Sobre a {{$tenant->name}}</h2>
                        <p class="lead text-muted mb-4" id="about-subtitle">Há 15 anos transformando sonhos em realidade.</p>
                        <div id="about-content">
                            <p>A {{$tenant->name}} é sinônimo de excelência no mercado imobiliário, com mais de 15 anos de experiência. Nossa missão é oferecer um serviço personalizado e de alta qualidade, auxiliando nossos clientes a encontrar o imóvel perfeito para suas necessidades. Trabalhamos com uma vasta gama de imóveis, desde apartamentos modernos a casas aconchegantes e terrenos para construção.</p>
                            <p>Nosso compromisso vai além da simples transação. Acreditamos na construção de relacionamentos duradouros, baseados na confiança e na transparência. Nossa equipe de corretores é altamente qualificada e está sempre pronta para guiar você em cada etapa do processo, garantindo uma experiência tranquila e segura. Venha nos visitar e descubra por que somos a escolha certa para o seu futuro.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contato" class="section-padding">
            <div class="container">
                <div class="text-center mx-auto" style="max-width: 600px;">
                    <h2 class="section-title" id="contact-title">Entre em Contato</h2>
                    <p class="lead text-muted mb-3" id="contact-subtitle">Estamos prontos para te ajudar a encontrar o seu novo lar.</p>
                    @if(!empty($tenant->phones))
                        <div class="mb-2">
                            <i class="fa-solid fa-phone me-2"></i>
                            @foreach($tenant->phones as $phone)
                                @if(is_string($phone))
                                    <span>{{$phone}}</span>@if(!$loop->last), @endif
                                @elseif(is_array($phone) && isset($phone['number']))
                                    @php
                                        $whatsNumber = preg_replace('/\D/', '', $phone['number']);
                                    @endphp
                                    @if(array_key_exists('whatsapp', $phone) && $phone['whatsapp'])
                                        <a href="https://wa.me/{{$whatsNumber}}" target="_blank" rel="noopener" title="WhatsApp" style="text-decoration:none;color:inherit;">
                                            {{$phone['number']}}
                                            <i class="fa-brands fa-whatsapp ms-1" style="color:#25D366"></i>
                                        </a>
                                    @else
                                        <span>{{$phone['number']}}</span>
                                    @endif
                                    @if(!$loop->last), @endif
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($tenant->email))
                        <div class="mb-2">
                            <i class="fa-solid fa-envelope me-2"></i>
                            <span>{{$tenant->email}}</span>
                        </div>
                    @endif
                    @if(!empty($tenant->social))
                        <div class="mb-2">
                            @foreach($tenant->social as $key => $url)
                                @if($url)
                                    <a href="{{$url}}" target="_blank" class="me-2" title="{{$key}}"><i class="fa-brands fa-{{$key}} fa-lg"></i></a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form id="main-contact-form" class="needs-validation" novalidate>
                            <div class="row g-4">
                                <div class="col-md-6"><input type="text" class="form-control" id="contact-name" name="name" placeholder="Seu nome" required></div>
                                <div class="col-md-6"><input type="tel" class="form-control" id="contact-phone" name="phone" placeholder="Seu telefone" maxlength="15" required></div>
                                <div class="col-12"><input type="email" class="form-control" id="contact-email" name="email" placeholder="Seu e-mail" required></div>
                                <div class="col-12"><textarea class="form-control" id="contact-message" name="message" rows="5" placeholder="Sua mensagem" required></textarea></div>
                                <div class="col-12 text-center"><button type="submit" class="btn btn-primary btn-lg px-5">Enviar Mensagem</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection