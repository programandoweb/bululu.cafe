<?php
/**
 * ---------------------------------------------------
 *  Developed by: Jorge Méndez - Programandoweb
 *  Email: lic.jorgemendez@gmail.com
 *  Phone: 3115000926
 *  Website: Programandoweb.net
 *  Project: Ivoolve
 * ---------------------------------------------------
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'label'       => 'Standard',
                'price'       => 0.00,
                'icon'        => 'flash-outline',
                'options'     => json_encode([
                    'color'       => '#a855f7',
                    'borderColor' => '#a855f7',
                ]),
                'is_current'  => true,

                // cuantificables
                'photos_limit'       => 5,
                'events_per_month'   => 2,
                'promotions_limit'   => 2,
                'push_notifications' => 0,

                // booleanos
                'profile_included'     => true,
                'branding_support'     => true,
                'map_visibility'       => true,
                'priority_support'     => false,
                'enhanced_positioning' => false,

                // extras
                'features'    => json_encode([
                    'Perfil del establecimiento',
                    'Branding y asesoría',
                    '5 fotos del lugar',
                    'Hasta 2 eventos por mes',
                    '2 promociones',
                    'Visibilidad mapa y búsqueda',
                    'Soporte técnico',
                ]),
            ],
            [
                'label'       => 'Premium',
                'price'       => 29.99,
                'icon'        => 'crown-outline',
                'options'     => json_encode([
                    'color'       => '#3b82f6',
                    'borderColor' => '#3b82f6',
                ]),
                'is_current'  => false,

                // cuantificables
                'photos_limit'       => 8,
                'events_per_month'   => 4,
                'promotions_limit'   => 4,
                'push_notifications' => 2,

                // booleanos
                'profile_included'     => true,
                'branding_support'     => true,
                'map_visibility'       => true,
                'priority_support'     => true,
                'enhanced_positioning' => false,

                // extras
                'features'    => json_encode([
                    'Perfil del establecimiento',
                    'Branding y asesoría',
                    '8 fotos del lugar',
                    'Hasta 4 eventos por mes',
                    '4 promociones',
                    'Soporte preferencial',
                    'Notificaciones push 2',
                ]),
            ],
            [
                'label'       => 'Luxury',
                'price'       => 59.99,
                'icon'        => 'diamond-outline',
                'options'     => json_encode([
                    'color'       => '#a855f7',
                    'borderColor' => '#a855f7',
                ]),
                'is_current'  => false,

                // cuantificables
                'photos_limit'       => 12,
                'events_per_month'   => 6,
                'promotions_limit'   => 6,
                'push_notifications' => 4,

                // booleanos
                'profile_included'     => true,
                'branding_support'     => true,
                'map_visibility'       => true,
                'priority_support'     => true,
                'enhanced_positioning' => true,

                // extras
                'features'    => json_encode([
                    'Perfil del establecimiento',
                    'Branding y asesoría',
                    '12 fotos del lugar',
                    'Hasta 6 eventos por mes',
                    '6 promociones',
                    'Posicionamiento mejorado en la app (condicional)',
                    'Soporte mejorado',
                    'Notificaciones push 4',
                ]),
            ],
        ];

        DB::table('memberships')->insert($plans);
    }
}
