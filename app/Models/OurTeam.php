<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'title',
        'description',
        'instagram',
        'twitter',
        'facebook',
        'linkedin',
        'phone',
        'whatsapp',
        'avatar'
    ];
}
