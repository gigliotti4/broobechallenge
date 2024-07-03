<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    public function run()
    {
        Category::create(['name' => 'ACCESSIBILITY']);
        Category::create(['name' => 'BEST_PRACTICES']);
        Category::create(['name' => 'PERFORMANCE']);
        Category::create(['name' => 'PWA']);
        Category::create(['name' => 'SEO']);
    }
}
