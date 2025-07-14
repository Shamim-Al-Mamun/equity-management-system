<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientHolding extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = ['client_id', 'stock_symbol', 'quantity', 'sector', 'purchase_price', 'current_price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('client_holding')
            ->logOnly(['client_id', 'stock_symbol', 'quantity', 'purchase_price'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Client Holding record has been {$eventName}");
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
