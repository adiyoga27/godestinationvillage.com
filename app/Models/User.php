<?php

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;

use Laravel\Passport\HasApiTokens;

use App\Models\VillageDetail;
use App\Models\Package;
use App\Models\Order;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use SanctumHasApiTokens;
    
    use Notifiable;
    use LogsActivity;
    use SoftDeletes;
    use HasRoles;
    

    protected $table = "users";

    protected $dates = ['deleted_at'];

    protected $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Users';

    protected static $logOnlyDirty = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'role_id', 'is_active', 'phone', 'country', 'address', 'avatar','provider','provider_id','village_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer'
    ];

    public function village_detail()
    {
        return $this->hasOne(VillageDetail::class, 'user_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function village_orders()
    {
        return $this->hasMany(Order::class, 'village_id');
    }

    public function blog()
    {
        return $this->hasMany(Blog::class, 'post_author');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'name', 'email', 'password', 'email_verified_at', 'role_id', 'is_active', 'phone', 'country', 'address', 'avatar','provider','provider_id',
        'village_id'
    
    ]);
        // Chain fluent methods for configuration options
    }

}
