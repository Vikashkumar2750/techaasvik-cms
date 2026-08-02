<?php
namespace Controllers\Admin;

use Core\Controller;
use Core\View;
use Models\Setting;

/**
 * Admin Settings Controller
 */
class SettingsController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $setting = new Setting();
        $all     = $this->groupSettings($setting->getAll());
        $flash   = $this->getFlash();

        $this->adminView('settings/index', [
            'pageTitle' => 'Site Settings',
            'groups'    => $all,
            'flash'     => $flash,
        ]);
    }

    public function update(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $allowedKeys = [
            'site_name','site_tagline','site_email','site_phone',
            'ga4_id','gtm_id','gsc_verify',
            'fb_pixel','twitter_pixel',
            'newsletter_provider','newsletter_api_key','newsletter_list_id',
            'smtp_host','smtp_port','smtp_user','smtp_pass','smtp_from',
            'posts_per_page','enable_comments','enable_hindi',
            'footer_text','about_text','og_default_image',
            'twitter_handle','linkedin_url','youtube_url',
            'seo_default_title_suffix','seo_separator',
        ];

        $setting = new Setting();
        foreach ($allowedKeys as $key) {
            $value = $this->request->post($key);
            if ($value !== null) {
                $setting->set($key, trim($value));
            }
        }

        $this->flash('success', 'Settings saved successfully.');
        View::redirect('/techaasvik_admin/settings');
    }

    private function groupSettings(array $rows): array
    {
        $groups = [
            'General'    => ['site_name','site_tagline','site_email','site_phone','footer_text','about_text'],
            'Analytics'  => ['ga4_id','gtm_id','gsc_verify','fb_pixel','twitter_pixel'],
            'SEO'        => ['seo_default_title_suffix','seo_separator','og_default_image'],
            'Social'     => ['twitter_handle','linkedin_url','youtube_url'],
            'Email'      => ['smtp_host','smtp_port','smtp_user','smtp_pass','smtp_from'],
            'Newsletter' => ['newsletter_provider','newsletter_api_key','newsletter_list_id'],
            'Content'    => ['posts_per_page','enable_comments','enable_hindi'],
        ];

        $map = [];
        foreach ($rows as $row) {
            $map[$row['setting_key']] = $row['setting_value'];
        }

        $result = [];
        foreach ($groups as $group => $keys) {
            foreach ($keys as $key) {
                $result[$group][] = ['key' => $key, 'value' => $map[$key] ?? ''];
            }
        }
        return $result;
    }

    private function requireAdmin(): void
    {
        \Core\Auth::startSession();
        \Core\Auth::requireAdmin();
    }
}
