<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormItem;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Resources\ItemCollection;
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
        $items = Item::paginate(10);
        return response()->json(new ItemCollection($items), 201);
    }

    /**
     * @return ItemResource
     */
    public function show($id)
    {
        try {
            $item = new ItemResource(Item::findOrFail($id));
            return response()->json(['data' => $item, 'success' => true], 201);
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
            $item = Item::findOrFail($id);
            $itemRequest->validateToUpdate();
            $itemUpdated = $item->update($itemRequest->all());
            $itemResult = new ItemResource($itemUpdated);
            return $itemResult;
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
