<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'description', 'image'
    ];

    // Add a method to upload the image
    public function setImageAttribute($value)
    {
        if (is_object($value)) {
            $this->attributes['image'] = $value->store('product_images', 'public');
        }
    }
}
