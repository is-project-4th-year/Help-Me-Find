<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    // Define the table name explicitly
    protected $table = 'items';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'image_name',
        'description',
        'finder_first_name',
        'finder_last_name',
        'finder_email',
        'owner_first_name',
        'owner_last_name',
        'owner_email',
        'found_date',
        'found_location',

        // ** ADD THESE TWO LINES **
        'latitude',
        'longitude',
    ];
}
