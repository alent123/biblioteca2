<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prestamos = auth()->user()->prestamos()
            ->with(['libro.autor', 'libro.categoria'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.loans', compact('prestamos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_devolucion_esperada' => 'required|date|after:today',
        ]);

        $libro = Libro::findOrFail($request->libro_id);
        $usuario = Usuario::findOrFail($request->usuario_id);

        // Verificar disponibilidad del libro
        if ($libro->estado !== 'disponible') {
            return back()->with('error', 'El libro no está disponible para préstamo.');
        }

        // Verificar límite de préstamos del usuario
        $prestamosActivos = $usuario->prestamos()
            ->whereIn('estado', ['prestado', 'vencido'])
            ->count();

        if ($prestamosActivos >= 5) {
            return back()->with('error', 'El usuario ha alcanzado el límite máximo de préstamos.');
        }

        try {
            DB::beginTransaction();

            $prestamo = Prestamo::create([
                'usuario_id' => $usuario->id,
                'libro_id' => $libro->id,
                'fecha_prestamo' => now(),
                'fecha_devolucion_esperada' => $request->fecha_devolucion_esperada,
                'estado' => 'prestado',
            ]);

            $libro->update(['estado' => 'prestado']);

            // Notificar al usuario
            Notificacion::create([
                'usuario_id' => $usuario->id,
                'titulo' => 'Nuevo préstamo',
                'mensaje' => "Se ha registrado un préstamo del libro '{$libro->titulo}'. Fecha de devolución: " . $prestamo->fecha_devolucion_esperada->format('d/m/Y'),
                'tipo' => 'info',
            ]);

            DB::commit();

            return back()->with('success', 'Préstamo creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al crear el préstamo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Libro $libro)
    {
        // Verificar que el usuario sea cliente
        if (Auth::user()->tipo !== 'cliente') {
            return back()->with('error', 'Solo los clientes pueden solicitar préstamos.');
        }

        // Verificar que el libro esté disponible
        if ($libro->estado !== 'disponible') {
            return back()->with('error', 'El libro no está disponible para préstamo.');
        }

        // Verificar límite de préstamos (máximo 3 libros prestados)
        $prestamosActivos = Auth::user()->prestamos()->where('estado', 'prestado')->count();
        if ($prestamosActivos >= 3) {
            return back()->with('error', 'Has alcanzado el límite máximo de 3 libros prestados.');
        }

        // Verificar si ya tiene este libro prestado
        $prestamoExistente = Auth::user()->prestamos()
            ->where('libro_id', $libro->id)
            ->where('estado', 'prestado')
            ->exists();
        
        if ($prestamoExistente) {
            return back()->with('error', 'Ya tienes este libro prestado.');
        }

        // Crear el préstamo
        $prestamo = Prestamo::create([
            'usuario_id' => Auth::id(),
            'libro_id' => $libro->id,
            'fecha_prestamo' => now(),
            'fecha_devolucion_esperada' => now()->addDays(14),
            'estado' => 'prestado',
        ]);

        // Actualizar estado del libro
        $libro->update(['estado' => 'prestado']);

        return redirect()->route('user.dashboard')
            ->with('success', 'Préstamo realizado exitosamente. Fecha de devolución: ' . $prestamo->fecha_devolucion_esperada->format('d/m/Y'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Renovar un préstamo
     */
    public function renew(Prestamo $prestamo)
    {
        // Verificar que el préstamo pertenece al usuario autenticado
        if ($prestamo->usuario_id !== auth()->id()) {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        if ($prestamo->estado !== 'prestado') {
            return back()->with('error', 'Solo se pueden renovar préstamos activos.');
        }

        // Verificar si no se ha renovado antes (máximo 1 renovación)
        if ($prestamo->fecha_devolucion_esperada->diffInDays($prestamo->fecha_prestamo) > 14) {
            return back()->with('error', 'Este préstamo ya ha sido renovado una vez.');
        }

        try {
            DB::beginTransaction();

            // Renovar por 7 días más
            $prestamo->update([
                'fecha_devolucion_esperada' => $prestamo->fecha_devolucion_esperada->addDays(7),
            ]);

            // Crear notificación
            Notificacion::create([
                'usuario_id' => auth()->id(),
                'titulo' => 'Préstamo renovado',
                'mensaje' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha sido renovado. Nueva fecha de devolución: " . $prestamo->fecha_devolucion_esperada->format('d/m/Y'),
                'tipo' => 'info',
            ]);

            DB::commit();

            return back()->with('success', 'Préstamo renovado exitosamente. Nueva fecha de devolución: ' . $prestamo->fecha_devolucion_esperada->format('d/m/Y'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al renovar el préstamo. Inténtalo de nuevo.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'estado' => 'required|in:prestado,devuelto,vencido,perdido',
            'fecha_devolucion_esperada' => 'nullable|date|after:today',
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $estadoAnterior = $prestamo->estado;
            
            $prestamo->update([
                'estado' => $request->estado,
                'fecha_devolucion_esperada' => $request->fecha_devolucion_esperada ?? $prestamo->fecha_devolucion_esperada,
                'observaciones' => $request->observaciones,
                'fecha_devolucion_real' => $request->estado === 'devuelto' ? now() : null,
            ]);

            // Actualizar estado del libro
            if ($request->estado === 'devuelto' && $estadoAnterior !== 'devuelto') {
                $prestamo->libro->update(['estado' => 'disponible']);
            } elseif ($estadoAnterior === 'devuelto' && $request->estado !== 'devuelto') {
                $prestamo->libro->update(['estado' => 'prestado']);
            }

            // Notificar al usuario si hay cambios importantes
            if ($estadoAnterior !== $request->estado) {
                $mensaje = match($request->estado) {
                    'devuelto' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha sido marcado como devuelto.",
                    'vencido' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha vencido. Por favor, devuélvelo lo antes posible.",
                    'perdido' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha sido marcado como perdido.",
                    default => "El estado de tu préstamo del libro '{$prestamo->libro->titulo}' ha sido actualizado."
                };

                Notificacion::create([
                    'usuario_id' => $prestamo->usuario_id,
                    'titulo' => 'Actualización de préstamo',
                    'mensaje' => $mensaje,
                    'tipo' => $request->estado === 'devuelto' ? 'success' : 'warning',
                ]);
            }

            DB::commit();

            return back()->with('success', 'Préstamo actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar el préstamo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        try {
            DB::beginTransaction();

            // Si el préstamo está activo, liberar el libro
            if ($prestamo->estado === 'prestado') {
                $prestamo->libro->update(['estado' => 'disponible']);
            }

            $prestamo->delete();

            DB::commit();

            return back()->with('success', 'Préstamo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al eliminar el préstamo.');
        }
    }

    // Método para marcar préstamos vencidos automáticamente
    public function markOverdue()
    {
        $vencidos = Prestamo::where('estado', 'prestado')
            ->where('fecha_devolucion_esperada', '<', now())
            ->get();

        foreach ($vencidos as $prestamo) {
            $prestamo->update(['estado' => 'vencido']);

            // Notificar al usuario
            Notificacion::create([
                'usuario_id' => $prestamo->usuario_id,
                'titulo' => 'Préstamo vencido',
                'mensaje' => "Tu préstamo del libro '{$prestamo->libro->titulo}' ha vencido. Por favor, devuélvelo lo antes posible.",
                'tipo' => 'warning',
            ]);
        }

        return response()->json(['vencidos' => $vencidos->count()]);
    }
}
