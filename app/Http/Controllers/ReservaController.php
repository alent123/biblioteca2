<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with(['usuario', 'libro.autor', 'libro.categoria'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Libro $libro)
    {
        // Verificar que el usuario sea cliente
        if (Auth::user()->tipo !== 'cliente') {
            return back()->with('error', 'Solo los clientes pueden hacer reservas.');
        }

        // Verificar que el libro no esté disponible (solo se pueden reservar libros prestados)
        if ($libro->estado === 'disponible') {
            return back()->with('error', 'Este libro está disponible. Puedes prestarlo directamente.');
        }

        // Verificar límite de reservas (máximo 2 reservas activas)
        $reservasActivas = Auth::user()->reservas()->where('estado', 'pendiente')->count();
        if ($reservasActivas >= 2) {
            return back()->with('error', 'Has alcanzado el límite máximo de 2 reservas activas.');
        }

        // Verificar si ya tiene una reserva para este libro
        $reservaExistente = Auth::user()->reservas()
            ->where('libro_id', $libro->id)
            ->where('estado', 'pendiente')
            ->exists();
        
        if ($reservaExistente) {
            return back()->with('error', 'Ya tienes una reserva activa para este libro.');
        }

        // Crear la reserva
        $reserva = Reserva::create([
            'usuario_id' => Auth::id(),
            'libro_id' => $libro->id,
            'fecha_reserva' => now(),
            'fecha_expiracion' => now()->addDays(7),
            'estado' => 'pendiente',
            'notificado' => false,
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Reserva realizada exitosamente. La reserva expira el: ' . $reserva->fecha_expiracion->format('d/m/Y'));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,cancelada,completada,expirada',
            'notificado' => 'boolean',
        ]);

        $reserva->update($request->all());

        return back()->with('success', 'Reserva actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        // Verificar que la reserva pertenezca al usuario o sea admin
        if (Auth::user()->tipo !== 'admin' && $reserva->usuario_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para cancelar esta reserva.');
        }

        $reserva->delete();

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }
}
