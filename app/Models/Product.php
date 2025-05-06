<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'unique_key';
    public $incrementing = false; // Since primary key is a string

    protected $keyType = 'string';

    protected $fillable = [
        'unique_key',
        'product_title',
        'product_description',
        'style',
        'sanmar_mainframe_color',
        'size',
        'color_name',
        'piece_price',
    ];
}

