<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $q = Modulo::query()->with('curso');

        if ($request->filled('cursoId')) {
            $q->where('cursoId', $request->cursoId);
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
        $module = Modulo::with(['curso', 'temas'])->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $module,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'cursoId' => ['required', 'integer', 'exists:' . (new Curso())->getTable() . ',id'],
        ]);

        $module = Modulo::create($data);

        return response()->json([
            'ok' => true,
            'data' => $module->load('curso'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $module = Modulo::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'cursoId' => ['sometimes', 'integer', 'exists:' . (new Curso())->getTable() . ',id'],
        ]);

        $module->update($data);

        return response()->json([
            'ok' => true,
            'data' => $module->load('curso'),
        ]);
    }

    public function destroy($id)
    {
        $module = Modulo::findOrFail($id);
        $module->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Modulo eliminado',
        ]);
    }
}
