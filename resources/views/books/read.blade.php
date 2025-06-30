@extends('layouts.app')

@section('title', 'Leer ' . $libro->titulo . ' - Retrolector')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar de Información -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        Información del Libro
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($libro->imagen_portada)
                            <img src="{{ asset('storage/' . $libro->imagen_portada) }}" 
                                 alt="{{ $libro->titulo }}" 
                                 class="img-fluid rounded" style="max-width: 150px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded mx-auto" 
                                 style="width: 150px; height: 200px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h6 class="mb-2">{{ $libro->titulo }}</h6>
                    <p class="text-muted small mb-3">{{ $libro->autor->nombre }} {{ $libro->autor->apellido }}</p>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $libro->categoria->nombre }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Información del Préstamo</h6>
                        <p class="small mb-1">
                            <strong>Fecha de préstamo:</strong><br>
                            {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                        </p>
                        <p class="small mb-1">
                            <strong>Fecha de devolución:</strong><br>
                            {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                        </p>
                        <p class="small">
                            <strong>Días restantes:</strong><br>
                            <span class="badge bg-{{ $prestamo->fecha_devolucion_esperada->diffInDays(now()) <= 3 ? 'danger' : 'success' }}">
                                {{ $prestamo->fecha_devolucion_esperada->diffInDays(now()) }} días
                            </span>
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleSidebar()">
                            <i class="fas fa-eye-slash me-2"></i>
                            Ocultar Panel
                        </button>
                        <a href="{{ route('books.show', $libro) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-info-circle me-2"></i>
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>

            <!-- Controles de Lectura -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Controles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small">Tamaño de Fuente</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="changeFontSize(-1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="form-control-plaintext text-center" id="fontSizeDisplay">16px</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="changeFontSize(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small">Tema</label>
                        <select class="form-select form-select-sm" id="themeSelect" onchange="changeTheme()">
                            <option value="light">Claro</option>
                            <option value="sepia">Sepia</option>
                            <option value="dark">Oscuro</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small">Navegación</label>
                        <div class="d-grid gap-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="previousPage()">
                                <i class="fas fa-chevron-left me-1"></i>Anterior
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="nextPage()">
                                Siguiente<i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small">Marcadores</label>
                        <div class="d-grid gap-1">
                            <button class="btn btn-sm btn-outline-warning" onclick="addBookmark()">
                                <i class="fas fa-bookmark me-1"></i>Agregar
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="showBookmarks()">
                                <i class="fas fa-list me-1"></i>Ver Todos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Lectura -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-book-open me-2"></i>
                            {{ $libro->titulo }}
                        </h5>
                        <small class="text-muted">Página <span id="currentPage">1</span> de <span id="totalPages">1</span></small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen()">
                            <i class="fas fa-expand"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="printBook()">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="reader" class="reader-container">
                        <!-- Contenido del libro se cargará aquí -->
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Cargando libro...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Marcadores -->
<div class="modal fade" id="bookmarksModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bookmark me-2"></i>
                    Mis Marcadores
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bookmarksList">
                    <p class="text-muted text-center">No hay marcadores guardados</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reader-container {
    min-height: 70vh;
    background: var(--bg-color);
    color: var(--text-color);
    font-family: 'Georgia', serif;
    line-height: 1.8;
    padding: 2rem;
    font-size: 16px;
    transition: all 0.3s ease;
}

.reader-container.light {
    --bg-color: #ffffff;
    --text-color: #333333;
}

.reader-container.sepia {
    --bg-color: #f4ecd8;
    --text-color: #5c4b37;
}

.reader-container.dark {
    --bg-color: #2c2c2c;
    --text-color: #e0e0e0;
}

.reader-container h1, .reader-container h2, .reader-container h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
}

.reader-container p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.reader-container .chapter {
    page-break-before: always;
    margin-top: 2rem;
}

.reader-container .page-break {
    page-break-after: always;
}

/* Responsive */
@media (max-width: 768px) {
    .col-lg-3 {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        z-index: 1050;
        background: white;
        transition: left 0.3s ease;
        overflow-y: auto;
    }
    
    .col-lg-3.show {
        left: 0;
    }
    
    .col-lg-9 {
        width: 100%;
    }
}

/* Fullscreen */
.reader-container.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 1060;
    background: var(--bg-color);
    overflow-y: auto;
}
</style>

<script>
let currentPage = 1;
let totalPages = 1;
let fontSize = 16;
let currentTheme = 'light';
let bookmarks = [];

// Simular contenido del libro (en un caso real, esto vendría del servidor)
const bookContent = `
    <div class="chapter">
        <h1>${@json($libro->titulo)}</h1>
        <h3>Por ${@json($libro->autor->nombre)} ${@json($libro->autor->apellido)}</h3>
        
        <p>Este es el contenido del libro "${@json($libro->titulo)}". Aquí encontrarás la historia completa, 
        los personajes y todas las aventuras que te esperan en sus páginas.</p>
        
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore 
        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut 
        aliquip ex ea commodo consequat.</p>
        
        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        
        <div class="page-break"></div>
        
        <h2>Capítulo 1: El Comienzo</h2>
        
        <p>En un lugar de la Mancha, de cuyo nombre no quiero acordarme, no ha mucho tiempo que vivía un hidalgo 
        de los de lanza en astillero, adarga antigua, rocín flaco y galgo corredor.</p>
        
        <p>Una olla de algo más vaca que carnero, salpicón las más noches, duelos y quebrantos los sábados, 
        lantejas los viernes, algún palomino de añadidura los domingos, consumían las tres partes de su hacienda.</p>
        
        <p>El resto della concluían sayo de velarte, calzas de velludo para las fiestas, con sus pantuflos de lo mesmo, 
        y los días de entresemana se honraba con su vellorí de lo más fino.</p>
    </div>
`;

// Inicializar el lector
document.addEventListener('DOMContentLoaded', function() {
    loadBook();
    updateFontSizeDisplay();
    loadBookmarks();
});

function loadBook() {
    const reader = document.getElementById('reader');
    reader.innerHTML = bookContent;
    reader.className = `reader-container ${currentTheme}`;
    
    // Calcular páginas (simulado)
    totalPages = Math.ceil(bookContent.length / 1000);
    updatePageDisplay();
}

function changeFontSize(delta) {
    fontSize = Math.max(12, Math.min(24, fontSize + delta));
    document.getElementById('reader').style.fontSize = fontSize + 'px';
    updateFontSizeDisplay();
    saveSettings();
}

function updateFontSizeDisplay() {
    document.getElementById('fontSizeDisplay').textContent = fontSize + 'px';
}

function changeTheme() {
    currentTheme = document.getElementById('themeSelect').value;
    document.getElementById('reader').className = `reader-container ${currentTheme}`;
    saveSettings();
}

function toggleFullscreen() {
    const reader = document.getElementById('reader');
    reader.classList.toggle('fullscreen');
    
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    
    if (reader.classList.contains('fullscreen')) {
        icon.className = 'fas fa-compress';
    } else {
        icon.className = 'fas fa-expand';
    }
}

function toggleSidebar() {
    const sidebar = document.querySelector('.col-lg-3');
    sidebar.classList.toggle('show');
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePageDisplay();
        scrollToPage();
    }
}

function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        updatePageDisplay();
        scrollToPage();
    }
}

function updatePageDisplay() {
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
}

function scrollToPage() {
    // Simular navegación por páginas
    const reader = document.getElementById('reader');
    const pageHeight = reader.scrollHeight / totalPages;
    reader.scrollTop = (currentPage - 1) * pageHeight;
}

function addBookmark() {
    const bookmark = {
        id: Date.now(),
        page: currentPage,
        title: `Página ${currentPage}`,
        timestamp: new Date().toLocaleString()
    };
    
    bookmarks.push(bookmark);
    saveBookmarks();
    
    // Mostrar notificación
    showNotification('Marcador agregado en la página ' + currentPage);
}

function showBookmarks() {
    const modal = new bootstrap.Modal(document.getElementById('bookmarksModal'));
    const list = document.getElementById('bookmarksList');
    
    if (bookmarks.length === 0) {
        list.innerHTML = '<p class="text-muted text-center">No hay marcadores guardados</p>';
    } else {
        list.innerHTML = bookmarks.map(bookmark => `
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                <div>
                    <strong>${bookmark.title}</strong><br>
                    <small class="text-muted">${bookmark.timestamp}</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="goToBookmark(${bookmark.page})">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeBookmark(${bookmark.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    modal.show();
}

function goToBookmark(page) {
    currentPage = page;
    updatePageDisplay();
    scrollToPage();
    bootstrap.Modal.getInstance(document.getElementById('bookmarksModal')).hide();
}

function removeBookmark(id) {
    bookmarks = bookmarks.filter(b => b.id !== id);
    saveBookmarks();
    showBookmarks(); // Recargar la lista
}

function printBook() {
    window.print();
}

function showNotification(message) {
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1070; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function saveSettings() {
    localStorage.setItem('readerSettings', JSON.stringify({
        fontSize: fontSize,
        theme: currentTheme
    }));
}

function loadSettings() {
    const settings = JSON.parse(localStorage.getItem('readerSettings') || '{}');
    if (settings.fontSize) fontSize = settings.fontSize;
    if (settings.theme) currentTheme = settings.theme;
    
    document.getElementById('themeSelect').value = currentTheme;
}

function saveBookmarks() {
    localStorage.setItem('bookmarks_' + @json($libro->id), JSON.stringify(bookmarks));
}

function loadBookmarks() {
    const saved = localStorage.getItem('bookmarks_' + @json($libro->id));
    if (saved) {
        bookmarks = JSON.parse(saved);
    }
}

// Cargar configuraciones al iniciar
loadSettings();
</script>
@endsection 