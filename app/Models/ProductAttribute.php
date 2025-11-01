<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    //
    protected $table = "product_attributes";
    protected $primaryKey = "attribute_id";
    public $incrementing = true;
    protected $keyType = "int";
    protected $fillable = ["attribute_name","value"];

    function products():HasMany
    {
        return $this->hasMany(Product::class, "role_id","role_id");
    }
}
