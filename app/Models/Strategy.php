<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;
    
    public function metricHistoryRuns()
    {
        return $this->hasMany(MetricHistoryRun::class);
    }

    public function run()
    {
        Strategy::create(['name' => 'DESKTOP']);
        Strategy::create(['name' => 'MOBILE']);
    }
}
