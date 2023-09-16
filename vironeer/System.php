<?php

namespace Vironeer;

class System
{
    public const ALIAS = "fowtickets";
    public const VERSION = "2.0";
    public const AUTHOR = "Vironeer";
    public const EMAIL = "support@vironeer.com";
    public const PROFILE = "https://codecanyon.net/user/vironeer";
    public const WEBSITE = "https://vironeer.com";
    public const LICENSE_URL = "http://license.vironeer.com/api/v1/license";

    public function info()
    {
        return (object) [
            'status' => config('vironeer.system.status'),
            'alias' => self::ALIAS,
            'version' => self::VERSION,
            'author' => self::AUTHOR,
            'email' => self::EMAIL,
            'profile' => self::PROFILE,
            'website' => self::WEBSITE,
            'license_url' => self::LICENSE_URL,
        ];
    }
}
