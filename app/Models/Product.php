<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * Scout index name.
     */
    public function searchableAs()
    {
        return 'products_index';
    }

    /**
     * Fields available for Scout searching.
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}