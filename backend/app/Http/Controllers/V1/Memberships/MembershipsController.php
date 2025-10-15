<?php

namespace App\Http\Controllers\V1\Memberships;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MembershipsRepository;
use Illuminate\Support\Facades\DB;

class MembershipsController extends Controller
{
    protected $membershipRepository;

    public function __construct(MembershipsRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    /**
     * GET /memberships
     * Listar todas las membresías con paginación.
     */
    public function index(Request $request)
    {
        try {
            $memberships = $this->membershipRepository->getAll($request);
            return response()->success(compact('memberships'), "Listado de membresías cargado con éxito.");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /dashboard/memberships
     * Listar todas las membresías sin paginación.
     */
    public function get()
    {
        try {
            $memberships = $this->membershipRepository->get();
            return response()->success(compact('memberships'), "Listado de membresías cargado con éxito.");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * POST /memberships
     * Crear una nueva membresía.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'label'       => 'required|string|max:255',
                'price'       => 'nullable|numeric|min:0',
                'icon'        => 'nullable|string|max:255',
                'features'    => 'nullable|array', // ✅ Se guarda en JSON
                'options'     => 'nullable|array',
                'is_current'  => 'nullable|boolean',
                'photos_limit'       => 'nullable|integer|min:0',
                'events_per_month'   => 'nullable|integer|min:0',
                'promotions_limit'   => 'nullable|integer|min:0',
                'push_notifications' => 'nullable|integer|min:0',
                'profile_included'   => 'nullable|boolean',
                'branding_support'   => 'nullable|boolean',
                'map_visibility'     => 'nullable|boolean',
                'priority_support'   => 'nullable|boolean',
                'enhanced_positioning' => 'nullable|boolean',
            ]);

            DB::beginTransaction();
            $membership = $this->membershipRepository->create($validated);
            DB::commit();

            return response()->success(compact('membership'), "Membresía creada exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /memberships/{id}
     * Mostrar detalle de una membresía.
     */
    public function show(string $id)
    {
        try {
            $membership = $this->membershipRepository->findById($id);
            if (!$membership) {
                return response()->error("Membresía no encontrada.", 404);
            }
            return response()->success(compact('membership'), "Detalle de la membresía.");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * PUT /memberships/{id}
     * Actualizar una membresía.
     */
    public function update(Request $request, string $id)
    {
        try {
            if ($request->has('options') && is_string($request->options)) {
                $request->merge([
                    'options' => json_decode($request->options, true) ?? []
                ]);
            }

            $validated = $request->validate([
                'label'       => 'sometimes|string|max:255',
                'price'       => 'nullable|numeric|min:0',
                'icon'        => 'nullable|string|max:255',
                'features'    => 'nullable|array',
                'options'     => 'nullable|array', // ✅ ya será array
                'is_current'  => 'nullable|boolean',
                'photos_limit'       => 'nullable|integer|min:0',
                'events_per_month'   => 'nullable|integer|min:0',
                'promotions_limit'   => 'nullable|integer|min:0',
                'push_notifications' => 'nullable|integer|min:0',
                'profile_included'   => 'nullable|boolean',
                'branding_support'   => 'nullable|boolean',
                'map_visibility'     => 'nullable|boolean',
                'priority_support'   => 'nullable|boolean',
                'enhanced_positioning' => 'nullable|boolean',
            ]);

            DB::beginTransaction();
            $membership = $this->membershipRepository->update($id, $validated);
            DB::commit();

            if (!$membership) {
                return response()->error("Membresía no encontrada.", 404);
            }

            return response()->success(compact('membership'), "Membresía actualizada exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * DELETE /memberships/{id}
     * Eliminar una membresía.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->membershipRepository->delete($id);
            if (!$deleted) {
                return response()->error("Membresía no encontrada.", 404);
            }
            return response()->success([], "Membresía eliminada exitosamente.");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
