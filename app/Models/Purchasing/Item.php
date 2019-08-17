<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use Uuids;
    use LogsActivity;

    protected $table = 'purchasing_items';

    protected $fillable = ['purchasing_id', 'quantity', 'unit', 'description', 'user_id', 'approved_by'];

    protected static $logAttributes = ['purchasing_id', 'quantity', 'unit', 'description', 'user_id', 'approved_by'];

    public function purchasing()
    {
        return $this->belongsTo('App\Models\Purchasing', 'purchasing_id');
    }
}
