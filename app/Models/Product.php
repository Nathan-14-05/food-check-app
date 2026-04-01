<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['barcode', 'name', 'brand', 'calories', 'nutriscore', 'image_url', 'food_list_id'];

    public function foodList()
    {
        return $this->belongsTo(FoodList::class);
    }
}
