<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    const PRIORITY_NORMAL = 1;
    const PRIORITY_LOW = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 4;

    const STATUS_OPENED = 1;
    const STATUS_CLOSED = 2;

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOpened($query)
    {
        return $query->where('status', self::STATUS_OPENED);
    }

    public function isOpened()
    {
        return $this->status == self::STATUS_OPENED;
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function isClosed()
    {
        return $this->status == self::STATUS_CLOSED;
    }

    public function scopeWithAttachments($query)
    {
        return $query->with('replies.attachments');
    }

    public function isNormalPriority()
    {
        return $this->priority == self::PRIORITY_NORMAL;
    }

    public function isLowPriority()
    {
        return $this->priority == self::PRIORITY_LOW;
    }

    public function isHighPriority()
    {
        return $this->priority == self::PRIORITY_HIGH;
    }

    public function isUrgentPriority()
    {
        return $this->priority == self::PRIORITY_URGENT;
    }

    public function scopeForAgentDepartments($query, $agent)
    {
        $departmentIds = $agent->departments->pluck('id')->toArray();
        return $query->whereIn('department_id', $departmentIds);
    }

    protected $fillable = [
        'subject',
        'priority',
        'status',
        'user_id',
        'department_id',
    ];

    public function getPriority()
    {
        return self::getPriorityOptions()[$this->priority];
    }

    public static function getPriorityOptions()
    {
        return [
            self::PRIORITY_NORMAL => lang('Normal', 'tickets'),
            self::PRIORITY_LOW => lang('Low', 'tickets'),
            self::PRIORITY_HIGH => lang('High', 'tickets'),
            self::PRIORITY_URGENT => lang('Urgent', 'tickets'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

}
