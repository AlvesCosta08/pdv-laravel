@extends('layouts.app')

@section('content')
<!-- Adicione esta meta tag no seu layout principal se ainda não tiver -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<div class="container-fluid px-0">
    <!-- Header Section - Reduzido para mobile -->
    <header class="bg-gradient-primary text-white py-2 py-md-3 shadow-sm">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white text-primary p-2 p-md-3 rounded-circle shadow">
                        <i class="fas fa-cash-register fa-lg"></i>
                    </div>
                    <h1 class="h6 h5-md mb-0">
                        <span style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 0.9rem; font-size-md: 1.2rem; font-weight: 600;">
                        SMComponentes | ESTOQUE
                        </span>
                    </h1>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('produtos.qrcodes-list') }}"
                       class="btn btn-light btn-sm px-2 px-md-3 py-1 py-md-2 rounded-pill d-flex align-items-center gap-1">
                        <i class="fas fa-print"></i>
                        <span class="d-none d-sm-inline">QR Codes</span>
                    </a>

                    <button class="btn btn-outline-light btn-sm px-2 px-md-3 py-1 py-md-2 rounded-pill d-flex align-items-center gap-1">
                        <i class="fas fa-bell"></i>
                        <span class="d-none d-sm-inline">Notif.</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Alerts Section -->
    <div class="container mt-2 mt-md-3">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="alert alert-success alert-dismissible fade show mb-2 mb-md-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <div class="flex-grow-1 small">
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="alert alert-danger alert-dismissible fade show mb-2 mb-md-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="flex-grow-1 small">
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="btn-close" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content - Layout de coluna única para mobile -->
    <div class="container my-2 my-md-4">
        <div class="row g-2 g-md-4">
            <!-- Product Search Section -->
            <section class="col-12 col-lg-8 order-2 order-lg-1">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-2 py-md-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h6 h5-md mb-0 d-flex align-items-center gap-1 gap-md-2">
                                <i class="fas fa-search text-primary"></i>
                                Buscar Produto
                            </h2>
                            <div class="d-flex gap-1 gap-md-2">
                                <button id="btn-abrir-camera" type="button"
                                    class="btn btn-outline-secondary btn-sm rounded-circle"
                                    aria-label="Abrir câmera para escanear QR"
                                    title="Escanear código">
                                    <i class="fas fa-camera"></i>
                                </button>
                                <button type="button" onclick="clearSearch()"
                                    class="btn btn-outline-secondary btn-sm rounded-circle"
                                    aria-label="Limpar busca"
                                    title="Limpar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-2 p-md-4">
                        <div class="position-relative mb-3">
                            <input
                                type="text"
                                id="busca-produto"
                                placeholder="Código, nome ou escanear..."
                                class="form-control form-control-lg ps-4 ps-md-5 border-2"
                                autocomplete="off"
                                autofocus
                            />
                            <div class="position-absolute top-50 start-0 translate-middle-y ms-2 ms-md-3 text-primary">
                                <i class="fas fa-barcode"></i>
                            </div>
                        </div>

                        <div id="search-results-container" class="d-none mb-3">
                            <ul id="lista-resultados" class="list-group list-group-flush overflow-auto" style="max-height: 200px; max-height-md: 300px;"></ul>
                        </div>

                        <form id="form-adicionar-produto" action="{{ route('pdv.adicionarItem') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="produto_id" id="produto_id" />

                            <div class="bg-light p-2 p-md-4 rounded-3 border">
                                <div class="row align-items-center g-2 g-md-3">
                                    <div class="col-12 col-md-5">
                                        <label for="quantidade" class="form-label fw-medium small small-md">Quantidade</label>
                                        <div class="input-group">
                                            <button type="button" onclick="adjustQuantity(-1)" class="btn btn-outline-secondary py-1">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input
                                                type="number"
                                                name="quantidade"
                                                id="quantidade"
                                                min="1"
                                                value="1"
                                                class="form-control text-center border-secondary py-1 py-md-2"
                                            />
                                            <button type="button" onclick="adjustQuantity(1)" class="btn btn-outline-secondary py-1">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-7 mt-2 mt-md-0">
                                        <button type="submit"
                                                class="btn btn-primary w-100 py-2 py-md-3 rounded-pill fw-medium d-flex align-items-center justify-content-center gap-1 gap-md-2 shadow-sm"
                                        >
                                            <i class="fas fa-cart-plus"></i>
                                            <span>Adicionar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Shopping Cart Section - Mover para cima em mobile -->
            <aside class="col-12 col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-2 py-md-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h6 h5-md mb-0 d-flex align-items-center gap-1 gap-md-2">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                Carrinho
                            </h2>
                            <span class="badge bg-primary rounded-pill">
                                {{ count($itens) }} {{ count($itens) === 1 ? 'item' : 'itens' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0 d-flex flex-column">
                        @if(count($itens) === 0)
                            <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center gap-2 gap-md-3 text-muted py-4 py-md-5">
                                <i class="fas fa-shopping-basket fs-3 fs-md-1 opacity-25"></i>
                                <p class="h6 h5-md mb-1">Carrinho vazio</p>
                                <p class="text-center small">Busque produtos para começar</p>
                            </div>
                        @else
                            <div class="flex-grow-1 overflow-auto" style="max-height: 300px; max-height-md: 400px;">
                                <div class="d-flex flex-column">
                                    @php $total = 0; @endphp
                                    @foreach($itens as $item)
                                        @php
                                            $subtotal = $item['valor_venda'] * $item['quantidade'];
                                            $total += $subtotal;
                                        @endphp
                                        <div class="p-2 p-md-3 border-bottom d-flex align-items-start gap-2 gap-md-3 hover-bg-light">
                                            <div class="bg-primary bg-opacity-10 text-primary p-1 p-md-2 rounded-2 rounded-md-3 flex-shrink-0">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h3 class="h6 mb-1 text-truncate">{{ $item['nome'] }}</h3>
                                                <p class="small text-muted mb-0">
                                                    {{ $item['quantidade'] }} × R$ {{ number_format($item['valor_venda'], 2, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="d-flex flex-column align-items-end">
                                                <p class="fw-semibold mb-1 small small-md">R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
                                                <form action="{{ route('pdv.removerItem', $item['produto_id']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-link text-danger p-0 small"
                                                            onclick="return confirm('Remover este item do carrinho?')">
                                                        <i class="fas fa-trash-alt fa-fw"></i>
                                                        <span class="d-none d-sm-inline">Remover</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-2 p-md-4 border-top bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1 mb-md-2">
                                    <span class="text-muted small small-md">Subtotal</span>
                                    <span class="fw-medium small small-md">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1 mb-md-2">
                                    <span class="text-muted small small-md">Desconto</span>
                                    <span class="fw-medium text-success small small-md">R$ 0,00</span>
                                </div>
                                <div class="border-top pt-2 pt-md-3 mt-1 mt-md-2 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold small small-md">Total</span>
                                    <span class="fw-bold fs-6 fs-md-5 text-primary">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>

                                <div class="mt-2 mt-md-3 d-grid gap-1 gap-md-2">
                                    <form action="{{ route('pdv.finalizar') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-success w-100 py-2 py-md-3 fw-medium d-flex align-items-center justify-content-center gap-1 gap-md-2 rounded-pill shadow-sm"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                            <span>Gravar Romaneio</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('pdv.comprovante', ['tipo' => 'impressao']) }}"
                                    target="_blank"
                                    class="btn btn-outline-primary py-2 py-md-3 px-2 px-md-3 fw-medium d-flex align-items-center justify-content-center gap-1 gap-md-2 rounded-pill"
                                    title="Imprimir Comprovante">
                                        <i class="fas fa-print"></i>
                                        <span>Romaneio</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div id="camera-modal"
     class="modal fade"
     tabindex="-1"
     aria-hidden="true"
     role="dialog"
     aria-labelledby="modal-title"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-2 py-md-3">
                <h5 class="modal-title d-flex align-items-center gap-1 gap-md-2 h6 h5-md" id="modal-title">
                    <i class="fas fa-qrcode"></i>
                    Escanear Código
                </h5>
                <button id="fechar-camera"
                    class="btn-close btn-close-white"
                    aria-label="Fechar câmera"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="qr-reader" class="w-100" style="height: 250px; height-md: 300px; background: #f8f9fa;"></div>
                <p class="small text-muted p-2 p-md-3 text-center mb-0">
                    Aponte a câmera para o código de barras ou QR Code do produto
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="position-fixed bottom-0 end-0 p-2 p-md-3" style="z-index: 1100"></div>

<!-- Scripts -->
<script src="https://unpkg.com/html5-qrcode@2.3.8"></script>
<script>
// Global Functions
function clearSearch() {
    document.getElementById('busca-produto').value = '';
    document.getElementById('search-results-container').classList.add('d-none');
    document.getElementById('lista-resultados').innerHTML = '';
    document.getElementById('form-adicionar-produto').classList.add('d-none');
}

function adjustQuantity(change) {
    const quantityInput = document.getElementById('quantidade');
    let newValue = parseInt(quantityInput.value) + change;
    if (newValue < 1) newValue = 1;
    quantityInput.value = newValue;
}

// Toast function
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');

    const types = {
        success: {
            icon: 'check-circle',
            class: 'alert-success'
        },
        error: {
            icon: 'exclamation-circle',
            class: 'alert-danger'
        },
        info: {
            icon: 'info-circle',
            class: 'alert-info'
        }
    };

    toast.className = `alert ${types[type].class} alert-dismissible fade show shadow`;
    toast.innerHTML = `
        <i class="fas fa-${types[type].icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}

document.addEventListener('DOMContentLoaded', () => {
    const buscaInput = document.getElementById('busca-produto');
    const searchResultsContainer = document.getElementById('search-results-container');
    const resultados = document.getElementById('lista-resultados');
    const formAdicionar = document.getElementById('form-adicionar-produto');
    const produtoIdInput = document.getElementById('produto_id');
    const btnCamera = document.getElementById('btn-abrir-camera');
    const cameraModal = new bootstrap.Modal(document.getElementById('camera-modal'));
    const fecharCameraBtn = document.getElementById('fechar-camera');

    let timer;
    let html5QrCode;

    function buscarProduto(query) {
        resultados.innerHTML = '';
        formAdicionar.classList.add('d-none');

        if (!query || query.length < 1) {
            searchResultsContainer.classList.add('d-none');
            return;
        }
            // Remove zero à esquerda
        const tentativaQuery = query.replace(/^0+/, '');

        fetch(`/pdv/api/v1/produtos/${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) throw new Error('Erro na resposta da API');
                return res.json();
            })
            .then(data => {
                resultados.innerHTML = '';
                if (!data || data.error) {
                    resultados.innerHTML = `
                        <li class="list-group-item text-center text-muted py-3 py-md-4">
                            <i class="fas fa-search mb-1 mb-md-2 fs-5 fs-md-4 opacity-50"></i>
                            <p class="mb-0 small small-md">Nenhum produto encontrado</p>
                        </li>
                    `;
                    searchResultsContainer.classList.remove('d-none');
                    formAdicionar.classList.add('d-none');
                } else {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action border-0 py-2 py-md-3';
                    li.innerHTML = `
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-1 p-md-2 rounded-2 rounded-md-3 flex-shrink-0">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 mb-0 text-truncate">${data.nome}</h3>
                                <p class="small text-muted mb-0">Código: ${data.id}</p>
                            </div>
                            <div class="fw-semibold text-primary small small-md">R$ ${parseFloat(data.valor_venda).toFixed(2).replace('.', ',')}</div>
                        </div>
                    `;

                    li.addEventListener('click', () => {
                        produtoIdInput.value = data.id;
                        formAdicionar.classList.remove('d-none');
                        searchResultsContainer.classList.add('d-none');
                        buscaInput.value = data.nome;
                        document.getElementById('quantidade').focus();
                    });

                    resultados.appendChild(li);
                    searchResultsContainer.classList.remove('d-none');
                }
            })
            .catch(() => {
                resultados.innerHTML = `
                    <li class="list-group-item text-center text-danger py-3 py-md-4">
                        <i class="fas fa-exclamation-triangle mb-1 mb-md-2"></i>
                        <p class="mb-0 small small-md">Erro ao buscar produto</p>
                    </li>
                `;
                searchResultsContainer.classList.remove('d-none');
                formAdicionar.classList.add('d-none');
            });
    }

    buscaInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => buscarProduto(buscaInput.value.trim()), 300);
    });


    // Camera QR Code
    btnCamera.addEventListener('click', () => {
        cameraModal.show();

        html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }, // Área menor para mobile
                aspectRatio: 1,
                disableFlip: false
            },
            qrCodeMessage => {
                try {
                    // Tenta parsear como JSON (para QR Codes mais complexos)
                    const qrData = JSON.parse(qrCodeMessage);

                    if (qrData.produto_id || qrData.id) {
                        // Se for um QR Code com estrutura JSON
                        const produtoId = qrData.produto_id || qrData.id;
                        buscaInput.value = qrData.nome || produtoId;
                        produtoIdInput.value = produtoId;
                        formAdicionar.classList.remove('d-none');
                        document.getElementById('quantidade').focus();
                    } else {
                        // Se não tiver a estrutura esperada, trata como ID simples
                        processarQRSimples(qrCodeMessage);
                    }
                } catch (e) {
                    // Se não for JSON, trata como ID simples
                    processarQRSimples(qrCodeMessage);
                }

                cameraModal.hide();
            },
            errorMessage => {
                console.error("Erro na leitura do QR Code:", errorMessage);
            }
        ).catch(err => {
            showToast("Erro ao iniciar a câmera: " + err, 'error');
            cameraModal.hide();
        });
    });
        // Função auxiliar para processar QR Codes simples (apenas ID)
    function processarQRSimples(qrCodeMessage) {
        buscaInput.value = qrCodeMessage;
        buscarProduto(qrCodeMessage);
        produtoIdInput.value = qrCodeMessage;
        formAdicionar.classList.remove('d-none');
    }
    // Close camera when modal is hidden
    document.getElementById('camera-modal').addEventListener('hidden.bs.modal', () => {
        if (html5QrCode) {
            html5QrCode.stop();
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Focus search input on Ctrl+K or /
        if ((e.ctrlKey && e.key === 'k') || e.key === '/') {
            e.preventDefault();
            buscaInput.focus();
        }

        // Close camera on Escape
        if (e.key === 'Escape' && document.getElementById('camera-modal').classList.contains('show')) {
            cameraModal.hide();
        }
    });
});
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }

    .border-2 {
        border-width: 2px !important;
    }

    /* Estilos específicos para mobile */
    @media (max-width: 576px) {
        body {
            font-size: 14px;
        }

        .form-control, .btn {
            font-size: 0.9rem;
        }

        .h6, h6 {
            font-size: 1rem;
        }

        .small {
            font-size: 0.8rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        /* Espaçamentos reduzidos */
        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .px-2 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Ajuste para inputs em mobile */
        input[type="number"], input[type="text"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Melhor visualização em mobile */
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
    }

    /* Ajustes para tablets */
    @media (min-width: 577px) and (max-width: 992px) {
        body {
            font-size: 15px;
        }

        .small-md {
            font-size: 0.9rem !important;
        }

        .h6-md, h6 {
            font-size: 1.1rem !important;
        }
    }
</style>
@endsection

