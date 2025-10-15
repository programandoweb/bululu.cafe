<?php
/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: +57 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve - CommentsRepository
 * ---------------------------------------------------
 */

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Comments;

class CommentsRepository
{
    /**
     * Obtener comentarios por pathname (y opcionalmente module)
     * Incluye relación con usuario.
     */
    public function getByPathname(?string $pathname, ?string $module = null)
    {
        return Comments::query()
            ->with("user")
            ->when($pathname, fn($q) => $q->where('pathname', $pathname))
            ->when($module, fn($q) => $q->where('module', $module))
            ->latest('created_at')
            ->get();
    }

    /**
     * Listado paginado con filtros.
     * Soporta filtros: module, pathname, user_id, fechas, orden.
     */
    public function getAll(Request $request)
    {
        $perPage   = (int) $request->input('per_page', config('constants.RESULT_X_PAGE'));
        $module    = trim((string) $request->input('module', ''));
        $pathname  = trim((string) $request->input('pathname', ''));
        $orderBy   = $request->input('order_by', 'created_at');
        $orderDir  = $request->input('order_dir', 'desc');

        $query = Comments::query()
            ->with("user")
            ->when($module !== '', fn($q) => $q->where("module", $module))
            ->when($pathname !== '', fn($q) => $q->where("pathname", $pathname))
            ->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    /**
     * Listado sin paginación (dataset liviano).
     */
    public function get(Request $request)
    {
        $limit     = (int) $request->input('limit', 100);
        $q         = $request->input('q', $request->input('search'));
        $module    = $request->input('module');
        $pathname  = $request->input('pathname');
        $userId    = $request->input('user_id');

        $query = Comments::query()
            ->with('user')
            ->when($q, fn($qq) => $qq->where('mensaje', 'like', "%{$q}%"))
            ->when($module, fn($qq) => $qq->where('module', $module))
            ->when($pathname, fn($qq) => $qq->where('pathname', $pathname))
            ->when($userId, fn($qq) => $qq->where('user_id', $userId))
            ->latest('created_at');

        return $query->limit($limit)->get();
    }

    /**
     * Crear nuevo comentario.
     * Asegura que el campo 'type' se respete (Pagos | Comentario).
     */
    public function create(array $data): Comments
    {
        // 🔹 Serializar JSON si es un arreglo
        if (isset($data['json']) && is_array($data['json'])) {
            $data['json'] = json_encode($data['json'], JSON_UNESCAPED_UNICODE);
        }

        // 🔹 Valor por defecto para 'type' si no se envía
        $data['type'] = $data['type'] ?? 'Comentario';

        // 🔹 Crear registro respetando el campo 'type'
        return Comments::create([
            'mensaje'  => $data['mensaje'],
            'type'     => $data['type'], // 👈 Aquí se garantiza que se guarde
            'image'    => $data['image']    ?? null,
            'module'   => $data['module']   ?? null,
            'pathname' => $data['pathname'] ?? null,
            'json'     => $data['json']     ?? null,
            'user_id'  => $data['user_id']  ?? null,
            'parent_id'  => $data['parent_id']  ?? null,
        ]);
    }

    /**
     * Actualizar comentario existente.
     * Incluye el campo 'type' en la actualización.
     */
    public function update(string $id, array $data): ?Comments
    {
        $comment = Comments::find($id);
        if (!$comment) {
            return null;
        }

        // 🔹 Serializar JSON si es un arreglo
        if (isset($data['json']) && is_array($data['json'])) {
            $data['json'] = json_encode($data['json'], JSON_UNESCAPED_UNICODE);
        }

        // 🔹 Mantener el tipo actual si no se envía
        $data['type'] = $data['type'] ?? $comment->type ?? 'Comentario';

        // 🔹 Actualizar registro
        $comment->update([
            'mensaje'  => $data['mensaje']  ?? $comment->mensaje,
            'type'     => $data['type'], // 👈 Se actualiza correctamente
            'image'    => $data['image']    ?? $comment->image,
            'module'   => $data['module']   ?? $comment->module,
            'pathname' => $data['pathname'] ?? $comment->pathname,
            'json'     => $data['json']     ?? $comment->json,
            'user_id'  => $data['user_id']  ?? $comment->user_id,
        ]);

        return $comment;
    }

    /**
     * Eliminar comentario.
     */
    public function delete(string $id): bool
    {
        $comment = Comments::find($id);
        return $comment ? (bool) $comment->delete() : false;
    }

    /**
     * Buscar comentario por ID (con usuario).
     */
    public function findById(string $id): ?Comments
    {
        return Comments::with('user')->find($id);
    }
}
