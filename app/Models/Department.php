<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDisabled()
    {
        return $this->status == self::STATUS_DISABLED;
    }

    protected $fillable = [
        'name',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
