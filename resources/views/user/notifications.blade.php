@extends('layouts.app')

@section('title', 'Mis Notificaciones - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">
                    <i class="fas fa-bell me-2"></i>
                    Mis Notificaciones
                </h1>
                <div>
                    <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-1"></i>
                        Marcar todas como leídas
                    </button>
                </div>
            </div>

            @if($notifications->count() > 0)
                <div class="row">
                    @foreach($notifications as $notification)
                        <div class="col-12 mb-3">
                            <div class="card {{ !$notification->leida ? 'border-primary' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="card-title mb-0 me-2">
                                                    @if(!$notification->leida)
                                                        <span class="badge bg-primary me-2">Nueva</span>
                                                    @endif
                                                    {{ $notification->titulo }}
                                                </h5>
                                                <span class="badge bg-{{ $notification->tipo === 'success' ? 'success' : ($notification->tipo === 'warning' ? 'warning' : ($notification->tipo === 'error' ? 'danger' : 'info')) }}">
                                                    {{ ucfirst($notification->tipo) }}
                                                </span>
                                            </div>
                                            <p class="card-text">{{ $notification->mensaje }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if(!$notification->leida)
                                                <button class="btn btn-sm btn-outline-primary" onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">No tienes notificaciones</h3>
                    <p class="text-muted">Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-read`, {
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

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
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

    function deleteNotification(notificationId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
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
    }
</script>
@endpush
@endsection 