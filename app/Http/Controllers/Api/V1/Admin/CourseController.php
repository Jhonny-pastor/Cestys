<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $q = Curso::query()->with('categoria');

        if ($request->filled('categoriaId')) {
            $q->where('categoriaId', $request->categoriaId);
        }

        if ($request->filled('estado')) {
            $q->where('estado', $request->estado);
        }

        if ($request->filled('q')) {
            $q->where('nombre', 'like', '%' . $request->q . '%');
        }

        return response()->json([
            'ok' => true,
            'data' => $q->orderByDesc('id')->paginate(15),
        ]);
    }

    public function show($id)
    {
        $course = Curso::with(['categoria', 'modulos'])->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $course,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:255', Rule::unique((new Curso())->getTable(), 'codigo')],
            'imagenPortada' => ['nullable', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['sometimes', 'numeric', 'min:0'],
            'horas' => ['sometimes', 'integer', 'min:0'],
            'valoracion' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'estado' => ['required', 'string', Rule::in(['DRAFT', 'PUBLISHED', 'ARCHIVED'])],
            'categoriaId' => ['required', 'integer', 'exists:' . (new Curso())->categoria()->getRelated()->getTable() . ',id'],
        ]);

        $course = Curso::create($data);

        return response()->json([
            'ok' => true,
            'data' => $course->load('categoria'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $course = Curso::findOrFail($id);

        $data = $request->validate([
            'codigo' => ['sometimes', 'string', 'max:255', Rule::unique((new Curso())->getTable(), 'codigo')->ignore($course->id)],
            'imagenPortada' => ['nullable', 'string', 'max:255'],
            'nombre' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['sometimes', 'numeric', 'min:0'],
            'horas' => ['sometimes', 'integer', 'min:0'],
            'valoracion' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'estado' => ['sometimes', 'string', Rule::in(['DRAFT', 'PUBLISHED', 'ARCHIVED'])],
            'categoriaId' => ['sometimes', 'integer', 'exists:' . (new Curso())->categoria()->getRelated()->getTable() . ',id'],
        ]);

        $course->update($data);

        return response()->json([
            'ok' => true,
            'data' => $course->load('categoria'),
        ]);
    }

    public function destroy($id)
    {
        $course = Curso::findOrFail($id);
        $course->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Curso eliminado',
        ]);
    }
}
