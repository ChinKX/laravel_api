<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'isbn',
        'title',
        'year',
        'publisher_id'
    ];

    /**
    * Get the publisher of the book.
    */
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
    * The authors that belong to the book.
    */
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
