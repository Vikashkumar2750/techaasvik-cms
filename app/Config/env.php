<?php
/**
 * Environment (.env) Loader
 * Parses .env file into $_ENV and getenv()
 * Call ONCE from index.php before anything else
 */

function loadEnv(string $envPath): void
{
    if (!file_exists($envPath)) {
        return; // .env optional; server may use real env vars
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        // Split on first = only
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // Strip surrounding quotes
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        if ($key && !isset($_ENV[$key])) {
            $_ENV[$key]    = $value;
            putenv("{$key}={$value}");
        }
    }
}

/**
 * Get an env variable with an optional default.
 * Use this instead of getenv() everywhere in app code.
 */
function env(string $key, mixed $default = null): mixed
{
    // Check all sources — $_ENV may be disabled on some hosts (variables_order without 'E')
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    $ge = getenv($key);
    if ($ge !== false && $ge !== '') return $ge;
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}
