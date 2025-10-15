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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Events;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;

class EventsSeeder extends Seeder
{
    public function run()
    {
        // Obtener el rol "providers" bajo el guard "api"
        $roleApi = Role::where('name', 'providers')
            ->where('guard_name', 'api')
            ->first();

        if (!$roleApi) {
            $this->command->warn('Rol "providers" con guard "api" no encontrado.');
            return;
        }

        // Obtener todos los usuarios con ese rol
        $providers = User::whereHas('roles', function ($q) use ($roleApi) {
            $q->where('role_id', $roleApi->id);
        })->get();

        if ($providers->isEmpty()) {
            $this->command->warn('No hay usuarios con rol providers disponibles.');
            return;
        }

        foreach ($providers as $provider) {
            // 🔹 Crear eventos
            for ($i = 0; $i < 5; $i++) {
                Events::create([
                    'user_id'    => $provider->id,
                    'type'       => 'event',
                    'nombre'     => 'Evento de ' . $provider->name . ' #' . ($i + 1),
                    'duracion'   => Carbon::now()
                        ->addDays(rand(0, 30))
                        ->format('d M. H:i') . ' - ' .
                        Carbon::now()
                        ->addDays(rand(1, 35))
                        ->format('d M. H:i'),
                    'descripcion'=> 'Este es un evento de prueba generado automáticamente para ' . $provider->name,
                    'portada'    => 'https://picsum.photos/seed/event' . rand(1, 9999) . '/800/600',
                    'galeria'    => json_encode([
                        'https://picsum.photos/seed/gallery' . rand(1, 9999) . '/800/600'
                    ], JSON_UNESCAPED_UNICODE),
                    'artistas'   => json_encode([
                        'https://picsum.photos/seed/artist' . rand(1, 9999) . '/400/400'
                    ], JSON_UNESCAPED_UNICODE),
                    'categories' => json_encode(['General', 'Música'], JSON_UNESCAPED_UNICODE),
                    'publication_status' => 'published',
                ]);
            }

            // 🔹 Crear promociones
            for ($i = 0; $i < 5; $i++) {
                Events::create([
                    'user_id'    => $provider->id,
                    'type'       => 'promotion',
                    'nombre'     => 'Promoción de ' . $provider->name . ' #' . ($i + 1),
                    'descripcion'=> 'Esta es una promoción de prueba generada automáticamente para ' . $provider->name,
                    'portada'    => 'https://picsum.photos/seed/promo' . rand(1, 9999) . '/800/600',
                    'galeria'    => json_encode([
                        'https://picsum.photos/seed/gallery' . rand(1, 9999) . '/800/600'
                    ], JSON_UNESCAPED_UNICODE),
                    'artistas'   => json_encode([], JSON_UNESCAPED_UNICODE),
                    'categories' => json_encode(['Promoción'], JSON_UNESCAPED_UNICODE),
                    'publication_status' => 'published',
                ]);
            }
        }

        $this->command->info('✅ Eventos y promociones de prueba creados correctamente para los providers.');
    }
}
