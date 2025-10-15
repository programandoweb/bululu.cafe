<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: 3115000926
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Http\Controllers\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserMembership;
use App\Models\User;
use App\Models\Memberships;

class UserMembershipsController extends Controller
{
    /**
     * GET /users/memberships
     * Listar todas las suscripciones de usuarios
     */
    public function index(Request $request)
    {
        try {
            $query = UserMembership::with(['user:id,name,email', 'membership:id,name,price,duration'])
                ->orderByDesc('id');

            // 🔹 Filtro opcional por usuario o membresía
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->input('user_id'));
            }
            if ($request->filled('membership_id')) {
                $query->where('membership_id', $request->input('membership_id'));
            }

            $perPage = $request->input('per_page', config('constants.RESULT_X_PAGE', 15));
            $memberships = $query->paginate($perPage);

            return response()->success(compact('memberships'), 'Suscripciones listadas con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * POST /users/memberships
     * Crear una nueva suscripción de usuario
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'       => 'required|exists:users,id',
                'membership_id' => 'required|exists:memberships,id',
                'start_date'    => 'nullable|date',
                'end_date'      => 'nullable|date|after_or_equal:start_date',
            ]);

            DB::beginTransaction();

            // 🔹 Finalizar membresías activas previas
            UserMembership::where('user_id', $validated['user_id'])
                ->whereNull('end_date')
                ->update(['end_date' => now()]);

            // 🔹 Crear nueva membresía
            $membership = UserMembership::create([
                'user_id'       => $validated['user_id'],
                'membership_id' => $validated['membership_id'],
                'start_date'    => $validated['start_date'] ?? now(),
                'end_date'      => $validated['end_date'] ?? now()->addYear(),
            ]);

            DB::commit();

            return response()->success(compact('membership'), 'Membresía asignada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /users/memberships/{id}
     * Mostrar detalle de una membresía asignada
     */
    public function show($id)
    {
        try {
            $membership = UserMembership::with(['user:id,name,email', 'membership:id,name,price,duration'])
                ->findOrFail($id);

            return response()->success(compact('membership'), 'Suscripción encontrada con éxito.');
        } catch (\Throwable $e) {
            return response()->error('Suscripción no encontrada', 404);
        }
    }

    /**
     * PUT /users/memberships/{id}
     * Actualizar una membresía de usuario
     */
    public function update(Request $request, $id)
    {
        try {
            $membership = UserMembership::findOrFail($id);

            $validated = $request->validate([
                'membership_id' => 'sometimes|required|exists:memberships,id',
                'start_date'    => 'nullable|date',
                'end_date'      => 'nullable|date|after_or_equal:start_date',
            ]);

            DB::beginTransaction();

            $membership->update($validated);

            DB::commit();

            return response()->success(compact('membership'), 'Suscripción actualizada con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * DELETE /users/memberships/{id}
     * Eliminar una membresía de usuario
     */
    public function destroy($id)
    {
        try {
            $membership = UserMembership::findOrFail($id);

            DB::beginTransaction();
            $membership->delete();
            DB::commit();

            return response()->success([], 'Suscripción eliminada con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error('No se pudo eliminar la suscripción', 500);
        }
    }

    /**
     * GET /users/{id}/memberships
     * Mostrar todas las membresías de un usuario
     */
    public function membershipsByUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $memberships = UserMembership::with('membership:id,name,price,duration')
                ->where('user_id', $user->id)
                ->orderByDesc('start_date')
                ->get();

            return response()->success(compact('user', 'memberships'), 'Suscripciones del usuario cargadas correctamente.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
