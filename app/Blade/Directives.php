<?php

namespace App\Blade;

use Illuminate\Support\Facades\Blade;

class Directives
{
    public static function render()
    {
        Blade::directive('customStyle', function () {
            return '<link rel="stylesheet" href="' . asset(config('vironeer.css.custom')) . '">';
        });

        Blade::directive('frontColors', function () {
            return '<link rel="stylesheet" href="' . asset(config('vironeer.colors.front')) . '">';
        });

        Blade::directive('adminColors', function () {
            return '<link rel="stylesheet" href="' . asset(config('vironeer.colors.admin')) . '">';
        });

    }
}