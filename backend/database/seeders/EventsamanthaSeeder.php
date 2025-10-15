<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Events;
use App\Models\EventItems;
use Illuminate\Support\Carbon;
use App\Models\Business;
use App\Models\Servicios;

class EventsamanthaSeeder extends Seeder
{
    public function run(): void
    {
        set_time_limit(600); // 10 minutos

        $businesses = Business::all();
        $aervicios  = Servicios::all();
        

        $event = Events::create([
            'user_id'    => 7,
            'title'      => 'Quince años de Samantha',
            'event_date' => Carbon::now()->addDays(rand(10, 60)),
            'budget'     => rand(1000, 10000),
            'guests'     => rand(20, 100),
            'notes'      => 'Este es un evento de prueba generado por seeder.',
        ]);

        // Seleccionar aleatoriamente hasta 4 negocios para asociar como ítems del evento
        $selectedBusinesses = $businesses->random(min(4, $businesses->count()));
        $selectedServicios  = $aervicios->random(min(4, $aervicios->count()));
        
        foreach ($selectedBusinesses as $key    => $business) {
            EventItems::create([
                'event_id'    => $event->id,
                'business_id' => $business->id,
                'servicio_id' => $selectedServicios[$key]->id,
                'quantity'    => rand(1, 10),
                'notes'       => 'Ítem de prueba para evento de Sama',
            ]);
        }
        
    }
}
