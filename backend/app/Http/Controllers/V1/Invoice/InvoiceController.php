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

namespace App\Http\Controllers\V1\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InvoiceRepository;
use App\Repositories\EventOrderRepository;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    protected $invoiceRepository;
    protected $eventOrderRepository;

    public function __construct(InvoiceRepository $invoiceRepository,EventOrderRepository $eventOrderRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->eventOrderRepository = $eventOrderRepository;
    }

    public function generate_invoice(Request $request)
    {
        try {
            $authUser = $request->user();

            $validated = $request->validate([
                'client_id'      => 'required|exists:users,id',
                'membership_id'  => 'required|exists:memberships,id',
                'amount'         => 'required|numeric|min:0',
                'payment_method' => 'required|string|max:50',
                'month_paid'     => 'required',
                'note'           => 'nullable|string',
            ]);

            DB::beginTransaction();

            // 🔹 Crear factura tipo membresía
            $invoice = \App\Models\Invoice::create([
                'order_name'   => 'Pago de Membresía',
                'client_id'    => $validated['client_id'],
                'employee_id'  => $authUser?->id,
                'total'        => $validated['amount'],
                'balance'      => 0,
                'status'       => 'completada',
                'month_paid'   => $validated['month_paid'],
                'note'         => $validated['note'] ?? null,
            ]);

            // 🔹 Obtener todos los pagos previos del cliente
            $payment = \App\Models\Invoice::where('client_id', $validated['client_id'])->get();

            /**
             * ---------------------------------------------------------
             * Generar comentario histórico
             * ---------------------------------------------------------
             */
            $user  = \App\Models\User::find($validated['client_id']);
            $admin = auth()->user();

            if ($user && function_exists('generaComentario')) {
                $mensaje = "Registró un pago de membresía para el usuario {$user->name} (ID {$user->id}) por un monto de {$validated['amount']} USD.";

                generaComentario(
                    $mensaje,
                    $admin->id,      // Usuario que realiza la acción
                    'historial',     // Módulo fijo
                    false,           // Replace no usado
                    [
                        'affected_user_id'   => $user->id,
                        'affected_user_name' => $user->name,
                        'membership_id'      => $validated['membership_id'],
                        'invoice_id'         => $invoice->id,
                        'amount'             => $validated['amount'],
                        'payment_method'     => $validated['payment_method'],
                        'changed_by'         => $admin->name,
                    ],
                    "event_{$user->id}" // 🔹 Pathname dinámico
                );
            }

            /**
             * ---------------------------------------------------------
             * Insertar o crear nueva membresía del usuario
             * ---------------------------------------------------------
             * - Usa $user->payment_day como día base
             * - Acepta "Octubre 2025" u otros formatos en español
             * - Crea un registro único por rango de fechas
             * ---------------------------------------------------------
             */
            if ($user) {
                $day = (int) ($user->payment_day ?? now()->day);

                // 🔹 Mapeo de meses en español → inglés
                $months = [
                    'enero' => 'January',
                    'febrero' => 'February',
                    'marzo' => 'March',
                    'abril' => 'April',
                    'mayo' => 'May',
                    'junio' => 'June',
                    'julio' => 'July',
                    'agosto' => 'August',
                    'septiembre' => 'September',
                    'setiembre' => 'September',
                    'octubre' => 'October',
                    'noviembre' => 'November',
                    'diciembre' => 'December',
                ];

                $monthPaid = strtolower(trim($validated['month_paid']));
                foreach ($months as $es => $en) {
                    if (str_contains($monthPaid, $es)) {
                        $monthPaid = str_replace($es, $en, $monthPaid);
                        break;
                    }
                }

                // 🔹 Generar fechas de inicio y fin seguras
                $baseDate = \Carbon\Carbon::parse($monthPaid)
                    ->setDay($day)
                    ->startOfDay();

                $startDate = $baseDate;
                $endDate   = $baseDate->copy()->addMonth();

                // 🔹 Actualizar o crear membresía del usuario (solo filtra por user_id)
                \App\Models\UserMembership::updateOrCreate(
                    [
                        'user_id' => $user->id, // 🔹 clave de búsqueda
                    ],
                    [
                        'membership_id' => $validated['membership_id'],
                        'start_date'    => $startDate,
                        'end_date'      => $endDate,
                    ]
                );
            }

            DB::commit();

            return response()->success(
                compact('invoice', 'payment'),
                'Pago de membresía registrado exitosamente.'
            );

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }





    public function generate_invoice_old(Request $request)
    {
        try {
            $authUser = $request->user();

            $validated = $request->validate([
                'client_id'         => 'required|exists:users,id',
                'membership_id'     => 'required|exists:memberships,id',
                'amount'            => 'required|numeric|min:0',
                'payment_method'    => 'required|string|max:50',
                'month_paid'        => 'required',
                'note'              => 'nullable|string',
            ]);

            // 🔹 Crear factura tipo membresía
            $invoice = \App\Models\Invoice::create([
                'order_name'  => 'Pago de Membresía',
                'client_id'   => $validated['client_id'],
                'employee_id' => $authUser?->id,
                'total'       => $validated['amount'],
                'balance'     => 0,
                'status'      => 'completada',
                'month_paid'  => $validated['month_paid'],
            ]);

            // 🔹 Obtener todos los pagos previos del cliente
            $payment = \App\Models\Invoice::where('client_id', $validated['client_id'])->get();

            /**
             * ---------------------------------------------------------
             * Generar comentario histórico
             * ---------------------------------------------------------
             * - Módulo: "historial"
             * - Pathname: "event_" + ID del usuario afectado
             * ---------------------------------------------------------
             */
            $user = \App\Models\User::find($validated['client_id']);
            $admin = auth()->user();

            if ($user && function_exists('generaComentario')) {
                $mensaje = "Registró un pago de membresía para el usuario {$user->name} (ID {$user->id}) por un monto de {$validated['amount']} USD.";

                generaComentario(
                    $mensaje,
                    $admin->id,      // Usuario que realiza la acción
                    'historial',     // Módulo fijo
                    false,           // Replace no usado
                    [
                        'affected_user_id'   => $user->id,
                        'affected_user_name' => $user->name,
                        'membership_id'      => $validated['membership_id'],
                        'invoice_id'         => $invoice->id,
                        'amount'             => $validated['amount'],
                        'payment_method'     => $validated['payment_method'],
                        'changed_by'         => $admin->name,
                    ],
                    "event_{$user->id}" // 🔹 Pathname dinámico
                );
            }

            /**
             * Necesito lógica para actualizar la membresía
             * Estas son las fechas de pago $user->payment_day;
             * necesito que al efectuar pago me haga un insert a UserMembership
             * con la fecha $user->payment_day + un mes, es decir, si pago el mes de julio, entonces tome la membresía desde 5 julio a 5 de agosto
             */

            
            return response()->success(
                compact('invoice', 'payment'),
                'Pago de membresía registrado exitosamente.'
            );

        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function generate_invoice_sin_comments(Request $request)
    {
        try {
            $authUser = $request->user();

            $validated = $request->validate([
                'client_id'      => 'required|exists:users,id',
                'membership_id'  => 'required|exists:memberships,id',
                'amount'         => 'required|numeric|min:0',
                'payment_method' => 'required|string|max:50',
                'note'           => 'nullable|string',
            ]);

            // 🔹 Crear o reutilizar una factura del cliente (tipo membresía)
            $invoice    =   \App\Models\Invoice::create([
                'order_name'  => 'Pago de Membresía',
                'client_id'   => $validated['client_id'],
                'employee_id' => $authUser?->id,
                'total'       => $validated['amount'],
                'balance'     => 0,
                'status'      => 'completada',
            ]);

            $payment            =   \App\Models\Invoice::where([
                'client_id'     =>  $validated['client_id'],                
            ])->get();

            return response()->success(
                compact('invoice', 'payment'),
                'Pago de membresía registrado exitosamente.'
            );
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function index(Request $request)
    {
        try {
            $invoices   =   $this->invoiceRepository->getAll($request);
            return response()->success(compact('invoices'), "Listado de invoices");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = $this->invoiceRepository->findById($id);

            if (!$invoice) {
                return response()->error("Factura no encontrada", 404);
            }

            return response()->success(compact('invoice'), "Factura encontrada");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function pay_partial(Request $request, $id)
    {
        try {
            $invoice = $this->invoiceRepository->findById($id);
            if (!$invoice) {
                return response()->error("Factura no encontrada", 404);
            }

            $data   =   $request->only(['amount', 'payment_method', 'note']);
            $amount =   (float) $data['amount'] ?? 0;

            if ($amount <= 0) {
                return response()->error("El monto debe ser mayor a 0", 422);
            }

            if ($amount > $invoice->balance) {
                return response()->error("El monto excede el saldo pendiente", 422);
            }

            //p($data);

            $authUser = $request->user();

            // Registrar el pago
            $payment = \App\Models\InvoicePayments::create([
                'invoice_id'     => $invoice->id,
                'client_id'      => $invoice->client_id,
                'amount'         => $amount,
                'payment_method' => $data['payment_method'] ?? 'no_definido',
                'note'           => $data['note'] ?? null,
                'paid_at'        => now(),
            ]);

            // Actualizar balance de la factura
            $invoice->balance -= $amount;
            if ($invoice->balance <= 0) {
                $invoice->status = 'completada';
            } else {
                $invoice->status = 'en_progreso';
            }
            $invoice->save();

            $invoice = $this->invoiceRepository->findById($id);

            return response()->success([
                'invoice' => $invoice,
                'payment' => $payment
            ], "Pago parcial registrado correctamente");

        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    public function pay_full(Request $request, $id)
    {
        try {
            $invoice = $this->invoiceRepository->findById($id);

            if (!$invoice) {
                return response()->error("Factura no encontrada", 404);
            }

            $balance = (float) $invoice->balance;

            if ($balance <= 0) {
                return response()->error("La factura ya está saldada", 422);
            }

            $authUser = $request->user();
            $paymentMethod = $request->input('payment_method', 'no_definido');
            $note = $request->input('note', 'Pago completo automático');

            // Registrar el pago completo
            $payment = \App\Models\InvoicePayments::create([
                'invoice_id'     => $invoice->id,
                'client_id'      => $invoice->client_id,
                'amount'         => $balance,
                'payment_method' => $paymentMethod,
                'note'           => $note,
                'paid_at'        => now(),
            ]);

            // Actualizar estado de la factura
            $invoice->balance = 0;
            $invoice->status = 'completada';
            $invoice->save();

            $invoice = $this->invoiceRepository->findById($id);

            return response()->success([
                'invoice' => $invoice,
                'payment' => $payment
            ], "Pago completo registrado correctamente");

        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


}
