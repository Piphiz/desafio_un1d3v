<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [ 'bill_identifier', 'bill_amount', 'bill_type', 'bill_date', 'bill_stats' ];

    public function getPriceFormatedAttribute()
    {
        return 'R$ '. number_format($this->attributes['bill_amount'], 2, ',', '.');
    }

    protected $casts = [
        'bill_date' => 'datetime'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
