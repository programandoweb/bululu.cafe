<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * DELETE /dashboard/tags/{id}
     * No elimina físicamente el registro, solo cambia su bool_status a 0.
     */
    public function delete_tags($id)
    {
        try {
            DB::beginTransaction();

            $tag = DB::table('master_tables')->where('id', $id)->first();

            if (!$tag) {
                return response()->error('Tag no encontrado.', 404);
            }

            $newStatus = $tag->bool_status == 1 ? 0 : 1;

            DB::table('master_tables')
                ->where('id', $id)
                ->update(['bool_status' => $newStatus]);

            DB::commit();

            $message = $newStatus == 1
                ? 'Tag activado correctamente.'
                : 'Tag deshabilitado correctamente.';

            return response()->success(
                ['id' => $id, 'bool_status' => $newStatus, 'message' => $message],
                $message
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /dashboard/tags
     * Si se pasa ?grupo=Categorías Lugares, devuelve solo los tags asociados a ese grupo padre.
     */
    public function get_tags(Request $request)
    {
        try {
            $grupo = $request->query('grupo'); // Ejemplo: "Categorías Lugares"

            $query = DB::table('master_tables as t')
                ->select('t.id', 't.label', 't.grupo', 't.medida_id', 't.bool_status')
                ->where('t.grupo', 'tags')
                ->orderBy('t.id', 'asc');

            if ($grupo) {
                $query->join('master_tables as parent', 't.medida_id', '=', 'parent.id')
                    ->where('parent.label', $grupo)
                    ->where('parent.grupo', 'group_farrea');
            }

            $tags = $query->get();

            return response()->success(
                ['tags' => $tags],
                'Tags cargados con éxito.'
            );
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /dashboard/master_tables/by-label?label=Categorías Lugares
     * Devuelve el grupo padre (para obtener su ID)
     */
    public function get_group_by_label(Request $request)
    {
        try {
            $label = $request->query('label');
            if (!$label) {
                return response()->error('Debe especificar un label.', 422);
            }

            $group = DB::table('master_tables')
                ->where('label', $label)
                ->where('grupo', 'group_farrea')
                ->first();

            if (!$group) {
                return response()->error('Grupo no encontrado.', 404);
            }

            return response()->success(
                ['group' => $group],
                'Grupo encontrado correctamente.'
            );
        } catch (\Throwable $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * POST /dashboard/tags
     * Crea un nuevo tag asociado a su grupo padre real (medida_id)
     */
    public function store_tags(Request $request)
    {
        try {
            $validated = $request->validate([
                'label'     => 'required|string|max:255',
                'grupo'     => 'nullable|string|max:255',
                'medida_id' => 'required|integer|exists:master_tables,id',
            ]);

            DB::beginTransaction();

            $id = DB::table('master_tables')->insertGetId([
                'label'      => $validated['label'],
                'grupo'      => $validated['grupo'] ?? 'tags',
                'medida_id'  => $validated['medida_id'],
                'bool_status'=> 1
            ]);

            DB::commit();

            $tag = DB::table('master_tables')->find($id);

            return response()->success(
                ['tag' => $tag, 'message' => 'Tag creado correctamente.'],
                'Tag creado correctamente.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * PUT /dashboard/tags/{id}
     * Actualiza un tag existente
     */
    public function update_tags(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'label'     => 'required|string|max:255',
                'grupo'     => 'nullable|string|max:255',
                'medida_id' => 'required|integer|exists:master_tables,id',
            ]);

            DB::beginTransaction();

            $exists = DB::table('master_tables')->where('id', $id)->first();
            if (!$exists) {
                return response()->error('Tag no encontrado.', 404);
            }

            DB::table('master_tables')
                ->where('id', $id)
                ->update([
                    'label'     => $validated['label'],
                    'grupo'     => $validated['grupo'] ?? 'tags',
                    'medida_id' => $validated['medida_id'],
                ]);

            DB::commit();

            $tag = DB::table('master_tables')->find($id);

            return response()->success(
                ['tag' => $tag, 'message' => 'Tag actualizado correctamente.'],
                'Tag actualizado correctamente.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * GET /dashboard/emails/email_register_business
     * Configuración de correos
     */
    public function get_email_register_business($type = '')
    {
        try {
            $setting = DB::table('master_tables')
                ->where('grupo', 'email_register_business')
                ->first();

            return response()->success(
                ['setting' => $setting],
                "Configuración cargada con éxito."
            );
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function show($type = '')
    {
        try {
            $setting = DB::table('master_tables')
                ->where('grupo', $type ?? 'email_register_business')
                ->first();

            return response()->success(
                ['setting' => $setting],
                "Configuración cargada con éxito."
            );
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request, $type = '')
    {
        try {
            $validated = $request->validate([
                'title'   => 'required|string|max:255',
                'message' => 'required|string',
                'grupo'   => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            $id = DB::table('master_tables')->insertGetId([
                'label'       => $validated['title'],
                'grupo'       => $validated['grupo'] ?? 'email_register_business',
                'description' => $validated['message']
            ]);

            DB::commit();

            $setting = DB::table('master_tables')->find($id);

            return response()->success(
                ['setting' => $setting, "message" => "Configuración creada exitosamente."],
                "Configuración creada exitosamente."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $type = '')
    {
        try {
            $validated = $request->validate([
                'title'   => 'required|string|max:255',
                'message' => 'required|string',
                'grupo'   => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            $exists = DB::table('master_tables')
                ->where('grupo', 'email_register_business')
                ->first();

            if ($exists) {
                DB::table('master_tables')
                    ->where('id', $exists->id)
                    ->update([
                        'label'       => $validated['title'],
                        'grupo'       => $validated['grupo'] ?? 'email_register_business',
                        'description' => $validated['message'],
                    ]);
            } else {
                DB::table('master_tables')->insert([
                    'label'       => $validated['title'],
                    'grupo'       => $validated['grupo'] ?? 'email_register_business',
                    'description' => $validated['message'],
                ]);
            }

            DB::commit();

            return response()->success(
                ['setting' => $validated, "message" => "Configuración actualizada exitosamente."],
                "Configuración actualizada exitosamente."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
}
