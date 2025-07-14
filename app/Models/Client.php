<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = ['name', 'email', 'phone'];

    /**
     * Get the options for activity logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('client')
            ->logOnly(['name', 'email', 'phone'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Client record has been {$eventName}");
    }

    public function holdings()
    {
        return $this->hasMany(ClientHolding::class);
    }
}
