<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'icono',
        'estado',
    ];

    // Relaciones
    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class);
    }

    // Métodos de ayuda
    public function getLibrosActivosAttribute()
    {
        return $this->libros()->where('estado', '!=', 'mantenimiento')->get();
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }
}
