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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Events;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        set_time_limit(600);

        $permissions = [
            'home_index',

            // Eventos
            'create_events', 'read_events', 'update_events', 'delete_events',

            // Invitados
            'create_guests', 'read_guests', 'update_guests', 'delete_guests',

            // Proveedores
            'create_providers', 'read_providers', 'update_providers', 'delete_providers',

            // Presupuestos
            'create_budget', 'read_budget', 'update_budget', 'delete_budget',

            // Tareas
            'create_tasks', 'read_tasks', 'update_tasks', 'delete_tasks',

            // Calendario
            'create_calendar', 'read_calendar', 'update_calendar', 'delete_calendar',

            // Invitaciones
            'create_invitations', 'read_invitations', 'update_invitations', 'delete_invitations',

            // Órdenes
            'create_orders', 'read_orders', 'update_orders', 'delete_orders',

            // Pagos
            'create_payments', 'read_payments', 'update_payments', 'delete_payments',

            // Notificaciones
            'create_notifications', 'read_notifications', 'update_notifications', 'delete_notifications',

            // Empleados
            'create_employees', 'read_employees', 'update_employees', 'delete_employees',

            // Membresías
            'create_memberships', 'read_memberships', 'update_memberships', 'delete_memberships',

            // Comentarios
            'read_comments',

            // Configuración
            'read_settings', 'update_settings',

            // Catálogos
            'read_services', 'read_products', 'read_categories',

            // Dev
            'dev_access',
        ];

        // 🔹 Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'api',
            ]);
        }

        $roles = Role::all()->pluck('name');

        // 🔹 Asignación de permisos por rol
        foreach ($roles as $roleName) {
            $roleApi = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);

            if (in_array($roleName, ['super-admin', 'admin'])) {
                $roleApi->syncPermissions(Permission::where('guard_name', 'api')->get());
            } elseif (in_array($roleName, ['providers', 'managers'])) {
                $roleApi->syncPermissions([
                    'create_events', 'read_events', 'update_events', 'delete_events',
                    'create_guests', 'read_guests', 'update_guests', 'delete_guests',
                    'create_budget', 'read_budget', 'update_budget', 'delete_budget',
                    'create_tasks', 'read_tasks', 'update_tasks', 'delete_tasks',
                    'create_calendar', 'read_calendar', 'update_calendar', 'delete_calendar',
                    'read_orders', 'update_orders',
                    'create_payments', 'read_payments', 'update_payments',
                    'create_notifications', 'read_notifications', 'update_notifications',
                    'create_employees', 'read_employees', 'update_employees',
                    'read_settings',
                    'read_services', 'read_products', 'read_categories',
                    'read_memberships',
                    'read_comments',
                ]);
            } elseif ($roleName === 'employees') {
                $roleApi->syncPermissions([
                    'read_events', 'update_events',
                    'read_guests', 'update_guests',
                    'read_budget',
                    'read_tasks', 'update_tasks',
                    'read_orders',
                    'read_payments',
                    'read_notifications',
                    'read_services', 'read_products', 'read_categories',
                    'read_memberships',
                    'read_comments',
                ]);
            } elseif ($roleName === 'clients') {
                $roleApi->syncPermissions([
                    'home_index',
                    'read_events',
                    'read_calendar',
                    'read_orders',
                    'read_payments',
                    'read_notifications',
                    'read_memberships',
                    'read_comments',
                ]);
            }
        }

        // 🔹 Crear usuarios de prueba
        foreach ($roles as $roleName) {
            $user = User::firstOrCreate([
                'name'     => ucfirst($roleName) . ' Jorge Méndez',
                'email'    => Str::slug($roleName) . '@programandoweb.net',
                'password' => Hash::make('password'),
                'status'   => 'activo',
            ]);

            $roleApi = Role::where('name', $roleName)->where('guard_name', 'api')->first();
            if ($roleApi) {
                $user->assignRole($roleApi);
            }
        }

        // 🔹 Usuarios adicionales
        $additionalUsers = [
            ['name' => 'Carlos Castillo', 'email' => 'carloscastillo@programandoweb.net', 'role' => 'providers'],
            ['name' => 'Cliente para fiesta', 'email' => 'cliente@programandoweb.net', 'role' => 'clients'],
        ];

        foreach ($additionalUsers as $data) {
            $user = User::firstOrCreate([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'status'   => 'activo',
            ]);

            $roleApi = Role::where('name', $data['role'])->where('guard_name', 'api')->first();
            if ($roleApi) {
                $user->assignRole($roleApi);
            }

            // 🔹 Crear eventos por defecto para el proveedor
            if ($data['role'] === 'providers') {
                for ($i = 0; $i < 5; $i++) {
                    Events::firstOrCreate([
                        'user_id'           => $user->id,
                        'type'              => 'event',
                        'nombre'            => 'Evento de lanzamiento #' . ($i + 1),
                        'duracion'          => '1 día',
                        'fecha_evento'      => now()->addDays(5),
                        'descripcion'       => 'Evento inicial de demostración creado por defecto para el proveedor.',
                        'portada'           => 'https://picsum.photos/1200',
                        'galeria'           => json_encode([]),
                        'artistas'          => json_encode([]),
                        'categories'        => json_encode(['Demo']),
                        'publication_status'=> 'published',
                    ]);
                }

                for ($i = 0; $i < 5; $i++) {
                    Events::firstOrCreate([
                        'user_id'           => $user->id,
                        'type'              => 'promotion',
                        'nombre'            => 'Promoción de lanzamiento #' . ($i + 1),
                        'duracion'          => '2 Horas',
                        'fecha_evento'      => now()->addDays(5),
                        'descripcion'       => 'Evento inicial de demostración creado por defecto para el proveedor.',
                        'portada'           => 'https://picsum.photos/1200',
                        'galeria'           => json_encode([]),
                        'artistas'          => json_encode([]),
                        'categories'        => json_encode(['Demo']),
                        'publication_status'=> 'published',
                    ]);
                }
            }
        }
    }
}
