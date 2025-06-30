<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'tipo',
        'telefono',
        'direccion',
        'idioma_preferencia',
        'tema_preferencia',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones
    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class);
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    // Métodos de ayuda
    public function isAdmin(): bool
    {
        return $this->tipo === 'admin';
    }

    public function isCliente(): bool
    {
        return $this->tipo === 'cliente';
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function getPrestamosActivosAttribute()
    {
        return $this->prestamos()->where('estado', 'prestado')->get();
    }

    public function getReservasPendientesAttribute()
    {
        return $this->reservas()->where('estado', 'pendiente')->get();
    }
}
