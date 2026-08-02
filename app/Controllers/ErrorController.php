<?php
namespace Controllers;

use Core\Controller;
use Core\View;

/**
 * Error Controller — handles HTTP errors gracefully.
 */
class ErrorController extends Controller
{
    public function notFound(array $params = []): void
    {
        View::status(404);
        $this->view('404', [
            'seo' => [
                'meta_title'       => '404 — Page Not Found | TechAasvik',
                'meta_description' => 'The page you were looking for could not be found.',
                'noindex'          => true,
            ],
            'schemas' => [],
        ]);
    }

    public function serverError(array $params = []): void
    {
        View::status(500);
        $this->view('500', [
            'seo' => [
                'meta_title' => '500 — Server Error | TechAasvik',
                'noindex'    => true,
            ],
            'schemas' => [],
        ]);
    }
}
