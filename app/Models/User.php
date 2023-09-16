<?php

namespace App\Models;

use App\Methods\Gravatar;
use App\Models\Notification;
use App\Notifications\Admin\AdminNewReplyNotification;
use App\Notifications\Admin\AdminNewTicketNotification;
use App\Notifications\Agent\AgentNewReplyNotification;
use App\Notifications\Agent\AgentNewTicketNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\User\UserNewReplyNotification;
use App\Notifications\User\UserNewTicketNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_AGENT = 'agent';
    const ROLE_ADMIN = 'Admin';

    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 0;

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function scopeBanned($query)
    {
        $query->where('status', self::STATUS_BANNED);
    }

    public function isBanned()
    {
        return $this->status == self::STATUS_BANNED;
    }

    public function isVerified()
    {
        return $this->email_verified_at != null;
    }

    public function has2fa()
    {
        return $this->google2fa_status == 1;
    }

    public function scopeUsers($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'user');
        });
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    public function scopeAgents($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        });
    }

    public function isAgent()
    {
        return $this->hasRole('agent');
    }

    public function hasDepartment($departmentId)
    {
        return $this->departments->contains('id', $departmentId);
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        });
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'avatar',
        'password',
        'ip_address',
        'facebook_id',
        'google_id',
        'google2fa_status',
        'google2fa_secret',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function updateIpAddress()
    {
        $this->ip_address = getIp();
        $this->save();
    }

    public function getName()
    {
        if ($this->firstname && $this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        }
        $emailUsername = explode('@', $this->email);
        return $emailUsername[0];
    }

    public function getAvatar()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return Gravatar::get($this->email);
    }

    public function getEditLink()
    {
        if ($this->isUser()) {
            return route('admin.members.users.edit', $this->id);
        } elseif ($this->isAgent()) {
            return route('admin.members.agents.edit', $this->id);
        } elseif ($this->isAdmin()) {
            return route('admin.members.admins.edit', $this->id);
        }
    }

    public function pushNotify($title, $image, $link = null)
    {
        $notification = new Notification();
        $notification->user_id = $this->id;
        $notification->title = $title;
        $notification->image = $image;
        $notification->link = $link;
        $notification->save();
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        if (settings('actions')->email_verification_status) {
            $this->notify(new VerifyEmailNotification());
        }
    }

    public function sendNewTicketNotification($ticket)
    {
        if ($this->isAgent() && mailTemplate('agent_new_ticket_notification')->status) {
            $this->notify(new AgentNewTicketNotification($ticket));
        } elseif ($this->isAdmin() && mailTemplate('admin_new_ticket_notification')->status) {
            $this->notify(new AdminNewTicketNotification($ticket));
        } elseif ($this->isUser() && mailTemplate('user_new_ticket_notification')->status) {
            $this->notify(new UserNewTicketNotification($ticket));
        }
    }

    public function sendNewReplyNotification($ticketReply)
    {
        if ($this->isAgent() && mailTemplate('agent_new_reply_notification')->status) {
            $this->notify(new AgentNewReplyNotification($ticketReply));
        } elseif ($this->isAdmin() && mailTemplate('admin_new_reply_notification')->status) {
            $this->notify(new AdminNewReplyNotification($ticketReply));
        } elseif ($this->isUser() && mailTemplate('user_new_reply_notification')->status) {
            $this->notify(new UserNewReplyNotification($ticketReply));
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $this->roles()->syncWithoutDetaching($role);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
