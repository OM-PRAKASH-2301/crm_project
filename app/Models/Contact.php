<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model {
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'profile_image', 'additional_file', 'custom_fields', 'deleted_at'
    ];    

    protected $casts = [
        'custom_fields' => 'array',
    ];

    // Global Scope to always exclude deleted records
    protected static function boot() {
        parent::boot();
        static::addGlobalScope('not_deleted', function ($query) {
            $query->where('deleted_at', 'N');
        });
    }
}

