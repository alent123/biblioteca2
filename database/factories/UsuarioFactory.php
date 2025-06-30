<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'tipo' => $this->faker->randomElement(['admin', 'cliente']),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'idioma_preferencia' => $this->faker->randomElement(['es', 'en']),
            'tema_preferencia' => $this->faker->randomElement(['claro', 'oscuro']),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
        ];
    }
} 