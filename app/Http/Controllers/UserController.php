<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Libro;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $usuario = Auth::user();
        return view('user.dashboard', compact('usuario'));
    }

    public function profile()
    {
        $usuario = Auth::user();
        return view('user.profile', compact('usuario'));
    }

    public function editProfile()
    {
        $usuario = Auth::user();
        return view('user.edit-profile', compact('usuario'));
    }

    public function updateProfile(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'idioma_preferencia' => 'required|in:es,en',
            'tema_preferencia' => 'required|in:claro,oscuro',
        ]);

        $usuario->update($request->only([
            'nombre', 'apellido', 'email', 'telefono', 'direccion',
            'idioma_preferencia', 'tema_preferencia'
        ]));

        return redirect()->route('user.profile')->with('success', 'Perfil actualizado exitosamente.');
    }

    public function toggleFavorite(Libro $libro)
    {
        $usuario = Auth::user();
        
        $favorito = Favorito::where('usuario_id', $usuario->id)
                           ->where('libro_id', $libro->id)
                           ->first();

        if ($favorito) {
            $favorito->delete();
            $isFavorite = false;
        } else {
            Favorito::create([
                'usuario_id' => $usuario->id,
                'libro_id' => $libro->id,
            ]);
            $isFavorite = true;
        }

        // Si es una petición AJAX, devolver JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'isFavorite' => $isFavorite,
                'message' => $isFavorite ? 'Libro agregado a favoritos' : 'Libro removido de favoritos'
            ]);
        }

        return back()->with('success', $isFavorite ? 'Libro agregado a favoritos.' : 'Libro removido de favoritos.');
    }

    public function favorites()
    {
        $usuario = Auth::user();
        $favoritos = $usuario->favoritos()->with('libro.autor', 'libro.categoria')->get();
        
        return view('user.favorites', compact('favoritos'));
    }

    public function reservations()
    {
        $usuario = Auth::user();
        $reservas = $usuario->reservas()->with('libro.autor', 'libro.categoria')->get();
        
        return view('user.reservations', compact('reservas'));
    }
}
