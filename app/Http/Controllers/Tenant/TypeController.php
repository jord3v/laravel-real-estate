<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TypeController extends Controller
{
    /**
     * Lista todos os tipos de propriedade.
     */
    public function index()
    {
        try {
            $types = Type::ordered()->get();
            return response()->json($types);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar tipos.',
            ], 500);
        }
    }

    /**
     * Cria um novo tipo de propriedade.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateTypeData($request);

            $type = Type::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'active' => $validated['active'] ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo criado com sucesso!',
                'data' => $type,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar tipo.',
            ], 500);
        }
    }

    /**
     * Exibe um tipo específico.
     */
    public function show(Type $type)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $type,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar tipo.',
            ], 500);
        }
    }

    /**
     * Atualiza um tipo existente.
     */
    public function update(Request $request, Type $type)
    {
        try {
            $validated = $this->validateTypeData($request, $type->id);

            $type->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? $type->description,
                'active' => $validated['active'] ?? $type->active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo atualizado com sucesso!',
                'data' => $type->fresh(),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar tipo.',
            ], 500);
        }
    }

    /**
     * Remove um tipo de propriedade.
     */
    public function destroy(Type $type)
    {
        try {
            if ($this->hasAssociatedProperties($type)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir este tipo pois existem propriedades associadas a ele.',
                ], 422);
            }

            $type->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tipo excluído com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir tipo.',
            ], 500);
        }
    }

    /**
     * Valida os dados do tipo.
     */
    private function validateTypeData(Request $request, ?int $excludeId = null): array
    {
        $uniqueRule = 'unique:types,name';
        if ($excludeId) {
            $uniqueRule .= ',' . $excludeId;
        }

        return $request->validate([
            'name' => "required|string|max:255|{$uniqueRule}",
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);
    }

    /**
     * Verifica se o tipo possui propriedades associadas.
     */
    private function hasAssociatedProperties(Type $type): bool
    {
        return $type->properties()->exists();
    }
}
