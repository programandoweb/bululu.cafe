<?php
/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular 3115000926
 *  website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTableCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'label' => 'Categorías Lugares',
                'grupo' => 'group_farrea',
                'items' => [
                    ['label' => 'Bar', 'grupo' => 'tags'],
                    ['label' => 'Discoteca', 'grupo' => 'tags'],
                    ['label' => 'Cantina', 'grupo' => 'tags'],
                    ['label' => 'Karaoke', 'grupo' => 'tags'],
                    ['label' => 'Rooftop', 'grupo' => 'tags'],
                    ['label' => 'Gastrobar', 'grupo' => 'tags'],
                    ['label' => 'Cervecería', 'grupo' => 'tags'],
                    ['label' => 'After Party', 'grupo' => 'tags'],
                ],
            ],
            [
                'label' => 'Categorías Música',
                'grupo' => 'group_farrea',
                'items' => [
                    ['label' => 'Reggaeton', 'grupo' => 'tags'],
                    ['label' => 'Electrónica', 'grupo' => 'tags'],
                    ['label' => 'Tecno', 'grupo' => 'tags'],
                    ['label' => 'Vallenato', 'grupo' => 'tags'],
                    ['label' => 'Salsa', 'grupo' => 'tags'],
                    ['label' => 'Pop', 'grupo' => 'tags'],
                    ['label' => 'Rock', 'grupo' => 'tags'],
                    ['label' => 'Dancehall / Afrobeats', 'grupo' => 'tags'],
                    ['label' => 'Crossover', 'grupo' => 'tags'],
                    ['label' => 'Popular', 'grupo' => 'tags'],
                    ['label' => 'Indie', 'grupo' => 'tags'],
                    ['label' => 'Jazz', 'grupo' => 'tags'],
                ],
            ],
            [
                'label' => 'Categorías Otras',
                'grupo' => 'group_farrea',
                'items' => [
                    ['label' => 'VIP', 'grupo' => 'tags'],
                    ['label' => 'Casual', 'grupo' => 'tags'],
                    ['label' => 'Universitario', 'grupo' => 'tags'],
                    ['label' => 'Familiar', 'grupo' => 'tags'],
                    ['label' => 'LGBTQI', 'grupo' => 'tags'],
                    ['label' => 'Urbano', 'grupo' => 'tags'],
                    ['label' => 'Parejas', 'grupo' => 'tags'],
                    ['label' => 'Cocteles', 'grupo' => 'tags'],
                    ['label' => 'Moderno', 'grupo' => 'tags'],
                    ['label' => 'Tropical', 'grupo' => 'tags'],
                    ['label' => 'Temático', 'grupo' => 'tags'],
                ],
            ],
            [
                'label' => 'Categorías por Zona (Bogotá)',
                'grupo' => 'group_farrea',
                'items' => [
                    ['label' => 'Zona T', 'grupo' => 'tags'],
                    ['label' => 'Parque la 93', 'grupo' => 'tags'],
                    ['label' => 'Modelia', 'grupo' => 'tags'],
                    ['label' => 'Galerías', 'grupo' => 'tags'],
                    ['label' => 'Chapinero', 'grupo' => 'tags'],
                    ['label' => 'Calle 85', 'grupo' => 'tags'],
                    ['label' => 'Zona Rosa', 'grupo' => 'tags'],
                    ['label' => 'Suba', 'grupo' => 'tags'],
                    ['label' => 'Usaquén', 'grupo' => 'tags'],
                    ['label' => 'Primera de Mayo', 'grupo' => 'tags'],
                    ['label' => 'Restrepo', 'grupo' => 'tags'],
                ],
            ],
        ];

        foreach ($categories as $value) {
            $groupId = DB::table('master_tables')->insertGetId([
                'label' => $value['label'],
                'grupo' => $value['grupo'],
            ]);

            foreach ($value['items'] as $item) {
                DB::table('master_tables')->insert([
                    'label' => $item['label'],
                    'grupo' => $item['grupo'],
                    'medida_id' => $groupId,
                ]);
            }
        }
    }
}
