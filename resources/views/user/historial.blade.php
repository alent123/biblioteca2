@extends('layouts.app')

@section('title', 'Mi Historial - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">
                <i class="fas fa-history me-2"></i>
                Mi Historial
            </h1>
        </div>
    </div>

    <!-- Pestañas -->
    <ul class="nav nav-tabs mb-4" id="historialTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras" type="button" role="tab">
                <i class="fas fa-shopping-cart me-2"></i>
                Compras ({{ $compras->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="prestamos-tab" data-bs-toggle="tab" data-bs-target="#prestamos" type="button" role="tab">
                <i class="fas fa-book-open me-2"></i>
                Préstamos ({{ $prestamos->total() }})
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="historialTabsContent">
        <!-- Pestaña de Compras -->
        <div class="tab-pane fade show active" id="compras" role="tabpanel">
            @if($compras->count() > 0)
                <div class="row">
                    @foreach($compras as $compra)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if($compra->libro->imagen_portada)
                                                <img src="{{ asset('storage/' . $compra->libro->imagen_portada) }}" 
                                                     alt="{{ $compra->libro->titulo }}" 
                                                     class="img-fluid rounded">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                     style="height: 120px;">
                                                    <i class="fas fa-book fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <h6 class="card-title mb-2">{{ $compra->libro->titulo }}</h6>
                                            <p class="text-muted small mb-2">{{ $compra->libro->autor->nombre }} {{ $compra->libro->autor->apellido }}</p>
                                            
                                            <div class="mb-3">
                                                @if($compra->isFisica())
                                                    <span class="badge bg-primary me-2">
                                                        <i class="fas fa-book me-1"></i>Físico
                                                    </span>
                                                @else
                                                    <span class="badge bg-info me-2">
                                                        <i class="fas fa-tablet-alt me-1"></i>Digital
                                                    </span>
                                                @endif
                                                
                                                <span class="badge bg-{{ $compra->estado === 'completada' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($compra->estado) }}
                                                </span>
                                            </div>
                                            
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <strong>Fecha:</strong><br>
                                                    {{ $compra->created_at->format('d/m/Y') }}
                                                </div>
                                                <div class="col-6">
                                                    <strong>Total:</strong><br>
                                                    S/{{ number_format($compra->precio, 2) }}
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('books.show', $compra->libro) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Ver Libro
                                                </a>
                                                
                                                @if($compra->isVirtual() && $compra->isCompletada())
                                                    <a href="{{ route('books.purchase.download', $compra) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download me-1"></i>Descargar
                                                    </a>
                                                @endif
                                                
                                                @if($compra->isFisica() && $compra->isCompletada())
                                                    <button class="btn btn-sm btn-outline-info" onclick="verEnvio({{ $compra->id }})">
                                                        <i class="fas fa-shipping-fast me-1"></i>Envío
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $compras->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tienes compras registradas</h5>
                    <p class="text-muted">¡Explora nuestro catálogo y encuentra tu próximo libro favorito!</p>
                    <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Explorar Catálogo
                    </a>
                </div>
            @endif
        </div>

        <!-- Pestaña de Préstamos -->
        <div class="tab-pane fade" id="prestamos" role="tabpanel">
            @if($prestamos->count() > 0)
                <div class="row">
                    @foreach($prestamos as $prestamo)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if($prestamo->libro->imagen_portada)
                                                <img src="{{ asset('storage/' . $prestamo->libro->imagen_portada) }}" 
                                                     alt="{{ $prestamo->libro->titulo }}" 
                                                     class="img-fluid rounded">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                     style="height: 120px;">
                                                    <i class="fas fa-book fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <h6 class="card-title mb-2">{{ $prestamo->libro->titulo }}</h6>
                                            <p class="text-muted small mb-2">{{ $prestamo->libro->autor->nombre }} {{ $prestamo->libro->autor->apellido }}</p>
                                            
                                            <div class="mb-3">
                                                <span class="badge bg-{{ $prestamo->estado === 'prestado' ? 'success' : ($prestamo->estado === 'devuelto' ? 'secondary' : 'danger') }}">
                                                    {{ ucfirst($prestamo->estado) }}
                                                </span>
                                                
                                                @if($prestamo->tipo === 'digital')
                                                    <span class="badge bg-info ms-2">
                                                        <i class="fas fa-eye me-1"></i>Digital
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <strong>Préstamo:</strong><br>
                                                    {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                                                </div>
                                                <div class="col-6">
                                                    <strong>Devolución:</strong><br>
                                                    {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            
                                            @if($prestamo->estado === 'prestado')
                                                <div class="mb-3">
                                                    @php
                                                        $diasRestantes = $prestamo->fecha_devolucion_esperada->diffInDays(now());
                                                    @endphp
                                                    <div class="alert alert-{{ $diasRestantes <= 3 ? 'danger' : ($diasRestantes <= 7 ? 'warning' : 'info') }} py-2">
                                                        <small>
                                                            <i class="fas fa-clock me-1"></i>
                                                            <strong>{{ $diasRestantes }} días restantes</strong>
                                                            @if($diasRestantes <= 3)
                                                                - ¡Próximo a vencer!
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('books.show', $prestamo->libro) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>Ver Libro
                                                </a>
                                                
                                                @if($prestamo->estado === 'prestado' && $prestamo->tipo === 'digital')
                                                    <a href="{{ route('books.read', $prestamo->libro) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-book-open me-1"></i>Leer
                                                    </a>
                                                @endif
                                                
                                                @if($prestamo->estado === 'prestado')
                                                    <button class="btn btn-sm btn-outline-warning" onclick="renovarPrestamo({{ $prestamo->id }})">
                                                        <i class="fas fa-redo me-1"></i>Renovar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $prestamos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tienes préstamos registrados</h5>
                    <p class="text-muted">¡Explora nuestro catálogo y presta tu primer libro!</p>
                    <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Explorar Catálogo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Información de Envío -->
<div class="modal fade" id="envioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shipping-fast me-2"></i>
                    Información de Envío
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="envioModalBody">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
function verEnvio(compraId) {
    // Aquí se cargaría la información de envío desde el servidor
    const modalBody = document.getElementById('envioModalBody');
    modalBody.innerHTML = `
        <div class="text-center">
            <i class="fas fa-truck fa-3x text-primary mb-3"></i>
            <h6>En proceso de envío</h6>
            <p class="text-muted">Tu libro está siendo preparado para el envío. Recibirás una notificación cuando esté en camino.</p>
            <div class="alert alert-info">
                <small>
                    <strong>Tiempo estimado de entrega:</strong><br>
                    • Lima: 2-4 días hábiles<br>
                    • Provincias: 3-7 días hábiles<br>
                    <strong>Estado actual:</strong> En preparación<br>
                    <strong>Envío:</strong> Gratuito a todo Perú
                </small>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('envioModal'));
    modal.show();
}

function renovarPrestamo(prestamoId) {
    if (confirm('¿Deseas renovar este préstamo por 14 días más?')) {
        // Aquí se implementaría la lógica de renovación
        alert('Función de renovación en desarrollo. Por favor, contacta al administrador.');
    }
}

// Activar la primera pestaña por defecto
document.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('#historialTabs .nav-link');
    const firstTabContent = document.querySelector('#historialTabsContent .tab-pane');
    
    if (firstTab && firstTabContent) {
        firstTab.classList.add('active');
        firstTabContent.classList.add('show', 'active');
    }
});
</script>

<style>
.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.nav-tabs .nav-link {
    border: none;
    color: var(--text-color);
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
    background: none;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
}
</style>
@endsection 