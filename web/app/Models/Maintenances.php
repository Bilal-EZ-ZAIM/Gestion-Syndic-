<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenances extends Model
{
    use HasFactory;

    protected $fillable = [
        'hoa_id',
        'user_id',
        'title',
        'description',
        'facture',
        'image'
    ];
}
