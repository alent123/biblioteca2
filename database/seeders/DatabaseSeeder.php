<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Libro;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Crear cuenta de admin predeterminada
        Usuario::create([
            'nombre' => 'Admin',
            'apellido' => 'Principal',
            'email' => 'admin@retrolector.com',
            'password' => bcrypt('admin123'),
            'tipo' => 'admin',
            'telefono' => '+1 (555) 123-4567',
            'direccion' => 'Av. Biblioteca 123, Ciudad',
            'idioma_preferencia' => 'es',
            'tema_preferencia' => 'claro',
            'estado' => 'activo',
        ]);

        // Crear cuenta de cliente de ejemplo
        Usuario::create([
            'nombre' => 'Cliente',
            'apellido' => 'Ejemplo',
            'email' => 'cliente@retrolector.com',
            'password' => bcrypt('cliente123'),
            'tipo' => 'cliente',
            'telefono' => '+1 (555) 987-6543',
            'direccion' => 'Calle Usuario 456, Ciudad',
            'idioma_preferencia' => 'es',
            'tema_preferencia' => 'claro',
            'estado' => 'activo',
        ]);

        // Crear 8 usuarios adicionales
        Usuario::factory(8)->create();

        // Crear categorías
        $this->call(CategoriaSeeder::class);
        
        // Crear autores
        $this->call(AutorSeeder::class);
        
        // Crear libros
        $this->call(LibroSeeder::class);
    }
}
