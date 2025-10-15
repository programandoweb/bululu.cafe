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

namespace App\Http\Controllers\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Services\EmailService;
use Illuminate\Support\Str; // ✅ <--- ESTA LÍNEA ES LA CLAVE


class UsersDashboardController extends Controller
{

        /**
     * POST /dashboard/companies/new_by_IA
     * Crea un nuevo registro de empresa con datos provenientes de la IA
     */
    public function store_by_ia(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'                  => 'required|string|max:150',
                'company_name'          => 'nullable|string|max:255',
                'email'                 => 'nullable|email|unique:users,email',
                'user_type'             => 'required|in:natural,juridica',
                'identification_number' => 'nullable|string|unique:users,identification_number',
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'phone_number'          => 'nullable|string|max:50',
                'tax_no'                => 'nullable|string|max:50',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'state'                 => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:100',
                'description'           => 'nullable|string|max:500',
                'website'               => 'nullable|string|max:255',
                'facebook'              => 'nullable|string|max:255',
                'instagram'             => 'nullable|string|max:255',
                'tiktok'                => 'nullable|string|max:255',
                'whatsapp_link'         => 'nullable|string|max:255',
                'status'                => 'nullable|in:activo,inactivo,solicitud,rechazado',
            ]);

            DB::beginTransaction();

            // 🔹 Generar email único si no se proporcionó
            if (empty($validated['email'])) {
                $validated['email'] = 'ia_' . Str::slug($validated['company_name'] ?? $validated['name']) . '@ivoolve.ai';
            }

            // 🔹 Generar contraseña aleatoria
            $validated['password'] = Hash::make(Str::random(10));

            // 🔹 Crear usuario
            $user = User::create($validated);

            // 🔹 Asignar rol por defecto (providers)
            $defaultRole = Role::where('name', 'providers')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole->name);
            }

            DB::commit();

            return response()->success(compact('user'), 'Empresa creada exitosamente mediante IA.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function get_membership($id)
    {
        try {
            // 🔹 Cargar usuario con sus relaciones
            $user = User::with(['userMembership.membership'])->find($id);

            if (!$user) {
                return response()->error('Usuario no encontrado', 404);
            }

            // 🔹 Buscar la última membresía (vigente o vencida)
            $lastMembership = \App\Models\UserMembership::with('membership')
                ->where('user_id', $user->id)
                ->orderByDesc('start_date')
                ->first();

            // 🔹 Obtener pagos realizados
            $payments = \App\Models\Invoice::where('client_id', $user->id)
                ->orderByDesc('id')
                ->get([
                    'id',
                    'order_name',
                    'total',
                    'balance',
                    'status',
                    'month_paid',
                    'created_at'
                ]);

            // 🔹 Armar respuesta
            if ($lastMembership && $lastMembership->membership) {
                $isActive = now()->between($lastMembership->start_date, $lastMembership->end_date);
                $status   = $isActive ? 'Activa' : 'Vencida';

                $data = [
                    'user_id'        => $user->id,
                    'name'           => $user->name,
                    'has_membership' => true,
                    'status_message' => "El usuario tiene una membresía {$status}.",
                    'membership' => [
                        'id'         => $lastMembership->membership->id,
                        'label'      => $lastMembership->membership->label,
                        'price'      => $lastMembership->membership->price,
                        'start_date' => $lastMembership->start_date,
                        'end_date'   => $lastMembership->end_date,
                        'status'     => $status,
                    ],
                    'payments' => $payments,
                ];
            } else {
                $data = [
                    'user_id'        => $user->id,
                    'name'           => $user->name,
                    'has_membership' => false,
                    'status_message' => 'El usuario no tiene ninguna membresía registrada.',
                    'membership'     => [
                        'label'      => 'Sin membresía',
                        'status'     => 'Ninguna',
                        'start_date' => null,
                        'end_date'   => null,
                    ],
                    'payments'       => $payments,
                ];
            }

            return response()->success($data, 'Información de membresía obtenida correctamente.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }


    /**
     * GET /dashboard/companies/{id}/membership
     * Retorna la membresía actual del usuario con su información completa.
     */
    public function get_membershipOLD($id)
    {
        try {
            $user = User::with('userMembership.membership')->findOrFail($id);

            if (!$user) {
                return response()->error('Usuario no encontrado', 404);
            }

            // 🔹 Traer la membresía activa del usuario
            $currentMembership = \App\Models\UserMembership::with('membership')
                ->where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->latest('start_date')
                ->first();

            if (!$currentMembership) {
                return response()->success(
                    ['membership' => null],
                    'El usuario no tiene una membresía activa.'
                );
            }

            // 🔹 Estructura de respuesta
            $data = [
                'user_id'    => $user->id,
                'name'       => $user->name,
                'membership' => [
                    'id'         => $currentMembership->membership->id,
                    'label'      => $currentMembership->membership->label,
                    'price'      => $currentMembership->membership->price,
                    'start_date' => $currentMembership->start_date,
                    'end_date'   => $currentMembership->end_date,
                    'is_active'  => now()->between(
                        $currentMembership->start_date,
                        $currentMembership->end_date
                    ),
                ],
            ];

            p($currentMembership);

            $payment            =   \App\Models\Invoice::where([
                'client_id'     =>  $currentMembership->user_id,                
            ])->get();

            $data["payments"]   =   $payment;

            return response()->success($data, 'Membresía actual del usuario obtenida con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }


    /**
     * GET /users
     * Listar usuarios con paginación y búsqueda
     */
    public function index(Request $request)
    {
        try {
            $query = User::query()->select(
                'id',
                'name',
                'email',
                'status'
            );

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('company_name', 'like', "%$search%");
                });
            }

            if ($request->is('api/v1/dashboard/companies')) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'providers');
                });
            }

            $query->orderByRaw("
                CASE 
                    WHEN status = 'solicitud' THEN 1
                    WHEN status = 'activo' THEN 2
                    WHEN status = 'inactivo' THEN 3
                    WHEN status = 'rechazado' THEN 4
                    ELSE 5
                END
            ")->orderBy('name', 'asc');

            $perPage = $request->input('per_page', config('constants.RESULT_X_PAGE', 15));
            $users = $query->paginate($perPage);

            if (!$request->is('api/v1/dashboard/companies')&&!$request->is('api/v1/dashboard/users')) {
                $users->getCollection()->transform(function ($user) {
                    $role = $user->roles()->first();
                    $user->role = $role ? $role->name : null;

                    // Decodificar campos JSON
                    foreach (['schedule', 'gallery', 'categories', 'eventsToday', 'promotions'] as $field) {
                        $user->$field = $user->$field ? json_decode($user->$field, true) : null;
                    }

                    return $user;
                });
            }

            return response()->success(compact('users'), 'Usuarios listados con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * POST /users
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_group_id'     => 'nullable|integer',
                'name'                  => 'required|string|max:150',
                'company_name'          => 'nullable|string|max:255',
                'image'                 => 'nullable|string|max:255',
                'cover'                 => 'nullable|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|min:6',
                'user_type'             => 'required|in:natural,juridica',
                'identification_number' => 'nullable|string|unique:users,identification_number',
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'phone_number'          => 'nullable|string|max:50',
                'tax_no'                => 'nullable|string|max:50',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'state'                 => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:5',
                'whatsapp_link'         => 'nullable|string|max:255',
                'description'           => 'nullable|string|max:500',
                'schedule'              => 'nullable|array',
                'gallery'               => 'nullable|array',
                'categories'            => 'nullable|array',
                'eventsToday'           => 'nullable|array',
                'promotions'            => 'nullable|array',
                'payment_day'            => 'nullable|string|max:50', // 🔹 nuevo campo
            ]);

            $validated['password']      =   Hash::make($validated['password']);

            DB::beginTransaction();
            $user = User::create($validated);
            DB::commit();

            return response()->success(compact('user'), 'Usuario creado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * GET /users/{id}
     */
    public function showOld($id)
    {
        try {
            $user           =   User::with('userMembership')->find($id);
            if (!$user) return response()->error('Usuario no encontrado', 404);

            $currentRole    =   $user->roles()->first();
            $user->role     =   $currentRole ? $currentRole->id : null;

            foreach (['schedule', 'gallery', 'categories', 'eventsToday', 'promotions'] as $field) {
                $user->$field = $user->$field ? json_decode($user->$field, true) : null;
            }

            $roles          =   Role::select('id', 'name as label')->get();
            $memberships    =   app(\App\Repositories\MembershipsRepository::class)->get();

            return response()->success(compact('user', 'roles', 'memberships'), 'Usuario encontrado con éxito.');
        } catch (\Throwable $e) {
            return response()->error('Usuario no encontrado', 404);
        }
    }

    public function show($id)
    {
        try {
            // 🔹 Cargar usuario con su membresía y tags
            $user = User::with(['userMembership', 'tags','gallery','events','promotions'])->find($id);
            if (!$user)  return response()->success([],"Nuevo");

            // 🔹 Rol actual del usuario
            $currentRole = $user->roles()->first();
            $user->role = $currentRole ? $currentRole->id : null;

            // 🔹 Decodificar campos JSON almacenados como string
            // 🔹 Decodificar solo si el campo es string (evita error doble decode)
            foreach (['schedule', 'gallery', 'categories', 'events', 'promotions'] as $field) {
                if (!empty($user->$field)) {
                    $user->$field = is_string($user->$field)
                        ? json_decode($user->$field, true)
                        : $user->$field;
                } else {
                    $user->$field = null;
                }
            }


            // 🔹 Incluir solo los IDs de tags seleccionados
            $user->tags = $user->tags->pluck('id');


            //p($user->events);

            // 🔹 Roles disponibles
            $roles = Role::select('id', 'name as label')->get();

            // 🔹 Membresías disponibles
            $memberships = app(\App\Repositories\MembershipsRepository::class)->get();

            // 🔹 Tags agrupados por su grupo padre ("group_farrea")
            $groups = DB::table('master_tables')
                ->where('grupo', 'group_farrea')
                ->get();

            $tagsByGroup = [];

            foreach ($groups as $group) {
                $tags = DB::table('master_tables as t')
                    ->select('t.id', 't.label', 't.grupo', 't.medida_id', 't.bool_status')
                    ->where('t.grupo', 'tags')
                    ->where('t.medida_id', $group->id)
                    ->where('t.bool_status', 1)
                    ->orderBy('t.id', 'asc')
                    ->get();

                $tagsByGroup[] = [
                    'group' => $group->label,
                    'tags'  => $tags
                ];
            }

            return response()->success(
                compact('user', 'roles', 'memberships', 'tagsByGroup'),
                'Usuario encontrado con éxito.'
            );

        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

     /**
     * PUT /users/{id}
     * Actualiza un usuario existente y sincroniza tags
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $statusOld = $user->status;

            $validated = $request->validate([
                'customer_group_id'     => 'nullable|integer',
                'name'                  => 'sometimes|required|string|max:150',
                'company_name'          => 'nullable|string|max:255',
                'image'                 => 'nullable|string|max:255',
                'cover'                 => 'nullable|string|max:255',
                'email'                 => ['sometimes','required','email', Rule::unique('users')->ignore($user->id)],
                'password'              => 'nullable|string|min:6',
                'user_type'             => 'sometimes|required|in:natural,juridica',
                'identification_number' => ['nullable','string', Rule::unique('users')->ignore($user->id)],
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'phone_number'          => 'nullable|string|max:50',
                'tax_no'                => 'nullable|string|max:50',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'state'                 => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:5',
                'status'                => 'nullable|in:activo,inactivo,solicitud,rechazado',
                'email_verified_at'     => 'nullable|date',
                'confirmation_code'     => 'nullable|string|max:100',
                'role'                  => 'nullable|integer|exists:roles,id',
                'whatsapp_link'         => 'nullable|string|max:255',
                'description'           => 'nullable|string|max:500',
                'schedule'              => 'nullable|array',
                'gallery'               => 'nullable|array',
                'categories'            => 'nullable|array',
                'eventsToday'           => 'nullable|array',
                'promotions'            => 'nullable|array',
                'tags'                  => 'nullable|array',
                'payment_day'           => 'nullable|max:50',               

            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            DB::beginTransaction();

            $user->update($validated);

            // 🔹 Actualizar rol si se envía
            if ($request->filled('role')) {
                $role = Role::find($request->input('role'));
                if ($role) {
                    $user->syncRoles([$role->name]);
                }
            }

            // 🔹 Sincronizar tags con formato mixto (IDs u objetos)
            if ($request->has('tags')) {
                $tags = collect($request->input('tags', []))
                    ->map(fn($tag) => is_array($tag) ? ($tag['id'] ?? null) : $tag)
                    ->filter(fn($id) => is_numeric($id))
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->toArray();

                DB::table('user_tags')->where('user_id', $user->id)->delete();

                if (!empty($tags)) {
                    $insertData = collect($tags)->map(fn($tagId) => [
                        'user_id' => $user->id,
                        'tag_id'  => $tagId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();

                    DB::table('user_tags')->insert($insertData);
                }
            }

            DB::commit();

            // 🔹 Enviar correo si cambia el estado
            if ($user->status !== $statusOld) {
                $setting = match ($user->status) {
                    'activo'    => DB::table('master_tables')->where('grupo', 'email_accept_business')->first(),
                    'rechazado' => DB::table('master_tables')->where('grupo', 'email_reject_business')->first(),
                    'solicitud' => DB::table('master_tables')->where('grupo', 'email_register_business')->first(),
                    'inactivo'  => DB::table('master_tables')->where('grupo', 'email_deactivate_user')->first(),
                    default     => null,
                };

                if ($setting) {
                    $email = env('APP_DEBUG') ? 'lic.jorgemendez@gmail.com' : $user->email;
                    app(EmailService::class)->sendGenericEmail($email, $setting->label, $setting->description);
                }
            }

            $user->tags = DB::table('user_tags')->where('user_id', $user->id)->pluck('tag_id');

            return response()->success(compact('user'), 'Usuario actualizado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * PUT /users/{id}
     */
    public function update2Old(Request $request, $id)
    {
        //p(5);
        try {
            $user = User::findOrFail($id);
            $statusOld = $user->status;

            $validated = $request->validate([
                'customer_group_id'     => 'nullable|integer',
                'name'                  => 'sometimes|required|string|max:150',
                'company_name'          => 'nullable|string|max:255',
                'image'                 => 'nullable|string|max:255',
                'cover'                 => 'nullable|string|max:255',
                'email'                 => ['sometimes','required','email', Rule::unique('users')->ignore($user->id)],
                'password'              => 'nullable|string|min:6',
                'user_type'             => 'sometimes|required|in:natural,juridica',
                'identification_number' => ['nullable','string', Rule::unique('users')->ignore($user->id)],
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'phone_number'          => 'nullable|string|max:50',
                'tax_no'                => 'nullable|string|max:50',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'state'                 => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:5',
                'status'                => 'nullable|in:activo,inactivo,solicitud,rechazado',
                'email_verified_at'     => 'nullable|date',
                'confirmation_code'     => 'nullable|string|max:100',
                'role'                  => 'nullable|integer|exists:roles,id',
                'whatsapp_link'         => 'nullable|string|max:255',
                'description'           => 'nullable|string|max:500',
                'schedule'              => 'nullable|array',
                'gallery'               => 'nullable|array',
                'categories'            => 'nullable|array',
                'eventsToday'           => 'nullable|array',
                'promotions'            => 'nullable|array',
                'tags'                  => 'nullable|array', // 🔹 validamos el array de tags}
                'payment_day'            => 'nullable|string|max:50', // 🔹 nuevo campo
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            $validated['payment_day']    =   $validated['payment_day'] ?? null;

            DB::beginTransaction();

            // 🔹 Actualizar datos del usuario
            $user->update($validated);

            // 🔹 Actualizar roles si viene uno
            if ($request->filled('role')) {
                $role = Role::find($request->input('role'));
                if ($role) $user->syncRoles([$role->name]);
            }

            // 🔹 Sincronizar tags (user_tags)
            if ($request->has('tags')) {
                $tags = $request->input('tags', []);

                // Borrar todos los tags previos del usuario
                DB::table('user_tags')->where('user_id', $user->id)->delete();

                // Insertar los nuevos
                $insertData = collect($tags)->map(fn($tagId) => [
                    'user_id' => $user->id,
                    'tag_id'  => $tagId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                if (!empty($insertData)) {
                    DB::table('user_tags')->insert($insertData);
                }
            }

            DB::commit();

            /**
             * ----------------------------------------------------
             * Envío de correo si el estatus ha cambiado
             * ----------------------------------------------------
             */
            if ($user->status !== $statusOld) {

                $setting = match ($user->status) {
                    'activo'    => DB::table('master_tables')->where('grupo', 'email_accept_business')->first(),
                    'rechazado' => DB::table('master_tables')->where('grupo', 'email_reject_business')->first(),
                    'solicitud' => DB::table('master_tables')->where('grupo', 'email_register_business')->first(),
                    'inactivo'  => DB::table('master_tables')->where('grupo', 'email_deactivate_user')->first(),
                    default     => null,
                };

                if ($setting) {
                    $email = env('APP_DEBUG') ? 'lic.jorgemendez@gmail.com' : $user->email;
                    app(EmailService::class)->sendGenericEmail($email, $setting->label, $setting->description);
                }
            }

            // 🔹 Devolver respuesta actualizada con tags incluidos
            $user->tags = DB::table('user_tags')->where('user_id', $user->id)->pluck('tag_id');

            return response()->success(compact('user'), 'Usuario actualizado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function updateOLD(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $statusOld  =   $user->status;

            $validated = $request->validate([
                'customer_group_id'     => 'nullable|integer',
                'name'                  => 'sometimes|required|string|max:150',
                'company_name'          => 'nullable|string|max:255',
                'image'                 => 'nullable|string|max:255',
                'cover'                 => 'nullable|string|max:255',
                'email'                 => ['sometimes','required','email', Rule::unique('users')->ignore($user->id)],
                'password'              => 'nullable|string|min:6',
                'user_type'             => 'sometimes|required|in:natural,juridica',
                'identification_number' => ['nullable','string', Rule::unique('users')->ignore($user->id)],
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'phone_number'          => 'nullable|string|max:50',
                'tax_no'                => 'nullable|string|max:50',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'state'                 => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:5',
                'status'                => 'nullable|in:activo,inactivo,solicitud,rechazado',
                'email_verified_at'     => 'nullable|date',
                'confirmation_code'     => 'nullable|string|max:100',
                'role'                  => 'nullable|integer|exists:roles,id',
                'whatsapp_link'         => 'nullable|string|max:255',
                'description'           => 'nullable|string|max:500',
                'schedule'              => 'nullable|array',
                'gallery'               => 'nullable|array',
                'categories'            => 'nullable|array',
                'eventsToday'           => 'nullable|array',
                'promotions'            => 'nullable|array',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            DB::beginTransaction();
            $user->update($validated);

            if ($request->filled('role')) {
                $role = Role::find($request->input('role'));
                if ($role) $user->syncRoles([$role->name]);
            }

            DB::commit();


            /**
             * 
             * Vamos a enviar un correo al usuario si su estatus ha cambiado
             * $statusOld  =   $user->status;
            */
            
            if ($user->status !== $statusOld) {
            
                $setting = null; // Inicializar la variable de configuración

                switch ($user->status) {
                    case 'activo':
                        // Carga el mensaje de BIENVENIDA / ACEPTACIÓN
                        $setting = DB::table('master_tables')->where('grupo', 'email_accept_business')->first();
                        break;

                    case 'rechazado':
                        // Carga el mensaje de RECHAZO por políticas
                        $setting = DB::table('master_tables')->where('grupo', 'email_reject_business')->first();
                        break;

                    case 'solicitud':
                        // Carga el mensaje de "SOLICITUD EN REVISIÓN"
                        $setting = DB::table('master_tables')->where('grupo', 'email_register_business')->first();
                        break;
                        
                    case 'inactivo':
                        // Carga el mensaje de CONFIRMACIÓN DE DESACTIVACIÓN
                        $setting = DB::table('master_tables')->where('grupo', 'email_deactivate_user')->first();
                        break;

                    default:
                        // El estado cambió a un valor no manejado, no hacer nada.
                        $setting = null;
                        break;
                }

                // 5. Si se encontró una plantilla de correo, prepararla y enviarla.
                if ($setting) {
                    if(env("APP_DEBUG")==true){
                        $email  =   'lic.jorgemendez@gmail.com';
                    }else{
                        $email  =   $user->email;
                    }
                    app(EmailService::class)->sendGenericEmail($email,$setting->label,$setting->description);                    
                }
            }
            

            return response()->success(compact('user'), 'Usuario actualizado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * DELETE /users/{id}
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return response()->success(compact('user'), 'Usuario eliminado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error('No se pudo eliminar el usuario', 500);
        }
    }

    /**
     * POST /dashboard/companies/{id}/membership
     */
    public function set_membership(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'membership_id' => 'required|exists:memberships,id',
            ]);

            DB::beginTransaction();

            $userMembership = \App\Models\UserMembership::where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->first();

            if ($userMembership) {
                $userMembership->update([
                    'membership_id' => $validated['membership_id'],
                    'start_date'    => now(),
                    'end_date'      => now()->addYear(),
                ]);
            } else {
                $userMembership = \App\Models\UserMembership::create([
                    'user_id'       => $user->id,
                    'membership_id' => $validated['membership_id'],
                    'start_date'    => now(),
                    'end_date'      => now()->addYear(),
                ]);
            }

            DB::commit();

            /**
             * ---------------------------------------------------------
             * Generar comentario histórico
             * ---------------------------------------------------------
             * - Módulo: "historial"
             * - Pathname: "event_" + ID del usuario afectado
             * ---------------------------------------------------------
             */
            $admin = auth()->user();
            $mensaje = "Actualizó la membresía del usuario {$user->name} (ID {$user->id})";

            generaComentario(
                $mensaje,
                $admin->id,      // Usuario que realiza la acción
                'historial',     // Módulo fijo
                false,           // Replace no usado
                [
                    'affected_user_id'   => $user->id,
                    'affected_user_name' => $user->name,
                    'membership_id'      => $validated['membership_id'],
                    'changed_by'         => $admin->name,
                ],
                "event_{$user->id}" // 🔹 Pathname dinámico
            );

            return $this->show($id);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }





    public function business(Request $request)
    {
        try {
            $authUser = auth()->user();
            if (!$authUser) {
                return response()->error("Usuario no autenticado", 401);
            }

            $user = User::findOrFail($authUser->id);

            // ✅ Decodificar los campos JSON si son string
            foreach (['schedule', 'gallery', 'categories', 'eventsToday', 'promotions'] as $field) {
                if (!empty($user->$field)) {
                    $user->$field = is_string($user->$field)
                        ? json_decode($user->$field, true)
                        : $user->$field;
                } else {
                    $user->$field = [];
                }
            }

            // 🔹 Hero → podrías hacerlo dinámico si guardas cover/imagen en BD
            $hero = [
                'image' => $user->cover ? url($user->cover) : url('images/bg-bar.jpg'),
                'title' => $user->name ?? 'Negocio',
            ];

            // 🔹 Datos de negocio base
            $business = [
                'name'        => $user->name ?? null,
                'company_name'=> $user->company_name ?? null,
                'address'     => $user->address ?? null,
                'phone'       => $user->phone_number ?? null,
                'whatsapp'    => $user->whatsapp_link ?? null,
                'description' => $user->description ?? null,
            ];

            // 🔹 Galería, categorías, eventos y promociones
            $gallery     = $user->gallery ?? [];
            $categories  = $user->categories ?? [];

            

            $eventsToday1 = \App\Models\Events::where('user_id', $user->id)
                ->where('type', 'event')
                ->get()
                ->filter(function ($event) {
                    if (!$event->duracion) {
                        return false;
                    }

                    // Ejemplo: "02 Oct. 16:55 - 24 Oct. 16:55"
                    $parts = explode('-', $event->duracion);
                    if (count($parts) !== 2) {
                        return false;
                    }

                    try {
                        $start = Carbon::parse(trim($parts[0]));
                        $end   = Carbon::parse(trim($parts[1]));
                    } catch (\Exception $e) {
                        return false; // no se pudo parsear
                    }

                    return now()->between($start, $end);
                })
                ->values(); // reindexar colección


            $eventsToday2   =   \App\Models\Events::where('user_id', $user->id)->where("type","promotion")->get();

            $eventsToday    =   $eventsToday1 ?? [];
            $promotions     =   $eventsToday2 ?? [];



            $data = [
                'hero'        => $hero,
                'business'    => $business,
                'gallery'     => $gallery,
                'categories'  => $categories,
                'eventsToday' => $eventsToday,
                'promotions'  => $promotions,
            ];

            return response()->success($data, 'Perfil del negocio cargado con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * GET /dashboard/business/show
     */

    public function businessOLD(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) return response()->error("Usuario no autenticado", 401);

            $user = User::findOrFail($user->id);

            foreach (['schedule', 'gallery', 'categories', 'eventsToday', 'promotions'] as $field) {
                if (!empty($user->$field)) {
                    // ✅ Decodificar solo si es string JSON
                    if (is_string($user->$field)) {
                        $user->$field = json_decode($user->$field, true);
                    }
                    // Si ya es array, lo dejamos tal cual
                } else {
                    $user->$field = null;
                }
            }

            

            return response()->success(compact('user'), 'Perfil del negocio cargado con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

}
