@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Editar Imóvel') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form id="property-form" action="{{ route('properties.update', $property) }}" method="POST">
            @method('PUT')
            @include('tenant.dashboard.properties._form', ['property' => $property, 'owners' => $owners])
        </form>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
        .dz-preview { transition: transform 0.2s, box-shadow 0.2s; }
        .dz-preview:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: grab; }
        .dz-preview:active { cursor: grabbing; }
        .dz-image img { border-radius: 8px; width: 100%; height: 150px; object-fit: cover; }
        .main-button, .dz-remove { transition: all 0.2s; }
        .main-button:hover, .dz-remove:hover { transform: scale(1.1); }
        .dropzone-area { background: #f8f9fa; border-radius: 12px; padding: 1.5rem; }
        .dz-preview { width: 200px; margin: 0.5rem; }
        .dz-empty-message { text-align: center; color: #6c757d; font-size: 1.1rem; padding: 2rem; }
        .svg-icon { width: 16px; height: 16px; fill: currentColor; }
        .btn-outline-success .svg-icon { fill: #198754; }
        .btn-outline-primary .svg-icon { fill: #0d6efd; }
        .btn-outline-danger .svg-icon { fill: #dc3545; }
        .btn-primary .svg-icon { fill: #fff; }
    </style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.6/Sortable.min.js" integrity="sha512-csIng5zcB+XpulRUa+ev1zKo7zRNGpEaVfNB9On1no9KYTEY/rLGAEEpvgdw6nim1WdTuihZY1eqZ31K7/fZjw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <script>
        Dropzone.autoDiscover = false;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('property-form');
            const dropzoneArea = document.getElementById('dropzone-area');
            const previewGrid = document.getElementById('preview-grid');
            const selectButton = document.getElementById('select-button');
            const emptyMessage = previewGrid.querySelector('.dz-empty-message');
            const propertyId = @json($property->id ?? null);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";
            let myDropzone;

            // Show/hide empty message
            function updateEmptyMessage() {
                const previews = previewGrid.querySelectorAll('.dz-preview').length;
                emptyMessage.style.display = previews === 0 ? 'block' : 'none';
            }

            if (dropzoneArea) {
                if (propertyId) {
                    myDropzone = new Dropzone(dropzoneArea, {
                        url: "{{ route('properties.media.upload', ['property' => 'PROPERTY_ID_PLACEHOLDER']) }}".replace('PROPERTY_ID_PLACEHOLDER', propertyId),
                        paramName: "file",
                        maxFilesize: 5,
                        acceptedFiles: "image/*",
                        addRemoveLinks: false,
                        dictDefaultMessage: "",
                        clickable: '#select-button',
                        previewsContainer: '#preview-grid',
                        autoProcessQueue: true,
                        parallelUploads: 10,
                        uploadMultiple: true,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        previewTemplate: `
                            <div class="dz-preview dz-file-preview card p-2 m-2 shadow-sm">
                                <div class="dz-image">
                                    <img data-dz-thumbnail class="img-fluid rounded" />
                                </div>
                                <div class="dz-details text-muted mt-1">
                                    <div class="dz-filename fw-bold"><span data-dz-name></span></div>
                                    <div class="dz-size small" data-dz-size></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                <div class="dz-error-message text-danger small"><span data-dz-errormessage></span></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="main-button" data-media-id="" role="button" title="Definir como Principal">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                    </span>
                                    <span class="dz-remove text-danger" data-dz-remove role="button" title="Remover">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                    </span>
                                </div>
                            </div>
                        `,
                        init: function() {
                            const dropzoneInstance = this;

                            // Prevent dropzone click from opening file dialog
                            dropzoneArea.addEventListener('click', (e) => {
                                e.stopPropagation();
                            });

                            // Load initial media from backend
                            fetch("{{ route('properties.media.get', ['property' => $property->id]) }}", {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(item => {
                                    const mockFile = {
                                        name: item.name,
                                        size: item.size,
                                        id: item.id,
                                        url: item.url,
                                        main: item.main,
                                        accepted: false
                                    };
                                    this.emit("addedfile", mockFile);
                                    this.emit("thumbnail", mockFile, item.url);
                                    const mainButton = mockFile.previewElement.querySelector('.main-button');
                                    mainButton.dataset.mediaId = item.id;
                                    mainButton.onclick = () => setMainMedia(item.id);
                                    if (mockFile.main) {
                                        mainButton.classList.add('text-warning');
                                        mainButton.innerHTML = `
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" /></svg>
                                        `;
                                    }
                                });
                                updateEmptyMessage();

                                // Initialize SortableJS with hover drag
                                new Sortable(previewGrid, {
                                    animation: 150,
                                    handle: '.dz-preview',
                                    onEnd: function(evt) {
                                        const mediaIds = Array.from(previewGrid.querySelectorAll('.dz-preview')).map(preview => {
                                            return preview.querySelector('.main-button').dataset.mediaId;
                                        }).filter(id => id);
                                        fetch("{{ route('properties.media.sort', ['property' => $property->id]) }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': csrfToken
                                            },
                                            body: JSON.stringify({ order: mediaIds })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                console.log('Ordem salva com sucesso!');
                                            }
                                        })
                                        .catch(error => console.error('Erro ao salvar ordem:', error));
                                    }
                                });
                            })
                            .catch(error => {
                                console.error('Erro ao carregar mídias:', error);
                                updateEmptyMessage();
                            });

                            // Handle successful multiple uploads
                            this.on("successmultiple", function(files, response) {
                                console.log('Imagens enviadas com sucesso!', response);
                                files.forEach(file => {
                                    if (response && response.media_ids && response.media_ids[file.name]) {
                                        file.id = response.media_ids[file.name];
                                        file.accepted = false;
                                        const mainButton = file.previewElement.querySelector('.main-button');
                                        mainButton.dataset.mediaId = file.id;
                                        mainButton.onclick = () => setMainMedia(file.id);
                                    } else {
                                        console.error(`Erro: media_id não encontrado para o arquivo ${file.name}`);
                                        const mainButton = file.previewElement.querySelector('.main-button');
                                        mainButton.classList.add('disabled');
                                        mainButton.onclick = null;
                                    }
                                });
                                updateEmptyMessage();
                            });

                            // Log queue completion
                            this.on("queuecomplete", function() {
                                console.log('Todas as imagens foram enviadas!');
                                updateEmptyMessage();
                            });

                            // Handle file removal
                            this.on("removedfile", function(file) {
                                if (file.id && !file.accepted) {
                                    fetch("{{ route('properties.media.delete', ['property' => $property->id, 'media' => 'MEDIA_ID_PLACEHOLDER']) }}".replace('MEDIA_ID_PLACEHOLDER', file.id), {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            console.log('Imagem removida do servidor.');
                                        } else {
                                            console.error('Erro ao remover imagem do servidor.');
                                        }
                                    })
                                    .catch(error => console.error('Erro ao remover imagem:', error));
                                }
                                updateEmptyMessage();
                            });

                            // Handle CSRF token mismatch error
                            this.on("error", function(file, errorMessage) {
                                console.error('Erro no upload:', errorMessage);
                                if (errorMessage === "CSRF token mismatch.") {
                                    alert('Erro: Token CSRF inválido. Por favor, recarregue a página ou verifique a configuração do CSRF.');
                                }
                                updateEmptyMessage();
                            });
                        }
                    });
                } else {
                    dropzoneArea.innerHTML = '<div class="alert alert-info">Salve o imóvel para adicionar fotos.</div>';
                }
            }
        });

        function setMainMedia(mediaId) {
            const propertyId = @json($property->id ?? null);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";
            if (propertyId) {
                fetch("{{ route('properties.media.set-main', ['property' => $property->id, 'media' => 'MEDIA_ID_PLACEHOLDER']) }}".replace('MEDIA_ID_PLACEHOLDER', mediaId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Imagem marcada como principal!');
                        document.querySelectorAll('.main-button').forEach(button => {
                            button.classList.remove('text-warning');
                            button.innerHTML = `
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                            `;
                        });
                        const activeButton = document.querySelector(`.main-button[data-media-id="${mediaId}"]`);
                        if (activeButton) {
                            activeButton.classList.add('text-warning');
                            activeButton.innerHTML = `
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" /></svg>
                            `;
                        }
                    }
                })
                .catch(error => console.error('Erro ao definir imagem principal:', error));
            }
        }
    </script>
@endpush