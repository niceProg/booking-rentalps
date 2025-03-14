<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false; // UUID bukan auto-increment

    protected $fillable = ['id', 'order_id', 'name', 'booking_date', 'service', 'total_price', 'status'];

    public static function calculatePrice($date, $service)
    {
        $basePrice = $service === 'PS4' ? 30000 : 40000;
        $weekendSurcharge = (Carbon::parse($date)->isWeekend()) ? 50000 : 0;
        return $basePrice + $weekendSurcharge;
    }
}