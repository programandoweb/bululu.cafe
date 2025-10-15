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
use App\Models\Products;
use App\Models\ProductsStock;
use App\Models\Servicios;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name'     => 'Proveedor Freshia',
            'email'    => 'proveedor@freshia.com',
            'password' => bcrypt('12345678'),
        ]);

        $items = [
            ['Vestido de 15 años', 'Modelo Gala', 'Rosa'],
            ['Corona', 'Clásica', 'Plateado'],
            ['Aretes', 'Perlas', 'Blanco'],
            ['Collar', 'Corazón', 'Dorado'],
            ['Pulcera', 'Charm', 'Fucsia'],
            ['Ramo', 'Rosas Artificiales', 'Rojo'],
            ['Muñeca u oso', 'Osito Soft', 'Beige'],
            ['Crinolina', 'Tul largo', 'Blanco'],
        ];

        foreach ($items as [$name, $model, $color]) {
            // Crear servicio principal de tipo producto
            $servicio = Servicios::create([
                'user_id'          => 10,
                'name'             => $name,
                'description'      => $model . ' en color ' . $color,
                'type'             => 'products',
                'rating'           => null,
                'image'            => null,
                'location'         => null,
                'map'              => null,
                'gallery'          => null,
                'category_id'      => null,
                'product_category_id' => null,
            ]);

            // Crear producto relacionado
            $product = Products::create([
                'servicio_id'             => $servicio->id,
                'name'                    => $name,
                'barcode'                 => strtoupper(Str::random(12)),
                'brand'                   => 'Freshia',
                'measure_unit'            => 'unit',
                'measure_quantity'        => 1,
                'short_description'       => $model,
                'long_description'        => 'Producto: ' . $name . ', modelo ' . $model . ' en color ' . $color,
                'category_name'           => 'Vestidos y accesorios',
                'stock_control'           => true,
                'stock_current'           => 0,
                'stock_alert_level'       => 5,
                'stock_reorder_amount'    => 10,
                'stock_notifications_enabled' => true,
                'model'                   => $model,
                'color'                   => $color,
                'sku'                     => strtoupper(Str::random(10)),
                'stock'                   => 0,
                'min_stock'               => 5,
                'price'                   => rand(30000, 200000) / 100,
                'provider_id'             => 10,
            ]);

            $stockQty = rand(10, 30);

            ProductsStock::create([
                'product_id' => $product->id,
                'type'       => 'entrada',
                'quantity'   => $stockQty,
                'note'       => 'Carga inicial (simulada)',
                'stocked_at' => now(),
            ]);

            $product->stock = $stockQty;
            $product->stock_current = $stockQty;
            $product->save();
        }
    }
}
