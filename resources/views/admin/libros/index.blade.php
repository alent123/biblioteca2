@extends('layouts.app')

@section('title', 'Gestión de Libros')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book me-2"></i>
                        Gestión de Libros
                    </h1>
                    <p class="text-muted mb-0">Administra el catálogo de libros de la biblioteca</p>
                </div>
                <div>
                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Agregar Libro
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.books.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Título, autor o ISBN...">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias ?? [] as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="prestado" {{ request('estado') == 'prestado' ? 'selected' : '' }}>Prestado</option>
                                <option value="reservado" {{ request('estado') == 'reservado' ? 'selected' : '' }}>Reservado</option>
                                <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="sort" class="form-label">Ordenar</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="titulo" {{ request('sort') == 'titulo' ? 'selected' : '' }}>Título</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Fecha de creación</option>
                                <option value="estado" {{ request('sort') == 'estado' ? 'selected' : '' }}>Estado</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Libros -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Libros ({{ $libros->total() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($libros->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Portada</th>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Stock</th>
                                        <th>Préstamos</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($libros as $libro)
                                    <tr>
                                        <td>
                                            @if($libro->imagen_portada)
                                                <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                                     alt="{{ $libro->titulo }}" 
                                                     class="img-thumbnail" style="width: 50px; height: 70px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 70px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $libro->titulo }}</strong>
                                            @if($libro->isbn)
                                                <br><small class="text-muted">ISBN: {{ $libro->isbn }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $libro->categoria->color }}">
                                                {{ $libro->categoria->nombre }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($libro->estado == 'disponible')
                                                <span class="badge bg-success">Disponible</span>
                                            @elseif($libro->estado == 'prestado')
                                                <span class="badge bg-danger">Prestado</span>
                                            @elseif($libro->estado == 'reservado')
                                                <span class="badge bg-warning">Reservado</span>
                                            @else
                                                <span class="badge bg-secondary">Mantenimiento</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $libro->stock }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $libro->prestamos->where('estado', 'prestado')->count() }} activos</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('books.show', $libro) }}" 
                                                   class="btn btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.books.edit', $libro) }}" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        onclick="deleteBook({{ $libro->id }})" 
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $libros->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h4>No hay libros registrados</h4>
                            <p class="text-muted">Comienza agregando el primer libro a la biblioteca</p>
                            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Agregar Primer Libro
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este libro?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBook(bookId) {
    if (confirm('¿Está seguro de que desea eliminar este libro?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/books/${bookId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection 