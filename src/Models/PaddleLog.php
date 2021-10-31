<?php

namespace Eduka\Models;

use Eduka\Notifications\SendSMSNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class PaddleLog extends Model
{
    use Notifiable;

    protected $fillable = [
        'alert_id',
        'alert_name',
        'checkout_id',
        'country',
        'currency',
        'customer_name',
        'email',
        'event_time',
        'order_id',
        'payment_method',
        'receipt_url',
        'earnings',
        'fee',
        'mode',
        'sale_gross',
        'payment_tax',
        'gross_refund',
        'refund_reason',
        'passthrough',
        'coupon',
        'payload',
        'ip',
    ];

    protected $table = 'paddle_log';

    protected $casts = [
        'event_time' => 'datetime',
        'passthrough' => 'array',
        'payload' => 'array',
    ];

    public static function storeCheckout($checkout)
    {
        // Send SMS Notification.
        Notification::route('nexmo', '41789654141')
            ->notify(new SendSMSNotification($checkout));

        return PaddleLog::create(array_merge($checkout, ['payload' => $checkout]));
    }
}
