<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    /**
     * Agregar o quitar un libro de favoritos
     */
    public function toggle(Request $request, Libro $libro)
    {
        $usuario = Auth::user();
        
        // Verificar si ya existe el favorito
        $favorito = Favorito::where('usuario_id', $usuario->id)
                           ->where('libro_id', $libro->id)
                           ->first();

        if ($favorito) {
            // Si existe, lo eliminamos
            $favorito->delete();
            $mensaje = 'Libro removido de favoritos';
            $isFavorite = false;
        } else {
            // Si no existe, lo creamos
            Favorito::create([
                'usuario_id' => $usuario->id,
                'libro_id' => $libro->id
            ]);
            $mensaje = 'Libro agregado a favoritos';
            $isFavorite = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'isFavorite' => $isFavorite
            ]);
        }

        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Mostrar los favoritos del usuario
     */
    public function index()
    {
        $usuario = Auth::user();
        $favoritos = $usuario->favoritos()
                           ->with(['libro.autor', 'libro.categoria'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(12);

        return view('user.favorites', compact('favoritos'));
    }

    /**
     * Eliminar un favorito
     */
    public function destroy(Favorito $favorito)
    {
        // Verificar que el favorito pertenece al usuario autenticado
        if ($favorito->usuario_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción');
        }

        $favorito->delete();
        return redirect()->back()->with('success', 'Libro removido de favoritos');
    }

    /**
     * Verificar si un libro está en favoritos (para AJAX)
     */
    public function check(Libro $libro)
    {
        $usuario = Auth::user();
        $isFavorite = $usuario->favoritos()->where('libro_id', $libro->id)->exists();

        return response()->json([
            'isFavorite' => $isFavorite
        ]);
    }
} 