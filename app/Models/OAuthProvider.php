<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OAuthProvider extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    protected $table = "oauth_providers";

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    protected $fillable = [
        'name',
        'alias',
        'icon',
        'credentials',
        'instructions',
        'status',
    ];

    protected $casts = [
        'credentials' => 'object',
    ];

    public function setCredentials()
    {
        switch ($this->alias) {
            case 'facebook':
                setEnv('FACEBOOK_CLIENT_ID', $this->credentials->client_id);
                setEnv('FACEBOOK_CLIENT_SECRET', $this->credentials->client_secret);
                break;
            case 'google':
                setEnv('GOOGLE_CLIENT_ID', $this->credentials->client_id);
                setEnv('GOOGLE_CLIENT_SECRET', $this->credentials->client_secret);
                break;
        }
    }
}
