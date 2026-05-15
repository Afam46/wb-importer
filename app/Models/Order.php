<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'date',
        'supplier_article',
        'total_price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'nm_id',
        'category',
        'brand',
    ];
}