<?php
/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: +57 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoicePayments;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class InvoiceRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function create_invoice($data): Invoice
    {
        return Invoice::firstOrCreate([
                            'slot_id'      => $data['slot_id'],
                            'order_name'   => $data['order_name'],
                            'client_id'    => $data['client_id'],
                            'provider_id'  => $data['provider_id'],
                            'employee_id'  => $data['employee_id'],
                            'total'        => $data['total'],
                            'balance'      => $data['balance'],
                            'status'       => $data['status'],
                        ]);

    }

    public function create_items(int $invoice_id, array $items): array
    {
        $inserted = [];
        
        foreach ($items as $item) {
            $inserted[] = InvoiceItems::firstOrCreate([
                'invoice_id'  => $invoice_id,
                'client_id'   => $item['client_id'],
                'servicio_id' => $item['servicio_id'],
                'description' => $item['servicio']['name'] ?? 'Item',
                'price'       => $item['price'],
                'quantity'    => $item['quantity'],
                'total_price' => (float) $item['price'] * (int) $item['quantity'],
            ]);
        }

        return $inserted;
    }

    public function getAll($request)
    {
        $perPage = $request->input('per_page', config('constants.RESULT_X_PAGE', 10));
        $user    = $request->user();

        $query = Invoice::with(['client', 'provider', 'employee', 'slot', 'items']);

        // Filtro por roles
        if ($user->hasRole(['super-admin', 'admin'])) {
            // sin restricción
        } elseif ($user->hasRole('clients')) {
            $query->where('client_id', $user->id);
        } elseif ($user->hasRole(['providers'])) {
            $query->where('provider_id', $user->customer_group_id ?? $user->id);
        }elseif ($user->hasRole(['managers', 'employees'])) {
            $query->where('provider_id', $user->customer_group_id ?? $user->id);
            $query->where('employee_id', $user->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        // Búsqueda por nombre de orden
        if ($search = $request->input('search')) {
            $query->where('order_name', 'like', "%{$search}%");
        }

        // Paginar y transformar los resultados
        return $query->orderByDesc('id')->paginate($perPage)->through(function ($item) {
            return [
                'id'         => $item->id,
                'Orden'      => $item->order_name,
                'Cliente'    => $item->client?->name,
                'Empleado'   => $item->employee?->name,
                'Proveedor'  => $item->provider?->name,
                'Total'      => $item->total,
                'Balance'    => $item->balance,
                'Estatus'    => $item->status,
                'Fecha'      => Carbon::parse($item->created_at)->format('d/m/Y'),                
            ];
        });
    }

    public function findById($id): ?Invoice
    {
        return Invoice::with(['client', 'items','payments'])->find($id);
    }


}
