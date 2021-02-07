<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormItem;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Resources\ItemCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Item;
use App\ApiCode;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/v1/items",
    *     summary="Mostrar productos",
    *     tags={"Item Module"},
    *     @OA\Response(
    *         response=201,
    *         description="Mostrar todos los productos."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="No hemos podido encontrar el item solicitado."
    *     )
    * )
    */
    /**
     * Se busca y retorna lo usuarios paginados
     *
     * @return ItemResource[]
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $items = Item::latest();
        foreach ($query as $key => $value) {
            switch ($key) {
                case 'sku':
                    $items->where('sku', "LIKE", "%{$value}%");
                    break;
                case 'name':
                    $items->where('name', "LIKE", "%{$value}%");
                    break;
            }
        }

        if ($query && $items->count() == 0) {
            return response('No pudimos encontrar items con el query insertado')->setStatusCode(404);
        }
        $result = $items->paginate(10);

        return (new ItemCollection($result))->response()->setStatusCode(201);
    }

    /**
    * @OA\Get(
    *     path="/api/v1/items/:id",
    *     summary="Mostrar un producto por id",
    *     tags={"Item Module"},
    *     @OA\Response(
    *         response=201,
    *         description="Se muestra un producto por id."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="No hemos podido encontrar el item solicitado."
    *     )
    * )
    */
    /**
     * @return ItemResource
     */
    public function show($id)
    {
        try {
            $item = new ItemResource(Item::findOrFail($id));
            return response()->json(['data' => $item, 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar el item', 'success' => false], 404);
        } catch (\Exception $e) {
            $code = ApiCode::SOMETHING_WENT_WRONG;
            return response()->json($e->getMessage(), $code);
        }
    }

    /**
    * @OA\Post(
    *     path="/api/v1/items",
    *     summary="Crear un producto",
    *     tags={"Item Module"},
    *     @OA\Response(
    *         response=201,
    *         description="Se crea un producto."
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Hubo un problema en el request."
    *     )
    * )
    */
    /**
     * @return ItemResource
     */
    public function store(FormItem $itemRequest)
    {
        try {
            if ($itemRequest->hasErrors()) {
                return response()->json(
                    [
                        'errors' => $itemRequest->getErrors(),
                        'success' => false
                    ],
                    200
                );
            }
            $item = new Item($itemRequest->all());
            if ($itemRequest->hasFile('image')) {
                $path = $itemRequest->file('image')->store('imgs');
                $item->image = $path;
            }

            $itemResult = new ItemResource($item);
            return response()->json(['data' => $itemResult, 'success' => true], 201);
        } catch (\Exception $e) {
            $code = ApiCode::SOMETHING_WENT_WRONG;
            return response()->json($e->getMessage(), $code);
        }
    }

    /**
    * @OA\Put(
    *     path="/api/v1/items/:id",
    *     summary="Actualiza un producto",
    *     tags={"Item Module"},
    *     @OA\Response(
    *         response=201,
    *         description="Se actualiza el producto."
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Hubo un problema en el request."
    *     )
    * )
    */
    /**
     * @return ItemResource
     */
    public function update(FormItem $itemRequest, $id)
    {
        try {
            if ($itemRequest->hasErrors()) {
                return response()->json(
                    [
                        'errors' => $itemRequest->getErrors(),
                        'success' => false
                    ],
                    200
                );
            }
            $item = Item::findOrFail($id);
            $item->fill($itemRequest->all());
            if ($itemRequest->hasFile('image')) {
                $path = $itemRequest->file('image')->store('imgs');
                $item->image = $path;
            }
            $item->save();
            $itemResult = new ItemResource($item);
            return response()->json(['data' => $itemResult, 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar el item', 'success' => false], 404);
        } catch (\Exception $e) {
            $code = ApiCode::SOMETHING_WENT_WRONG;
            return response()->json($e->getMessage(), $code);
        }
    }

    /**
    * @OA\Delete(
    *     path="/api/v1/items/:id",
    *     summary="Elimina un producto",
    *     tags={"Item Module"},
    *     @OA\Response(
    *         response=201,
    *         description="Se Elimina el producto."
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Hubo un problema en el request."
    *     )
    * )
    */
    /**
     * @return JsonContent
     */
    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json(['message' => 'Hemos borrado el item', 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar el item', 'success' => false], 404);
        } catch (\Exception $e) {
            $code = ApiCode::SOMETHING_WENT_WRONG;
            return response()->json($e->getMessage(), $code);
        }
    }
}
