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

class CommentsUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'mensaje'   => 'El usuario presenta comportamiento inapropiado en el chat general.',
                'type'      => 'Reporte de Usuario',
                'module'    => 'Usuarios',
                'pathname'  => '/usuarios/chat',
                'json'      => json_encode(['reported_user_id' => 8, 'reason' => 'lenguaje ofensivo']),
                'user_id'   => 2,
            ],
            [
                'mensaje'   => 'Se detectaron múltiples cuentas con el mismo correo electrónico.',
                'type'      => 'Reporte de Usuario',
                'module'    => 'Autenticación',
                'pathname'  => '/auth/register',
                'json'      => json_encode(['duplicate_emails' => true]),
                'user_id'   => 1,
            ],
            [
                'mensaje'   => 'El usuario ha realizado pagos falsos adjuntando comprobantes modificados.',
                'type'      => 'Reporte de Usuario',
                'module'    => 'Pagos',
                'pathname'  => '/pagos/verificacion',
                'json'      => json_encode(['reported_user_id' => 5, 'evidence' => 'fake_receipt']),
                'user_id'   => 3,
            ],
            [
                'mensaje'   => 'Actividad sospechosa detectada: múltiples inicios de sesión desde diferentes ubicaciones.',
                'type'      => 'Reporte de Usuario',
                'module'    => 'Seguridad',
                'pathname'  => '/usuarios/logs',
                'json'      => json_encode(['reported_user_id' => 7, 'ips' => ['181.50.1.23', '190.85.4.17']]),
                'user_id'   => 4,
            ],
            [
                'mensaje'   => 'El usuario comparte información falsa en comentarios públicos.',
                'type'      => 'Reporte de Usuario',
                'module'    => 'Comentarios',
                'pathname'  => '/comentarios/publicos',
                'json'      => json_encode(['reported_user_id' => 9, 'evidence' => 'spam content']),
                'user_id'   => 6,
            ],
        ]);
    }
}
