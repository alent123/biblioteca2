@extends('layouts.app')

@section('title', 'Retrolector - Biblioteca Digital')

@section('content')
<!-- Hero Section -->
<div class="hero-section d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="hero-title mb-3">
                        <span class="text-primary">Retrolector</span>
                        <br>
                        <span class="hero-subtitle">Tu Biblioteca Digital Inteligente</span>
                    </h1>
                    <p class="hero-description mb-4">
                        Explora una colección moderna de miles de libros, gestiona tus préstamos y reservas en tiempo real, recibe notificaciones y disfruta de acceso 24/7 desde cualquier dispositivo.<br>
                        <strong>¡Descubre una nueva forma de leer y aprender!</strong>
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('books.catalog') }}" class="btn btn-primary btn-lg me-3 mb-2">
                            <i class="fas fa-book-open me-2"></i>
                            Explorar Catálogo
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-flex justify-content-center">
                <div class="hero-image text-center">
                    <div class="book-stack">
                        <div class="book book-1"></div>
                        <div class="book book-2"></div>
                        <div class="book book-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="stats-section py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="stat-item">
                    <i class="fas fa-books fa-2x mb-2" style="color: white;"></i>
                    <h3 class="stat-number" style="color: white;">{{ number_format($estadisticas['total_libros']) }}</h3>
                    <p class="stat-label" style="color: white;">Libros Disponibles</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-item">
                    <i class="fas fa-users fa-2x mb-2" style="color: white;"></i>
                    <h3 class="stat-number" style="color: white;">{{ number_format($estadisticas['total_usuarios']) }}</h3>
                    <p class="stat-label" style="color: white;">Usuarios Registrados</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-item">
                    <i class="fas fa-bookmark fa-2x mb-2" style="color: white;"></i>
                    <h3 class="stat-number" style="color: white;">{{ number_format($estadisticas['prestamos_activos']) }}</h3>
                    <p class="stat-label" style="color: white;">Préstamos Activos</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-item">
                    <i class="fas fa-clock fa-2x mb-2" style="color: white;"></i>
                    <h3 class="stat-number" style="color: white;">{{ number_format($estadisticas['reservas_pendientes']) }}</h3>
                    <p class="stat-label" style="color: white;">Reservas Pendientes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Libros Populares -->
<div class="popular-books-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">📚 Libros Más Populares</h2>
            <p class="section-subtitle">Los títulos más solicitados por nuestra comunidad</p>
        </div>
        <div class="row">
            @forelse($librosPopulares as $libro)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="book-card">
                    <div class="book-cover">
                        @if($libro->imagen_portada)
                            <img src="{{ asset('storage/' . $libro->imagen_portada) }}" alt="{{ $libro->titulo }}" class="img-fluid">
                        @else
                            <div class="book-placeholder">
                                <i class="fas fa-book fa-3x"></i>
                            </div>
                        @endif
                        <div class="book-overlay">
                            <a href="{{ route('books.show', $libro) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Ver Detalles
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <h5 class="book-title">{{ $libro->titulo }}</h5>
                        <p class="book-author">{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</p>
                        <div class="book-meta">
                            <span class="badge bg-primary">{{ $libro->categoria->nombre }}</span>
                            <span class="book-loans">
                                <i class="fas fa-hand-holding-usd me-1"></i>
                                {{ $libro->prestamos_count }} préstamos
                            </span>
                        </div>
                        @auth
                        <div class="book-actions mt-2">
                            <button class="btn btn-outline-primary btn-sm toggle-favorite" 
                                    data-book-id="{{ $libro->id }}" 
                                    data-url="{{ route('books.toggle-favorite', $libro) }}">
                                <i class="fas fa-heart me-1"></i>
                                <span class="favorite-text">Me gusta</span>
                            </button>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No hay libros populares disponibles en este momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Noticias y Artículos -->
<div class="news-section py-5" style="background-color: var(--bg-tertiary);">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">📰 Noticias y Artículos</h2>
            <p class="section-subtitle">Mantente informado sobre el mundo de la lectura</p>
        </div>
        <div class="row">
            @foreach($noticias as $noticia)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-icon mb-3">
                        <i class="{{ $noticia['icono'] }} fa-2x text-primary"></i>
                    </div>
                    <div class="news-category">{{ $noticia['categoria'] }}</div>
                    <h5 class="news-title">{{ $noticia['titulo'] }}</h5>
                    <p class="news-summary">{{ $noticia['resumen'] }}</p>
                    <div class="news-meta">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $noticia['fecha']->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Citas Inspiradoras -->
<div class="quotes-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="quote-carousel">
                    @foreach($citas as $cita)
                    <div class="quote-item">
                        <div class="quote-text">
                            <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                            <p class="quote-content">{{ $cita['texto'] }}</p>
                            <cite class="quote-author">— {{ $cita['autor'] }}</cite>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Eventos Próximos -->
<div class="events-section py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title" style="color: white;">📅 Eventos Próximos</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.9);">Participa en nuestras actividades y talleres</p>
        </div>
        <div class="row">
            @foreach($eventos as $evento)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="event-card">
                    <div class="event-date">
                        <div class="event-day">{{ $evento['fecha']->format('d') }}</div>
                        <div class="event-month">{{ $evento['fecha']->format('M') }}</div>
                    </div>
                    <div class="event-content">
                        <h5 class="event-title">{{ $evento['titulo'] }}</h5>
                        <p class="event-description">{{ $evento['descripcion'] }}</p>
                        <div class="event-time">
                            <i class="fas fa-clock me-1"></i>
                            {{ $evento['hora'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Libros Recientes -->
<div class="recent-books-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">🆕 Libros Recientes</h2>
            <p class="section-subtitle">Las últimas adquisiciones de nuestra biblioteca</p>
        </div>
        <div class="row">
            @forelse($librosRecientes as $libro)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="recent-book-card">
                    <div class="recent-book-cover">
                        @if($libro->imagen_portada)
                            <img src="{{ asset('storage/' . $libro->imagen_portada) }}" alt="{{ $libro->titulo }}" class="img-fluid">
                        @else
                            <div class="recent-book-placeholder">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        @endif
                        <div class="new-badge">NUEVO</div>
                    </div>
                    <div class="recent-book-info">
                        <h6 class="recent-book-title">{{ $libro->titulo }}</h6>
                        <p class="recent-book-author">{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</p>
                        <div class="recent-book-actions">
                            <a href="{{ route('books.show', $libro) }}" class="btn btn-outline-primary btn-sm me-2">
                                Ver Libro
                            </a>
                            @auth
                            <button class="btn btn-outline-danger btn-sm toggle-favorite" 
                                    data-book-id="{{ $libro->id }}" 
                                    data-url="{{ route('books.toggle-favorite', $libro) }}">
                                <i class="fas fa-heart"></i>
                            </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No hay libros recientes disponibles.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Acceso Administrativo -->
<div class="admin-access-section py-5" style="background-color: var(--bg-tertiary);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 class="mb-4">Acceso Administrativo</h3>
                <p class="text-muted mb-4">
                    ¿Eres administrador? Accede al panel de gestión para administrar usuarios, libros y préstamos del sistema.
                </p>
                <a href="{{ route('login.admin') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-user-shield me-2"></i>
                    Panel Administrativo
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
    color: var(--text-primary);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.book-stack {
    position: relative;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.book {
    position: absolute;
    width: 120px;
    height: 160px;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: rotate(-5deg);
    transition: all 0.3s ease;
}

.book-1 {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    transform: rotate(-15deg) translateX(-60px);
    z-index: 1;
}

.book-2 {
    background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
    transform: rotate(-5deg) translateX(0px);
    z-index: 2;
}

.book-3 {
    background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
    transform: rotate(5deg) translateX(60px);
    z-index: 3;
}

.book:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

/* Hero Section Styles */
.hero-title {
    font-size: 3rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.1;
    transition: color 0.3s;
}
.hero-subtitle {
    font-size: 2rem;
    font-weight: 400;
    color: var(--text-primary);
    transition: color 0.3s;
}
.hero-description {
    font-size: 1.25rem;
    color: var(--text-primary);
    margin-bottom: 2rem;
    transition: color 0.3s;
}

/* Stats Section */
.stat-item {
    padding: 1rem;
}
.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-white);
}
.stat-label {
    font-size: 0.9rem;
    margin: 0;
    opacity: 0.9;
    color: var(--text-white);
}

/* Section Headers */
.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text-primary);
}
.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 0;
}

/* Book Cards */
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
}
.book-loans {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* News Cards */
.news-card {
    background: var(--bg-card);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    height: 100%;
}
.news-card:hover {
    transform: translateY(-5px);
}
.news-category {
    color: var(--primary-color);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}
.news-title {
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-primary);
}
.news-summary {
    color: var(--text-secondary);
    margin-bottom: 1rem;
    line-height: 1.6;
}

/* Quote Carousel */
.quote-carousel {
    position: relative;
}
.quote-item {
    padding: 2rem;
}
.quote-content {
    font-size: 1.5rem;
    font-style: italic;
    margin-bottom: 1rem;
    color: var(--text-primary);
}
.quote-author {
    font-size: 1rem;
    color: var(--primary-color);
    font-weight: 600;
}

/* Event Cards */
.event-card {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}
.event-card:hover {
    transform: translateY(-3px);
}
.event-date {
    text-align: center;
    margin-right: 1.5rem;
    min-width: 60px;
}
.event-day {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: var(--text-white);
}
.event-month {
    font-size: 0.8rem;
    text-transform: uppercase;
    opacity: 0.8;
    color: var(--text-white);
}
.event-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-white);
}
.event-description {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    color: var(--text-white);
}

/* Recent Books */
.recent-book-card {
    background: var(--bg-card);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}
.recent-book-card:hover {
    transform: translateY(-3px);
}
.recent-book-cover {
    position: relative;
    height: 150px;
    overflow: hidden;
}
.recent-book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.recent-book-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.new-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff6b6b;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
}
.recent-book-info {
    padding: 1rem;
    text-align: center;
}
.recent-book-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}
.recent-book-author {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

/* Admin Access Section */
.admin-access-section {
    background: var(--bg-tertiary) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    .hero-subtitle {
        font-size: 1.5rem;
    }
    .section-title {
        font-size: 2rem;
    }
    .stat-number {
        font-size: 2rem;
    }
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    .book-stack {
        height: 200px;
        margin-top: 2rem;
    }
    .book {
        width: 80px;
        height: 120px;
    }
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
            const icon = this.querySelector('i');
            const text = this.querySelector('.favorite-text');
            
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
                    // Actualizar el botón
                    if (data.isFavorite) {
                        this.classList.remove('btn-outline-primary', 'btn-outline-danger');
                        this.classList.add('btn-danger');
                        icon.className = 'fas fa-heart me-1';
                        if (text) text.textContent = 'Me gusta';
                    } else {
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-primary', 'btn-outline-danger');
                        icon.className = 'far fa-heart me-1';
                        if (text) text.textContent = 'Me gusta';
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
    
    // Verificar estado inicial de favoritos
    favoriteButtons.forEach(button => {
        const bookId = button.dataset.bookId;
        const checkUrl = `/books/${bookId}/check-favorite`;
        
        fetch(checkUrl, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const icon = button.querySelector('i');
            const text = button.querySelector('.favorite-text');
            
            if (data.isFavorite) {
                button.classList.remove('btn-outline-primary', 'btn-outline-danger');
                button.classList.add('btn-danger');
                icon.className = 'fas fa-heart me-1';
                if (text) text.textContent = 'Me gusta';
            }
        })
        .catch(error => console.error('Error checking favorite status:', error));
    });
});
</script>
@endsection
