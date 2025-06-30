@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Mis Reservas
                    </h1>
                    <p class="text-muted mb-0">Libros que has reservado</p>
                </div>
                <div>
                    <a href="{{ route('books.catalog') }}" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>
                        Explorar Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Reservas -->
    <div class="row">
        <div class="col-12">
            @if($reservas->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Reservas ({{ $reservas->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Libro</th>
                                        <th>Fecha de Reserva</th>
                                        <th>Fecha de Expiración</th>
                                        <th>Estado</th>
                                        <th>Días Restantes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservas as $reserva)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="book-cover-mini me-3">
                                                    @if($reserva->libro->imagen_portada)
                                                        <img src="{{ asset('storage/' . $reserva->libro->imagen_portada) }}" 
                                                             alt="{{ $reserva->libro->titulo }}" 
                                                             class="img-thumbnail" style="width: 50px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 70px;">
                                                            <i class="fas fa-book text-primary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $reserva->libro->titulo }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $reserva->libro->autor->nombre }} {{ $reserva->libro->autor->apellido }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $reserva->fecha_reserva->format('d/m/Y H:i') }}</td>
                                        <td>{{ $reserva->fecha_expiracion->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($reserva->estado == 'pendiente')
                                                <span class="badge bg-warning">Pendiente</span>
                                            @elseif($reserva->estado == 'completada')
                                                <span class="badge bg-success">Completada</span>
                                            @elseif($reserva->estado == 'cancelada')
                                                <span class="badge bg-secondary">Cancelada</span>
                                            @elseif($reserva->estado == 'expirada')
                                                <span class="badge bg-danger">Expirada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $diasRestantes = now()->diffInDays($reserva->fecha_expiracion, false);
                                            @endphp
                                            @if($diasRestantes > 0)
                                                <span class="badge bg-success">{{ $diasRestantes }} días</span>
                                            @elseif($diasRestantes == 0)
                                                <span class="badge bg-warning">Hoy</span>
                                            @else
                                                <span class="badge bg-danger">{{ abs($diasRestantes) }} días vencida</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('books.show', $reserva->libro) }}" 
                                                   class="btn btn-outline-primary" title="Ver libro">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($reserva->estado == 'pendiente')
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="cancelarReserva({{ $reserva->id }})"
                                                            title="Cancelar reserva">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <h4>No tienes reservas</h4>
                    <p class="text-muted">Explora el catálogo y reserva libros que te interesen</p>
                    <a href="{{ route('books.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Explorar Catálogo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function cancelarReserva(reservaId) {
    if (confirm('¿Está seguro de que desea cancelar esta reserva?')) {
        fetch(`/user/reservations/${reservaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error al cancelar la reserva');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la reserva');
        });
    }
}
</script>
@endsection 