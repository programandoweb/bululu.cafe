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

namespace App\Http\Controllers\V1\Events;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\EventRepository;
use App\Repositories\ServiciosRepository;
use App\Services\WhatsAppSenderService;

class EventsController extends Controller
{
    protected $eventRepository;
    protected $serviciosRepository;
    protected $whatsAppSenderService;

    public function __construct(
        ServiciosRepository $serviciosRepository,
        EventRepository $eventRepository,
        WhatsAppSenderService $whatsAppSenderService
    ) {
        $this->serviciosRepository      = $serviciosRepository;
        $this->eventRepository          = $eventRepository;
        $this->whatsAppSenderService    = $whatsAppSenderService;
    }

    public function index(Request $request)
    {
        try {
            $events = $this->eventRepository->getAll($request);
            return response()->success(compact('events'), 'Listado de eventos');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'required|string|max:255',
                'user_id'     => 'nullable',
                'duracion'    => 'nullable|string|max:255',
                'descripcion' => 'nullable|string',
                'portada'     => 'nullable|string|max:255',
                'type'        => 'required|in:event,promotion', // ⬅️ validación del tipo
            ]);

            $validated['user_id'] = $validated["user_id"]??$request->user()->id;

            $event = $this->eventRepository->create($validated);

            return response()->success(compact('event'), 'Evento creado exitosamente');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function show(string $id)
    {
        try {
            $event = $this->eventRepository->findById($id);

            if (!$event && $id != 'new') {
                return response()->error('Evento no encontrado', 404);
            }

            // Incluimos los servicios disponibles
            $services = $this->serviciosRepository->getServicios();

            return response()->success(compact('event', 'services'), 'Evento encontrado');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'sometimes|required|string|max:255',
                'duracion'    => 'nullable|string|max:255',
                'descripcion' => 'nullable|string',
                'portada'     => 'nullable|string|max:255',
            ]);

            $event = $this->eventRepository->update($id, $validated);

            if (!$event) {
                return response()->error('Evento no encontrado', 404);
            }

            return response()->success(compact('event'), 'Evento actualizado');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $deleted = $this->eventRepository->delete($id);

            if (!$deleted) {
                return response()->error('Evento no encontrado', 404);
            }

            return response()->success([], 'Evento eliminado correctamente');
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    // Métodos de ítems (los mantenemos iguales)
    public function addItem(string $id, Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->error('No autenticado', 401);
        }

        $event = $this->eventRepository->lastEventByUser($userId);
        if (!$event) {
            return response()->error('No hay evento activo para asignar ítems', 404);
        }

        $servicio = $this->serviciosRepository->findById($request->newServiceId);
        if (!$servicio) {
            return response()->error('No hay servicio activo para asignar ítems', 404);
        }

        $request->merge([
            'notes'       => $event->nombre,
            'servicio_id' => $servicio->id,
        ]);

        $this->eventRepository->addItem($event->id, $request);

        return $this->show($event->id);
    }

    public function removeItem(string $id, Request $request)
    {
        try {
            $this->eventRepository->removeItem($id, $request);
            return $this->show($id);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function acceptItem(string $id, Request $request)
    {
        try {
            $item = \App\Models\EventItems::where('id', $request->itemId)
                ->where('event_id', $id)
                ->first();

            if (!$item) {
                return response()->error('Ítem no encontrado', 404);
            }

            if ($item->status === 'aceptado') {
                return response()->error('El ítem ya está aceptado', 400);
            }

            $item->update(['status' => 'aceptado']);
            return $this->show($id);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
