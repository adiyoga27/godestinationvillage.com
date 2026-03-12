<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\Package;
use App\Models\User;
use App\Models\BankAccount;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "orders";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Orders';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'village_id',
        'package_id',
        'user_id',
        'bank_account_id',
        'code',
        'village_name',
        'package_name',
        'customer_name',
        'customer_address',
        'customer_phone',
        'customer_email',
        'package_price',
        'package_discount',
        'package_currency',
        'total_payment',
        'payment_type',
        'payment_date',
        'payment_status',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'payment_img',
        'pax',
        'special_note',
        'checkin_date',
        'uuid'
    ];

    protected $casts = [
        'pax' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function village()
    {
        // return $this->belongsTo(User::class, 'village_id');
        return $this->hasOne(VillageDetail::class, 'id', 'village_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
        'village_id',
        'package_id',
        'user_id',
        'bank_account_id',
        'code',
        'village_name',
        'package_name',
        'customer_name',
        'customer_address',
        'customer_phone',
        'customer_email',
        'package_price',
        'package_discount',
        'package_currency',
        'total_payment',
        'payment_type',
        'payment_date',
        'payment_status',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'payment_img',
        'pax',
        'special_note',
        'checkin_date',
        'uuid'
    ]);
        // Chain fluent methods for configuration options
    }
}
