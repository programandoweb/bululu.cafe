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
use Illuminate\Support\Facades\DB;

class CommentsSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'mensaje'   => 'El sistema no carga correctamente el módulo de facturación. Solicito revisión.',
                'type'      => 'Soporte',
                'module'    => 'Facturación',
                'pathname'  => '/dashboard/facturacion',
                'json'      => json_encode(['error' => 'timeout', 'status' => 500]),
                'user_id'   => 1,
            ],
            [
                'mensaje'   => 'No se pueden subir imágenes en el perfil del usuario.',
                'type'      => 'Soporte',
                'module'    => 'Usuarios',
                'pathname'  => '/perfil',
                'json'      => json_encode(['action' => 'upload', 'error' => 'invalid format']),
                'user_id'   => 2,
            ],
            [
                'mensaje'   => 'Error al intentar registrar nuevos servicios. Aparece mensaje de validación.',
                'type'      => 'Soporte',
                'module'    => 'Servicios',
                'pathname'  => '/servicios/create',
                'json'      => json_encode(['field' => 'name', 'error' => 'required']),
                'user_id'   => 3,
            ],
            [
                'mensaje'   => 'La página de inicio tarda mucho en cargar después del login.',
                'type'      => 'Soporte',
                'module'    => 'Dashboard',
                'pathname'  => '/dashboard',
                'json'      => json_encode(['latency' => '8s']),
                'user_id'   => 4,
            ],
            [
                'mensaje'   => 'Al generar reportes de inventario se produce un error de permisos.',
                'type'      => 'Soporte',
                'module'    => 'Inventario',
                'pathname'  => '/inventario/reportes',
                'json'      => json_encode(['permission' => 'denied']),
                'user_id'   => 5,
            ],
        ]);
    }
}
