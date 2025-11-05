<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'finder_id',
        'item_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function finder()
    {
        return $this->belongsTo(User::class, 'finder_id');
    }
}
