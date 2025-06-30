<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Autor;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autores = [
            [
                'nombre' => 'Gabriel',
                'apellido' => 'García Márquez',
                'biografia' => 'Escritor colombiano, premio Nobel de Literatura en 1982.',
                'nacionalidad' => 'Colombiana',
                'fecha_nacimiento' => '1927-03-06',
            ],
            [
                'nombre' => 'Jorge Luis',
                'apellido' => 'Borges',
                'biografia' => 'Escritor argentino, uno de los más importantes del siglo XX.',
                'nacionalidad' => 'Argentina',
                'fecha_nacimiento' => '1899-08-24',
            ],
            [
                'nombre' => 'Pablo',
                'apellido' => 'Neruda',
                'biografia' => 'Poeta chileno, premio Nobel de Literatura en 1971.',
                'nacionalidad' => 'Chilena',
                'fecha_nacimiento' => '1904-07-12',
            ],
            [
                'nombre' => 'Isabel',
                'apellido' => 'Allende',
                'biografia' => 'Escritora chilena, autora de "La casa de los espíritus".',
                'nacionalidad' => 'Chilena',
                'fecha_nacimiento' => '1942-08-02',
            ],
            [
                'nombre' => 'Mario',
                'apellido' => 'Vargas Llosa',
                'biografia' => 'Escritor peruano, premio Nobel de Literatura en 2010.',
                'nacionalidad' => 'Peruana',
                'fecha_nacimiento' => '1936-03-28',
            ],
            [
                'nombre' => 'Julio',
                'apellido' => 'Cortázar',
                'biografia' => 'Escritor argentino, autor de "Rayuela".',
                'nacionalidad' => 'Argentina',
                'fecha_nacimiento' => '1914-08-26',
            ],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}
