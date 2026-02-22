<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Tema;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $q = Tema::query()->with('modulo');

        if ($request->filled('moduloId')) {
            $q->where('moduloId', $request->moduloId);
        }

        if ($request->filled('q')) {
            $q->where('nombre', 'like', '%' . $request->q . '%');
        }

        return response()->json([
            'ok' => true,
            'data' => $q->orderBy('orden')->orderByDesc('id')->paginate(15),
        ]);
    }

    public function show($id)
    {
        $topic = Tema::with('modulo')->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $topic,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'moduloId' => ['required', 'integer', 'exists:' . (new Modulo())->getTable() . ',id'],
            'duracion' => ['sometimes', 'integer', 'min:0'],
            'orden' => ['sometimes', 'integer', 'min:0'],
            'videoUrl' => ['nullable', 'string', 'max:255'],
        ]);

        $topic = Tema::create($data);

        return response()->json([
            'ok' => true,
            'data' => $topic->load('modulo'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $topic = Tema::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'moduloId' => ['sometimes', 'integer', 'exists:' . (new Modulo())->getTable() . ',id'],
            'duracion' => ['sometimes', 'integer', 'min:0'],
            'orden' => ['sometimes', 'integer', 'min:0'],
            'videoUrl' => ['nullable', 'string', 'max:255'],
        ]);

        $topic->update($data);

        return response()->json([
            'ok' => true,
            'data' => $topic->load('modulo'),
        ]);
    }

    public function destroy($id)
    {
        $topic = Tema::findOrFail($id);
        $topic->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Tema eliminado',
        ]);
    }
}
