<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'supplier_article',
        'quantity',
        'quantity_full',
        'warehouse_name',
        'nm_id',
        'price',
        'discount',
    ];
}