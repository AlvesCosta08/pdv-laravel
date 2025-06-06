@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 px-sm-5 py-sm-5">

    <!-- Header Section -->
    <header class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 mb-sm-5">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h2 mb-0 d-flex align-items-center gap-3">
                <span class="bg-primary text-white p-3 rounded-3 shadow">
                    <i class="fas fa-cash-register"></i>
                </span>
                <span>Frente de Caixa <span class="text-primary">(PDV)</span></span>
            </h1>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('produtos.qrcodes-list') }}"
            class="btn btn-primary px-2 px-sm-3 py-1 py-sm-2 rounded-3 d-flex align-items-center gap-1 gap-sm-2">
                <i class="fas fa-print"></i>  <!-- Mudei o ícone para fa-print que é mais intuitivo para impressão -->
                <span class="d-none d-sm-inline">Imprimir</span>
                <span class="d-inline d-sm-none">QR</span>  <!-- Texto alternativo para mobile -->
            </a>

            <button class="btn btn-outline-secondary px-3 py-2 rounded-3 d-flex align-items-center gap-2">
                <i class="fas fa-bell"></i>
                <span class="d-none d-sm-inline">Notificações</span>
            </button>
        </div>
    </header>

    <!-- Alerts Section -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-check-circle fs-4"></i>
                <div>
                    <strong>Sucesso!</strong> {{ session('success') }}
                </div>
                <button @click="show = false" class="btn-close" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-exclamation-circle fs-4"></i>
                <div>
                    <strong>Erro!</strong> {{ session('error') }}
                </div>
                <button @click="show = false" class="btn-close" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Product Search Section -->
        <section class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                            <h2 class="h4 mb-0 d-flex align-items-center gap-3">
                                <i class="fas fa-search text-primary"></i>
                                Buscar Produto
                            </h2>
                            <div class="d-flex align-items-center gap-2">
                                <button id="btn-abrir-camera" type="button"
                                    class="btn btn-outline-secondary p-2 rounded-3"
                                    aria-label="Abrir câmera para escanear QR"
                                    title="Escanear código">
                                    <i class="fas fa-camera"></i>
                                </button>
                                <button type="button" onclick="clearSearch()"
                                    class="btn btn-outline-secondary p-2 rounded-3"
                                    aria-label="Limpar busca"
                                    title="Limpar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="position-relative">
                            <input
                                type="text"
                                id="busca-produto"
                                placeholder="Digite o código, nome ou escaneie o produto..."
                                class="form-control form-control-lg ps-5"
                                autocomplete="off"
                                autofocus
                            />
                            <div class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                                <i class="fas fa-barcode fs-5"></i>
                            </div>
                        </div>

                        <div id="search-results-container" class="d-none">
                            <ul id="lista-resultados" class="list-group overflow-auto" style="max-height: 400px;"></ul>
                        </div>

                        <form id="form-adicionar-produto" action="{{ route('pdv.adicionarItem') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="produto_id" id="produto_id" />

                            <div class="bg-light p-4 rounded-3">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4">
                                        <label for="quantidade" class="form-label">Quantidade</label>
                                        <div class="input-group">
                                            <button type="button" onclick="adjustQuantity(-1)" class="btn btn-outline-secondary">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input
                                                type="number"
                                                name="quantidade"
                                                id="quantidade"
                                                min="1"
                                                value="1"
                                                class="form-control text-center"
                                            />
                                            <button type="button" onclick="adjustQuantity(1)" class="btn btn-outline-secondary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <button type="submit"
                                                class="btn btn-primary w-100 py-3 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2"
                                        >
                                            <i class="fas fa-cart-plus"></i>
                                            Adicionar ao Carrinho
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Shopping Cart Section -->
        <aside class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4 p-md-5 d-flex flex-column">
                    <div class="flex-grow-1 d-flex flex-column gap-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h4 mb-0 d-flex align-items-center gap-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                Carrinho
                            </h2>
                            <span class="badge bg-primary">
                                {{ count($itens) }} {{ count($itens) === 1 ? 'item' : 'itens' }}
                            </span>
                        </div>

                        @if(count($itens) === 0)
                            <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center gap-3 text-muted py-4">
                                <i class="fas fa-shopping-basket fs-1 opacity-50"></i>
                                <p class="h5">Seu carrinho está vazio</p>
                                <p class="text-center">Busque por produtos acima para começar</p>
                            </div>
                        @else
                            <!-- Modified this div to add scrollable area -->
                            <div class="flex-grow-1 overflow-auto" style="max-height: 400px;">
                                <div class="d-flex flex-column gap-3">
                                    @php $total = 0; @endphp
                                    @foreach($itens as $item)
                                        @php
                                            $subtotal = $item['valor_venda'] * $item['quantidade'];
                                            $total += $subtotal;
                                        @endphp
                                        <div class="bg-light rounded-3 p-3 d-flex align-items-start gap-3 border">
                                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h3 class="h6 mb-1">{{ $item['nome'] }}</h3>
                                                <p class="small text-muted mb-0">
                                                    {{ $item['quantidade'] }} × R$ {{ number_format($item['valor_venda'], 2, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="d-flex flex-column align-items-end">
                                                <p class="fw-semibold mb-1">R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
                                                <form action="{{ route('pdv.removerItem', $item['produto_id']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-link text-danger p-0 small"
                                                            onclick="return confirm('Remover este item do carrinho?')">
                                                        <i class="fas fa-trash-alt fa-fw"></i>
                                                        Remover
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-light rounded-3 p-4 border">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium">Subtotal</span>
                                    <span class="fw-medium">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium">Desconto</span>
                                    <span class="fw-medium text-success">R$ 0,00</span>
                                </div>
                                <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5">Total</span>
                                    <span class="fw-bold fs-5">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>

                                <form action="{{ route('pdv.finalizar') }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-success w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                        Finalizar Venda
                                    </button>
                                </form>
                                        <a href="{{ route('pdv.comprovante', ['tipo' => 'impressao']) }}"
                                        target="_blank"
                                        class="btn btn-outline-primary py-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2"
                                        title="Imprimir Comprovante">
                                            <i class="fas fa-print"></i>
                                            <span class="d-none d-sm-inline">Comprovante</span>
                                        </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modal-title">
                    <i class="fas fa-qrcode text-primary"></i>
                    Escanear Código
                </h5>
                <button id="fechar-camera"
                    class="btn-close"
                    aria-label="Fechar câmera"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" class="w-100" style="height: 300px; background: black;"></div>
                <p class="small text-muted mt-3 text-center">
                    Aponte a câmera para o código de barras ou QR Code do produto
                </p>
            </div>
        </div>
    </div>
</div>

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

    toast.className = `alert ${types[type].class} alert-dismissible fade show`;
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

        fetch(`/api/v1/produtos/${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) throw new Error('Erro na resposta da API');
                return res.json();
            })
            .then(data => {
                resultados.innerHTML = '';
                if (!data || data.error) {
                    resultados.innerHTML = `
                        <li class="list-group-item text-center text-muted">
                            <i class="fas fa-search mb-2 fs-4 opacity-50"></i>
                            <p>Nenhum produto encontrado</p>
                        </li>
                    `;
                    searchResultsContainer.classList.remove('d-none');
                    formAdicionar.classList.add('d-none');
                } else {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.innerHTML = `
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h6 mb-0">${data.nome}</h3>
                                <p class="small text-muted mb-0">Código: ${data.id}</p>
                            </div>
                            <div class="fw-semibold">R$ ${parseFloat(data.valor_venda).toFixed(2).replace('.', ',')}</div>
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
                    <li class="list-group-item text-center text-danger">
                        <i class="fas fa-exclamation-triangle mb-2"></i>
                        <p>Erro ao buscar produto</p>
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
                qrbox: { width: 300, height: 300 }, // Área maior e quadrada
                aspectRatio: 1,
                disableFlip: false // Permite rotação automática
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
@endsection

