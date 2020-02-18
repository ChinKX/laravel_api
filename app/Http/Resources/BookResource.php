<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BookResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'year' => $this->year,
            'publisher' => new PublisherResource($this->whenLoaded('publisher')),
            'authors' => new AuthorCollection($this->whenLoaded('authors')),
            'author_id' => $this->whenPivotLoaded('author_book', function() {
                return $this->pivot->author_id;
            }),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
