<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificado;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $q = Certificado::query();

        if ($request->filled('courseId')) {
            $q->where('cursoId', $request->courseId);
        }

        if ($request->filled('userId')) {
            $q->where('usuarioId', $request->userId);
        }

        return response()->json([
            'ok' => true,
            'data' => $q->orderByDesc('id')->paginate(15),
        ]);
    }

    public function show($id)
    {
        $cert = Certificado::findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => $cert
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:certificado,codigo'],
            'link' => ['nullable', 'string', 'max:255'],
            'fechaEmision' => ['required', 'date'],
            'usuarioId' => ['required', 'integer'],
            'cursoId' => ['required', 'integer'],
            'estado' => ['required'], // depende tu tipo: string o boolean
            'notasAdicionales' => ['nullable', 'string'],
        ]);

        $cert = Certificado::create($data);

        return response()->json([
            'ok' => true,
            'data' => $cert
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cert = Certificado::findOrFail($id);

        $data = $request->validate([
            'link' => ['nullable', 'string', 'max:255'],
            'estado' => ['sometimes'],
            'notasAdicionales' => ['nullable', 'string'],
        ]);

        $cert->update($data);

        return response()->json([
            'ok' => true,
            'data' => $cert
        ]);
    }

    public function destroy($id)
    {
        $cert = Certificado::findOrFail($id);
        $cert->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Certificado eliminado'
        ]);
    }
}