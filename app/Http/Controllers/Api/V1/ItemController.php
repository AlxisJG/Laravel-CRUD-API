<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormItem;
use App\Http\Resources\ItemResource;
use App\Item;

class ItemController extends Controller
{
    /**
     * Se busca y retorna lo usuarios paginados
     *
     * @return ItemResource[]
     */
    public function index()
    {
        $items = Item::paginate(10)->get();
        return ItemResource::collection($items);
    }

    /**
     * @return ItemResource
     */
    public function show($id)
    {
        try {
            $item = new ItemResource(Item::findOrFail($id));
            return $item;
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
            $itemRequest->validate();
            $itemCreated = Item::create($itemRequest->all());
            $itemResult = new ItemResource($itemCreated);
            return $itemResult;
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
