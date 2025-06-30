<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Libro;
use App\Models\Autor;
use App\Models\Categoria;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libros = [
            [
                'titulo' => 'Cien años de soledad',
                'isbn' => '9788497592208',
                'sinopsis' => 'La historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',
                'anio_publicacion' => 1967,
                'editorial' => 'Editorial Sudamericana',
                'paginas' => 471,
                'idioma' => 'Español',
                'stock' => 5,
                'ubicacion' => 'Estante A1',
                'estado' => 'disponible',
                'autor_nombre' => 'Gabriel García Márquez',
                'categoria_nombre' => 'Ficción',
            ],
            [
                'titulo' => 'El Aleph',
                'isbn' => '9788497592215',
                'sinopsis' => 'Colección de cuentos que exploran temas como el infinito, el tiempo y la realidad.',
                'anio_publicacion' => 1949,
                'editorial' => 'Losada',
                'paginas' => 223,
                'idioma' => 'Español',
                'stock' => 3,
                'ubicacion' => 'Estante B2',
                'estado' => 'disponible',
                'autor_nombre' => 'Jorge Luis Borges',
                'categoria_nombre' => 'Ficción',
            ],
            [
                'titulo' => 'Veinte poemas de amor',
                'isbn' => '9788497592222',
                'sinopsis' => 'Colección de poemas que exploran el amor, la pasión y la melancolía.',
                'anio_publicacion' => 1924,
                'editorial' => 'Nascimento',
                'paginas' => 95,
                'idioma' => 'Español',
                'stock' => 8,
                'ubicacion' => 'Estante C3',
                'estado' => 'disponible',
                'autor_nombre' => 'Pablo Neruda',
                'categoria_nombre' => 'Poesía',
            ],
            [
                'titulo' => 'La casa de los espíritus',
                'isbn' => '9788497592239',
                'sinopsis' => 'Historia de la familia Trueba a lo largo de cuatro generaciones en Chile.',
                'anio_publicacion' => 1982,
                'editorial' => 'Plaza & Janés',
                'paginas' => 433,
                'idioma' => 'Español',
                'stock' => 4,
                'ubicacion' => 'Estante A4',
                'estado' => 'disponible',
                'autor_nombre' => 'Isabel Allende',
                'categoria_nombre' => 'Ficción',
            ],
            [
                'titulo' => 'La ciudad y los perros',
                'isbn' => '9788497592246',
                'sinopsis' => 'Novela que narra la vida de los cadetes en el Colegio Militar Leoncio Prado.',
                'anio_publicacion' => 1963,
                'editorial' => 'Seix Barral',
                'paginas' => 408,
                'idioma' => 'Español',
                'stock' => 6,
                'ubicacion' => 'Estante B5',
                'estado' => 'disponible',
                'autor_nombre' => 'Mario Vargas Llosa',
                'categoria_nombre' => 'Ficción',
            ],
            [
                'titulo' => 'Rayuela',
                'isbn' => '9788497592253',
                'sinopsis' => 'Novela experimental que puede leerse en múltiples órdenes.',
                'anio_publicacion' => 1963,
                'editorial' => 'Sudamericana',
                'paginas' => 635,
                'idioma' => 'Español',
                'stock' => 2,
                'ubicacion' => 'Estante C6',
                'estado' => 'disponible',
                'autor_nombre' => 'Julio Cortázar',
                'categoria_nombre' => 'Ficción',
            ],
        ];

        foreach ($libros as $libro) {
            $autor = Autor::where('nombre', explode(' ', $libro['autor_nombre'])[0])
                         ->where('apellido', explode(' ', $libro['autor_nombre'])[1])
                         ->first();
            
            $categoria = Categoria::where('nombre', $libro['categoria_nombre'])->first();
            
            if ($autor && $categoria) {
                Libro::create([
                    'titulo' => $libro['titulo'],
                    'isbn' => $libro['isbn'],
                    'sinopsis' => $libro['sinopsis'],
                    'anio_publicacion' => $libro['anio_publicacion'],
                    'editorial' => $libro['editorial'],
                    'paginas' => $libro['paginas'],
                    'idioma' => $libro['idioma'],
                    'stock' => $libro['stock'],
                    'ubicacion' => $libro['ubicacion'],
                    'estado' => $libro['estado'],
                    'autor_id' => $autor->id,
                    'categoria_id' => $categoria->id,
                ]);
            }
        }
    }
}
