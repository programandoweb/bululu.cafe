<?php
/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Repositories;

use App\Models\Memberships;

class MembershipsRepository
{
    /**
     * Obtener todas las membresías sin paginar.
     */
    public function get()
    {
        return Memberships::get();
    }

    /**
     * Obtener todas las membresías con filtros y paginación.
     */
    public function getAll($request)
    {
        $perPage = $request->input('per_page', config('constants.RESULT_X_PAGE', 15));
        $search  = $request->input('search');

        $query = Memberships::select(
            'id',
            'label',
            'price',
            'features',
            'icon',
            'options',
            'is_current',
            'created_at',
            'updated_at'
        );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%$search%");
            });
        }

        $results = $query->orderBy('id', 'DESC')->paginate($perPage);

        // Normalizar JSON (features, options)
        $results->getCollection()->transform(function ($item) {
            $item->features = is_string($item->features)
                ? json_decode($item->features, true)
                : ($item->features ?? []);
            $item->options = is_string($item->options)
                ? json_decode($item->options, true)
                : ($item->options ?? []);
            return $item;
        });

        return $results;
    }




    /**
     * Crear una nueva membresía.
     */
    public function create(array $data)
    {
        // Normaliza el campo options (guardado como JSON)
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }

        return Memberships::create($data);
    }

    /**
     * Buscar membresía por ID.
     */
    public function findById($id)
    {
        return Memberships::find($id);
    }

    /**
     * Actualizar una membresía existente.
     */
    public function update($id, array $data)
    {
        $membership = Memberships::find($id);
        if (!$membership) {
            return null;
        }

        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }

        $membership->update($data);
        return $membership;
    }

    /**
     * Eliminar (soft delete o delete directo).
     */
    public function delete($id)
    {
        $membership = Memberships::find($id);
        if (!$membership) {
            return false;
        }

        return $membership->delete();
    }
}
