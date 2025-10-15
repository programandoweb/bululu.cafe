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

namespace App\Http\Controllers\V1\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductsRepository;
use App\Repositories\ProductCategoriesRepository;
use App\Models\UnifiedLocations;
use App\Models\EventSchedule;
use App\Models\Servicios;
use App\Services\DownloadService;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    protected $productsRepository;
    protected $unifiedLocations;
    protected $eventSchedule;
    protected $productCategoriesRepository;

    public function __construct(
        ProductsRepository $productsRepository,
        UnifiedLocations $unifiedLocations,
        EventSchedule $eventSchedule,
        ProductCategoriesRepository $productCategoriesRepository
    ) {
        $this->productsRepository   = $productsRepository;
        $this->unifiedLocations     = $unifiedLocations;
        $this->eventSchedule        = $eventSchedule;
        $this->productCategoriesRepository        = $productCategoriesRepository;
    }

    public function schedule(Request $request)
    {
        try {
            $data = $request->all();

            $clientId = auth()->id();
            if (!$clientId) {
                return response()->error("No autenticado", 401);
            }

            $product = $this->productsRepository->findById($data["id"]);
            if (!$product) {
                return response()->error("Producto no autorizado", 401);
            }

            $schedule = $this->eventSchedule->updateOrCreate(
                [
                    'client_id'   => $clientId,
                    'provider_id' => $product->user_id,
                    'servicio_id' => $product->id,
                ],
                [
                    'status'       => 'pendiente',
                    'scheduled_at' => null,
                ]
            );

            create_notification([
                'to_user_id'   => $product->user_id,
                'concepto'     => 'Nueva cita agendada',
                'descripcion'  => 'Tienes una nueva solicitud de cita',
                'tipo'         => 'cita',
                'related_type' => $schedule->id,
            ]);

            return response()->success(compact('schedule'), "schedule dataset");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function service(string $id)
    {
        $product = $this->productsRepository->getService($id);
        $gallery = $product->gallery;

        if (!is_array($product->gallery)) {
            $gallery = json_decode($product->gallery);
        }

        $name        = $product->name ?? 'Producto';
        $description = $product->description ?? 'Descripción por defecto';
        $baseUrl     = env('APP_URL');
        $firstImage  = $gallery[0] ?? '';

        $seo = (object) [
            'title'       => $name,
            'description' => $description,
            'openGraph'   => (object) [
                'title'       => $name,
                'description' => $description,
                'image'       => Str::startsWith($firstImage, ['http://', 'https://'])
                    ? $firstImage
                    : $baseUrl . '/' . ltrim($firstImage, '/'),
            ],
        ];

        return response()->success(compact('product', 'seo'), "detalle producto 2025");
    }

    public function related_items(string $slug)
    {
        try {
            $related_items = $this->productsRepository->getBySlug($slug);
            return response()->success(compact('related_items'), "dataset");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function get(DownloadService $downloadService)
    {
        try {
            $products = $this->productsRepository->get();
            return response()->success(compact('products'), "dataset");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        try {            
            $products = $this->productsRepository->getAll($request);
            return response()->success(compact('products'), "Listado de productos 2025");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rawGallery = $request->input('gallery');
            if (is_string($rawGallery)) {
                $decoded = json_decode($rawGallery, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge(['gallery' => $decoded]);
                }
            }

            $validated = $request->validate([
                'name'                     => 'required|string|max:255',
                'description'              => 'nullable|string',
                'product_category_id'      => 'nullable|integer|exists:product_categories,id',
                'rating'                   => 'nullable|integer|min:0|max:5',
                'image'                    => 'nullable|string|max:255',
                'gallery'                  => 'nullable|array',
                'gallery.*'                => 'nullable|string|max:255',

                'barcode'                  => 'nullable|string|max:255',
                'brand'                    => 'nullable|string|max:255',
                'measure_unit'             => 'nullable|in:ml,l,fl_oz,g,kg,gal,oz,lb,cm,ft,in,unit',
                'measure_quantity'         => 'nullable|numeric',
                'short_description'        => 'nullable|string|max:100',
                'long_description'         => 'nullable|string',
                'stock_control'            => 'nullable|boolean',
                'stock_alert_level'        => 'nullable|integer',
                'stock_reorder_amount'     => 'nullable|integer',
                'model'                    => 'nullable|string|max:255',
                'color'                    => 'nullable|string|max:255',
                'sku'                      => 'nullable|string|max:255',
                'price'                    => 'nullable|numeric',
            ]);

            $validated['user_id'] = $request->user()->id;

            $product = $this->productsRepository->create($validated);

            return response()->success(compact('product'), "Producto creado exitosamente");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->productsRepository->findById($id);
            if (!$product && $id !== 'new') {
                return response()->error("Producto no encontrado", 404);
            }

            $products       =   $this->productsRepository->get();
            $categories     =   $this->productCategoriesRepository->get();

            return response()->success(compact('product', 'products','categories'), "Producto encontrado");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update($id, Request $request)
    {
        // Actualizar servicio principal
        $servicio = Servicios::where('type', 'products')->find($id);
        if (!$servicio) {
            return null;
        }

        $data   =   $request->all();

        $servicio->update([
            'name'                => $data['name'] ?? $servicio->name,
            'description'         => $data['description'] ?? $servicio->description,
            'product_category_id' => $data['product_category_id'] ?? $servicio->product_category_id,
            'image'               => $data['image'] ?? $servicio->image,
            'gallery'             => $data['gallery'] ?? $servicio->gallery,
        ]);

        // Actualizar o crear registro en tabla products
        $servicio->product()->updateOrCreate(
            ['servicio_id' => $servicio->id],
            [
                'name'                    => $servicio->name,
                'barcode'                 => $data['barcode'] ?? '',
                'brand'                   => $data['brand'] ?? '',
                'measure_unit'            => $data['measure_unit'] ?? '',
                'measure_quantity'        => isset($data['measure_quantity']) ? (float) $data['measure_quantity'] : null,
                'short_description'       => $data['short_description'] ?? '',
                'long_description'        => $data['long_description'] ?? '',
                'category_name'           => $servicio->productCategory?->name ?? '',
                'stock_control'           => isset($data['stock_control']) ? $data['stock_control'] : false,
                'stock_alert_level'       => $data['stock_alert_level'] ?? 0,
                'stock_reorder_amount'    => $data['stock_reorder_amount'] ?? 0,
                'model'                   => $data['model'] ?? '',
                'color'                   => $data['color'] ?? '',
                'sku'                     => $data['sku'] ?? '',
                'price'                   => $data['price'] ?? null,
                'provider_id'             => $servicio->user_id,
            ]
        );


        \Log::debug('measure_quantity recibido:', ['value' => $data['measure_quantity'] ?? null]);


        return $servicio->load(['product', 'productCategory', 'user']);
    }


    public function destroy(string $id)
    {
        try {
            $deleted = $this->productsRepository->delete($id);
            if (!$deleted) {
                return response()->error("Producto no encontrado", 404);
            }

            return response()->success([], "Producto eliminado exitosamente");
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
