<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class BankAccount extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "bank_accounts";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Bank Accounts';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'is_active'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'is_active']);
        // Chain fluent methods for configuration options
    }

}
