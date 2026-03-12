<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\User;
use Spatie\Activitylog\LogOptions;

class VillageDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "village_details";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Village Details';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'user_id',
        'village_name',
        'village_address',
        'lat',
        'lng',
        'contact_person',
        'desc',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'slug'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'village_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'name', 'email', 'password', 'email_verified_at', 'role_id', 'is_active', 'phone', 'country', 'address', 'avatar','slug']);
        // Chain fluent methods for configuration options
    }

  

}
