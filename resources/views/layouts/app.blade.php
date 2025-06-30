<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ $theme ?? session('theme', 'claro') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Retrolector - Biblioteca Digital')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Colores principales */
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            
            /* Colores de texto */
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --text-white: #ffffff;
            
            /* Colores de fondo */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-card: #ffffff;
            
            /* Colores de borde */
            --border-color: #e5e7eb;
            --border-light: #f3f4f6;
            
            /* Sombras */
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .oscuro {
            /* Colores principales */
            --primary-color: #3b82f6;
            --secondary-color: #60a5fa;
            --accent-color: #93c5fd;
            
            /* Colores de texto */
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --text-white: #ffffff;
            
            /* Colores de fondo */
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --bg-tertiary: #374151;
            --bg-card: #1f2937;
            
            /* Colores de borde */
            --border-color: #374151;
            --border-light: #4b5563;
            
            /* Sombras */
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            background-color: var(--bg-secondary);
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--text-primary);
        }

        p, span, div {
            color: var(--text-primary);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .text-white {
            color: var(--text-white) !important;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: var(--shadow);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-white) !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--text-white) !important;
            transform: translateY(-2px);
        }

        .dropdown-menu {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
        }

        .dropdown-item {
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .dropdown-header {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: var(--text-white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: var(--text-white);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid var(--text-secondary);
            color: var(--text-secondary);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: var(--text-secondary);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .btn-outline-info {
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--accent-color);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .btn-outline-warning {
            border: 2px solid var(--warning-color);
            color: var(--warning-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-warning:hover {
            background: var(--warning-color);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .btn-outline-success {
            border: 2px solid var(--success-color);
            color: var(--success-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-success:hover {
            background: var(--success-color);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .card-body {
            color: var(--text-primary);
        }

        .table {
            color: var(--text-primary);
        }

        .table th {
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }

        .table td {
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }

        .form-control {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: var(--bg-primary);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
        }

        .input-group-text {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .alert {
            border: none;
            border-radius: 8px;
            color: var(--text-primary);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .badge {
            font-weight: 500;
        }

        .bg-primary {
            background: var(--primary-color) !important;
        }

        .bg-secondary {
            background: var(--secondary-color) !important;
        }

        .bg-success {
            background: var(--success-color) !important;
        }

        .bg-warning {
            background: var(--warning-color) !important;
        }

        .bg-danger {
            background: var(--danger-color) !important;
        }

        .bg-info {
            background: var(--accent-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-info {
            color: var(--accent-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-white);
            padding: 4rem 0;
            margin-top: -1.5rem;
        }

        .hero-section h1 {
            color: var(--text-white) !important;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }

        .hero-section .lead {
            color: rgba(255,255,255,0.9);
            font-size: 1.25rem;
        }

        .features-section {
            background: var(--bg-card);
            padding: 4rem 0;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--text-white);
            font-size: 2rem;
        }

        .stats-section {
            background: var(--bg-secondary);
            padding: 3rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
        }

        .stat-item h3 {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-white);
            padding: 3rem 0 1rem;
        }

        .footer h5 {
            color: var(--text-white);
            margin-bottom: 1.5rem;
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--text-white);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem;
            padding-top: 1rem;
            text-align: center;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }

        /* Animaciones */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Notificaciones */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: var(--text-white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .notification-dropdown {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background: var(--bg-tertiary);
        }

        .notification-item.unread {
            background: rgba(59, 130, 246, 0.1);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .stat-item h3 {
                font-size: 2rem;
            }
        }

        /* Theme Toggle Button */
        .theme-toggle {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background: rgba(255,255,255,0.2);
            color: var(--text-white) !important;
            transform: translateY(-2px);
        }

        /* Smooth transitions for theme changes */
        body, .card, .navbar, .btn, .form-control, .table, .dropdown-menu {
            transition: all 0.3s ease;
        }

        /* Language switcher styling */
        .navbar-nav .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>
                Retrolector
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>
                            {{ __('messages.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books.catalog') }}">
                            <i class="fas fa-search me-1"></i>
                            {{ __('messages.books') }}
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->tipo === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>
                                    {{ __('messages.dashboard') }}
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-user me-1"></i>
                                    {{ __('messages.dashboard') }}
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i>
                            {{ app()->getLocale() === 'es' ? 'ES' : 'EN' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'es') }}">Español</a></li>
                            <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                        </ul>
                    </li>
                    
                    <!-- Theme Switcher -->
                    <li class="nav-item">
                        <a class="nav-link theme-toggle" href="{{ route('theme.switch', session('theme', 'claro') === 'claro' ? 'oscuro' : 'claro') }}" title="{{ session('theme', 'claro') === 'claro' ? 'Cambiar a tema oscuro' : 'Cambiar a tema claro' }}">
                            <i class="fas fa-{{ session('theme', 'claro') === 'claro' ? 'moon' : 'sun' }} me-1"></i>
                            <span class="d-none d-md-inline">{{ session('theme', 'claro') === 'claro' ? 'Oscuro' : 'Claro' }}</span>
                        </a>
                    </li>

                    @auth
                        <!-- Notificaciones -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell me-1"></i>
                                @php
                                    $unreadNotifications = auth()->user()->notificaciones()->where('leida', false)->count();
                                @endphp
                                @if($unreadNotifications > 0)
                                    <span class="notification-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 300px;">
                                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                                @forelse(auth()->user()->notificaciones()->latest()->take(5)->get() as $notification)
                                    <li>
                                        <div class="notification-item {{ !$notification->leida ? 'unread' : '' }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $notification->titulo }}</h6>
                                                    <p class="mb-1 small">{{ $notification->mensaje }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if(!$notification->leida)
                                                    <button class="btn btn-sm btn-outline-primary" onclick="markAsRead({{ $notification->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li><div class="dropdown-item text-muted">No hay notificaciones</div></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                            </ul>
                        </li>
                    @endauth
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login.user') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                {{ __('messages.login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                {{ __('messages.register') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->tipo === 'cliente')
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Mi Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.favorites') }}">
                                        <i class="fas fa-heart me-2"></i>Mis Favoritos
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.reservations') }}">
                                        <i class="fas fa-clock me-2"></i>Mis Reservas
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.loans') }}">
                                        <i class="fas fa-book me-2"></i>Mis Préstamos
                                    </a></li>
                                @elseif(auth()->user()->tipo === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Panel Admin
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 90px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 90px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>Retrolector</h5>
                    <p>Tu biblioteca digital moderna con acceso a miles de libros. Descubre, presta y disfruta de la lectura.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Inicio</a></li>
                        <li><a href="{{ route('books.catalog') }}">Catálogo</a></li>
                        <li><a href="#">Acerca de</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Servicios</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Préstamos</a></li>
                        <li><a href="#">Reservas</a></li>
                        <li><a href="#">Favoritos</a></li>
                        <li><a href="#">Notificaciones</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope me-2"></i>info@retrolector.com</p>
                    <p><i class="fas fa-phone me-2"></i>+1 234 567 890</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Biblioteca Digital</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Retrolector. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Mark notification as read
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

        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in-up');
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 