<?php
namespace Controllers;

use Core\Controller;
use Services\LlmsTxtService;

/**
 * SEO Controller — serves llms.txt and llms-full.txt via PHP when
 * static files are not available or blocked by server configuration.
 */
class SeoController extends Controller
{
    /**
     * Serve /llms.txt
     * First tries static file, then generates on-the-fly.
     */
    public function llmsTxt(array $params = []): void
    {
        $staticFile = APP_ROOT . '/llms.txt';
        
        // Try static file first
        if (file_exists($staticFile) && filesize($staticFile) > 0) {
            $content = file_get_contents($staticFile);
        } else {
            // Generate on-the-fly
            $svc = new LlmsTxtService();
            $content = $svc->generateLlmsTxt();
        }

        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        echo $content;
        exit;
    }

    /**
     * Serve /llms-full.txt
     * First tries static file, then generates on-the-fly.
     */
    public function llmsFullTxt(array $params = []): void
    {
        $staticFile = APP_ROOT . '/llms-full.txt';
        
        if (file_exists($staticFile) && filesize($staticFile) > 0) {
            $content = file_get_contents($staticFile);
        } else {
            $svc = new LlmsTxtService();
            $content = $svc->generateLlmsFullTxt();
        }

        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        echo $content;
        exit;
    }
}
