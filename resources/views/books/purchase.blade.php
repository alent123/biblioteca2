@extends('layouts.app')

@section('title', 'Comprar ' . $libro->titulo . ' - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Información del Libro -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($libro->imagen_portada)
                                <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                     alt="{{ $libro->titulo }}" 
                                     class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 200px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h2 class="h3 mb-2">{{ $libro->titulo }}</h2>
                            <p class="text-muted mb-2">{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</p>
                            <span class="badge bg-primary">{{ $libro->categoria->nombre }}</span>
                            
                            @if($libro->sinopsis)
                                <p class="mt-3 text-muted">{{ Str::limit($libro->sinopsis, 200) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones de Compra -->
            <div class="row">
                <!-- Compra Física -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-book fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Libro Físico</h5>
                            <p class="card-text text-muted">Recibe el libro en tu casa</p>
                            <div class="mb-3">
                                <span class="h4 text-success">S/{{ $libro->precio ?? 99.90 }}</span>
                            </div>
                            <ul class="list-unstyled text-start small">
                                <li><i class="fas fa-check text-success me-2"></i>Envío gratuito a todo Perú</li>
                                <li><i class="fas fa-check text-success me-2"></i>Entrega en 2-4 días hábiles</li>
                                <li><i class="fas fa-check text-success me-2"></i>Garantía de calidad</li>
                            </ul>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#compraFisicaModal">
                                Comprar Físico
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Compra Virtual -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-tablet-alt fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Libro Digital</h5>
                            <p class="card-text text-muted">Descarga PDF inmediata</p>
                            <div class="mb-3">
                                <span class="h4 text-info">S/{{ $libro->precio_virtual ?? 69.90 }}</span>
                            </div>
                            <ul class="list-unstyled text-start small">
                                <li><i class="fas fa-check text-success me-2"></i>Descarga inmediata</li>
                                <li><i class="fas fa-check text-success me-2"></i>Acceso ilimitado</li>
                                <li><i class="fas fa-check text-success me-2"></i>Compatible con todos los dispositivos</li>
                            </ul>
                            <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#compraVirtualModal">
                                Comprar Digital
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Préstamo -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-eye fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Préstamo Digital</h5>
                            <p class="card-text text-muted">Lee en línea por 14 días</p>
                            <div class="mb-3">
                                <span class="h4 text-success">Gratis</span>
                            </div>
                            <ul class="list-unstyled text-start small">
                                <li><i class="fas fa-check text-success me-2"></i>Acceso por 14 días</li>
                                <li><i class="fas fa-check text-success me-2"></i>Lector integrado</li>
                                <li><i class="fas fa-check text-success me-2"></i>Sin descarga necesaria</li>
                            </ul>
                            <form action="{{ route('books.prestamo', $libro) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    Prestar Libro
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resumen de Compra -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Resumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Libro Físico:</span>
                        <span>S/{{ $libro->precio ?? 99.90 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Libro Digital:</span>
                        <span>S/{{ $libro->precio_virtual ?? 69.90 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Préstamo:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Envío:</strong>
                        <span class="text-success">Gratis</span>
                    </div>
                </div>
            </div>

            <!-- Información de Seguridad -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Seguridad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-lock text-success me-2"></i>
                        <span class="small">Pago seguro con SSL</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-credit-card text-success me-2"></i>
                        <span class="small">Múltiples métodos de pago</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        <span class="small">Protegido por Indecopi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-undo text-success me-2"></i>
                        <span class="small">Garantía de devolución</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Compra Física -->
<div class="modal fade" id="compraFisicaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comprar Libro Físico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('books.purchase.fisica', $libro) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información Personal</h6>
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" name="telefono" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Dirección de Envío</h6>
                            <div class="mb-3">
                                <label class="form-label">Departamento *</label>
                                <select class="form-select" name="departamento" required>
                                    <option value="">Seleccionar departamento</option>
                                    <option value="Lima">Lima</option>
                                    <option value="Arequipa">Arequipa</option>
                                    <option value="La Libertad">La Libertad</option>
                                    <option value="Piura">Piura</option>
                                    <option value="Lambayeque">Lambayeque</option>
                                    <option value="Junín">Junín</option>
                                    <option value="Cusco">Cusco</option>
                                    <option value="Ancash">Ancash</option>
                                    <option value="Ica">Ica</option>
                                    <option value="Tacna">Tacna</option>
                                    <option value="Cajamarca">Cajamarca</option>
                                    <option value="Puno">Puno</option>
                                    <option value="San Martín">San Martín</option>
                                    <option value="Ayacucho">Ayacucho</option>
                                    <option value="Huánuco">Huánuco</option>
                                    <option value="Moquegua">Moquegua</option>
                                    <option value="Huancavelica">Huancavelica</option>
                                    <option value="Ucayali">Ucayali</option>
                                    <option value="Loreto">Loreto</option>
                                    <option value="Madre de Dios">Madre de Dios</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Pasco">Pasco</option>
                                    <option value="Tumbes">Tumbes</option>
                                    <option value="Apurímac">Apurímac</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección *</label>
                                <textarea class="form-control" name="direccion" rows="3" required placeholder="Calle, número, urbanización, distrito"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Distrito *</label>
                                <input type="text" class="form-control" name="distrito" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Código Postal</label>
                                <input type="text" class="form-control" name="codigo_postal" placeholder="Opcional">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6>Método de Pago</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="tarjeta" id="tarjeta" required>
                                <label class="form-check-label" for="tarjeta">
                                    <i class="fas fa-credit-card me-2"></i>Tarjeta de Crédito/Débito
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="paypal" id="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="fab fa-paypal me-2"></i>PayPal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="transferencia" id="transferencia">
                                <label class="form-check-label" for="transferencia">
                                    <i class="fas fa-university me-2"></i>Transferencia Bancaria
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="yape" id="yape">
                                <label class="form-check-label" for="yape">
                                    <i class="fas fa-mobile-alt me-2"></i>Yape
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Total a pagar:</strong> S/{{ $libro->precio ?? 99.90 }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Confirmar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Compra Virtual -->
<div class="modal fade" id="compraVirtualModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comprar Libro Digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('books.purchase.virtual', $libro) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-tablet-alt fa-3x text-info mb-3"></i>
                        <h6>Descarga Inmediata</h6>
                        <p class="text-muted">Recibirás el PDF por email y podrás descargarlo inmediatamente.</p>
                    </div>
                    
                    <h6>Método de Pago</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="tarjeta" id="tarjeta_virtual" required>
                                <label class="form-check-label" for="tarjeta_virtual">
                                    <i class="fas fa-credit-card me-2"></i>Tarjeta
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="paypal" id="paypal_virtual">
                                <label class="form-check-label" for="paypal_virtual">
                                    <i class="fab fa-paypal me-2"></i>PayPal
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="transferencia" id="transferencia_virtual">
                                <label class="form-check-label" for="transferencia_virtual">
                                    <i class="fas fa-university me-2"></i>Transferencia
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="yape" id="yape_virtual">
                                <label class="form-check-label" for="yape_virtual">
                                    <i class="fas fa-mobile-alt me-2"></i>Yape
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Total a pagar:</strong> S/{{ $libro->precio_virtual ?? 69.90 }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-download me-2"></i>
                        Comprar y Descargar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Estilos para modales adaptables al tema */
.modal-content {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.modal-header {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
}

.modal-body {
    background-color: var(--bg-card);
    color: var(--text-primary);
}

.modal-footer {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border-top: 1px solid var(--border-color);
}

.modal-title {
    color: var(--text-primary);
}

.form-label {
    color: var(--text-primary);
}

.form-control {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.form-control:focus {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-select {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.form-select:focus {
    background-color: var(--bg-primary);
    color: var(--text-primary);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-check-label {
    color: var(--text-primary);
}

.alert {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.alert-info {
    background-color: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
    color: var(--text-primary);
}

.btn-close {
    filter: var(--btn-close-filter);
}

/* Filtro para el botón de cerrar */
.claro .btn-close {
    filter: none;
}

.oscuro .btn-close {
    filter: invert(1);
}

/* Variables CSS para temas */
:root {
    --bg-color: #ffffff;
    --text-color: #333333;
    --border-color: #dee2e6;
    --primary-color: #0d6efd;
    --primary-rgb: 13, 110, 253;
    --info-rgb: 13, 202, 240;
    --btn-close-filter: invert(0);
}

[data-theme="dark"] {
    --bg-color: #2c2c2c;
    --text-color: #e0e0e0;
    --border-color: #495057;
    --primary-color: #0d6efd;
    --primary-rgb: 13, 110, 253;
    --info-rgb: 13, 202, 240;
    --btn-close-filter: invert(1);
}

[data-theme="light"] {
    --bg-color: #ffffff;
    --text-color: #333333;
    --border-color: #dee2e6;
    --primary-color: #0d6efd;
    --primary-rgb: 13, 110, 253;
    --info-rgb: 13, 202, 240;
    --btn-close-filter: invert(0);
}
</style>

<script>
// Función para aplicar el tema actual a los modales
function applyThemeToModals() {
    const htmlElement = document.documentElement;
    const isDarkTheme = htmlElement.classList.contains('oscuro');
    const modals = document.querySelectorAll('.modal-content');
    
    modals.forEach(modal => {
        if (isDarkTheme) {
            modal.classList.add('oscuro');
            modal.classList.remove('claro');
        } else {
            modal.classList.add('claro');
            modal.classList.remove('oscuro');
        }
    });
}

// Aplicar tema cuando se cargan los modales
document.addEventListener('DOMContentLoaded', function() {
    applyThemeToModals();
    
    // Aplicar tema cuando se abren los modales
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            setTimeout(applyThemeToModals, 100);
        });
    });
    
    // Observar cambios en el tema
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                applyThemeToModals();
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});

// Aplicar tema cuando se muestra un modal
document.addEventListener('shown.bs.modal', function() {
    applyThemeToModals();
});
</script>
@endsection 