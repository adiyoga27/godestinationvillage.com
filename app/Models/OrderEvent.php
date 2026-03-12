<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderEvent extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "order_events";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'order_events';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'event_id',
        'user_id',
        'bank_account_id',
        'code',
        'customer_name',
        'customer_address',
        'customer_phone',
        'customer_email',
        'event_price',
        'event_discount',
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
        'event_name',
        'snap_token',
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
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
        'event_id',
        'user_id',
        'bank_account_id',
        'code',
        'customer_name',
        'customer_address',
        'customer_phone',
        'customer_email',
        'event_price',
        'event_discount',
        'total_payment',
        'payment_type',
        'payment_date',
        'payment_status',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'payment_img',
        'pax',
        'event_name',
        'special_note',
        'uuid'
    ]);
        // Chain fluent methods for configuration options
    }
}
