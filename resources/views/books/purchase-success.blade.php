@extends('layouts.app')

@section('title', 'Compra Exitosa - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Mensaje de Éxito -->
            <div class="card border-0 shadow-sm text-center mb-4">
                <div class="card-body py-5">
                    <div class="mb-4">
                        @if($compra->isFisica())
                            <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                            <h2 class="text-success">¡Compra Realizada con Éxito!</h2>
                            <p class="lead text-muted">Tu libro físico será enviado pronto a tu dirección.</p>
                        @else
                            <i class="fas fa-download fa-5x text-info mb-3"></i>
                            <h2 class="text-info">¡Gracias por tu Compra!</h2>
                            <p class="lead text-muted">Tu libro digital está listo para descargar.</p>
                        @endif
                    </div>

                    <div class="alert alert-success">
                        <h5>Detalles de la Transacción</h5>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <p><strong>Número de Compra:</strong> #{{ $compra->id }}</p>
                                <p><strong>Fecha:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Método de Pago:</strong> {{ ucfirst($compra->metodo_pago) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Libro:</strong> {{ $compra->libro->titulo }}</p>
                                <p><strong>Autor:</strong> {{ $compra->libro->autor->nombre }} {{ $compra->libro->autor->apellido }}</p>
                                <p><strong>Total:</strong> S/{{ number_format($compra->precio, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Específica por Tipo -->
            @if($compra->isFisica())
                <!-- Información de Envío Físico -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shipping-fast me-2"></i>
                            Información de Envío
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Datos de Contacto</h6>
                                <p><strong>Nombre:</strong> {{ $compra->nombre_completo }}</p>
                                <p><strong>Email:</strong> {{ $compra->email }}</p>
                                <p><strong>Teléfono:</strong> {{ $compra->telefono }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Dirección de Envío</h6>
                                <p><strong>Dirección:</strong> {{ $compra->datos_envio['direccion'] }}</p>
                                <p><strong>Distrito:</strong> {{ $compra->datos_envio['distrito'] }}</p>
                                <p><strong>Departamento:</strong> {{ $compra->datos_envio['departamento'] }}</p>
                                @if(isset($compra->datos_envio['codigo_postal']) && $compra->datos_envio['codigo_postal'])
                                    <p><strong>Código Postal:</strong> {{ $compra->datos_envio['codigo_postal'] }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Próximos Pasos</h6>
                            <ul class="mb-0">
                                <li>Recibirás un email de confirmación con el número de seguimiento</li>
                                <li>El envío se realizará en 1-2 días hábiles</li>
                                <li>Tiempo de entrega estimado: 2-4 días hábiles en Lima, 3-7 días en provincias</li>
                                <li>Recibirás notificaciones sobre el estado de tu envío</li>
                                <li>Envío gratuito a todo Perú</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <!-- Información de Descarga Digital -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Descarga tu Libro Digital
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                            <h5>{{ $compra->libro->titulo }}.pdf</h5>
                            <p class="text-muted">Archivo PDF - Descarga inmediata</p>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-block">
                            <a href="{{ route('books.purchase.download', $compra) }}" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-download me-2"></i>
                                Descargar PDF
                            </a>
                            <button class="btn btn-outline-secondary btn-lg" onclick="enviarEmail()">
                                <i class="fas fa-envelope me-2"></i>
                                Enviar por Email
                            </button>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <small>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Importante:</strong> Este enlace de descarga estará disponible por 30 días. 
                                Te recomendamos descargar el archivo lo antes posible.
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Acciones Adicionales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Acciones Adicionales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <a href="{{ route('books.show', $compra->libro) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-book me-2"></i>
                                Ver Detalles del Libro
                            </a>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <a href="{{ route('user.historial') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-history me-2"></i>
                                Ver Historial
                            </a>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <a href="{{ route('books.catalog') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-search me-2"></i>
                                Explorar Más Libros
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recomendaciones -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Te Recomendamos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Libros del mismo autor</h6>
                            <p class="text-muted">Descubre más obras de {{ $compra->libro->autor->nombre }} {{ $compra->libro->autor->apellido }}</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Ver más libros</a>
                        </div>
                        <div class="col-md-6">
                            <h6>Libros similares</h6>
                            <p class="text-muted">Basado en tu compra, te pueden interesar estos títulos</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">Ver recomendaciones</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function enviarEmail() {
    // Aquí se implementaría la lógica para enviar el PDF por email
    alert('Función de envío por email en desarrollo. Por favor, usa la descarga directa.');
}
</script>

<style>
.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}
</style>
@endsection 