<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderHomestay extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "order_homestay";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'order_homestay';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id', 'user_id', 'homestay_id', 'code', 'customer_name', 'customer_address', 'customer_phone', 'customer_email', 'homestay_name', 'homestay_price', 'homestay_discount', 'total_payment', 'payment_type', 'payment_date', 'payment_status', 'pax', 'special_note', 'snap_token','uuid'
    ];
  protected $casts = [
        'pax' => 'integer'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'id');
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'user_id', 'homestay_id', 'code', 'customer_name', 'customer_address', 'customer_phone', 'customer_email', 'homestay_name', 'homestay_price', 'homestay_discount', 'total_payment', 'payment_type', 'payment_date', 'payment_status', 'pax', 'special_note', 'snap_token',
        'uuid'
    ]);
        // Chain fluent methods for configuration options
    }
}
