<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Repositories;

use App\Models\EventOrder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;


class EventOrderRepository
{

    /**
     * Obtener una orden de evento con relaciones por ID.
     *
     * @param int $id
     * @return EventOrder|null
     */
    public function show(int $id): ?EventOrder
    {
        $return     =   EventOrder::with([
            'client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'
        ])->find($id);
        
        if($return){
            $return->items = EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])->where("name",$return->name)->get();
        }
        return $return;
    }

    public function showByString(string $id): ?EventOrder
    {
        $return = EventOrder::with([
            'client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'
        ])->where("name",$id)->first();

        if($return){
            $return->items = EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])->where("name",$id)->get();
        }

        return $return;
    }

    /**
     * Verifica si ya existe una orden de evento con los mismos parámetros
     * excluyendo `quantity` y `servicio_id`, lo cual permite evitar duplicados
     * al momento de previsualizar una orden.
     *
     * @param array $data Datos de entrada que incluyen los campos relevantes para la comparación.
     * @return bool Retorna true si existe una coincidencia exacta, false en caso contrario.
     */
    public function previewOrder(array $data)
    {
        return EventOrder::where('client_id', $data['client_id'])
                            ->where('provider_id', $data['provider_id'])
                            ->where('calendar_slot_id', $data['id'])
                            ->where('status', $data['status'])
                            ->first();
    }


    /**
     * Obtener las órdenes de evento con paginación y filtros por rol.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(Request $request)
    {
        $perPage  = $request->input('per_page', config('constants.RESULT_X_PAGE', 10));
        $authUser = $request->user();

        $query = EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot']);

        // Filtro por rol
        if ($authUser->hasRole(['super-admin', 'admin'])) {
            // sin restricción
        } elseif ($authUser->hasRole('clients')) {
            $query->where('client_id', $authUser->customer_group_id ?? $authUser->id);
        } elseif ($authUser->hasRole(['providers', 'managers', 'employees'])) {
            $providerId = $authUser->customer_group_id ?? $authUser->id;

            $ids = EventOrder::selectRaw('MAX(id) as id')
                ->where('provider_id', $providerId)
                ->groupBy('name')
                ->pluck('id');

            $query->whereIn('id', $ids);
        } else {
            $query->whereRaw('1 = 0');
        }

        $query->where('status','!=', 'completada');

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->whereHas('servicio', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Paginar y transformar los resultados
        return $query->orderByDesc('id')->paginate($perPage)->through(function ($item) {
            return [
                'id'        => $item->name,
                'Cliente'   => $item->client?->name,
                'Empleado'  => $item->employee?->name,
                'Precio'    => $item->price,
                'Cantidad'  => $item->quantity,
                'Creada'    => Carbon::parse($item->created_at)->format('d/m/Y'),
                'Servicio'  => $item->servicio?->name,
                'Estatus'   => $item->status,
            ];
        });
    }



    /**
     * Crear o actualizar una orden de evento por slot y servicio.
     *
     * @param array $data
     * @return EventOrder
     */
    public function createOrUpdate(array $data): EventOrder
    {
        $event  =   EventOrder::where("calendar_slot_id",$data['calendar_slot_id'])
                                ->where("servicio_id",$data['servicio_id'])
                                ->first();
        
        if($event){
            $data["status"]     =   "pendiente";
            /**
             * 
             * Aquí hay que hacer una consulta a la factura si existe para eliminarla o editarla luego vemos
             * 
             */
        }
        //p($data["status"]);
        return EventOrder::updateOrCreate(
            [
                'calendar_slot_id' => $data['calendar_slot_id'],
                'servicio_id'      => $data['servicio_id'],
            ],
            $data
        );
    }


    /**
     * Crear una nueva orden de evento.
     *
     * @param array $data
     * @return EventOrder
     */
    public function create(array $data): EventOrder
    {
        return EventOrder::create($data);
    }

    /**
     * Obtener todas las órdenes de evento.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return EventOrder::all();
    }

    /**
     * Obtener una orden de evento por ID.
     *
     * @param int $id
     * @return EventOrder|null
     */
    public function find(int $id): ?EventOrder
    {
        return EventOrder::find($id);
    }

    /**
     * Obtener las órdenes filtradas por proveedor autenticado.
     *
     * @param Request $request
     * @return Collection
     */
    public function get(Request $request,$order): Collection
    {
        $user       = $request->user();
        $providerId = $user->customer_group_id ?? $user->id;

        $ids        = EventOrder::selectRaw('MAX(id) as id')        
                                    ->where('name', $order->name)
                                    ->where('provider_id', $providerId)
                                    ->where('employee_id', $user->id)
                                    ->groupBy('servicio_id')->pluck('id');

            

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            // Admin: sin filtro por provider_id
            return EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])->get();
        }else if ($user->hasRole('providers')) {
            return EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])
                                ->where('provider_id', $providerId)
                                ->get();    
            
        }else if ($user->hasRole(['managers', 'employees'])) {
            return EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])
                                ->where('provider_id', $providerId)
                                ->whereIn('id', $ids)
                                ->get();    
        }

        // Otros roles: filtro por provider_id
        return EventOrder::with(['client', 'provider', 'employee', 'event', 'servicio', 'calendar_slot'])
                                ->where('provider_id', $providerId)
                                ->get();
    }

}
