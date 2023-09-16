<?php

function extensionAvailability($name)
{
    if (!extension_loaded($name)) {
        $response = false;
    } else {
        $response = true;
    }
    return $response;
}

function phpExtensions()
{
    $extensions = [
        'BCMath',
        'Ctype',
        'Fileinfo',
        'JSON',
        'Mbstring',
        'OpenSSL',
        'PDO',
        'pdo_mysql',
        'Tokenizer',
        'XML',
        'cURL',
        'zip',
        'GD',
    ];
    return $extensions;
}

function filePermissionValidation($name)
{
    $perm = substr(sprintf('%o', fileperms($name)), -4);
    if ($perm >= '0775') {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}

function filePermissions()
{
    $filePermissions = [
        base_path('bootstrap/'),
        base_path('bootstrap/cache/'),
        base_path('plugins/'),
        base_path('lang/'),
        base_path('lang/en/'),
        base_path('public/'),
        base_path('public/images/'),
        base_path('public/media/'),
        base_path('storage/'),
        base_path('storage/app/'),
        base_path('storage/framework/'),
        base_path('storage/logs/'),
        base_path('vendor/'),
        base_path('vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer/'),
    ];
    return $filePermissions;
}

function baseCounter($data, $count)
{
    $r = $data;
    for ($i = 0; $i < $count; $i++) {
        $r = base64_decode($r);
    }
    return $r;
}

function activeStep($array)
{
    if (in_array(request()->segment(2), $array)) {
        return 'active';
    }
    return '';
}
