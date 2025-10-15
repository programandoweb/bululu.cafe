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
use Illuminate\Support\Facades\DB;

class MembershipsSeeder extends Seeder
{
    public function run()
    {
        return;
        DB::table('memberships')->insert([
            [
                'label' => 'Estandar',
                'price' => 0.00,
                'features' => json_encode([
                    'Publicar eventos',
                    'Publicar promociones',
                    'Notificaciones',
                    'Gestión y aprendizaje',
                    'Visualización',
                ]),
                'icon' => 'flash-outline',
                'options' => json_encode([
                    'color' => '#a855f7',
                    'borderColor' => '#a855f7',
                ]),
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Premium',
                'price' => 29.99,
                'features' => json_encode([
                    'Publicar eventos',
                    'Publicar promociones',
                    'Notificaciones',
                    'Gestión y aprendizaje',
                    'Visualización',
                ]),
                'icon' => 'crown-outline',
                'options' => json_encode([
                    'color' => '#3b82f6',
                    'borderColor' => '#3b82f6',
                ]),
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Luxury',
                'price' => 59.99,
                'features' => json_encode([
                    'Publicar eventos',
                    'Publicar promociones',
                    'Notificaciones',
                    'Gestión y aprendizaje',
                    'Visualización',
                ]),
                'icon' => 'diamond-outline',
                'options' => json_encode([
                    'color' => '#a855f7',
                    'borderColor' => '#a855f7',
                ]),
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
