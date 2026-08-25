<?php
// Temporary OPcache reset — DELETE this file after use!
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo '✅ OPcache cleared successfully.';
} else {
    echo '⚠️ OPcache not available (or already disabled).';
}
echo '<br><small>Delete this file immediately after use!</small>';
