<?php

namespace App\Http\Controllers\V1\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrdersRepository;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    protected $ordersRepository;

    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    public function index(Request $request)
    {
        try {
            $orders = $this->ordersRepository->getAll($request);
            return response()->success(compact('orders'), "Listado de órdenes");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'        => 'nullable|exists:users,id',
                'business_id'    => 'required|exists:businesses,id',
                'status'         => 'nullable|in:pendiente,procesando,completada,cancelada',
                'scheduled_at'   => 'nullable|date',
                'total_price'    => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string|max:100',
                'notes'          => 'nullable|string',
            ]);

            $order = $this->ordersRepository->create($validated);
            return response()->success(compact('order'), "Orden creada exitosamente");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $order = $this->ordersRepository->findById($id);
            if (!$order) {
                return response()->error("Orden no encontrada", 404);
            }

            return response()->success(compact('order'), "Orden encontrada");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'business_id'    => 'sometimes|required|exists:businesses,id',
                'status'         => 'nullable|in:pendiente,procesando,completada,cancelada',
                'scheduled_at'   => 'nullable|date',
                'total_price'    => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string|max:100',
                'notes'          => 'nullable|string',
            ]);

            $order = $this->ordersRepository->update($id, $validated);
            if (!$order) {
                return response()->error("Orden no encontrada", 404);
            }

            return response()->success(compact('order'), "Orden actualizada exitosamente");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $deleted = $this->ordersRepository->delete($id);
            if (!$deleted) {
                return response()->error("Orden no encontrada", 404);
            }

            return response()->success([], "Orden eliminada exitosamente");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
