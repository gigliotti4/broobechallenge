<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricHistoryRun extends Model
{
    use HasFactory;

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
}
