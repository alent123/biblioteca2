@extends('layouts.app')

@section('title', 'Mis Favoritos - Retrolector')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">
                    <i class="fas fa-heart text-danger me-2"></i>
                    Mis Favoritos
                </h1>
                <a href="{{ route('books.catalog') }}" class="btn btn-outline-primary">
                    <i class="fas fa-search me-1"></i>
                    Explorar Catálogo
                </a>
            </div>

            @if($favoritos->count() > 0)
                <div class="row">
                    @foreach($favoritos as $favorito)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="book-card">
                            <div class="book-cover">
                                @if($favorito->libro->imagen_portada)
                                    <img src="{{ asset('storage/' . $favorito->libro->imagen_portada) }}" 
                                         alt="{{ $favorito->libro->titulo }}" class="img-fluid">
                                @else
                                    <div class="book-placeholder">
                                        <i class="fas fa-book fa-3x"></i>
                                    </div>
                                @endif
                                <div class="book-overlay">
                                    <a href="{{ route('books.show', $favorito->libro) }}" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </a>
                                    <button class="btn btn-danger btn-sm toggle-favorite" 
                                            data-book-id="{{ $favorito->libro->id }}" 
                                            data-url="{{ route('books.toggle-favorite', $favorito->libro) }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="book-info">
                                <h5 class="book-title">{{ $favorito->libro->titulo }}</h5>
                                <p class="book-author">{{ $favorito->libro->autor->nombre }} {{ $favorito->libro->autor->apellido }}</p>
                                <div class="book-meta">
                                    <span class="badge bg-primary">{{ $favorito->libro->categoria->nombre }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Agregado {{ $favorito->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="book-actions mt-2">
                                    <button class="btn btn-danger btn-sm toggle-favorite" 
                                            data-book-id="{{ $favorito->libro->id }}" 
                                            data-url="{{ route('books.toggle-favorite', $favorito->libro) }}">
                                        <i class="fas fa-heart me-1"></i>
                                        Quitar de favoritos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $favoritos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted">No tienes favoritos aún</h3>
                        <p class="text-muted mb-4">
                            Explora nuestro catálogo y marca los libros que te gusten como favoritos.
                        </p>
                        <a href="{{ route('books.catalog') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Explorar Catálogo
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.book-card {
    background: var(--bg-card);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.book-cover {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.book-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.book-card:hover .book-overlay {
    opacity: 1;
}

.book-info {
    padding: 1.5rem;
}

.book-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.book-author {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.book-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botones de favoritos
    const favoriteButtons = document.querySelectorAll('.toggle-favorite');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const bookId = this.dataset.bookId;
            const url = this.dataset.url;
            const bookCard = this.closest('.book-card');
            
            // Mostrar loading
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!data.isFavorite) {
                        // Si se quitó de favoritos, remover la tarjeta con animación
                        bookCard.style.transition = 'all 0.3s ease';
                        bookCard.style.transform = 'scale(0.8)';
                        bookCard.style.opacity = '0';
                        
                        setTimeout(() => {
                            bookCard.remove();
                            
                            // Verificar si no quedan favoritos
                            const remainingCards = document.querySelectorAll('.book-card');
                            if (remainingCards.length === 0) {
                                location.reload(); // Recargar para mostrar estado vacío
                            }
                        }, 300);
                    }
                    
                    // Mostrar notificación
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al actualizar favoritos', 'error');
            })
            .finally(() => {
                // Restaurar botón
                this.disabled = false;
                this.innerHTML = originalHTML;
            });
        });
    });
    
    // Función para mostrar notificaciones
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remover después de 3 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
});
</script>
@endsection 