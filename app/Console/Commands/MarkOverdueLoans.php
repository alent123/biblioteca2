<?php

namespace App\Console\Commands;

use App\Models\Prestamo;
use App\Models\Notificacion;
use Illuminate\Console\Command;

class MarkOverdueLoans extends Command
{
    protected $signature = 'loans:mark-overdue';
    protected $description = 'Marcar préstamos vencidos y enviar notificaciones';

    public function handle()
    {
        $this->info('Verificando préstamos vencidos...');

        $vencidos = Prestamo::where('estado', 'prestado')
            ->where('fecha_devolucion_esperada', '<', now())
            ->with(['usuario', 'libro'])
            ->get();

        $count = 0;
        foreach ($vencidos as $prestamo) {
            $prestamo->update(['estado' => 'vencido']);

            // Crear notificación
            Notificacion::create([
                'usuario_id' => $prestamo->usuario_id,
                'titulo' => 'Préstamo vencido',
                'mensaje' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha vencido. Por favor, devuélvelo lo antes posible.",
                'tipo' => 'warning',
            ]);

            $count++;
        }

        $this->info("Se marcaron {$count} préstamos como vencidos.");
        $this->info('Notificaciones enviadas a los usuarios.');

        return 0;
    }
} 