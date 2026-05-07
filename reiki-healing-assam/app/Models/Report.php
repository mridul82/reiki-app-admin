<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'module', 'customer_first_name', 'customer_last_name',
        'customer_dob', 'customer_contact', 'subcategory_ids', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'subcategory_ids' => 'array',
            'customer_dob' => 'date',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
