<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = Categoria::query();

        if ($request->filled('estado')) {
            $q->where('estado', filter_var($request->estado, FILTER_VALIDATE_BOOLEAN));
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
        $category = Categoria::findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique((new Categoria())->getTable(), 'nombre')],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $category = Categoria::create($data);

        return response()->json([
            'ok' => true,
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Categoria::findOrFail($id);

        $data = $request->validate([
            'nombre' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique((new Categoria())->getTable(), 'nombre')->ignore($category->id),
            ],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $category->update($data);

        return response()->json([
            'ok' => true,
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Categoria::findOrFail($id);
        $category->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Categoria eliminada',
        ]);
    }
}
