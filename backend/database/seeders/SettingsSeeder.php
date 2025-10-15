<?php
/**
 * ---------------------------------------------------
 * Desarrollado por: Jorge Méndez - Programandoweb
 * Correo: lic.jorgemendez@gmail.com
 * Celular: 3115000926
 * website: Programandoweb.net
 * Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // MENSAJE ORIGINAL DE REGISTRO ENVIADO
        DB::table('master_tables')->insert([
            'label'       => 'Solicitud de registro enviada',
            'grupo'       => 'email_register_business',
            'description' => 'Como parte de nuestras políticas para admisión de empresas, vamos a estudiar su caso y le daremos respuesta en los próximos días',
            'value'       => null,
            'options'     => null,
            'medida_id'   => null,
            'bool_status' => 1,
            'icon'        => null,
            ],[
                'label'       => '¡Bienvenido/a a Farrea!',
                'grupo'       => 'email_accept_user',
                'description' => 'Para nosotros es un placer darte la bienvenida a Farrea, tu solicitud ha sido aceptada, puedes iniciar sesión con tu correo electrónico y la clave previamente seleccionada.',
                'value'       => null, 'options' => null, 'medida_id' => null, 'bool_status' => 1, 'icon' => null,
            ],[
                'label'       => 'Resolución sobre tu solicitud en Farrea',
                'grupo'       => 'email_reject_user',
                'description' => 'Estimado/a [Nombre de usuario], te escribimos para informarte que, tras una cuidadosa revisión, hemos determinado que tu solicitud no cumple con las políticas de nuestra comunidad y no podemos aceptarla. Agradecemos tu interés.',
                'value'       => null, 'options' => null, 'medida_id' => null, 'bool_status' => 1, 'icon' => null,
            ],[
                'label'       => 'Confirmación de desactivación de cuenta',
                'grupo'       => 'email_deactivate_user',
                'description' => 'Hola [Nombre de usuario], te confirmamos que tu cuenta ha sido desactivada correctamente. Tu perfil ya no está visible. Si decides volver, puedes reactivarla iniciando sesión en los próximos 30 días. Esperamos verte de nuevo.',
                'value'       => null, 'options' => null, 'medida_id' => null, 'bool_status' => 1, 'icon' => null,
            ],

        );        
    }
}