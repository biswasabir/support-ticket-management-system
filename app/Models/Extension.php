<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    protected $fillable = [
        'name',
        'alias',
        'logo',
        'credentials',
        'status',
    ];

    protected $casts = [
        'credentials' => 'object',
    ];

    public function setCredentials()
    {
        switch ($this->alias) {
            case 'google_recaptcha':
                setEnv('NOCAPTCHA_SITEKEY', $this->credentials->site_key);
                setEnv('NOCAPTCHA_SECRET', $this->credentials->secret_key);
                break;
        }
    }
}
