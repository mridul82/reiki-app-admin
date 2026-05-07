<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class)->orderBy('sort_order');
    }
}
