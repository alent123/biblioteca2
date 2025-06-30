<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Ficción', 'color' => '#3B82F6', 'icono' => 'fas fa-book-open'],
            ['nombre' => 'No Ficción', 'color' => '#10B981', 'icono' => 'fas fa-graduation-cap'],
            ['nombre' => 'Ciencia Ficción', 'color' => '#8B5CF6', 'icono' => 'fas fa-rocket'],
            ['nombre' => 'Misterio', 'color' => '#EF4444', 'icono' => 'fas fa-search'],
            ['nombre' => 'Romance', 'color' => '#EC4899', 'icono' => 'fas fa-heart'],
            ['nombre' => 'Historia', 'color' => '#F59E0B', 'icono' => 'fas fa-landmark'],
            ['nombre' => 'Ciencia', 'color' => '#06B6D4', 'icono' => 'fas fa-flask'],
            ['nombre' => 'Tecnología', 'color' => '#6366F1', 'icono' => 'fas fa-laptop'],
            ['nombre' => 'Poesía', 'color' => '#F97316', 'icono' => 'fas fa-feather'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
