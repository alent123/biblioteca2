@extends('layouts.app')

@section('title', $libro->titulo)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Información del Libro -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <!-- Imagen de Portada -->
                        <div class="col-md-4 mb-4">
                            @if($libro->imagen_portada)
                                <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                     alt="{{ $libro->titulo }}" 
                                     class="img-fluid rounded shadow-sm">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 300px;">
                                    <i class="fas fa-book fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Detalles del Libro -->
                        <div class="col-md-8">
                            <h1 class="h2 mb-3">{{ $libro->titulo }}</h1>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary me-2">{{ $libro->categoria->nombre }}</span>
                                @if($libro->estado == 'disponible')
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($libro->estado == 'prestado')
                                    <span class="badge bg-danger">Prestado</span>
                                @elseif($libro->estado == 'reservado')
                                    <span class="badge bg-warning">Reservado</span>
                                @endif
                            </div>

                            <div class="book-info mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p><strong>Autor:</strong> {{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</p>
                                        @if($libro->anio_publicacion)
                                            <p><strong>Año:</strong> {{ $libro->anio_publicacion }}</p>
                                        @endif
                                        @if($libro->editorial)
                                            <p><strong>Editorial:</strong> {{ $libro->editorial }}</p>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        @if($libro->isbn)
                                            <p><strong>ISBN:</strong> {{ $libro->isbn }}</p>
                                        @endif
                                        @if($libro->paginas)
                                            <p><strong>Páginas:</strong> {{ $libro->paginas }}</p>
                                        @endif
                                        @if($libro->idioma)
                                            <p><strong>Idioma:</strong> {{ $libro->idioma }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($libro->sinopsis)
                                <div class="mb-4">
                                    <h5>Sinopsis</h5>
                                    <p class="text-muted">{{ $libro->sinopsis }}</p>
                                </div>
                            @endif

                            <!-- Acciones -->
                            <div class="d-flex gap-2 flex-wrap">
                                @auth
                                    @if(auth()->user()->tipo === 'cliente')
                                        <!-- Botón de Compra -->
                                        <a href="{{ route('books.purchase', $libro) }}" class="btn btn-primary">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Comprar Libro
                                        </a>
                                        
                                        @if($libro->estado == 'disponible')
                                            <button class="btn btn-success" onclick="prestarLibro({{ $libro->id }})">
                                                <i class="fas fa-hand-holding me-2"></i>
                                                Prestar Libro
                                            </button>
                                        @else
                                            <button class="btn btn-warning" onclick="reservarLibro({{ $libro->id }})">
                                                <i class="fas fa-clock me-2"></i>
                                                Reservar Libro
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-outline-primary favorite-btn" 
                                                data-book-id="{{ $libro->id }}"
                                                onclick="toggleFavorite({{ $libro->id }})">
                                            <i class="fas fa-heart {{ $isFavorite ? 'text-danger' : '' }}"></i>
                                            {{ $isFavorite ? 'Quitar de Favoritos' : 'Agregar a Favoritos' }}
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login.user') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Iniciar Sesión para Comprar
                                    </a>
                                @endauth
                                
                                <a href="{{ route('books.catalog') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver al Catálogo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Adicional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Stock:</strong> {{ $libro->stock }} ejemplares</p>
                            @if($libro->ubicacion)
                                <p><strong>Ubicación:</strong> {{ $libro->ubicacion }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha de registro:</strong> {{ $libro->created_at->format('d/m/Y') }}</p>
                            <p><strong>Última actualización:</strong> {{ $libro->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Información del Autor -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Sobre el Autor
                    </h5>
                </div>
                <div class="card-body">
                    <h6>{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</h6>
                    @if($libro->autor->nacionalidad)
                        <p class="text-muted mb-2">{{ $libro->autor->nacionalidad }}</p>
                    @endif
                    @if($libro->autor->biografia)
                        <p class="small">{{ Str::limit($libro->autor->biografia, 150) }}</p>
                    @endif
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        Ver más libros del autor
                    </a>
                </div>
            </div>

            <!-- Libros Relacionados -->
            @if($relatedBooks->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bookmark me-2"></i>
                        Libros Relacionados
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                @if($relatedBook->imagen_portada)
                                    <img src="{{ asset('storage/' . $relatedBook->imagen_portada) }}" 
                                         alt="{{ $relatedBook->titulo }}" 
                                         class="img-thumbnail" style="width: 60px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 80px;">
                                        <i class="fas fa-book text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('books.show', $relatedBook) }}" class="text-decoration-none">
                                        {{ Str::limit($relatedBook->titulo, 40) }}
                                    </a>
                                </h6>
                                <p class="small text-muted mb-1">
                                    {{ $relatedBook->autor->nombre }} {{ $relatedBook->autor->apellido }}
                                </p>
                                <span class="badge bg-{{ $relatedBook->estado == 'disponible' ? 'success' : 'danger' }} small">
                                    {{ ucfirst($relatedBook->estado) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de préstamo -->
<div class="modal fade" id="prestamoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea solicitar el préstamo de <strong>{{ $libro->titulo }}</strong>?</p>
                <p class="text-muted"><small>El préstamo tendrá una duración de 14 días.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="prestamoForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirmar Préstamo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de reserva -->
<div class="modal fade" id="reservaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hacer Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea reservar <strong>{{ $libro->titulo }}</strong>?</p>
                <p class="text-muted"><small>La reserva tendrá una duración de 7 días.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="reservaForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Confirmar Reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function prestarLibro(libroId) {
    const modal = new bootstrap.Modal(document.getElementById('prestamoModal'));
    const form = document.getElementById('prestamoForm');
    form.action = `/user/loans/${libroId}`;
    modal.show();
}

function reservarLibro(libroId) {
    const modal = new bootstrap.Modal(document.getElementById('reservaModal'));
    const form = document.getElementById('reservaForm');
    form.action = `/user/reservations/${libroId}`;
    modal.show();
}

function toggleFavorite(libroId) {
    fetch(`/user/favorites/${libroId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.querySelector(`[data-book-id="${libroId}"]`);
        const icon = btn.querySelector('i');
        
        if (data.isFavorite) {
            icon.classList.add('text-danger');
            btn.innerHTML = '<i class="fas fa-heart text-danger"></i> Quitar de Favoritos';
        } else {
            icon.classList.remove('text-danger');
            btn.innerHTML = '<i class="fas fa-heart"></i> Agregar a Favoritos';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection 