@section('title', 'Imóvel | ' . ($tenant->name ?? 'Imobiliária'))
@extends('tenant.website.layout')

@section('content')
<main>
    <header class="mb-4 p-0" style="border:none;">
        @php
            $images = $property->getMedia('property');
            if ($images->isEmpty()) {
                $images = collect([]);
                for ($i = 1; $i <= 10; $i++) {
                    $images->push(['url' => 'https://picsum.photos/800/400?random=' . $i]);
                }
            }
        @endphp
        <!-- Slick Slider & Fancybox CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    <div class="property-header-slider mb-3" style="overflow:hidden;max-width:100vw;">
            @foreach($images as $i => $img)
                <div style="position:relative;">
                    <a data-fancybox="gallery" href="{{ isset($img['url']) ? $img['url'] : $img->getUrl('preview') }}">
                        <img src="{{ isset($img['url']) ? $img['url'] : $img->getUrl('preview') }}" class="w-100 object-fit-cover" alt="Imagem do imóvel" style="height:300px;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,0.12);">
                        @php $totalFotos = is_array($images) ? count($images) : $images->count(); @endphp
                        @if($totalFotos > 3)
                            @if($i === 2)
                                <span class="d-none d-md-flex" style="position:absolute;right:16px;bottom:16px;background:rgba(0,0,0,0.7);color:#fff;padding:8px 16px;border-radius:24px;font-size:1.1rem;display:flex;align-items:center;gap:8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 7a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5V7zm1.5.5v4h5v-4h-5z"/><path d="M1 2a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3z"/></svg>
                                    {{ $totalFotos }} fotos
                                </span>
                            @elseif($i === 0)
                                <span class="d-flex d-md-none" style="position:absolute;right:16px;bottom:16px;background:rgba(0,0,0,0.7);color:#fff;padding:8px 16px;border-radius:24px;font-size:1.1rem;display:flex;align-items:center;gap:8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 7a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5V7zm1.5.5v4h5v-4h-5z"/><path d="M1 2a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3z"/></svg>
                                    {{ $totalFotos }} fotos
                                </span>
                            @endif
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Slider no header, não precisa duplicar abaixo -->
        <style>
            .property-header-slider img:hover { box-shadow:0 4px 16px rgba(0,0,0,0.18); }
            .property-header-slider .slick-slide { padding: 0 !important; }
        </style>
        <script>
            $(document).ready(function(){
                $('.property-header-slider').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    infinite: true,
                    responsive: [
                        { breakpoint: 1200, settings: { slidesToShow: 2 } },
                        { breakpoint: 900, settings: { slidesToShow: 1 } }
                    ]
                });
                // Inicializar Fancybox apenas nos slides originais
                Fancybox.bind('.property-header-slider .slick-slide:not(.slick-cloned) a[data-fancybox="gallery"]', {
                    groupAll : true,
                });
            });
        </script>
    </header>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <section id="imovel-detalhes" class="section-padding">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
                                <div>
                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                        <h2 class="card-title fw-bold text-dark" style="font-size:2rem;">{{$property->title ?? 'Título do Imóvel'}}</h2>
                                        <div class="card-actions d-flex align-items-center gap-2 ms-3">
                                            <button type="button" class="btn-action btn-favorite" title="Favoritar" data-property-id="{{$property->id ?? 0}}">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
                                            <label class="btn-action compare-label mb-0" title="Comparar">
                                                <input type="checkbox" class="compare-checkbox" data-property-id="{{$property->id ?? 0}}" style="display:none;">
                                                <i class="fa-solid fa-scale-balanced"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 align-items-center mt-2 mb-2">
                                        <span class="fw-bold fs-5 text-dark px-3 py-1 border rounded-pill shadow-sm">{{$property->purpose ?? 'Finalidade'}}</span>
                                        <span class="fw-bold fs-5 text-dark px-3 py-1 border rounded-pill shadow-sm">{{$property->type ?? 'Tipo'}}</span>
                                        @if(!empty($property->code))<span class="fw-bold fs-5 text-dark px-3 py-1 border rounded-pill shadow-sm">Código: {{$property->code}}</span>@endif
                                    </div>
                                </div>
                                <div class="text-md-end mt-3 mt-md-0">
                                    <span class="fs-4 fw-bold text-dark">R$ {{$property->price ?? 'Preço'}}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted"><i class="fa fa-map-marker-alt me-1"></i> {{$property->location ?? 'Localização'}}</span>
                            </div>
                            <div class="row text-center mb-4 g-2">
                                <div class="col-4">
                                    <div class="bg-white border rounded-3 py-3">
                                        <div class="fs-5 fw-bold text-dark">{{$property->bedrooms ?? 'N/A'}}</div>
                                        <div class="small text-muted">Dormitórios</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white border rounded-3 py-3">
                                        <div class="fs-5 fw-bold text-dark">{{$property->bathrooms ?? 'N/A'}}</div>
                                        <div class="small text-muted">Banheiros</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white border rounded-3 py-3">
                                        <div class="fs-5 fw-bold text-dark">{{$property->parking ?? 'N/A'}}</div>
                                        <div class="small text-muted">Vagas</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="lead text-muted">{{$property->description ?? 'Descrição do imóvel.'}}</p>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="fw-bold text-dark mb-2">Infraestrutura do imóvel</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Piscina</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Salão de festas</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Academia</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Portaria 24h</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Elevador</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Playground</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-2">Proximidades</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Praia</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Supermercado</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Padaria</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Farmácia</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Escola</li>
                                        <li class="d-flex align-items-center mb-2"><i class="fa fa-check-circle text-success me-2"></i>Restaurantes</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                @if(!empty($property->area))<span class="px-2 py-1 border rounded">Área: {{$property->area}} m²</span>@endif
                            </div>
                            <div class="mt-4">
                                <a href="#contato" class="btn btn-lg btn-dark px-4 py-2 shadow-sm">Tenho interesse</a>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="imovel-share" class="section-padding">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-bold text-dark">Compartilhe este imóvel</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-success">
                                    <i class="fa fa-whatsapp me-1"></i> WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fa fa-facebook me-1"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="btn btn-outline-info">
                                    <i class="fa fa-twitter me-1"></i> Twitter
                                </a>
                                <button type="button" class="btn btn-outline-dark" onclick="navigator.clipboard.writeText(window.location.href);this.innerText='Link copiado!';setTimeout(()=>this.innerText='Copiar link',2000)">
                                    <i class="fa fa-link me-1"></i> Copiar link
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-4">
                <section id="imovel-contato" class="section-padding">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-bold text-dark">Fale com o corretor</h5>
                            <form id="imovel-contact-form" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="contact-name" class="form-label">Nome</label>
                                    <input type="text" class="form-control form-control-lg" id="contact-name" name="name" placeholder="Seu nome completo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contact-phone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control form-control-lg" id="contact-phone" name="phone" placeholder="(99) 99999-9999" maxlength="15" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contact-email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control form-control-lg" id="contact-email" name="email" placeholder="Seu melhor e-mail" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contact-message" class="form-label">Mensagem</label>
                                    <textarea class="form-control form-control-lg" id="contact-message" name="message" rows="4" placeholder="Como podemos te ajudar?" required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-dark btn-lg px-4 py-2">Enviar mensagem</button>
                                </div>
                            </form>
                            <div class="mt-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold">WhatsApp:</span>
                                    <span class="text-muted">{{$tenant->phones[0] ?? '(00) 00000-0000'}}</span>
                                </div>
                                @if(!empty($tenant->email))
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="fw-bold">E-mail:</span>
                                    <span class="text-muted">{{$tenant->email}}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar favoritos da API
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    fetch('/api/favorites')
        .then(res => res.json())
        .then(data => {
            favorites = data.favorites || [];
            localStorage.setItem('favorites', JSON.stringify(favorites));
            
            // Atualizar ícone após carregar favoritos da API
            const favoriteBtn = document.querySelector('.btn-favorite');
            if (favoriteBtn) {
                const propertyId = parseInt(favoriteBtn.dataset.propertyId, 10);
                updateFavoriteIcon(favoriteBtn, favorites, propertyId);
            }
        })
        .catch(error => {
            console.warn('Erro ao carregar favoritos da API, usando localStorage:', error);
        });

    // Favoritar
    const favoriteBtn = document.querySelector('.btn-favorite');
    if (favoriteBtn) {
        const propertyId = parseInt(favoriteBtn.dataset.propertyId, 10);
        
        function updateFavoriteIcon(btn, favList, propId) {
            if (favList.includes(propId)) {
                btn.classList.add('favorited');
                btn.querySelector('i').classList.remove('fa-regular');
                btn.querySelector('i').classList.add('fa-solid');
            } else {
                btn.classList.remove('favorited');
                btn.querySelector('i').classList.remove('fa-solid');
                btn.querySelector('i').classList.add('fa-regular');
            }
        }
        
        updateFavoriteIcon(favoriteBtn, favorites, propertyId);
        
        favoriteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Usar a mesma lógica do layout - fazer requisição para API
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
                localStorage.setItem('favorites', JSON.stringify(favorites));
                updateFavoriteIcon(favoriteBtn, favorites, propertyId);
            })
            .catch(error => {
                console.error('Erro ao favoritar:', error);
                // Fallback para localStorage local em caso de erro
                if (favorites.includes(propertyId)) {
                    favorites = favorites.filter(id => id !== propertyId);
                } else {
                    favorites.push(propertyId);
                }
                localStorage.setItem('favorites', JSON.stringify(favorites));
                updateFavoriteIcon(favoriteBtn, favorites, propertyId);
            });
        });
    }

    // Comparar
    const compareCheckbox = document.querySelector('.compare-checkbox');
    const compareLabel = document.querySelector('.compare-label');
    if (compareCheckbox && compareLabel) {
        const propertyId = parseInt(compareCheckbox.dataset.propertyId, 10);
        let compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
        const MAX_COMPARE = 4;
        
        function updateCompareIcon() {
            compareCheckbox.checked = compareList.includes(propertyId);
            compareLabel.classList.toggle('checked', compareCheckbox.checked);
        }
        
        updateCompareIcon();
        
        function updateAndRenderCompare() {
            updateCompareIcon();
            updateCompareTray();
            // Se o modal estiver aberto, atualiza imediatamente
            const modal = document.getElementById('compare-modal');
            if (modal && modal.classList.contains('show')) {
                setTimeout(() => renderCompareModal(), 100); // aguarda localStorage atualizar
            }
        }
        
        compareLabel.addEventListener('click', function(e) {
            e.preventDefault();
            
            const wasChecked = compareCheckbox.checked;
            const newChecked = !wasChecked;
            
            // Usar a mesma lógica do layout
            const index = compareList.indexOf(propertyId);
            if (newChecked) {
                if (index === -1 && compareList.length < MAX_COMPARE) {
                    compareList.push(propertyId);
                    compareCheckbox.checked = true;
                } else if (compareList.length >= MAX_COMPARE) {
                    // Usar a mesma função do layout
                    if (typeof showCustomToast === 'function') {
                        showCustomToast('Você pode comparar no máximo 4 imóveis.', 'compare');
                    } else {
                        alert('Você pode comparar no máximo 4 imóveis.');
                    }
                    compareCheckbox.checked = false;
                    return;
                }
            } else {
                if (index > -1) {
                    compareList.splice(index, 1);
                }
                compareCheckbox.checked = false;
            }
            
            localStorage.setItem('compareList', JSON.stringify(compareList));
            updateAndRenderCompare();
        });
    }

    // Compare Tray
    function updateCompareTray() {
        const tray = document.getElementById('compare-tray');
        const countSpan = document.getElementById('compare-count');
        const nowBtn = document.getElementById('compare-now-btn');
        let compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
        if (tray && countSpan && nowBtn) {
            if (compareList.length > 0) {
                tray.classList.add('show');
                countSpan.textContent = `(${compareList.length}/4 selecionados)`;
                nowBtn.disabled = compareList.length < 2;
            } else {
                tray.classList.remove('show');
                countSpan.textContent = '';
                nowBtn.disabled = true;
            }
        }
    }
    updateCompareTray();

    // Limpar comparação
    const clearBtn = document.getElementById('clear-compare-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            localStorage.setItem('compareList', JSON.stringify([]));
            updateCompareTray();
            // Atualizar ícone de comparação se disponível
            const checkbox = document.querySelector('.compare-checkbox');
            const label = document.querySelector('.compare-label');
            if (checkbox) {
                checkbox.checked = false;
            }
            if (label) {
                label.classList.remove('checked');
            }
        });
    }

    // Abrir modal de comparação
    const compareNowBtn = document.getElementById('compare-now-btn');
    async function renderCompareModal() {
        let compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
        if (compareList.length < 2) return;
        // Busca dados reais via API
        let propertiesToCompare = [];
        try {
            const response = await fetch(`/api/properties?ids=${compareList.join(',')}`);
            if (!response.ok) throw new Error('Erro ao buscar imóveis');
            const data = await response.json();
            propertiesToCompare = Array.isArray(data) ? data : (data.data || []);
        } catch (e) {
            // fallback: mostra imóvel atual e placeholders
            const propertyData = {
                id: {{ $property->id ?? 0 }},
                title: "{{ $property->title ?? 'Imóvel' }}",
                code: "{{ $property->code ?? '' }}",
                purpose: "{{ $property->purpose ?? '' }}",
                price: "{{ $property->price ?? '' }}",
                type: "{{ $property->type ?? '' }}",
                location: "{{ $property->location ?? '' }}",
                bedrooms: "{{ $property->bedrooms ?? '' }}",
                bathrooms: "{{ $property->bathrooms ?? '' }}",
                parking: "{{ $property->parking ?? '' }}",
                area: "{{ $property->area ?? '' }}",
                imagens: [{ url: '{{ $images[0]["url"] ?? ($images[0]->getUrl('preview') ?? 'https://placehold.co/600x400') }}' }]
            };
            if (compareList.includes(propertyData.id)) {
                propertiesToCompare.push(propertyData);
            }
            compareList.forEach(id => {
                if (id !== propertyData.id) {
                    propertiesToCompare.push({
                        id: id,
                        title: 'Imóvel ' + id,
                        code: id,
                        purpose: '-',
                        price: '-',
                        type: '-',
                        location: '-',
                        bedrooms: '-',
                        bathrooms: '-',
                        parking: '-',
                        area: '-',
                        imagens: [{ url: 'https://placehold.co/600x400' }]
                    });
                }
            });
        }
        const modal = document.getElementById('compare-modal');
        const modalBody = document.getElementById('compare-modal-body');
        if (modal && modalBody) {
            let html = '';
            html += `<div class='table-responsive'><table class='table table-bordered compare-table align-middle text-center'><thead><tr><th class='text-start'>Característica</th>`;
            propertiesToCompare.forEach(prop => {
                const imgUrl = prop.imagens && prop.imagens.length > 0 ? (prop.imagens[0].url || prop.imagens[0]) : 'https://placehold.co/600x400';
                html += `<th><img src='${imgUrl}' alt='${prop.title}' class='img-fluid rounded-3 mb-2'><h6>${prop.title}</h6><small class='text-muted'>Cód: ${prop.code}</small></th>`;
            });
            html += `</tr></thead><tbody>`;
            const features = [
                { label: 'Finalidade', key: 'purpose' },
                { label: 'Preço', key: 'price' },
                { label: 'Tipo', key: 'type' },
                { label: 'Localização', key: 'location' },
                { label: 'Quartos', key: 'bedrooms' },
                { label: 'Banheiros', key: 'bathrooms' },
                { label: 'Vagas', key: 'parking' },
                { label: 'Área (m²)', key: 'area' }
            ];
            features.forEach(f => {
                html += `<tr><td class='text-start'>${f.label}</td>`;
                propertiesToCompare.forEach(prop => {
                    html += `<td>${prop[f.key] ?? '-'}</td>`;
                });
                html += `</tr>`;
            });
            html += `</tbody></table></div>`;
            modalBody.innerHTML = html;
            modal.classList.add('show');
        }
    }

    if (compareNowBtn) {
        compareNowBtn.addEventListener('click', renderCompareModal);
    }

    // Fechar modal
    const compareModal = document.getElementById('compare-modal');
    const compareModalCloseBtn = document.getElementById('compare-modal-close-btn');
    if (compareModal && compareModalCloseBtn) {
        compareModalCloseBtn.addEventListener('click', function() {
            compareModal.classList.remove('show');
        });
    }

    // Inicializar tray de comparação
    updateCompareTray();
});
</script>
@endsection
