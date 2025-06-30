<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id',
        'libro_id',
        'fecha_reserva',
        'fecha_expiracion',
        'estado',
        'notificado',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_expiracion' => 'datetime',
        'notificado' => 'boolean',
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
    public function isExpirada(): bool
    {
        return $this->fecha_expiracion < now() && $this->estado === 'pendiente';
    }

    public function getDiasRestantesAttribute(): int
    {
        if ($this->estado !== 'pendiente') {
            return 0;
        }
        return max(0, now()->diffInDays($this->fecha_expiracion, false));
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeExpiradas($query)
    {
        return $query->where('estado', 'pendiente')
                    ->where('fecha_expiracion', '<', now());
    }
}
