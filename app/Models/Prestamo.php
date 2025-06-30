<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    protected $table = 'prestamos';

    protected $fillable = [
        'usuario_id',
        'libro_id',
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'fecha_devolucion_real',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_devolucion_esperada' => 'datetime',
        'fecha_devolucion_real' => 'datetime',
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class);
    }

    // Métodos de ayuda
    public function isVencido(): bool
    {
        return $this->fecha_devolucion_esperada < now() && $this->estado === 'prestado';
    }

    public function getDiasRestantesAttribute(): int
    {
        if ($this->estado !== 'prestado') {
            return 0;
        }
        return max(0, now()->diffInDays($this->fecha_devolucion_esperada, false));
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'prestado');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'prestado')
                    ->where('fecha_devolucion_esperada', '<', now());
    }
}
