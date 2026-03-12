<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory;
    use LogsActivity;
    public $table = "transaction";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'transaction';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'category', 'transaction_time', 'transaction_status', 'transaction_id', 'status_message', 'status_code', 'signature_key', 'settlement_time', 'payment_type', 'order_id', 'merchant_id', 'gross_amount', 'fraud_status', 'currency'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['category', 'transaction_time', 'transaction_status', 'transaction_id', 'status_message', 'status_code', 'signature_key', 'settlement_time', 'payment_type', 'order_id', 'merchant_id', 'gross_amount', 'fraud_status', 'currency']);
        // Chain fluent methods for configuration options
    }

  
}
