<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price_monthly',
        'course_limit',
        'content_upload_limit',
        'student_limit'
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'course_limit' => 'integer',
        'content_upload_limit' => 'integer',
        'student_limit' => 'integer'
    ];
}
