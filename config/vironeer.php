<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Settings
    |--------------------------------------------------------------------------
    |
    | Configure various system settings here, such as the system status and admin.
    | The system status can be controlled using the VR_SYSTEM_STATUS environment variable.
    | Default value is 0, which indicates the system is inactive.
    |
     */
    'system' => [
        'status' => env('VR_SYSTEM_STATUS', 0), // System status (0 for inactive, 1 for active)
    ],

    /*
    |--------------------------------------------------------------------------
    | Front and Admin Colors
    |--------------------------------------------------------------------------
    |
    | Define the paths to the CSS files for front and admin colors here.
    | These files determine the color scheme used in the respective areas.
    |
     */
    'colors' => [
        'front' => 'assets/css/colors.css', // Path to front colors CSS file
        'admin' => 'assets/vendor/admin/css/colors.css', // Path to admin colors CSS file
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom CSS
    |--------------------------------------------------------------------------
    |
    | Add your custom CSS file path here.
    | This CSS file will be loaded in the front to apply any specific customizations.
    |
     */
    'css' => [
        'custom' => 'assets/css/custom.css', // Path to custom CSS file
    ],

];