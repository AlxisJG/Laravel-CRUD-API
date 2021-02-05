<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'metadata' => [
                'count' => $this->count(),
                'total' => $this->total(),
                'prev-link'  => $this->previousPageUrl(),
                'next-link'  => $this->nextPageUrl(),
            ],
            'success' => true
        ];
    }
}
