@extends('layouts.app')

@section('title', 'Mis Préstamos - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">
                    <i class="fas fa-book me-2"></i>
                    Mis Préstamos
                </h1>
                <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Prestar otro libro
                </a>
            </div>

            @if($prestamos->count() > 0)
                <div class="row">
                    @foreach($prestamos as $prestamo)
                        <div class="col-12 mb-3">
                            <div class="card {{ $prestamo->estado === 'vencido' ? 'border-danger' : ($prestamo->estado === 'prestado' ? 'border-primary' : 'border-success') }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="card-title mb-0 me-3">{{ $prestamo->libro->titulo }}</h5>
                                                <span class="badge bg-{{ $prestamo->estado === 'prestado' ? 'primary' : ($prestamo->estado === 'vencido' ? 'danger' : 'success') }}">
                                                    {{ ucfirst($prestamo->estado) }}
                                                </span>
                                            </div>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $prestamo->libro->autor->nombre }} {{ $prestamo->libro->autor->apellido }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $prestamo->libro->categoria->nombre }}
                                            </p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        Prestado: {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        Devolución: {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            @if($prestamo->estado === 'devuelto' && $prestamo->fecha_devolucion_real)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-times me-1"></i>
                                                        Devuelto: {{ $prestamo->fecha_devolucion_real->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            @endif
                                            @if($prestamo->estado === 'vencido')
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Vencido hace {{ $prestamo->fecha_devolucion_esperada->diffForHumans() }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="d-flex flex-column gap-2">
                                                @if($prestamo->estado === 'prestado')
                                                    <button class="btn btn-outline-primary btn-sm" onclick="renewLoan({{ $prestamo->id }})">
                                                        <i class="fas fa-redo me-1"></i>
                                                        Renovar
                                                    </button>
                                                    <button class="btn btn-success btn-sm" onclick="returnLoan({{ $prestamo->id }})">
                                                        <i class="fas fa-undo me-1"></i>
                                                        Devolver
                                                    </button>
                                                @elseif($prestamo->estado === 'vencido')
                                                    <button class="btn btn-success btn-sm" onclick="returnLoan({{ $prestamo->id }})">
                                                        <i class="fas fa-undo me-1"></i>
                                                        Devolver
                                                    </button>
                                                @endif
                                                <a href="{{ route('books.show', $prestamo->libro) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Ver libro
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $prestamos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">No tienes préstamos activos</h3>
                    <p class="text-muted">Explora nuestro catálogo y presta tu primer libro.</p>
                    <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Explorar catálogo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function renewLoan(prestamoId) {
        if (confirm('¿Estás seguro de que quieres renovar este préstamo?')) {
            fetch(`/loans/${prestamoId}/renew`, {
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
                    alert(data.message || 'Error al renovar el préstamo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al renovar el préstamo');
            });
        }
    }

    function returnLoan(prestamoId) {
        if (confirm('¿Estás seguro de que quieres devolver este libro?')) {
            fetch(`/loans/${prestamoId}/return`, {
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
                    alert(data.message || 'Error al devolver el libro');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al devolver el libro');
            });
        }
    }
</script>
@endpush
@endsection 