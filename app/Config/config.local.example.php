<?php
/**
 * LOCAL CONFIG OVERRIDES — NOT tracked by Git.
 * Put your real DB credentials, API keys, etc. here.
 * This file is auto-merged over config.php values.
 *
 * On Hostinger server, create this file at:
 *   app/Config/config.local.php
 */

return [

    'database' => [
        'host'    => 'localhost',
        'name'    => 'u375939934_t1_db',
        'user'    => 'u375939934_t1_admin',
        'pass'    => 'YOUR_REAL_PASSWORD_HERE',
        'charset' => 'utf8mb4',
        'port'    => 3306,
    ],

    // Uncomment and fill as needed:
    // 'analytics' => [
    //     'ga4_id' => 'G-XXXXXXXXXX',
    //     'gtm_id' => 'GTM-XXXXXXX',
    // ],

    // 'mail' => [
    //     'username' => 'real_brevo_user',
    //     'password' => 'real_brevo_key',
    // ],

];
