<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $fillable = ['subcategory_id', 'remedy_type', 'title', 'content', 'image_path', 'sort_order', 'is_active'];

    public const REMEDY_TYPES = ['Crystal', 'Lal Kitab', 'Switch Word', 'Vedic Switch Word'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
