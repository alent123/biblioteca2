@extends('layouts.app')

@section('title', 'Catálogo de Libros - Retrolector')

@section('content')
<div class="container py-5">
    <!-- Header del catálogo -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">
                <i class="fas fa-books me-2"></i>
                Catálogo de Libros
            </h1>
            <p class="text-muted">Explora nuestra colección de libros digitales</p>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('books.catalog') }}" id="searchForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Buscar</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Título, autor, ISBN...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="prestado" {{ request('estado') == 'prestado' ? 'selected' : '' }}>Prestado</option>
                                    <option value="reservado" {{ request('estado') == 'reservado' ? 'selected' : '' }}>Reservado</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="orden" class="form-label">Ordenar</label>
                                <select class="form-select" id="orden" name="orden">
                                    <option value="titulo" {{ request('orden') == 'titulo' ? 'selected' : '' }}>Título</option>
                                    <option value="autor" {{ request('orden') == 'autor' ? 'selected' : '' }}>Autor</option>
                                    <option value="fecha" {{ request('orden') == 'fecha' ? 'selected' : '' }}>Fecha</option>
                                    <option value="popularidad" {{ request('orden') == 'popularidad' ? 'selected' : '' }}>Popularidad</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>
                                    Buscar
                                </button>
                                <a href="{{ route('books.catalog') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h4>{{ $totalLibros }}</h4>
                    <p class="mb-0">Total de libros</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4>{{ $disponibles }}</h4>
                    <p class="mb-0">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4>{{ $prestados }}</h4>
                    <p class="mb-0">Prestados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-bookmark fa-2x mb-2"></i>
                    <h4>{{ $reservados }}</h4>
                    <p class="mb-0">Reservados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="row">
        <div class="col-12">
            @if($libros->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        Mostrando {{ $libros->firstItem() }}-{{ $libros->lastItem() }} de {{ $libros->total() }} libros
                    </p>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="changeView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="changeView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <div class="row" id="booksGrid">
                    @foreach($libros as $libro)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 book-card">
                                <div class="card-img-top position-relative" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                    @if($libro->imagen_portada)
                                        <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                             class="img-fluid" alt="{{ $libro->titulo }}" 
                                             style="max-height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-book fa-3x text-white"></i>
                                    @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $libro->estado === 'disponible' ? 'success' : ($libro->estado === 'prestado' ? 'warning' : 'info') }}">
                                            {{ ucfirst($libro->estado) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $libro->titulo }}</h5>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $libro->autor->nombre }} {{ $libro->autor->apellido }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $libro->categoria->nombre }}
                                    </p>
                                    @if($libro->anio_publicacion)
                                        <p class="card-text text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $libro->anio_publicacion }}
                                        </p>
                                    @endif
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2 mb-2">
                                            <a href="{{ route('books.show', $libro) }}" class="btn btn-primary btn-sm flex-fill">
                                                <i class="fas fa-eye me-1"></i>
                                                Ver
                                            </a>
                                            @auth
                                                <button class="btn btn-outline-danger btn-sm" onclick="toggleFavorite({{ $libro->id }})">
                                                    <i class="fas fa-heart {{ $libro->isFavoritedBy(auth()->user()) ? 'text-danger' : '' }}"></i>
                                                </button>
                                            @endauth
                                        </div>
                                        @auth
                                            <div class="d-flex gap-2">
                                                @if($libro->estado === 'disponible')
                                                    <button class="btn btn-success btn-sm flex-fill" onclick="borrowBook({{ $libro->id }})">
                                                        <i class="fas fa-book me-1"></i>
                                                        Prestar
                                                    </button>
                                                @else
                                                    <button class="btn btn-info btn-sm flex-fill" onclick="reserveBook({{ $libro->id }})">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Reservar
                                                    </button>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $libros->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">No se encontraron libros</h3>
                    <p class="text-muted">Intenta ajustar tus filtros de búsqueda.</p>
                    <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-undo me-1"></i>
                        Ver todos los libros
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeView(view) {
        const grid = document.getElementById('booksGrid');
        if (view === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }
    }

    function toggleFavorite(libroId) {
        fetch(`/books/${libroId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function borrowBook(libroId) {
        if (confirm('¿Estás seguro de que quieres prestar este libro?')) {
            fetch(`/books/${libroId}/borrow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al prestar el libro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al prestar el libro');
            });
        }
    }

    function reserveBook(libroId) {
        if (confirm('¿Estás seguro de que quieres reservar este libro?')) {
            fetch(`/books/${libroId}/reserve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al reservar el libro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al reservar el libro');
            });
        }
    }

    // Auto-submit form on select change
    document.getElementById('categoria').addEventListener('change', function() {
        document.getElementById('searchForm').submit();
    });

    document.getElementById('estado').addEventListener('change', function() {
        document.getElementById('searchForm').submit();
    });

    document.getElementById('orden').addEventListener('change', function() {
        document.getElementById('searchForm').submit();
    });
</script>

<style>
    .list-view .col-lg-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .list-view .book-card {
        flex-direction: row;
    }

    .list-view .card-img-top {
        width: 150px;
        height: 150px;
        flex-shrink: 0;
    }

    .list-view .card-body {
        flex: 1;
    }

    .book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection 