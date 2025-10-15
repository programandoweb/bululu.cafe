<?php

namespace App\Http\Controllers\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserGalery;
use App\Models\Memberships;
use App\Models\UserMembership;

class UsersController extends Controller
{   
    /**
     * GET /dashboard/requests
     */
    public function requests(Request $request)
    {
        try {
            // 🔹 Total de solicitudes pendientes
            $requestsTotal = User::where('status', 'solicitud')->count();

            // 🔹 Total de membresías activas (vigentes)
            $membershipsTotal = User::whereHas('activeMembership')->count();

            // 🔹 Total de membresías vencidas (end_date menor a hoy)
            $expiredMembershipsTotal = \App\Models\UserMembership::whereNotNull('end_date')
                ->where('end_date', '<', now())
                ->count();

            // 🔹 Total de comentarios con estado "pending"
            $pendingCommentsTotal = \App\Models\Comments::where('status', 'pending')->count();

            return response()->success([
                'requests_total'          => $requestsTotal,
                'memberships_total'       => $membershipsTotal,
                'memberships_expired'     => $expiredMembershipsTotal,
                'comments_pending_total'  => $pendingCommentsTotal,
            ], 'Totales de solicitudes, membresías y comentarios pendientes cargados con éxito.');

        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }



    public function requestsOLD(Request $request)
    {
        try {
            // 🔹 Usuarios en estado "solicitud"
            $users = User::query()
                ->where('status', 'solicitud')
                ->select('id', 'name', 'email', 'phone_number', 'status', 'created_at')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($u) {
                    return [
                        'id'         => $u->id,
                        'name'       => $u->name,
                        'email'      => $u->email,
                        'phone'      => $u->phone_number,
                        'status'     => $u->status,
                        'created_at' => $u->created_at?->format('Y-m-d H:i:s'),
                    ];
                });

            // 🔹 Usuarios con membresías activas y vigentes
            $memberships = User::query()
                ->whereHas('activeMembership')
                ->with(['activeMembership.membership' => function ($q) {
                    $q->select(
                        'id',
                        'label',
                        'price',
                        'icon',
                        'photos_limit',
                        'events_per_month',
                        'promotions_limit',
                        'push_notifications',
                        'profile_included',
                        'branding_support',
                        'map_visibility',
                        'priority_support',
                        'enhanced_positioning'
                    );
                }])
                ->select('id', 'name', 'email', 'phone_number', 'status')
                ->orderBy('name')
                ->get()
                ->map(function ($u) {
                    $membership = $u->activeMembership?->membership;
                    return [
                        'id'                   => $u->id,
                        'name'                 => $u->name,
                        'email'                => $u->email,
                        'phone'                => $u->phone_number,
                        'membership_id'        => $membership?->id,
                        'membership_label'     => $membership?->label ?? '—',
                        'membership_price'     => $membership?->price,
                        'membership_icon'      => $membership?->icon,
                        'membership_start' => $u->activeMembership?->start_date
                            ? date('Y-m-d', strtotime($u->activeMembership->start_date))
                            : null,

                        'membership_end' => $u->activeMembership?->end_date
                            ? date('Y-m-d', strtotime($u->activeMembership->end_date))
                            : null,
                        'photos_limit'         => $membership?->photos_limit,
                        'events_per_month'     => $membership?->events_per_month,
                        'promotions_limit'     => $membership?->promotions_limit,
                        'push_notifications'   => $membership?->push_notifications,
                        'priority_support'     => $membership?->priority_support,
                        'map_visibility'       => $membership?->map_visibility,
                        'branding_support'     => $membership?->branding_support,
                        'enhanced_positioning' => $membership?->enhanced_positioning,
                        'status'               => $u->status,
                    ];
                });

            return response()->success([
                'requests'    => $users,
                'memberships' => $memberships,
            ], 'Solicitudes y membresías cargadas con éxito.');

        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }



    /**
     * GET /open/memberships
     */
    public function memberships(Request $request)
    {
        try {
            $plans = Memberships::query()
                ->where('is_current', true)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'label'      => $item->label,
                        'price'      => $item->price,
                        'features'   => $item->features ?? [],
                        'icon'       => $item->icon,
                        'options'    => $item->options ?? [],
                        'is_current' => $item->is_current,
                    ];
                });

            return response()->success($plans, 'Membresías cargadas con éxito.');
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * PUT /users/update
     */
    public function user_update(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) return response()->error("Usuario no autenticado", 401);

            $validated = $request->validate([
                'name'                  => 'sometimes|string|max:255',
                'company_name'          => 'nullable|string|max:255',
                'email'                 => 'sometimes|email|unique:users,email,' . $user->id,
                'phone_number'          => 'nullable|string|max:20',
                'address'               => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:255',
                'state'                 => 'nullable|string|max:255',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:5',
                'identification_type'   => 'nullable|in:cedula_ciudadania,cedula_extrajeria,nit,pasaporte,otro',
                'identification_number' => 'nullable|string|max:50|unique:users,identification_number,' . $user->id,
                'image'                 => 'nullable|string|max:255',
                'cover'                 => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            foreach (['image','cover'] as $field) {
                if (isset($validated[$field]) && str_starts_with($validated[$field], url('/'))) {
                    $validated[$field] = str_replace(url('/') . '/', '', $validated[$field]);
                }
            }

            $user->fill($validated);
            $user->save();

            DB::commit();

            return response()->success(['profile' => $user], 'Perfil actualizado con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }


    /**
     * GET /business/init
     */
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
            $eventsToday = $user->eventsToday ?? [];
            $promotions  = $user->promotions ?? [];

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
     * GET /users/init
     */
    public function user_init(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) return response()->error("Usuario no autenticado", 401);

            $profile = [
                'id'                    => $user->id,
                'name'                  => $user->name,
                'company_name'          => $user->company_name,
                'image'                 => $user->image ? url($user->image) : null,
                'cover'                 => $user->cover ? url($user->cover) : null,
                'email'                 => $user->email,
                'user_type'             => $user->user_type,
                'identification_type'   => $user->identification_type,
                'identification_number' => $user->identification_number,
                'phone_number'          => $user->phone_number,
                'address'               => $user->address,
                'city'                  => $user->city,
                'state'                 => $user->state,
                'postal_code'           => $user->postal_code,
                'country'               => $user->country,
                'whatsapp_link'         => $user->whatsapp_link,
                'description'           => $user->description,
                'schedule'              => $user->schedule,
                'gallery'               => $user->gallery,
                'categories'            => $user->categories,
                'eventsToday'           => $user->eventsToday,
                'promotions'            => $user->promotions,
                'created_at'            => $user->created_at?->format('Y-m-d H:i:s'),
            ];

            return response()->success(['profile' => $profile], 'Perfil del usuario cargado con éxito.');
            
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * POST /business/update
     */
    public function business_update(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) return response()->error("Usuario no autenticado", 401);

            $validated = $request->validate([
                'name'          => 'nullable|string|max:255',
                'company_name'  => 'nullable|string|max:255',
                'address'       => 'nullable|string|max:255',
                'city'          => 'nullable|string|max:255',
                'state'         => 'nullable|string|max:255',
                'postal_code'   => 'nullable|string|max:20',
                'country'       => 'nullable|string|max:5',
                'phone_number'  => 'nullable|string|max:20',
                'description'   => 'nullable|string|max:500',
                'schedule'      => 'nullable|array',
                'gallery'       => 'nullable|array',
                'categories'    => 'nullable|array',
                'eventsToday'   => 'nullable|array',
                'promotions'    => 'nullable|array',
                'whatsapp_link' => 'nullable|string|max:255',
                'image'         => 'nullable|string|max:255',
                'cover'         => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            foreach (['image','cover'] as $field) {
                if (isset($validated[$field]) && str_starts_with($validated[$field], url('/'))) {
                    $validated[$field] = str_replace(url('/') . '/', '', $validated[$field]);
                }
            }

            $user->fill($validated);
            $user->save();

            DB::commit();

            return response()->success(compact('user'), 'Datos de negocio actualizados con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
