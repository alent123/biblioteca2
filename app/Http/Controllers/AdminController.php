<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => Usuario::where('tipo', 'cliente')->count(),
            'total_books' => Libro::count(),
            'active_loans' => Prestamo::where('estado', 'activo')->count(),
            'pending_reservations' => Reserva::where('estado', 'pendiente')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $usuarios = Usuario::where('tipo', 'cliente')
                          ->with(['prestamos' => function($query) {
                              $query->where('estado', 'activo');
                          }])
                          ->paginate(15);

        return view('admin.users.index', compact('usuarios'));
    }

    public function showUser(Usuario $usuario)
    {
        $prestamos = $usuario->prestamos()->with('libro.autor')->orderBy('created_at', 'desc')->get();
        $reservas = $usuario->reservas()->with('libro.autor')->orderBy('created_at', 'desc')->get();

        return view('admin.users.show', compact('usuario', 'prestamos', 'reservas'));
    }

    public function editUser(Usuario $usuario)
    {
        return view('admin.users.edit', compact('usuario'));
    }

    public function updateUser(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $usuario->update($request->only([
            'nombre', 'apellido', 'email', 'telefono', 'direccion', 'estado'
        ]));

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroyUser(Usuario $usuario)
    {
        // Verificar que no tenga préstamos activos
        if ($usuario->prestamos()->where('estado', 'activo')->count() > 0) {
            return back()->with('error', 'No se puede eliminar un usuario con préstamos activos.');
        }

        $usuario->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => 'cliente',
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'idioma_preferencia' => 'es',
            'tema_preferencia' => 'claro',
            'estado' => 'activo',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }
}
