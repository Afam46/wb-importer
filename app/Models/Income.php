<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'date',
        'supplier_article',
        'quantity',
        'total_price',
        'warehouse_name',
        'nm_id',
    ];
}