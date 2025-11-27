<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enrollment extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'order_reference',
        'amount',
        'payment_method',
        'payment_status',
        'chip_purchase_id',
        'payment_proof',
        'status',
        'enrolled_at',
        'expires_at',
        'payment_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'enrolled_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (empty($enrollment->order_reference)) {
                $enrollment->order_reference = 'ENR-' . strtoupper(Str::random(10));
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student name (user or guest)
     */
    public function getStudentNameAttribute()
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    /**
     * Get the student email (user or guest)
     */
    public function getStudentEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    /**
     * Check if enrollment is active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->payment_status === 'paid';
    }

    /**
     * Mark enrollment as paid and active
     */
    public function markAsPaid($paymentData = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'active',
            'enrolled_at' => now(),
            'payment_data' => $paymentData,
        ]);
    }

    /**
     * Scope for active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('payment_status', 'paid');
    }

    /**
     * Scope for pending enrollments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
