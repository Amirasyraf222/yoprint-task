<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = [
        'file_name',
        'uploaded_at',
        'status',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
}
