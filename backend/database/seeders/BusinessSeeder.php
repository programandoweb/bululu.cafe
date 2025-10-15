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
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        $businesses = [
            /*
            [
                'name'            => 'Salón de Eventos La Estancia',
                'description'     => 'Amplio y elegante salón con decoración temática para quinceañeras',
                'price'           => 2500000,
                'unit'            => 'unitario',
                'contact_phone'   => '3001234567',
                'contact_email'   => 'contacto@laestancia.com',
                'whatsapp_link'   => 'https://wa.me/573001234567',
                'location'        => 'Bogotá, Colombia',
                'allow_comments'  => true,
                'allow_location'  => true,
                'category_id'     => 1,
            ],
            */
            [
                'name'            => 'Delicias Gourmet',
                'description'     => 'Buffet de alimentos y bebidas premium para fiestas de quince años',
                'price'           => 1800000,
                'unit'            => 'unitario',
                'contact_phone'   => '3109876543',
                'contact_email'   => 'delicias@gourmet.com',
                'whatsapp_link'   => 'https://wa.me/573109876543',
                'location'        => 'Medellín, Colombia',
                'allow_comments'  => true,
                'allow_location'  => true,
                'category_id'     => 2,
            ],
            [
                'name'            => 'DJ Quince Beats',
                'description'     => 'DJ en vivo, sonido profesional y luces para animar la fiesta',
                'price'           => 1200000,
                'unit'            => 'unitario',
                'contact_phone'   => '3205556789',
                'contact_email'   => 'beats@quince.com',
                'whatsapp_link'   => 'https://wa.me/573205556789',
                'location'        => 'Cali, Colombia',
                'allow_comments'  => true,
                'allow_location'  => true,
                'category_id'     => 3,
            ],
            [
                'name'            => 'Florería Rosabella',
                'description'     => 'Arreglos florales y centros de mesa personalizados para el evento',
                'price'           => 700000,
                'unit'            => 'unitario',
                'contact_phone'   => '3009998877',
                'contact_email'   => 'ventas@rosabella.com',
                'whatsapp_link'   => 'https://wa.me/573009998877',
                'location'        => 'Cartagena, Colombia',
                'allow_comments'  => true,
                'allow_location'  => true,
                'category_id'     => 4,
            ],
            [
                'name'            => 'Flash Memories',
                'description'     => 'Fotografía y video profesional para inmortalizar cada momento',
                'price'           => 1500000,
                'unit'            => 'unitario',
                'contact_phone'   => '3011237890',
                'contact_email'   => 'flash@memories.com',
                'whatsapp_link'   => 'https://wa.me/573011237890',
                'location'        => 'Barranquilla, Colombia',
                'allow_comments'  => true,
                'allow_location'  => true,
                'category_id'     => 5,
            ],
        ];

        $role = Role::where('name', 'providers')->where('guard_name', 'api')->first();

        foreach ($businesses as $data) {
            // Crear usuario
            $user = User::firstOrCreate(
                ['email' => Str::slug($data['name'], '_') . '@proveedores.com'],
                [
                    'name'     => $data['name'],
                    'password' => \Hash::make('password'),
                ]
            );

            if ($role) {
                $user->assignRole($role);
            }

            // Insertar negocio
            $businessId = DB::table('businesses')->insertGetId([
                'user_id'         => $user->id,
                'name'            => $data['name'],
                'description'     => $data['description'],
                'price'           => $data['price'],
                'unit'            => $data['unit'],
                'is_active'       => true,
                'contact_phone'   => $data['contact_phone'],
                'contact_email'   => $data['contact_email'],
                'whatsapp_link'   => $data['whatsapp_link'],
                'location'        => $data['location'],
                'allow_comments'  => $data['allow_comments'],
                'allow_location'  => $data['allow_location'],
                'category_id'     => $data['category_id'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Crear 2 cupones para el negocio
            DB::table('coupons')->insert([
                [
                    'business_id' => $businessId,
                    'title'       => '10% de descuento',
                    'code'        => strtoupper(Str::random(6)),
                    'type'        => 'percentage',
                    'value'       => 10,
                    'expires_at'  => now()->addMonths(3),
                    'used_count'  => rand(0, 15),
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'business_id' => $businessId,
                    'title'       => '$50.000 descuento por reserva',
                    'code'        => strtoupper(Str::random(6)),
                    'type'        => 'fixed',
                    'value'       => 50000,
                    'expires_at'  => now()->addMonths(6),
                    'used_count'  => rand(0, 10),
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }
    }
}
