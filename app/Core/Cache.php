<?php
namespace Core;

/**
 * Cache — dual-driver file/APCu cache system.
 * Falls back to file cache if APCu is not available.
 */
class Cache
{
    private static ?Cache $instance = null;
    private string $driver;
    private string $cachePath;
    private bool   $enabled;

    private function __construct()
    {
        $config          = require APP_PATH . '/Config/config.php';
        $this->driver    = function_exists('apcu_enabled') && apcu_enabled() ? 'apcu' : 'file';
        $this->cachePath = $config['cache']['path'] ?? STORAGE_PATH . '/cache';
        $this->enabled   = $config['cache']['enabled'] ?? false;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // ── Get cached value ──────────────────────────────────
    public function get(string $key): mixed
    {
        if (!$this->enabled) return null;

        if ($this->driver === 'apcu') {
            $value = apcu_fetch($this->prefix($key), $success);
            return $success ? $value : null;
        }

        $file = $this->filePath($key);
        if (!file_exists($file)) return null;

        $data = unserialize(file_get_contents($file));
        if ($data['expires'] !== 0 && $data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    // ── Set cached value ──────────────────────────────────
    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        if (!$this->enabled) return false;

        if ($this->driver === 'apcu') {
            return apcu_store($this->prefix($key), $value, $ttl);
        }

        $data = [
            'value'   => $value,
            'expires' => $ttl === 0 ? 0 : time() + $ttl,
        ];

        return (bool) file_put_contents(
            $this->filePath($key),
            serialize($data),
            LOCK_EX
        );
    }

    // ── Delete a key ──────────────────────────────────────
    public function delete(string $key): void
    {
        if ($this->driver === 'apcu') {
            apcu_delete($this->prefix($key));
            return;
        }

        $file = $this->filePath($key);
        if (file_exists($file)) unlink($file);
    }

    // ── Get or compute & cache ─────────────────────────────
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $cached = $this->get($key);
        if ($cached !== null) return $cached;

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }

    // ── Flush all cache ───────────────────────────────────
    public function flush(): void
    {
        if ($this->driver === 'apcu') {
            apcu_clear_cache();
            return;
        }

        // Delete all .cache files
        foreach (glob($this->cachePath . '/fragments/*.cache') as $file) {
            unlink($file);
        }
        foreach (glob($this->cachePath . '/pages/*.cache') as $file) {
            unlink($file);
        }
    }

    // ── Full page caching ────────────────────────────────
    public function pageStart(string $url, int $ttl = 3600): bool
    {
        if (!$this->enabled) return false;

        $key   = 'page_' . md5($url);
        $cached = $this->get($key);

        if ($cached) {
            echo $cached;
            return true;
        }

        ob_start();
        return false;
    }

    public function pageEnd(string $url, int $ttl = 3600): void
    {
        if (!$this->enabled) return;
        $key  = 'page_' . md5($url);
        $html = ob_get_contents();
        $this->set($key, $html, $ttl);
        ob_end_flush();
    }

    // ── Helpers ──────────────────────────────────────────
    private function prefix(string $key): string
    {
        return 'ta_' . $key;
    }

    private function filePath(string $key): string
    {
        return $this->cachePath . '/fragments/' . md5($key) . '.cache';
    }

    private function __clone() {}
}
