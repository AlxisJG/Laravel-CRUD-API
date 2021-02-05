<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormItem;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Resources\ItemCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/v1/items",
    *     summary="Mostrar productos",
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
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

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
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

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
            $item->update($itemRequest->all());
            $itemResult = new ItemResource($item);
            return response()->json(['data' => $itemResult, 'success' => true], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No pudimos encontrar el item', 'success' => false], 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

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
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
