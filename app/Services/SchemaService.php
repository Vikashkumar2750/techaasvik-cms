<?php
namespace Services;

/**
 * Schema Service — generates JSON-LD structured data for all content types.
 * Every schema is valid against schema.org and Google's requirements.
 */
class SchemaService
{
    private array $config;
    private string $siteUrl;
    private string $siteName;

    public function __construct()
    {
        $this->config   = require APP_PATH . '/Config/config.php';
        $this->siteUrl  = rtrim($this->config['site']['url'], '/');
        $this->siteName = $this->config['site']['name'];
    }

    // ── Organization schema (for homepage / global) ───────
    public function organization(): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            '@id'         => $this->siteUrl . '/#organization',
            'name'        => $this->siteName,
            'url'         => $this->siteUrl,
            'logo'        => [
                '@type' => 'ImageObject',
                'url'   => $this->siteUrl . $this->config['site']['logo'],
            ],
            'sameAs'      => [
                'https://linkedin.com/company/techaasvik',
                'https://youtube.com/@techaasvik',
                'https://instagram.com/techaasvik',
                'https://twitter.com/techaasvik',
            ],
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'email'       => $this->config['site']['email'],
                'contactType' => 'customer service',
            ],
        ];
    }

    // ── WebSite schema with SearchAction ──────────────────
    public function website(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            '@id'             => $this->siteUrl . '/#website',
            'name'            => $this->siteName,
            'url'             => $this->siteUrl,
            'description'     => $this->config['site']['tagline'],
            'inLanguage'      => 'en-IN',
            'publisher'       => ['@id' => $this->siteUrl . '/#organization'],
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $this->siteUrl . '/search?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    // ── Article / Blog Post schema ─────────────────────────
    public function article(array $content, array $seo, string $schemaType = 'Article'): array
    {
        $schema = [
            '@context'         => 'https://schema.org',
            '@type'            => $schemaType,
            'headline'         => $content['title'],
            'description'      => $seo['meta_description'] ?? '',
            'url'              => $seo['canonical'],
            'datePublished'    => $this->formatDate($content['published_at']),
            'dateModified'     => $this->formatDate($content['updated_at'] ?? $content['published_at']),
            'publisher'        => ['@id' => $this->siteUrl . '/#organization'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $seo['canonical']],
            'inLanguage'       => $content['lang'] === 'hi' ? 'hi-IN' : 'en-IN',
        ];

        if (!empty($content['featured_image_url'])) {
            $schema['image'] = [
                '@type'  => 'ImageObject',
                'url'    => $content['featured_image_url'],
                'width'  => 1200,
                'height' => 630,
            ];
        }

        if (!empty($content['author_name'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name'  => $content['author_name'],
                'url'   => $this->siteUrl . '/authors/' . ($content['author_slug'] ?? ''),
            ];
        }

        if (!empty($content['word_count'])) {
            $schema['wordCount'] = (int)$content['word_count'];
        }

        return $schema;
    }

    // ── BreadcrumbList ────────────────────────────────────
    public function breadcrumbs(array $crumbs): array
    {
        $items = [];
        foreach ($crumbs as $i => $crumb) {
            $item = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'],
            ];
            if (!empty($crumb['url'])) {
                $item['item'] = $this->siteUrl . $crumb['url'];
            }
            $items[] = $item;
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    // ── FAQPage schema ────────────────────────────────────
    public function faqPage(array $faqs): array
    {
        $entities = array_map(fn($faq) => [
            '@type'          => 'Question',
            'name'           => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => strip_tags($faq['answer']),
            ],
        ], $faqs);

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    // ── HowTo schema ─────────────────────────────────────
    public function howTo(array $data): array
    {
        $steps = array_map(fn($step, $i) => [
            '@type'    => 'HowToStep',
            'position' => $i + 1,
            'name'     => $step['name'],
            'text'     => strip_tags($step['text']),
        ], $data['steps'], array_keys($data['steps']));

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'HowTo',
            'name'        => $data['title'],
            'description' => $data['description'] ?? '',
            'step'        => $steps,
        ];
    }

    // ── DefinedTerm (Glossary) ────────────────────────────
    public function definedTerm(array $content, string $termUrl): array
    {
        return [
            '@context'       => 'https://schema.org',
            '@type'          => 'DefinedTerm',
            'name'           => $content['title'],
            'description'    => strip_tags($content['excerpt'] ?? ''),
            'url'            => $termUrl,
            'inDefinedTermSet' => [
                '@type' => 'DefinedTermSet',
                'name'  => 'Digital Marketing Glossary',
                'url'   => $this->siteUrl . '/glossary',
            ],
        ];
    }

    // ── Course schema ─────────────────────────────────────
    public function course(array $content, array $extras = []): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Course',
            'name'        => $content['title'],
            'description' => strip_tags($content['excerpt'] ?? ''),
            'url'         => $this->siteUrl . '/courses/' . $content['slug'],
            'provider'    => ['@id' => $this->siteUrl . '/#organization'],
            'courseMode'  => 'online',
            'inLanguage'  => 'en-IN',
            'isAccessibleForFree' => (bool)($extras['is_free'] ?? true),
            'teaches'     => $extras['teaches'] ?? [],
        ];
    }

    // ── SoftwareApplication (Tool) ────────────────────────
    public function softwareApp(array $content, array $extras = []): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'SoftwareApplication',
            'name'            => $content['title'],
            'description'     => strip_tags($content['excerpt'] ?? ''),
            'url'             => $this->siteUrl . '/tools/' . $content['slug'],
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web Browser',
            'offers'          => [
                '@type'       => 'Offer',
                'price'       => '0',
                'priceCurrency' => 'INR',
            ],
        ];
    }

    // ── Person / Author schema ────────────────────────────
    public function person(array $author): array
    {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Person',
            '@id'         => $this->siteUrl . '/authors/' . $author['slug'] . '/#person',
            'name'        => $author['name'],
            'url'         => $this->siteUrl . '/authors/' . $author['slug'],
            'description' => strip_tags($author['bio'] ?? ''),
            'worksFor'    => ['@id' => $this->siteUrl . '/#organization'],
        ];

        if (!empty($author['social_links'])) {
            $links = json_decode($author['social_links'], true) ?? [];
            if ($links) $schema['sameAs'] = array_values($links);
        }

        return $schema;
    }

    // ── VideoObject schema ────────────────────────────────
    public function videoObject(array $content): array
    {
        $meta = json_decode($content['meta_json'] ?? '{}', true);
        return [
            '@context'       => 'https://schema.org',
            '@type'          => 'VideoObject',
            'name'           => $content['title'],
            'description'    => strip_tags($content['excerpt'] ?? ''),
            'thumbnailUrl'   => $meta['thumbnail_url'] ?? '',
            'uploadDate'     => $this->formatDate($content['published_at']),
            'contentUrl'     => $meta['video_url'] ?? '',
            'embedUrl'       => $meta['embed_url'] ?? '',
            'publisher'      => ['@id' => $this->siteUrl . '/#organization'],
        ];
    }

    // ── Dataset (Statistics) ─────────────────────────────
    public function dataset(array $content): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Dataset',
            'name'        => $content['title'],
            'description' => strip_tags($content['excerpt'] ?? ''),
            'url'         => $this->siteUrl . '/statistics/' . $content['slug'],
            'creator'     => ['@id' => $this->siteUrl . '/#organization'],
            'dateModified'=> $this->formatDate($content['updated_at'] ?? $content['published_at']),
            'inLanguage'  => 'en-IN',
            'license'     => 'https://creativecommons.org/licenses/by/4.0/',
        ];
    }

    // ── Render JSON-LD <script> tag ───────────────────────
    public function render(array $schema): string
    {
        $json = json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return '<script type="application/ld+json">' . $json . '</script>';
    }

    // ── Render multiple schemas ───────────────────────────
    public function renderAll(array $schemas): string
    {
        return implode("\n", array_map([$this, 'render'], $schemas));
    }



    // ── Date formatter ────────────────────────────────────
    private function formatDate(?string $date): string
    {
        if (!$date) return date('c');
        return date('c', strtotime($date));
    }
}

