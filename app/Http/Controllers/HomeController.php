<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Libros más populares (por número de préstamos)
        $librosPopulares = Libro::with(['autor', 'categoria'])
            ->withCount('prestamos')
            ->orderBy('prestamos_count', 'desc')
            ->limit(6)
            ->get();

        // Libros más recientes
        $librosRecientes = Libro::with(['autor', 'categoria'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Estadísticas de la biblioteca
        $estadisticas = [
            'total_libros' => Libro::count(),
            'libros_disponibles' => Libro::where('estado', 'disponible')->count(),
            'total_usuarios' => Usuario::count(),
            'prestamos_activos' => Prestamo::where('estado', 'prestado')->count(),
            'reservas_pendientes' => Reserva::where('estado', 'pendiente')->count(),
        ];

        // Noticias sobre lectura (contenido estático pero atractivo)
        $noticias = [
            [
                'titulo' => 'Los beneficios de la lectura digital',
                'resumen' => 'Descubre cómo la tecnología está transformando la forma en que leemos y aprendemos.',
                'fecha' => now()->subDays(2),
                'categoria' => 'Tecnología',
                'icono' => 'fas fa-tablet-alt'
            ],
            [
                'titulo' => 'Libros más leídos este mes',
                'resumen' => 'Conoce los títulos que están causando sensación en nuestra comunidad de lectores.',
                'fecha' => now()->subDays(5),
                'categoria' => 'Tendencias',
                'icono' => 'fas fa-chart-line'
            ],
            [
                'titulo' => 'Cómo crear el hábito de la lectura',
                'resumen' => 'Tips y consejos para desarrollar una rutina de lectura efectiva y disfrutable.',
                'fecha' => now()->subDays(8),
                'categoria' => 'Consejos',
                'icono' => 'fas fa-lightbulb'
            ]
        ];

        // Citas inspiradoras sobre lectura
        $citas = [
            [
                'texto' => 'Un libro es un regalo que puedes abrir una y otra vez.',
                'autor' => 'Garrison Keillor'
            ],
            [
                'texto' => 'La lectura es a la mente lo que el ejercicio es al cuerpo.',
                'autor' => 'Joseph Addison'
            ],
            [
                'texto' => 'Los libros son las abejas que llevan el polen de una inteligencia a otra.',
                'autor' => 'James Russell Lowell'
            ]
        ];

        // Eventos próximos (simulados)
        $eventos = [
            [
                'titulo' => 'Club de Lectura Virtual',
                'fecha' => now()->addDays(3),
                'hora' => '19:00',
                'descripcion' => 'Discusión sobre "El Principito" de Antoine de Saint-Exupéry'
            ],
            [
                'titulo' => 'Taller de Escritura Creativa',
                'fecha' => now()->addDays(7),
                'hora' => '16:00',
                'descripcion' => 'Aprende técnicas básicas de narrativa y creación de personajes'
            ],
            [
                'titulo' => 'Presentación de Nuevos Libros',
                'fecha' => now()->addDays(10),
                'hora' => '18:30',
                'descripcion' => 'Conoce las últimas adquisiciones de nuestra biblioteca'
            ]
        ];

        return view('welcome', compact(
            'librosPopulares',
            'librosRecientes', 
            'estadisticas',
            'noticias',
            'citas',
            'eventos'
        ));
    }
} 