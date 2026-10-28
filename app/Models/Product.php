<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['partner_id', 'brand_id', 'sku', 'name', 'ean', 'recommended_price'];
}
