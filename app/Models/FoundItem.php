<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    // Define the table name explicitly
    protected $table = 'found_items';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'image_name',
        'description',
        'finder_first_name',
        'finder_last_name',
        'finder_email',
        'found_at',
    ];
}
