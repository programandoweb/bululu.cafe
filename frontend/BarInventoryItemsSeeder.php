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
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Products;
use App\Models\ProductsStock;
use App\Models\Servicios;
use App\Models\ProductCategory;
use App\Models\ProductsItem; // ⬅️ IMPORTANTE

class BarInventoryItemsSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =========================================================
         * 1. CREAR UNIDADES Y MATERIAS PRIMAS
         * =========================================================
         */
        $categoryId = DB::table('inventory_categories')->insertGetId([
            'name'       => 'General',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $units = [
            ['code' => 'g',  'name' => 'Gramo',     'ratio_to_base' => 0.001],
            ['code' => 'kg', 'name' => 'Kilogramo', 'ratio_to_base' => 1],
            ['code' => 'ml', 'name' => 'Mililitro', 'ratio_to_base' => 0.001],
            ['code' => 'l',  'name' => 'Litro',     'ratio_to_base' => 1],
            ['code' => 'ud', 'name' => 'Unidad',    'ratio_to_base' => 1],
        ];

        foreach ($units as $unit) {
            DB::table('units')->updateOrInsert(
                ['code' => $unit['code']],
                [
                    'name'          => $unit['name'],
                    'ratio_to_base' => $unit['ratio_to_base'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }

        $units = DB::table('units')->pluck('id', 'code');

        // Materias primas
        $inventoryItems = [
            ['sku' => 'BEV-CER-001', 'name' => 'Cerveza en Botella 330ml', 'unit' => 'ud', 'stock' => 200, 'avg_cost' => 2500],
            ['sku' => 'BEV-AGU-021', 'name' => 'Agua Mineral Botella 600ml','unit' => 'ud','stock' => 100,'avg_cost' => 1800],
            ['sku' => 'MIX-LIMON-007','name' => 'Jugo de Limón',     'unit' => 'ml', 'stock' => 3000, 'avg_cost' => 30],
            ['sku' => 'MIX-SAL-010',  'name' => 'Sal para Margaritas','unit' => 'g', 'stock' => 500,  'avg_cost' => 15],
            ['sku' => 'MIX-PIÑA-006', 'name' => 'Pulpa Piña Colada', 'unit' => 'ml', 'stock' => 5000, 'avg_cost' => 45],
            ['sku' => 'MIX-CRE-013', 'name' => 'Crema de Leche',     'unit' => 'ml', 'stock' => 2000, 'avg_cost' => 35],
            ['sku' => 'MIX-HIER-008', 'name' => 'Hierbabuena',       'unit' => 'g',  'stock' => 800,  'avg_cost' => 60],
            ['sku' => 'MIX-AZU-009',  'name' => 'Azúcar Blanca',     'unit' => 'g',  'stock' => 2000, 'avg_cost' => 12],
        ];

        foreach ($inventoryItems as $item) {
            DB::table('inventory_items')->insert([
                'sku'                     => $item['sku'],
                'name'                    => $item['name'],
                'inventory_categories_id' => $categoryId,
                'base_unit_id'            => $units[$item['unit']] ?? null,
                'stock'                   => $item['stock'],
                'avg_cost'                => $item['avg_cost'],
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);
        }

        // ⬇️ Mapear insumos
        $invByName = DB::table('inventory_items')->pluck('id', 'name')->toArray();
        $beerItemId     = $invByName['Cerveza en Botella 330ml']    ?? null;
        $aguaItemId     = $invByName['Agua Mineral Botella 600ml']  ?? null;
        $limonItemId    = $invByName['Jugo de Limón']               ?? null;
        $salItemId      = $invByName['Sal para Margaritas']         ?? null;
        $pinaColadaId   = $invByName['Pulpa Piña Colada']           ?? null;
        $cremaLecheId   = $invByName['Crema de Leche']              ?? null;
        $hierbabuenaId  = $invByName['Hierbabuena']                 ?? null;
        $azucarBlancaId = $invByName['Azúcar Blanca']               ?? null;

        $user = User::factory()->create([
            'name'     => 'Proveedor Bar',
            'email'    => 'proveedor@bar.com',
            'password' => bcrypt('12345678'),
        ]);

        $products = [
            ['Cerveza grande (1 lt)', '', 'Cervezas'],
            ['Corona margarita', '', 'Cócteles'],
            ['Piña colada', '', 'Cócteles'],
            ['Mojito cubano', '', 'Cócteles'],
            ['Botella de agua', '', 'Sin Alcohol'],
        ];

        foreach ($products as [$name, $model, $categoryName]) {
            $category = ProductCategory::firstOrCreate(['name' => $categoryName]);

            $servicio = Servicios::create([
                'user_id'             => $user->id,
                'name'                => $name,
                'description'         => $model,
                'type'                => 'products',
                'product_category_id' => $category->id,
            ]);

            $product = Products::create([
                'name'                  => $name,
                'barcode'               => strtoupper(Str::random(12)),
                'brand'                 => 'Bar House',
                'measure_unit'          => 'unit',
                'measure_quantity'      => 1,
                'short_description'     => $model,
                'long_description'      => 'Producto: ' . $name . ' - ' . $model,
                'stock_control'         => true,
                'stock_current'         => 0,
                'stock_alert_level'     => 5,
                'stock_reorder_amount'  => 10,
                'model'                 => $model,
                'sku'                   => strtoupper(Str::random(10)),
                'stock'                 => 0,
                'min_stock'             => 5,
                'price'                 => 10000,
                'cost'                  => 5000,
                'provider_id'           => $user->id,
                'product_category_id'   => $category->id,
            ]);

            $stockQty = rand(10, 50);

            // Recetas
            $nameLower = Str::lower($name);

            $addIngredient = function (?int $inventoryItemId, int $qty) use ($product) {
                if ($inventoryItemId && $qty > 0) {
                    ProductsItem::create([
                        'product_id'          => $product->id,
                        'inventory_items_id'  => $inventoryItemId,
                        'quantity'            => $qty,
                    ]);
                }
            };

            if (Str::contains($nameLower, 'cerveza') && $beerItemId) {
                $beerQty = 1;
                if (Str::contains($nameLower, ['1 lt', '1lt', 'grande'])) {
                    $beerQty = 3;
                }
                $addIngredient($beerItemId, $beerQty);
            }

            if (Str::contains($nameLower, 'botella de agua')) {
                $addIngredient($aguaItemId, 1);
            }

            if (Str::contains($nameLower, 'corona margarita')) {
                $addIngredient($beerItemId, 1);
                $addIngredient($limonItemId, 50);
                $addIngredient($salItemId,   5);
            }

            if (Str::contains($nameLower, 'piña colada')) {
                $addIngredient($pinaColadaId, 150);
                $addIngredient($cremaLecheId, 50);
            }

            if (Str::contains($nameLower, 'mojito cubano')) {
                $addIngredient($limonItemId,    30);
                $addIngredient($azucarBlancaId, 10);
                $addIngredient($hierbabuenaId,  10);
            }

            ProductsStock::create([
                'product_id' => $product->id,
                'type'       => 'entrada',
                'quantity'   => $stockQty,
                'note'       => 'Carga inicial bebidas/cócteles',
                'stocked_at' => now(),
            ]);

            $product->update([
                'stock'         => $stockQty,
                'stock_current' => $stockQty,
            ]);
        }

        $this->command->info('Seeder ejecutado con recetas de productos vinculadas a insumos.');
    }
}
