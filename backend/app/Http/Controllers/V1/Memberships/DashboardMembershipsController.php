<?php

namespace App\Http\Controllers\V1\Memberships;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardMembershipsController extends Controller
{
    /**
     * Listado paginado de usuarios con sus membresías y estatus.
     */
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 15);
            $today   = now()->toDateString();

            $users = DB::table('users')
                ->select(
                    'users.id',
                    'users.name as nombre',
                    'users.email as correo',
                    DB::raw("COALESCE(memberships.label, '-') AS membresía"),
                    DB::raw("COALESCE(DATE(user_memberships.start_date), '-') AS 'fecha inicio'"),
                    DB::raw("COALESCE(DATE(user_memberships.end_date), '-') AS 'fecha fin'"),
                    DB::raw("
                        CASE 
                            WHEN user_memberships.end_date IS NULL THEN 'Sin membresía'
                            WHEN user_memberships.end_date < '$today' THEN 'Vencida'
                            ELSE 'Vigente'
                        END AS estado
                    ")
                )
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->leftJoin('user_memberships', 'users.id', '=', 'user_memberships.user_id')
                ->leftJoin('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                ->where('roles.name', 'providers')
                ->orderByDesc('user_memberships.end_date')
                ->paginate($perPage);

            return response()->success(compact("users"), 'Listado paginado de membresías por usuario');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function indexOld(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 15);
            $today   = now()->toDateTimeString();

            $users = DB::table('users')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'memberships.label as membership_name',
                    'user_memberships.start_date',
                    'user_memberships.end_date',
                    DB::raw("CASE 
                        WHEN user_memberships.end_date IS NULL THEN 'Sin membresía'
                        WHEN user_memberships.end_date < '$today' THEN 'Vencida'
                        ELSE 'Vigente'
                    END as status")
                )
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->leftJoin('user_memberships', 'users.id', '=', 'user_memberships.user_id')
                ->leftJoin('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                ->where('roles.name', 'providers')
                ->orderByDesc('user_memberships.end_date')
                ->paginate($perPage);

            return response()->success(compact("users"), 'Listado paginado de membresías por usuario');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function paids(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 15);

            $payments = \App\Models\Invoice::select(
                    'invoices.id',
                    'invoices.order_name',
                    'invoices.total',
                    'invoices.balance',
                    'invoices.status',
                    'invoices.month_paid',
                    'invoices.created_at',
                    'clients.name as client_name',
                    'clients.email as client_email',
                    'employees.name as employee_name'
                )
                ->leftJoin('users as clients', 'invoices.client_id', '=', 'clients.id')
                ->leftJoin('users as employees', 'invoices.employee_id', '=', 'employees.id')
                ->whereNotNull('invoices.client_id')
                ->where('invoices.status', 'completada')
                ->orderByDesc('invoices.created_at')
                ->paginate($perPage);

            return response()->success(compact("payments"), 'Pagos de membresías realizados');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }



}
