@extends('layouts.app')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Panel de Usuario
                    </h1>
                    <p class="text-muted mb-0">Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
                </div>
                <div class="text-end">
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary mb-3">
                        <i class="fas fa-book-open fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-primary">{{ auth()->user()->prestamos->where('estado', 'prestado')->count() }}</h4>
                    <p class="text-muted mb-0">Libros Prestados</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-success bg-opacity-10 text-success mb-3">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-success">{{ auth()->user()->prestamos->count() }}</h4>
                    <p class="text-muted mb-0">Total Préstamos</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning mb-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-warning">{{ auth()->user()->reservas->where('estado', 'pendiente')->count() }}</h4>
                    <p class="text-muted mb-0">Reservas Activas</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-info bg-opacity-10 text-info mb-3">
                        <i class="fas fa-heart fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ auth()->user()->favoritos->count() }}</h4>
                    <p class="text-muted mb-0">Favoritos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="profile-avatar mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <span class="badge bg-success">{{ ucfirst(auth()->user()->tipo) }}</span>
                    </div>
                    
                    <div class="profile-info">
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Teléfono:</div>
                            <div class="col-8">{{ auth()->user()->telefono ?: 'No especificado' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Dirección:</div>
                            <div class="col-8">{{ auth()->user()->direccion ?: 'No especificada' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Idioma:</div>
                            <div class="col-8">{{ ucfirst(auth()->user()->idioma_preferencia) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Tema:</div>
                            <div class="col-8">{{ ucfirst(auth()->user()->tema_preferencia) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Estado:</div>
                            <div class="col-8">
                                <span class="badge bg-{{ auth()->user()->estado === 'activo' ? 'success' : 'danger' }}">
                                    {{ ucfirst(auth()->user()->estado) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted">Registrado:</div>
                            <div class="col-8">{{ auth()->user()->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.catalog') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>
                            Explorar Catálogo
                        </a>
                        <a href="{{ route('user.favorites') }}" class="btn btn-outline-info">
                            <i class="fas fa-heart me-2"></i>
                            Mis Favoritos
                        </a>
                        <a href="{{ route('user.historial') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i>
                            Mi Historial
                        </a>
                        <a href="{{ route('user.reservations') }}" class="btn btn-outline-warning">
                            <i class="fas fa-clock me-2"></i>
                            Mis Reservas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="col-lg-8">
            <!-- Préstamos Activos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2"></i>
                        Préstamos Activos
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->prestamos->where('estado', 'prestado')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Días Restantes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->prestamos->where('estado', 'prestado') as $prestamo)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="book-cover-mini me-2">
                                                    @if($prestamo->libro->imagen_portada)
                                                        <img src="{{ asset('storage/' . $prestamo->libro->imagen_portada) }}" 
                                                             alt="{{ $prestamo->libro->titulo }}" 
                                                             class="img-thumbnail" style="width: 40px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-book text-primary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $prestamo->libro->titulo }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $prestamo->libro->autor->nombre }} {{ $prestamo->libro->autor->apellido }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                                        <td>{{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $diasRestantes = now()->diffInDays($prestamo->fecha_devolucion_esperada, false);
                                            @endphp
                                            @if($diasRestantes > 0)
                                                <span class="badge bg-success">{{ $diasRestantes }} días</span>
                                            @elseif($diasRestantes == 0)
                                                <span class="badge bg-warning">Hoy</span>
                                            @else
                                                <span class="badge bg-danger">{{ abs($diasRestantes) }} días vencido</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="renovarPrestamo({{ $prestamo->id }})">
                                                <i class="fas fa-sync-alt me-1"></i>
                                                Renovar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5>No tienes préstamos activos</h5>
                            <p class="text-muted">Explora el catálogo para encontrar libros interesantes</p>
                            <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Explorar Catálogo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Préstamos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Préstamos
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->prestamos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->prestamos->sortByDesc('fecha_prestamo')->take(10) as $prestamo)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="book-cover-mini me-2">
                                                    @if($prestamo->libro->imagen_portada)
                                                        <img src="{{ asset('storage/' . $prestamo->libro->imagen_portada) }}" 
                                                             alt="{{ $prestamo->libro->titulo }}" 
                                                             class="img-thumbnail" style="width: 40px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-book text-primary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $prestamo->libro->titulo }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $prestamo->libro->autor->nombre }} {{ $prestamo->libro->autor->apellido }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                                        <td>
                                            @if($prestamo->fecha_devolucion_real)
                                                {{ $prestamo->fecha_devolucion_real->format('d/m/Y') }}
                                            @else
                                                {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($prestamo->estado === 'prestado')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($prestamo->estado === 'devuelto')
                                                <span class="badge bg-info">Devuelto</span>
                                            @elseif($prestamo->estado === 'vencido')
                                                <span class="badge bg-danger">Vencido</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5>No hay historial de préstamos</h5>
                            <p class="text-muted">Comienza a explorar y prestar libros</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Libros Vistos Recientemente -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Libros Vistos Recientemente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $librosVistos = session('libros_vistos', []);
                            $librosVistos = collect($librosVistos)->take(4);
                        @endphp
                        
                        @if($librosVistos->count() > 0)
                            @foreach($librosVistos as $libroId)
                                @php
                                    $libro = App\Models\Libro::with(['autor', 'categoria'])->find($libroId);
                                @endphp
                                @if($libro)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="row g-0">
                                                <div class="col-4">
                                                    @if($libro->imagen_portada)
                                                        <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                                             class="img-fluid rounded-start h-100" 
                                                             style="object-fit: cover;" alt="{{ $libro->titulo }}">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ Str::limit($libro->titulo, 30) }}</h6>
                                                        <p class="card-text small text-muted">
                                                            {{ $libro->autor->nombre }} {{ $libro->autor->apellido }}
                                                        </p>
                                                        <a href="{{ route('books.show', $libro) }}" class="btn btn-sm btn-outline-primary">
                                                            Ver Detalles
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="fas fa-eye fa-3x text-muted mb-3"></i>
                                    <h5>No hay libros vistos recientemente</h5>
                                    <p class="text-muted">Explora el catálogo para ver libros</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

.book-cover-mini {
    width: 40px;
    height: 50px;
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.profile-info .row {
    border-bottom: 1px solid var(--border-color);
    padding: 0.5rem 0;
}

.profile-info .row:last-child {
    border-bottom: none;
}
</style>

<script>
function renovarPrestamo(prestamoId) {
    if (confirm('¿Está seguro de que desea renovar este préstamo?')) {
        fetch(`/user/loans/${prestamoId}/renew`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Préstamo renovado exitosamente');
                location.reload();
            } else {
                alert('Error al renovar el préstamo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al renovar el préstamo');
        });
    }
}
</script>
@endsection 