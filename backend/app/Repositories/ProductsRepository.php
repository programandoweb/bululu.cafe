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

use App\Models\Servicios;
use App\Models\Business;

class ProductsRepository
{
    /**
     * Obtener un producto por ID del servicio asociado.
     */
    public function getService(string $getService)
    {
        return Servicios::where('type', 'products')
            ->with(['user:id,name,email', 'category:id,name'])
            ->find($getService);
    }

    /**
     * Buscar productos relacionados por slug.
     * Ajusta este método según tu lógica real para productos.
     */
    public function getBySlug(string $slug)
    {
        return Servicios::select('id', 'name', 'description', 'image', 'gallery')
            ->where('type', 'products')
            ->where('pathname', $slug)
            ->first();
    }

    /**
     * Obtener todos los productos sin paginar.
     */
    public function get($user_id=false)
    {   
        if($user_id){
            return Servicios::where('type', 'products')
                            ->select('id','name','name as label', 'description', 'image','rating','price')
                            ->where("user_id",$user_id)
                            ->get();    
        }
        return Servicios::where('type', 'products')
            ->select('id','name','name as label', 'description', 'image')
            ->get();
    }

    /**
     * Obtener todos los productos con paginación y filtros.
     */
    public function getAll($request)
    {
        $perPage = $request->input('per_page', config('constants.RESULT_X_PAGE'));
        $user    = $request->user();
        $search  = $request->input('search');

        $query = Servicios::query()
            ->with(['user:id,name,email', 'category:id,name'])
            ->select('id', 'user_id', 'category_id', 'name', 'description', 'image')
            ->where('type', 'products');



        $authUser   = auth()->user();
        if ($authUser->hasRole('employees')||$authUser->hasRole('managers')) {
            if (!$authUser->customer_group_id) {
                return response()->error("El empleado no tiene un proveedor asignado (customer_group_id).", 400);
            }
            $providerId = $authUser->customer_group_id;
        } else {
            $providerId = $authUser->id;
        }


        if ($user->hasRole(['super-admin', 'admin'])) {
            // sin restricción
        } elseif ($user->hasRole('providers')||$user->hasRole('employees')||$user->hasRole('managers')) {
            $query->where('user_id', $providerId);
        } else {
            $query->whereRaw('1 = 0');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('id')->paginate($perPage)->through(function ($item) {
            $item->usuario   = $item->user?->name;
            $item->categoria = $item->category?->name;
            unset($item->user, $item->category, $item->user_id, $item->category_id);
            return $item;
        });
    }

    /**
     * Crear un nuevo producto.
     */
    public function create(array $data)
    {
        $data['type'] = 'products';
        return Servicios::create($data);
    }

    /**
     * Buscar un producto por ID.
     */
    public function findById($id)
    {
        return Servicios::where('type', 'products')
            ->with([
                'user:id,name,email',
                'category:id,name',
                'productCategory:id,name',
                'product' // incluye datos específicos del producto
            ])
            ->find($id);
    }


    /**
     * Obtener negocios (si aplica para productos).
     */
    public function getServicios()
    {
        return Business::get();
    }

    /**
     * Actualizar un producto existente.
     */
    public function update($id, array $data)
    {
        $servicio = Servicios::where('type', 'products')->find($id);
        if (!$servicio) {
            return null;
        }

        $servicio->update($data);
        return $servicio;
    }

    /**
     * Eliminar un producto.
     */
    public function delete($id)
    {
        $servicio = Servicios::where('type', 'products')->find($id);
        if (!$servicio) {
            return false;
        }

        return $servicio->delete();
    }
}
