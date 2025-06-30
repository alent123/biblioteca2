<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Autor;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Mostrar catálogo de libros
     */
    public function catalog(Request $request)
    {
        $query = Libro::with(['autor', 'categoria']);

        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('sinopsis', 'like', "%{$search}%")
                  ->orWhereHas('autor', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Ordenamiento
        switch ($request->get('orden', 'titulo')) {
            case 'autor':
                $query->join('autores', 'libros.autor_id', '=', 'autores.id')
                      ->orderBy('autores.nombre')
                      ->orderBy('autores.apellido');
                break;
            case 'fecha':
                $query->orderBy('anio_publicacion', 'desc');
                break;
            case 'popularidad':
                $query->withCount('prestamos')
                      ->orderBy('prestamos_count', 'desc');
                break;
            default:
                $query->orderBy('titulo');
                break;
        }

        $libros = $query->paginate(12);

        // Estadísticas
        $totalLibros = Libro::count();
        $disponibles = Libro::where('estado', 'disponible')->count();
        $prestados = Libro::where('estado', 'prestado')->count();
        $reservados = Libro::where('estado', 'reservado')->count();

        // Categorías para el filtro
        $categorias = Categoria::where('estado', 'activa')->get();

        return view('books.catalog', compact('libros', 'categorias', 'totalLibros', 'disponibles', 'prestados', 'reservados'));
    }

    /**
     * Mostrar detalles de un libro
     */
    public function show(Libro $libro)
    {
        $libro->load(['autor', 'categoria']);
        
        // Agregar libro a la lista de vistos recientemente
        $this->agregarLibroVisto($libro->id);
        
        // Verificar si el usuario tiene este libro en favoritos
        $isFavorite = false;
        if (auth()->check()) {
            $isFavorite = auth()->user()->favoritos()->where('libro_id', $libro->id)->exists();
        }

        // Libros relacionados
        $relatedBooks = Libro::where('categoria_id', $libro->categoria_id)
            ->where('id', '!=', $libro->id)
            ->with(['autor', 'categoria'])
            ->limit(4)
            ->get();

        return view('books.show', compact('libro', 'isFavorite', 'relatedBooks'));
    }

    /**
     * Agregar libro a la lista de vistos recientemente
     */
    private function agregarLibroVisto($libroId)
    {
        $librosVistos = session('libros_vistos', []);
        
        // Remover si ya existe
        $librosVistos = array_filter($librosVistos, function($id) use ($libroId) {
            return $id != $libroId;
        });
        
        // Agregar al inicio
        array_unshift($librosVistos, $libroId);
        
        // Mantener solo los últimos 10
        $librosVistos = array_slice($librosVistos, 0, 10);
        
        session(['libros_vistos' => $librosVistos]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Libro::with(['autor', 'categoria']);

        // Filtros para administrador
        if (request()->filled('search')) {
            $search = request()->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('autor', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%");
                  });
            });
        }

        if (request()->filled('categoria')) {
            $query->where('categoria_id', request()->categoria);
        }

        if (request()->filled('estado')) {
            $query->where('estado', request()->estado);
        }

        $sort = request()->get('sort', 'created_at');
        $direction = request()->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $libros = $query->paginate(15);
        $categorias = Categoria::activas()->get();

        return view('admin.libros.index', compact('libros', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $autores = Autor::activos()->get();
        $categorias = Categoria::activas()->get();
        return view('admin.libros.create', compact('autores', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:libros',
            'sinopsis' => 'nullable|string',
            'anio_publicacion' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'editorial' => 'nullable|string|max:255',
            'paginas' => 'nullable|integer|min:1',
            'idioma' => 'nullable|string|max:50',
            'autor_id' => 'required|exists:autores,id',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['estado'] = 'disponible';

        if ($request->hasFile('imagen_portada')) {
            $data['imagen_portada'] = $request->file('imagen_portada')->store('libros', 'public');
        }

        Libro::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro agregado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function showAdmin(Libro $libro)
    {
        $libro->load(['autor', 'categoria', 'prestamos', 'reservas']);
        return view('admin.libros.show', compact('libro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        $autores = Autor::activos()->get();
        $categorias = Categoria::activas()->get();
        return view('admin.libros.edit', compact('libro', 'autores', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Libro $libro)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn,' . $libro->id,
            'sinopsis' => 'nullable|string',
            'anio_publicacion' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'editorial' => 'nullable|string|max:255',
            'paginas' => 'nullable|integer|min:1',
            'idioma' => 'nullable|string|max:50',
            'autor_id' => 'required|exists:autores,id',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen_portada')) {
            // Eliminar imagen anterior si existe
            if ($libro->imagen_portada) {
                \Storage::disk('public')->delete($libro->imagen_portada);
            }
            $data['imagen_portada'] = $request->file('imagen_portada')->store('libros', 'public');
        }

        $libro->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        // Verificar si el libro tiene préstamos o reservas activas
        if ($libro->prestamos()->where('estado', 'prestado')->exists()) {
            return back()->with('error', 'No se puede eliminar un libro con préstamos activos.');
        }

        if ($libro->reservas()->where('estado', 'pendiente')->exists()) {
            return back()->with('error', 'No se puede eliminar un libro con reservas pendientes.');
        }

        // Eliminar imagen si existe
        if ($libro->imagen_portada) {
            \Storage::disk('public')->delete($libro->imagen_portada);
        }

        $libro->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro eliminado exitosamente');
    }

    /**
     * Upload image for a book
     */
    public function uploadImage(Request $request, Libro $libro)
    {
        $request->validate([
            'imagen_portada' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen_portada')) {
            // Eliminar imagen anterior si existe
            if ($libro->imagen_portada) {
                \Storage::disk('public')->delete($libro->imagen_portada);
            }

            $path = $request->file('imagen_portada')->store('libros', 'public');
            $libro->update(['imagen_portada' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen subida exitosamente',
                'path' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al subir la imagen'
        ], 400);
    }
}
