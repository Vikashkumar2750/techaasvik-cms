<?php
/**
 * TechAasvik — Local Config Override
 * This file is OUTSIDE the git directory — safe from deploy overwrites.
 * Path: /home/u375939934/config.local.php
 * 
 * ⚠️ NEVER commit this file to git. It contains live credentials.
 */
return [

    // ── Database ──────────────────────────────────────────────
    'database' => [
        'host'    => 'localhost',
        'name'    => 'u375939934_t1_db',
        'user'    => 'u375939934_t1_user',
        'pass'    => 'Yu@vg@06iP!2',
        'charset' => 'utf8mb4',
        'port'    => 3306,
    ],

    // ── Razorpay (Live Keys) ───────────────────────────────────
    // These are read by env() function via config.php bridge
    'razorpay' => [
        'key_id'         => 'rzp_live_S6yFy0aX6jTn0d',
        'key_secret'     => 'TjOifRuW1Gs4QxRZwCZIDhk8',
        'webhook_secret' => 'Tujheterimaakikasamhaiagarphonemangato',
    ],

];
