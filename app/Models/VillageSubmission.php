<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'village_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'regency',
        'description',
        'tourism_potential',
        'attachment',
        'status',
        'pic_team_id',
        'internal_note',
    ];

    public function pic()
    {
        return $this->belongsTo(OurTeam::class, 'pic_team_id');
    }
}
