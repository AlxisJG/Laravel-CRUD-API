<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormItem;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Resources\ItemCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Item;

class ItemController extends Controller
{
    /**
     * Se busca y retorna lo usuarios paginados
     *
     * @return ItemResource[]
     */
    public function index(Request $request)
    {
        $items = Item::query();
        foreach ($request->query() as $key => $value) {
            //TODO Make It case insensible
            switch ($key) {
                case 'sku':
                    $items->where('sku', $value);
                    break;
                case 'name':
                    $items->where('name', $value);
                    break;
            }
        }

        if ($items->count() == 1) {
            return (new ItemResource($items->first()))->response()->setStatusCode(201);
        } elseif ($items->count() == 0) {
            return response('No pudimos encontrar el item')->setStatusCode(404);
        }

        $items = Item::paginate(10);
        return (new ItemCollection($items))->response()->setStatusCode(201);
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
            return response()->json($e->getMessage(), 404);
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
            $itemCreated = Item::create($itemRequest->all());
            $itemResult = new ItemResource($itemCreated);
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
            return $itemResult->response()->setStatusCode(201);
        } catch (ModelNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return JsonContent
     */
    public function delete()
    {

    }
}
