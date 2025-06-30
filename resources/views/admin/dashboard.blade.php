@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Panel de Administración
                    </h1>
                    <p class="text-muted mb-0">Gestión completa de la biblioteca digital</p>
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
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-primary">{{ App\Models\Usuario::where('tipo', 'cliente')->count() }}</h4>
                    <p class="text-muted mb-0">Usuarios Registrados</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-success bg-opacity-10 text-success mb-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-success">{{ App\Models\Libro::count() }}</h4>
                    <p class="text-muted mb-0">Total Libros</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-warning bg-opacity-10 text-warning mb-3">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-warning">{{ App\Models\Prestamo::where('estado', 'activo')->count() }}</h4>
                    <p class="text-muted mb-0">Préstamos Activos</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stats-icon bg-info bg-opacity-10 text-info mb-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ App\Models\Reserva::where('estado', 'pendiente')->count() }}</h4>
                    <p class="text-muted mb-0">Reservas Pendientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users Management -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Gestión de Usuarios
                    </h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Usuario
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Préstamos</th>
                                    <th>Registrado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Usuario::where('tipo', 'cliente')->with(['prestamos'])->get() as $usuario)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <i class="fas fa-user-circle text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $usuario->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->telefono ?: 'No especificado' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->estado === 'activo' ? 'success' : 'danger' }}">
                                            {{ ucfirst($usuario->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $usuario->prestamos->where('estado', 'activo')->count() }} activos</span>
                                    </td>
                                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="verHistorialUsuario({{ $usuario->id }})"
                                                    title="Ver Historial">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" 
                                                    onclick="editarUsuario({{ $usuario->id }})"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-{{ $usuario->estado === 'activo' ? 'danger' : 'success' }}" 
                                                    onclick="toggleEstadoUsuario({{ $usuario->id }}, '{{ $usuario->estado }}')"
                                                    title="{{ $usuario->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $usuario->estado === 'activo' ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>
                            Gestionar Libros
                        </a>
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Gestionar Préstamos
                        </a>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-clock me-2"></i>
                            Gestionar Reservas
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-users me-2"></i>
                            Gestionar Usuarios
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @foreach(App\Models\Prestamo::with(['usuario', 'libro'])->latest()->take(5)->get() as $prestamo)
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon me-3">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="activity-content">
                                <p class="mb-0 small">
                                    <strong>{{ $prestamo->usuario->nombre }}</strong> tomó prestado 
                                    <strong>{{ $prestamo->libro->titulo }}</strong>
                                </p>
                                <small class="text-muted">{{ $prestamo->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User History Modal -->
<div class="modal fade" id="userHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userHistoryContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
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
    background: var(--bg-tertiary);
    color: var(--primary-color);
}

.user-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: var(--bg-tertiary);
    border-radius: 50%;
    color: var(--primary-color);
}

.table {
    background: var(--bg-card);
    color: var(--text-primary);
}

.table thead th {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-bottom: 2px solid var(--border-color);
}

.table-hover tbody tr:hover {
    background: var(--bg-secondary);
}

.table td, .table th {
    border-color: var(--border-color);
}

.card, .modal-content {
    background: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.card-header, .modal-header {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
}

.card-body, .modal-body {
    background: var(--bg-card);
    color: var(--text-primary);
}

.card-footer, .modal-footer {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border-top: 1px solid var(--border-color);
}

.form-label, .modal-title {
    color: var(--text-primary);
}

.form-control, .form-select {
    background: var(--bg-primary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    background: var(--bg-primary);
    color: var(--text-primary);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
}

.form-control::placeholder {
    color: var(--text-muted);
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

.claro .btn-close {
    filter: none;
}
.oscuro .btn-close {
    filter: invert(1);
}

.activity-icon {
    width: 30px;
    height: 30px;
    background: var(--bg-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.activity-item {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
</style>

<script>
function verHistorialUsuario(userId) {
    // Aquí cargarías el historial del usuario en el modal
    document.getElementById('userHistoryContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;
    
    // Simular carga de datos
    setTimeout(() => {
        document.getElementById('userHistoryContent').innerHTML = `
            <h6>Historial de Préstamos</h6>
            <p>Aquí se mostraría el historial completo del usuario ${userId}</p>
        `;
    }, 1000);
    
    new bootstrap.Modal(document.getElementById('userHistoryModal')).show();
}

function editarUsuario(userId) {
    // Lógica para editar usuario
    alert('Editar usuario ' + userId);
}

function toggleEstadoUsuario(userId, estadoActual) {
    const nuevaAccion = estadoActual === 'activo' ? 'desactivar' : 'activar';
    if (confirm(`¿Deseas ${nuevaAccion} este usuario?`)) {
        // Lógica para cambiar estado
        alert(`Usuario ${nuevaAccion}do exitosamente`);
        location.reload();
    }
}

// Form para agregar usuario
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Lógica para crear usuario
    alert('Usuario creado exitosamente');
    location.reload();
});

// Adaptar modales al tema
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
document.addEventListener('DOMContentLoaded', function() {
    applyThemeToModals();
    const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            setTimeout(applyThemeToModals, 100);
        });
    });
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
document.addEventListener('shown.bs.modal', function() {
    applyThemeToModals();
});
</script>
@endsection 