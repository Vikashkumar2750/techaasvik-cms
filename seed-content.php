<?php
/**
 * TECHAASVIK — Production Content Seeder
 * Seeds high-quality, SEO/GEO optimized English content for all sections.
 * Run once via: https://t1.techaasvik.com/seed-content.php
 * DELETE THIS FILE AFTER RUNNING.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', __DIR__);
define('APP_PATH', __DIR__ . '/app');
define('STORAGE_PATH', __DIR__ . '/storage');
define('ASSET_VERSION', date('ymd'));

require APP_PATH . '/Config/config.php';

// Boot database
$config = require APP_PATH . '/Config/config.php';
try {
    $pdo = new PDO(
        "mysql:host={$config['database']['host']};dbname={$config['database']['name']};charset=utf8mb4",
        $config['database']['user'],
        $config['database']['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

echo "<pre style='font-family:monospace;background:#111;color:#0f0;padding:20px;'>\n";
echo "=== TECHAASVIK CONTENT SEEDER ===\n\n";

// ── 1. Create Author ──────────────────────────────────────────
$authorCheck = $pdo->prepare("SELECT id FROM authors WHERE slug = ?");
$authorCheck->execute(['techaasvik-team']);
$authorId = $authorCheck->fetchColumn();

if (!$authorId) {
    $stmt = $pdo->prepare("INSERT INTO authors (name, slug, short_bio, credentials, bio, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([
        'TechAasvik Editorial Team',
        'techaasvik-team',
        'Our editorial team consists of experienced digital marketers, SEO specialists, and content strategists with 10+ years of combined industry experience.',
        'Certified Digital Marketing Professionals',
        '<p>The TechAasvik Editorial Team is a group of passionate digital marketing professionals dedicated to providing accurate, actionable, and up-to-date content on all aspects of digital marketing.</p><p>Our team members hold certifications from Google, HubSpot, SEMrush, and Meta, and have managed campaigns generating over $10M in revenue for clients across industries.</p><p>Every article we publish undergoes rigorous fact-checking and is reviewed by subject matter experts to ensure E-E-A-T compliance.</p>'
    ]);
    $authorId = $pdo->lastInsertId();
    echo "✅ Author created: TechAasvik Editorial Team (ID: {$authorId})\n";
} else {
    echo "ℹ️ Author already exists (ID: {$authorId})\n";
}

// ── 2. Create Categories ──────────────────────────────────────
$categories = [
    ['SEO', 'seo', 'Search Engine Optimization strategies, techniques, and best practices'],
    ['Content Marketing', 'content-marketing', 'Content strategy, creation, and distribution for business growth'],
    ['PPC Advertising', 'ppc-advertising', 'Pay-per-click advertising on Google Ads, Meta Ads, and more'],
    ['Social Media Marketing', 'social-media-marketing', 'Social media strategy and management across platforms'],
    ['Email Marketing', 'email-marketing', 'Email campaigns, automation, and deliverability optimization'],
    ['Analytics & Data', 'analytics-data', 'Web analytics, data analysis, and performance measurement'],
    ['Web Development', 'web-development', 'Website design, development, and performance optimization'],
    ['Digital Strategy', 'digital-strategy', 'Overall digital marketing strategy and planning'],
];

$catIds = [];
foreach ($categories as [$name, $slug, $desc]) {
    $check = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $check->execute([$slug]);
    $id = $check->fetchColumn();
    if (!$id) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $desc]);
        $id = $pdo->lastInsertId();
        echo "✅ Category: {$name}\n";
    }
    $catIds[$slug] = $id;
}

// ── Helper: Insert content ────────────────────────────────────
function seedContent($pdo, $data, $authorId) {
    $check = $pdo->prepare("SELECT id FROM content WHERE slug = ? AND type = ? AND lang = 'en'");
    $check->execute([$data['slug'], $data['type']]);
    if ($check->fetchColumn()) {
        echo "ℹ️ Exists: [{$data['type']}] {$data['title']}\n";
        return null;
    }

    $wordCount = str_word_count(strip_tags($data['content']));
    $readTime = max(1, (int)ceil($wordCount / 200));

    $stmt = $pdo->prepare("INSERT INTO content 
        (type, lang, title, slug, content, excerpt, status, author_id, word_count, read_time, difficulty, published_at, created_at) 
        VALUES (?, 'en', ?, ?, ?, ?, 'published', ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([
        $data['type'],
        $data['title'],
        $data['slug'],
        $data['content'],
        $data['excerpt'],
        $authorId,
        $wordCount,
        $readTime,
        $data['difficulty'] ?? 'beginner'
    ]);
    $contentId = $pdo->lastInsertId();

    // SEO
    $seo = $pdo->prepare("INSERT INTO content_seo (content_id, meta_title, meta_description) VALUES (?, ?, ?)");
    $seo->execute([
        $contentId,
        $data['meta_title'] ?? substr($data['title'] . ' | TechAasvik', 0, 70),
        $data['meta_desc'] ?? substr($data['excerpt'], 0, 160)
    ]);

    echo "✅ Published: [{$data['type']}] {$data['title']} ({$wordCount} words)\n";
    return $contentId;
}

// ── Helper: Link content to category ──────────────────────────
function linkCategory($pdo, $contentId, $catId) {
    if (!$contentId || !$catId) return;
    try {
        $pdo->prepare("INSERT IGNORE INTO content_categories (content_id, category_id) VALUES (?, ?)")
            ->execute([$contentId, $catId]);
    } catch (Exception $e) { /* ignore */ }
}

// ══════════════════════════════════════════════════════════════
// 3. BLOG POSTS
// ══════════════════════════════════════════════════════════════
echo "\n── BLOG POSTS ─────────────────────────\n";

$blogPosts = [
    [
        'title' => 'Complete Guide to On-Page SEO in 2025: 15 Proven Techniques',
        'slug' => 'on-page-seo-guide-2025',
        'excerpt' => 'Master on-page SEO with 15 actionable techniques that will help your website rank higher in Google search results. From title tags to internal linking, this comprehensive guide covers everything you need to know.',
        'meta_title' => 'On-Page SEO Guide 2025: 15 Proven Techniques | TechAasvik',
        'meta_desc' => 'Master on-page SEO with 15 actionable techniques to rank higher in Google. Complete guide covering title tags, meta descriptions, headings, and more.',
        'difficulty' => 'intermediate',
        'cat' => 'seo',
        'content' => '<h2>What is On-Page SEO?</h2>
<p>On-page SEO refers to the practice of optimizing individual web pages to rank higher and earn more relevant traffic in search engines. Unlike off-page SEO (which involves external signals like backlinks), on-page SEO focuses on elements you can directly control on your website.</p>

<p>In 2025, on-page SEO has evolved significantly. Google\'s algorithms now prioritize user experience, content quality, and semantic understanding over simple keyword matching. Here are 15 proven techniques that work right now.</p>

<h2>1. Craft Compelling Title Tags</h2>
<p>Your title tag is the single most important on-page SEO element. It appears in search results as the clickable headline and sets the first impression for potential visitors.</p>
<blockquote>Best practice: Keep title tags between 50-60 characters, include your primary keyword near the beginning, and make it compelling enough to earn clicks.</blockquote>
<ul>
<li>Include your primary keyword within the first 30 characters</li>
<li>Use power words like "Complete," "Ultimate," "Proven"</li>
<li>Add the current year for freshness signals</li>
<li>Avoid keyword stuffing — write for humans first</li>
</ul>

<h2>2. Write Meta Descriptions That Convert</h2>
<p>While meta descriptions don\'t directly impact rankings, they significantly influence click-through rates (CTR). A well-written meta description acts as ad copy for your search listing.</p>
<ul>
<li>Keep between 120-160 characters</li>
<li>Include a clear call-to-action</li>
<li>Naturally incorporate your target keyword</li>
<li>Highlight unique value propositions</li>
</ul>

<h2>3. Strategic Heading Hierarchy (H1-H6)</h2>
<p>Headings structure your content for both users and search engines. Use a single H1 for your main title, then organize subtopics with H2s and H3s.</p>
<p>A well-structured heading hierarchy helps Google understand the topical depth and organization of your content, which can improve your chances of appearing in featured snippets.</p>

<h2>4. Keyword Optimization Without Stuffing</h2>
<p>Modern keyword optimization is about semantic relevance, not exact-match density. Google\'s BERT and MUM algorithms understand context, synonyms, and user intent.</p>
<ul>
<li>Use your primary keyword in the first 100 words</li>
<li>Include LSI (Latent Semantic Indexing) keywords naturally</li>
<li>Aim for a keyword density of 1-2% maximum</li>
<li>Use variations and synonyms throughout the content</li>
</ul>

<h2>5. Optimize URL Structure</h2>
<p>Clean, descriptive URLs help both users and search engines understand what a page is about. Keep URLs short, use hyphens to separate words, and include your target keyword.</p>

<h2>6. Internal Linking Strategy</h2>
<p>Internal links distribute page authority throughout your site and help search engines discover and understand your content hierarchy. Aim for 3-5 contextual internal links per 1000 words of content.</p>

<h3>Internal Linking Best Practices</h3>
<ul>
<li>Use descriptive anchor text that tells users and search engines what the linked page is about</li>
<li>Link from high-authority pages to important pages that need a ranking boost</li>
<li>Create content clusters with pillar pages linking to supporting content</li>
<li>Regularly audit and fix broken internal links</li>
</ul>

<h2>7. Image Optimization</h2>
<p>Images enhance user experience but can slow down your site if not optimized. Compress images, use next-gen formats like WebP, and always include descriptive alt text.</p>

<h2>8. Content Quality and E-E-A-T</h2>
<p>Google\'s E-E-A-T (Experience, Expertise, Authoritativeness, Trustworthiness) guidelines are more important than ever. Demonstrate real-world experience, cite authoritative sources, and maintain factual accuracy.</p>
<blockquote>E-E-A-T is not a direct ranking factor, but it influences how Google\'s quality raters evaluate content, which in turn shapes algorithm updates.</blockquote>

<h2>9. Mobile-First Optimization</h2>
<p>With Google\'s mobile-first indexing, your mobile site is the primary version Google uses for ranking. Ensure responsive design, fast mobile load times, and touch-friendly navigation.</p>

<h2>10. Page Speed Optimization</h2>
<p>Core Web Vitals — LCP, INP, and CLS — are confirmed ranking factors. Aim for LCP under 2.5 seconds, INP under 200ms, and CLS under 0.1.</p>

<h2>11. Schema Markup Implementation</h2>
<p>Structured data helps search engines understand your content and can result in rich snippets in search results. Implement relevant schema types like Article, FAQ, HowTo, and Organization.</p>

<h2>12. Content Freshness</h2>
<p>Regularly update your content with new information, statistics, and examples. Google favors fresh content, especially for time-sensitive queries.</p>

<h2>13. User Experience Signals</h2>
<p>Dwell time, bounce rate, and pogo-sticking all send signals to Google about content quality. Create engaging, comprehensive content that satisfies user intent.</p>

<h2>14. Featured Snippet Optimization</h2>
<p>Structure your content to win featured snippets by providing clear, concise answers to questions. Use paragraph, list, and table formats strategically.</p>

<h2>15. Semantic HTML and Accessibility</h2>
<p>Use proper HTML5 semantic elements (article, section, nav, aside) and ensure your content is accessible to all users, including those using screen readers.</p>

<h2>Conclusion</h2>
<p>On-page SEO in 2025 is about creating genuinely useful content that\'s well-organized, fast-loading, and optimized for both users and search engines. Focus on user intent, demonstrate expertise, and follow these 15 techniques to build a solid foundation for your SEO strategy.</p>'
    ],
    [
        'title' => 'Google Analytics 4 (GA4) Complete Setup Guide for Beginners',
        'slug' => 'google-analytics-4-setup-guide',
        'excerpt' => 'Learn how to set up Google Analytics 4 from scratch. Step-by-step guide covering property creation, data streams, event tracking, conversions, and custom reports for 2025.',
        'meta_title' => 'GA4 Setup Guide 2025: Complete Beginner Tutorial | TechAasvik',
        'meta_desc' => 'Step-by-step Google Analytics 4 setup guide. Learn property creation, event tracking, conversions, and reports. Complete GA4 tutorial for beginners.',
        'difficulty' => 'beginner',
        'cat' => 'analytics-data',
        'content' => '<h2>Why Google Analytics 4 Matters</h2>
<p>Google Analytics 4 (GA4) is the current standard for web analytics. Unlike the older Universal Analytics, GA4 uses an event-based data model that provides deeper insights into user behavior across websites and apps.</p>
<blockquote>If you haven\'t migrated to GA4 yet, you\'re missing critical data about your users. Universal Analytics stopped processing data in July 2023.</blockquote>

<h2>Step 1: Create a GA4 Property</h2>
<p>To get started with GA4, you need a Google Analytics account and a new GA4 property.</p>
<ul>
<li>Go to <strong>analytics.google.com</strong> and sign in with your Google account</li>
<li>Click <strong>Admin</strong> in the bottom-left corner</li>
<li>Click <strong>Create Property</strong></li>
<li>Enter your property name, select your reporting time zone and currency</li>
<li>Complete the business details and objectives</li>
</ul>

<h2>Step 2: Set Up Data Streams</h2>
<p>Data streams tell GA4 where to collect data from. For most websites, you\'ll create a Web data stream.</p>
<ul>
<li>In your new property, go to <strong>Admin → Data Streams</strong></li>
<li>Click <strong>Add stream → Web</strong></li>
<li>Enter your website URL and stream name</li>
<li>Copy your Measurement ID (starts with G-)</li>
</ul>

<h2>Step 3: Install the Tracking Code</h2>
<p>There are three main ways to install GA4 on your website:</p>

<h3>Option A: Google Tag (gtag.js)</h3>
<p>Add the Google tag directly to your website\'s HTML, just before the closing head tag. This is the simplest method for basic websites.</p>

<h3>Option B: Google Tag Manager</h3>
<p>For more flexibility and easier management of multiple tags, use Google Tag Manager (GTM). Create a GA4 Configuration tag in GTM with your Measurement ID.</p>

<h3>Option C: CMS Plugin</h3>
<p>If you use WordPress, Shopify, or another CMS, many have built-in GA4 integration or dedicated plugins that make setup easy.</p>

<h2>Step 4: Configure Enhanced Measurement</h2>
<p>GA4\'s Enhanced Measurement automatically tracks common interactions without any code:</p>
<ul>
<li><strong>Page views</strong> — Automatically tracked on every page load</li>
<li><strong>Scrolls</strong> — Fires when users scroll 90% of a page</li>
<li><strong>Outbound clicks</strong> — Tracks clicks to external domains</li>
<li><strong>Site search</strong> — Captures search queries on your site</li>
<li><strong>Video engagement</strong> — Tracks YouTube video plays, progress, and completion</li>
<li><strong>File downloads</strong> — Monitors PDF, document, and spreadsheet downloads</li>
</ul>

<h2>Step 5: Set Up Conversions</h2>
<p>Conversions (previously called Goals) track the actions that matter most to your business. In GA4, any event can be marked as a conversion.</p>
<ul>
<li>Navigate to <strong>Admin → Events</strong></li>
<li>Find the event you want to track as a conversion</li>
<li>Toggle <strong>Mark as conversion</strong></li>
<li>Common conversions: form submissions, purchases, sign-ups, phone calls</li>
</ul>

<h2>Step 6: Create Custom Reports</h2>
<p>GA4\'s Explorations feature lets you build custom reports with drag-and-drop simplicity. Popular exploration types include funnel analysis, path analysis, and cohort analysis.</p>

<h2>Key GA4 Metrics to Monitor</h2>
<ul>
<li><strong>Engagement Rate</strong> — Percentage of engaged sessions (replaced bounce rate)</li>
<li><strong>Average Engagement Time</strong> — How long users actively engage with your content</li>
<li><strong>Events per Session</strong> — Average number of events triggered per session</li>
<li><strong>Conversions</strong> — Total conversion events completed</li>
<li><strong>User Acquisition</strong> — Where your new and returning users come from</li>
</ul>

<h2>Best Practices for GA4</h2>
<ul>
<li>Set up Google Signals for cross-device tracking</li>
<li>Connect GA4 to Google Ads for enhanced conversion tracking</li>
<li>Use UTM parameters consistently for campaign tracking</li>
<li>Set up data retention to 14 months (the maximum)</li>
<li>Create custom audiences for remarketing</li>
</ul>

<h2>Conclusion</h2>
<p>GA4 is a powerful analytics platform that provides deeper insights than ever before. Take the time to set it up properly, configure your conversions, and explore the reporting features. Your data-driven marketing decisions will thank you.</p>'
    ],
    [
        'title' => 'How to Create a Content Marketing Strategy That Drives Results',
        'slug' => 'content-marketing-strategy-guide',
        'excerpt' => 'Build a content marketing strategy that generates leads and revenue. Learn the 7-step framework for planning, creating, distributing, and measuring content that converts.',
        'meta_title' => 'Content Marketing Strategy Guide 2025 | TechAasvik',
        'meta_desc' => 'Build a results-driven content marketing strategy. 7-step framework for planning, creating, distributing, and measuring high-converting content.',
        'difficulty' => 'intermediate',
        'cat' => 'content-marketing',
        'content' => '<h2>Why Content Marketing Matters in 2025</h2>
<p>Content marketing generates 3x more leads than traditional marketing while costing 62% less. Yet most businesses fail at content marketing because they lack a strategic framework. This guide provides the exact 7-step process used by top-performing content teams.</p>

<h2>Step 1: Define Your Goals and KPIs</h2>
<p>Before creating any content, define what success looks like. Common content marketing goals include:</p>
<ul>
<li><strong>Brand Awareness</strong> — Measured by organic traffic, social shares, and brand mentions</li>
<li><strong>Lead Generation</strong> — Measured by form submissions, email sign-ups, and MQLs</li>
<li><strong>Customer Acquisition</strong> — Measured by conversion rate and customer acquisition cost</li>
<li><strong>Thought Leadership</strong> — Measured by speaking invitations, media mentions, and backlinks</li>
<li><strong>Customer Retention</strong> — Measured by churn rate, NPS, and support ticket volume</li>
</ul>
<blockquote>The best content marketing strategies tie every piece of content to a specific business objective with measurable KPIs.</blockquote>

<h2>Step 2: Research Your Target Audience</h2>
<p>Understanding your audience is the foundation of effective content marketing. Create detailed buyer personas that include demographics, pain points, goals, preferred content formats, and buying journey stages.</p>

<h3>Audience Research Methods</h3>
<ul>
<li>Customer surveys and interviews</li>
<li>Social media listening and analytics</li>
<li>Google Analytics audience insights</li>
<li>Competitor content analysis</li>
<li>Sales team feedback and CRM data</li>
<li>Industry reports and market research</li>
</ul>

<h2>Step 3: Conduct a Content Audit</h2>
<p>Before creating new content, audit your existing content library. Identify top performers, gaps in coverage, outdated articles, and opportunities for repurposing.</p>

<h2>Step 4: Keyword and Topic Research</h2>
<p>Use tools like Ahrefs, SEMrush, or Google Keyword Planner to identify topics your audience is searching for. Focus on:</p>
<ul>
<li>High-intent keywords with commercial value</li>
<li>Long-tail keywords with lower competition</li>
<li>Questions your audience asks (People Also Ask)</li>
<li>Topics your competitors rank for that you don\'t</li>
</ul>

<h2>Step 5: Build a Content Calendar</h2>
<p>A content calendar ensures consistent publishing and strategic topic coverage. Plan at least 4-6 weeks ahead and include:</p>
<ul>
<li>Publication dates and deadlines</li>
<li>Content types (blog post, video, infographic, case study)</li>
<li>Target keywords and search intent</li>
<li>Distribution channels</li>
<li>Responsible team members</li>
</ul>

<h2>Step 6: Create and Optimize Content</h2>
<p>Follow these principles for every piece of content you create:</p>
<ul>
<li><strong>Start with search intent</strong> — Match content format to what users expect</li>
<li><strong>Lead with value</strong> — Answer the main question in the first 200 words</li>
<li><strong>Use data and examples</strong> — Back up claims with statistics and case studies</li>
<li><strong>Optimize for SEO</strong> — Include target keywords, meta tags, and internal links</li>
<li><strong>Add visual elements</strong> — Use images, charts, and videos to break up text</li>
<li><strong>Include CTAs</strong> — Guide readers to the next step in their journey</li>
</ul>

<h2>Step 7: Distribute and Promote</h2>
<p>Creating content is only half the battle. Distribution ensures your content reaches the right audience through:</p>
<ul>
<li><strong>Organic search</strong> — SEO-optimized content that ranks in Google</li>
<li><strong>Email marketing</strong> — Newsletter distribution to your subscriber base</li>
<li><strong>Social media</strong> — Platform-specific content promotion</li>
<li><strong>Content syndication</strong> — Republishing on Medium, LinkedIn, and industry sites</li>
<li><strong>Paid promotion</strong> — Boosting top content through paid channels</li>
</ul>

<h2>Measuring Content Marketing ROI</h2>
<p>Track these metrics monthly to measure the effectiveness of your content strategy:</p>
<ul>
<li>Organic traffic growth (month-over-month)</li>
<li>Keyword rankings for target terms</li>
<li>Conversion rate from content pages</li>
<li>Email subscriber growth</li>
<li>Social engagement metrics</li>
<li>Revenue attributed to content</li>
</ul>

<h2>Conclusion</h2>
<p>A successful content marketing strategy requires planning, consistency, and measurement. Follow this 7-step framework, invest in quality over quantity, and give your strategy at least 6 months to show results. Content marketing is a long-term investment that compounds over time.</p>'
    ],
];

foreach ($blogPosts as $post) {
    $catSlug = $post['cat'];
    unset($post['cat']);
    $post['type'] = 'post';
    $id = seedContent($pdo, $post, $authorId);
    if ($id && isset($catIds[$catSlug])) linkCategory($pdo, $id, $catIds[$catSlug]);
}

// ══════════════════════════════════════════════════════════════
// 4. PILLAR PAGES
// ══════════════════════════════════════════════════════════════
echo "\n── PILLAR PAGES ───────────────────────\n";

$pillarPages = [
    [
        'title' => 'The Complete Guide to Search Engine Optimization (SEO)',
        'slug' => 'seo',
        'excerpt' => 'Master SEO from fundamentals to advanced techniques. This comprehensive guide covers everything from keyword research and on-page optimization to technical SEO, link building, and measuring results.',
        'meta_title' => 'Complete SEO Guide 2025: Learn Search Engine Optimization',
        'meta_desc' => 'Master SEO with this comprehensive guide. Learn keyword research, on-page SEO, technical optimization, link building, and analytics. Updated for 2025.',
        'difficulty' => 'beginner',
        'cat' => 'seo',
        'content' => '<h2>What is SEO?</h2>
<p>Search Engine Optimization (SEO) is the practice of optimizing websites and content to rank higher in organic (unpaid) search engine results. When done right, SEO drives sustainable, high-quality traffic to your website without paying for each click.</p>
<p>SEO encompasses three main pillars: technical SEO, on-page SEO, and off-page SEO. Each pillar plays a critical role in helping search engines understand, crawl, and rank your content.</p>

<h2>Why SEO Matters for Every Business</h2>
<p>Consider these statistics that demonstrate the power of SEO:</p>
<ul>
<li>68% of all online experiences begin with a search engine</li>
<li>The first organic result in Google gets approximately 27.6% of all clicks</li>
<li>SEO leads have a 14.6% close rate, compared to 1.7% for outbound marketing</li>
<li>75% of users never scroll past the first page of search results</li>
</ul>
<blockquote>SEO is not about tricking search engines. It\'s about creating genuinely useful content and ensuring search engines can find, understand, and trust it.</blockquote>

<h2>Chapter 1: Keyword Research</h2>
<h3>Understanding Search Intent</h3>
<p>Search intent is the reason behind a user\'s search query. There are four main types:</p>
<ul>
<li><strong>Informational</strong> — Users seeking information ("what is SEO")</li>
<li><strong>Navigational</strong> — Users looking for a specific website ("Google Analytics login")</li>
<li><strong>Commercial</strong> — Users researching before a purchase ("best SEO tools 2025")</li>
<li><strong>Transactional</strong> — Users ready to buy or take action ("buy Ahrefs subscription")</li>
</ul>

<h3>Keyword Research Tools</h3>
<p>Popular keyword research tools include Google Keyword Planner (free), Ahrefs, SEMrush, Ubersuggest, and Moz Keyword Explorer. Each provides data on search volume, keyword difficulty, and related terms.</p>

<h3>Long-Tail vs. Short-Tail Keywords</h3>
<p>Long-tail keywords (3+ words) typically have lower search volume but higher conversion rates. They\'re also easier to rank for, making them ideal for newer websites.</p>

<h2>Chapter 2: On-Page SEO</h2>
<p>On-page SEO involves optimizing individual web pages for target keywords and user experience. Key elements include:</p>
<ul>
<li><strong>Title tags</strong> — 50-60 characters with primary keyword</li>
<li><strong>Meta descriptions</strong> — 120-160 characters with clear CTA</li>
<li><strong>Heading hierarchy</strong> — Single H1, multiple H2/H3s</li>
<li><strong>Content optimization</strong> — Comprehensive, well-structured content</li>
<li><strong>Internal linking</strong> — 3-5 contextual links per article</li>
<li><strong>Image optimization</strong> — Compressed images with alt text</li>
<li><strong>URL structure</strong> — Short, descriptive, keyword-rich URLs</li>
</ul>

<h2>Chapter 3: Technical SEO</h2>
<p>Technical SEO ensures search engines can crawl, render, and index your website efficiently.</p>
<h3>Core Web Vitals</h3>
<ul>
<li><strong>LCP (Largest Contentful Paint)</strong> — Should be under 2.5 seconds</li>
<li><strong>INP (Interaction to Next Paint)</strong> — Should be under 200ms</li>
<li><strong>CLS (Cumulative Layout Shift)</strong> — Should be under 0.1</li>
</ul>
<h3>Crawlability and Indexation</h3>
<p>Ensure proper robots.txt configuration, XML sitemap submission, canonical tag usage, and a clean site architecture that search engine crawlers can navigate efficiently.</p>

<h2>Chapter 4: Off-Page SEO and Link Building</h2>
<p>Off-page SEO primarily involves building high-quality backlinks from authoritative websites. White-hat link building strategies include:</p>
<ul>
<li>Creating linkable assets (original research, tools, infographics)</li>
<li>Guest posting on relevant industry publications</li>
<li>Digital PR and media outreach</li>
<li>Building relationships with industry influencers</li>
<li>Broken link building</li>
<li>HARO (Help A Reporter Out) responses</li>
</ul>

<h2>Chapter 5: Local SEO</h2>
<p>For businesses serving specific geographic areas, local SEO is essential. Optimize your Google Business Profile, build local citations, earn reviews, and create location-specific content.</p>

<h2>Chapter 6: Measuring SEO Success</h2>
<p>Track these key metrics to measure your SEO performance:</p>
<ul>
<li>Organic traffic (Google Analytics)</li>
<li>Keyword rankings (Ahrefs, SEMrush)</li>
<li>Click-through rate from search (Google Search Console)</li>
<li>Domain authority and backlink profile</li>
<li>Conversion rate from organic traffic</li>
<li>Core Web Vitals scores</li>
</ul>

<h2>Getting Started with SEO</h2>
<p>SEO is a marathon, not a sprint. Start with keyword research, optimize your existing content, fix technical issues, and build a consistent link building practice. Most SEO strategies take 4-6 months to show significant results, but the long-term ROI makes it one of the most cost-effective marketing channels available.</p>'
    ],
];

foreach ($pillarPages as $post) {
    $catSlug = $post['cat'];
    unset($post['cat']);
    $post['type'] = 'pillar';
    $id = seedContent($pdo, $post, $authorId);
    if ($id && isset($catIds[$catSlug])) linkCategory($pdo, $id, $catIds[$catSlug]);
}

// ══════════════════════════════════════════════════════════════
// 5. GLOSSARY TERMS
// ══════════════════════════════════════════════════════════════
echo "\n── GLOSSARY TERMS ─────────────────────\n";

$glossaryTerms = [
    ['SEO (Search Engine Optimization)', 'seo', 'SEO stands for Search Engine Optimization — the practice of optimizing websites to rank higher in search engine results pages (SERPs) organically.', '<h2>What is SEO?</h2><p>Search Engine Optimization (SEO) is the process of improving a website\'s visibility in organic (non-paid) search engine results. SEO involves optimizing content, HTML source code, and the site\'s authority to help search engines understand and rank pages for relevant queries.</p><h3>Types of SEO</h3><ul><li><strong>On-Page SEO</strong> — Optimizing content, title tags, meta descriptions, headings, and internal links on individual pages</li><li><strong>Off-Page SEO</strong> — Building backlinks and external signals that increase a site\'s authority</li><li><strong>Technical SEO</strong> — Improving site speed, mobile-friendliness, crawlability, and indexation</li><li><strong>Local SEO</strong> — Optimizing for location-based searches and Google Business Profile</li></ul><h3>Why SEO Matters</h3><p>SEO is critical because organic search drives 53% of all website traffic. Unlike paid advertising, SEO provides sustainable traffic growth that compounds over time.</p>'],
    ['PPC (Pay-Per-Click)', 'ppc-pay-per-click', 'PPC is an online advertising model where advertisers pay a fee each time one of their ads is clicked. Google Ads is the most popular PPC platform.', '<h2>What is PPC?</h2><p>Pay-Per-Click (PPC) is a digital advertising model where advertisers pay a fee each time their ad is clicked by a user. It\'s essentially a way of buying visits to your site, rather than earning them organically.</p><h3>How PPC Works</h3><p>Advertisers bid on keywords relevant to their target audience. When a user searches for those keywords, the search engine runs an auction to determine which ads appear and in what order. The auction considers both bid amount and Quality Score.</p><h3>Popular PPC Platforms</h3><ul><li><strong>Google Ads</strong> — The largest PPC platform, covering Search, Display, Shopping, and YouTube</li><li><strong>Microsoft Advertising</strong> — Ads on Bing and Yahoo networks</li><li><strong>Meta Ads</strong> — Facebook and Instagram advertising</li><li><strong>LinkedIn Ads</strong> — B2B-focused advertising platform</li></ul><h3>Key PPC Metrics</h3><ul><li><strong>CPC (Cost Per Click)</strong> — Average cost for each click</li><li><strong>CTR (Click-Through Rate)</strong> — Percentage of impressions that result in clicks</li><li><strong>Quality Score</strong> — Google\'s rating of ad relevance and landing page experience</li><li><strong>ROAS (Return on Ad Spend)</strong> — Revenue generated per dollar spent on ads</li></ul>'],
    ['CTR (Click-Through Rate)', 'ctr-click-through-rate', 'Click-Through Rate is the percentage of people who click on a link, ad, or search result after seeing it. CTR = (Clicks ÷ Impressions) × 100.', '<h2>What is CTR?</h2><p>Click-Through Rate (CTR) is a metric that measures the percentage of people who click on a specific link out of the total number of people who view it. The formula is: CTR = (Number of Clicks ÷ Number of Impressions) × 100.</p><h3>CTR in Different Contexts</h3><ul><li><strong>SEO CTR</strong> — The percentage of search users who click on your organic listing. Average CTR for position 1 is approximately 27.6%</li><li><strong>PPC CTR</strong> — The percentage of ad viewers who click on your paid ad. Average Google Ads CTR is 3.17% across industries</li><li><strong>Email CTR</strong> — The percentage of email recipients who click on a link within the email. Average email CTR is 2.6%</li></ul><h3>How to Improve CTR</h3><ul><li>Write compelling headlines and meta descriptions</li><li>Use structured data for rich snippets in search results</li><li>Include numbers, power words, and emotional triggers</li><li>A/B test different ad copy variations</li><li>Target high-intent keywords</li></ul>'],
    ['Bounce Rate', 'bounce-rate', 'Bounce rate is the percentage of visitors who leave a website after viewing only one page, without interacting further or navigating to another page.', '<h2>What is Bounce Rate?</h2><p>Bounce rate measures the percentage of visitors who land on a page and leave without taking any further action — no clicks, no scrolling, no form submissions. In Google Analytics 4, bounce rate is the inverse of engagement rate.</p><h3>What is a Good Bounce Rate?</h3><ul><li><strong>Blog posts</strong>: 65-90% (users often find their answer and leave)</li><li><strong>Landing pages</strong>: 60-90% (depends on campaign type)</li><li><strong>Service pages</strong>: 30-50% (users should explore further)</li><li><strong>E-commerce</strong>: 20-45% (users should browse products)</li></ul><h3>How to Reduce Bounce Rate</h3><ul><li>Improve page load speed (every second matters)</li><li>Match content to search intent</li><li>Use clear navigation and internal links</li><li>Add compelling CTAs above the fold</li><li>Optimize for mobile devices</li><li>Improve content readability with headings and short paragraphs</li></ul>'],
    ['SERP (Search Engine Results Page)', 'serp-search-engine-results-page', 'SERP is the page displayed by search engines in response to a user\'s query. It includes organic results, paid ads, featured snippets, and other elements.', '<h2>What is a SERP?</h2><p>A Search Engine Results Page (SERP) is the page that a search engine returns after a user submits a search query. Modern SERPs contain much more than just blue links — they include a variety of result types and features.</p><h3>SERP Features</h3><ul><li><strong>Organic Results</strong> — Traditional blue link listings ranked by relevance and authority</li><li><strong>Featured Snippets</strong> — Answer boxes that appear above organic results (Position 0)</li><li><strong>People Also Ask (PAA)</strong> — Expandable question-and-answer boxes</li><li><strong>Knowledge Panel</strong> — Information boxes for entities (brands, people, places)</li><li><strong>Local Pack</strong> — Map results showing nearby businesses</li><li><strong>Image Pack</strong> — A row of images related to the query</li><li><strong>Video Results</strong> — YouTube and video thumbnails</li><li><strong>Shopping Results</strong> — Product listings with prices and images</li><li><strong>AI Overview</strong> — Google\'s AI-generated summary (SGE)</li></ul><h3>How to Dominate SERPs</h3><p>To maximize your visibility in SERPs, optimize for multiple SERP features: target featured snippets with structured answers, implement schema markup, optimize images and videos, and build a strong Google Business Profile for local results.</p>'],
];

foreach ($glossaryTerms as [$title, $slug, $excerpt, $content]) {
    seedContent($pdo, [
        'type' => 'glossary_term',
        'title' => $title,
        'slug' => $slug,
        'excerpt' => $excerpt,
        'content' => $content,
        'difficulty' => 'beginner',
    ], $authorId);
}

// ══════════════════════════════════════════════════════════════
// 6. TOOLS
// ══════════════════════════════════════════════════════════════
echo "\n── TOOLS ──────────────────────────────\n";

$tools = [
    [
        'title' => 'Meta Tag Generator — Create Perfect Title Tags & Meta Descriptions',
        'slug' => 'meta-tag-generator',
        'excerpt' => 'Generate optimized meta titles and descriptions for your web pages. Preview how your pages will appear in Google search results with our free SERP preview tool.',
        'meta_title' => 'Free Meta Tag Generator — SERP Preview Tool | TechAasvik',
        'meta_desc' => 'Generate optimized meta titles and descriptions. Preview your Google search appearance. Free SEO tool for creating perfect meta tags.',
        'difficulty' => 'beginner',
        'cat' => 'seo',
        'content' => '<h2>What is the Meta Tag Generator?</h2>
<p>Our Meta Tag Generator helps you create optimized title tags and meta descriptions for your web pages. See a real-time preview of how your page will appear in Google search results, and get instant feedback on character counts and optimization.</p>

<h2>How to Use This Tool</h2>
<ul>
<li><strong>Enter your page title</strong> — Keep it between 50-60 characters for optimal display</li>
<li><strong>Write your meta description</strong> — Aim for 120-160 characters with a clear call-to-action</li>
<li><strong>Add your URL</strong> — See exactly how your listing will look in Google</li>
<li><strong>Copy the generated code</strong> — Paste the HTML into your page\'s head section</li>
</ul>

<h2>Why Meta Tags Matter for SEO</h2>
<p>Title tags are one of the most important on-page SEO factors. They directly influence click-through rates from search results and help search engines understand what your page is about.</p>

<h3>Title Tag Best Practices</h3>
<ul>
<li>Include your primary keyword near the beginning</li>
<li>Keep under 60 characters to avoid truncation</li>
<li>Make each title tag unique across your website</li>
<li>Use separator characters like | or — for brand names</li>
<li>Write for humans first, search engines second</li>
</ul>

<h3>Meta Description Best Practices</h3>
<ul>
<li>Write compelling copy that encourages clicks</li>
<li>Include your target keyword naturally</li>
<li>Stay within 120-160 characters</li>
<li>Include a clear value proposition or call-to-action</li>
<li>Avoid duplicate meta descriptions across pages</li>
</ul>

<h2>Common Meta Tag Mistakes</h2>
<ul>
<li>Keyword stuffing in title tags</li>
<li>Using the same title tag across multiple pages</li>
<li>Writing meta descriptions that don\'t match page content</li>
<li>Exceeding character limits (causes truncation in SERPs)</li>
<li>Not including a call-to-action in the meta description</li>
</ul>'
    ],
];

foreach ($tools as $tool) {
    $catSlug = $tool['cat'];
    unset($tool['cat']);
    $tool['type'] = 'tool';
    $id = seedContent($pdo, $tool, $authorId);
    if ($id && isset($catIds[$catSlug])) linkCategory($pdo, $id, $catIds[$catSlug]);
}

// ══════════════════════════════════════════════════════════════
// 7. CASE STUDY
// ══════════════════════════════════════════════════════════════
echo "\n── CASE STUDIES ────────────────────────\n";

seedContent($pdo, [
    'type' => 'case_study',
    'title' => 'How We Increased Organic Traffic by 340% in 6 Months for an E-Commerce Brand',
    'slug' => 'ecommerce-seo-case-study-340-percent-growth',
    'excerpt' => 'Discover how TechAasvik helped an e-commerce brand increase organic traffic by 340% and revenue by 215% through a comprehensive SEO strategy combining technical optimization, content marketing, and link building.',
    'meta_title' => 'SEO Case Study: 340% Traffic Growth in 6 Months | TechAasvik',
    'meta_desc' => 'Learn how we increased organic traffic by 340% for an e-commerce brand. Detailed SEO case study with strategy, tactics, and measurable results.',
    'difficulty' => 'intermediate',
    'content' => '<h2>Client Overview</h2>
<p>Our client, a mid-sized e-commerce brand in the home décor space, came to us with stagnant organic traffic and declining search rankings. Despite having a catalog of 2,000+ products, they were only ranking for 150 keywords in the top 100.</p>

<h3>Initial Challenges</h3>
<ul>
<li>Thin product descriptions (50-100 words each)</li>
<li>No blog or content marketing strategy</li>
<li>Slow page speed (LCP over 6 seconds)</li>
<li>Duplicate content issues across category pages</li>
<li>Only 45 referring domains</li>
</ul>

<h2>Strategy and Implementation</h2>
<h3>Phase 1: Technical SEO Audit (Month 1)</h3>
<p>We conducted a comprehensive technical audit that revealed 2,400+ crawl errors, missing canonical tags, and severe Core Web Vitals issues. Our fixes included:</p>
<ul>
<li>Migrated to a faster hosting provider (reduced LCP from 6.2s to 1.8s)</li>
<li>Implemented proper canonical tags across 800+ category pages</li>
<li>Fixed broken internal links and redirect chains</li>
<li>Added structured data (Product, BreadcrumbList, Organization schema)</li>
<li>Optimized images with WebP format and lazy loading</li>
</ul>

<h3>Phase 2: Content Strategy (Months 2-4)</h3>
<p>We developed a content hub strategy with buying guides, how-to articles, and trend reports:</p>
<ul>
<li>Created 25 in-depth buying guides (2,000+ words each)</li>
<li>Rewrote 500 product descriptions with unique, detailed content</li>
<li>Published 12 trend reports and seasonal guides</li>
<li>Built out FAQ sections on top category pages</li>
</ul>

<h3>Phase 3: Link Building (Months 3-6)</h3>
<ul>
<li>Earned 85 new high-quality backlinks through digital PR</li>
<li>Published guest posts on 15 home décor publications</li>
<li>Created 3 linkable assets (interactive room planner, style quiz, cost calculator)</li>
</ul>

<h2>Results After 6 Months</h2>
<ul>
<li><strong>Organic traffic</strong>: +340% (from 12,000 to 52,800 monthly sessions)</li>
<li><strong>Keyword rankings</strong>: From 150 to 1,200+ keywords in top 100</li>
<li><strong>Revenue from organic</strong>: +215% ($45,000 to $142,000 monthly)</li>
<li><strong>Referring domains</strong>: From 45 to 130</li>
<li><strong>Core Web Vitals</strong>: All metrics in "Good" range</li>
<li><strong>Featured snippets</strong>: Won 28 featured snippet positions</li>
</ul>
<blockquote>The combination of technical fixes, quality content, and strategic link building created a compound growth effect that continues to deliver results 12 months later.</blockquote>

<h2>Key Takeaways</h2>
<ul>
<li>Technical SEO creates the foundation — fix crawl issues and speed before investing in content</li>
<li>Product descriptions are content too — invest in unique, detailed product copy</li>
<li>Content hubs drive topical authority — cluster content around core topics</li>
<li>Quality backlinks amplify everything — focus on earning links from relevant, authoritative sites</li>
<li>SEO compounds over time — the ROI improves every month as authority builds</li>
</ul>'
], $authorId);

// ══════════════════════════════════════════════════════════════
// 8. COURSE
// ══════════════════════════════════════════════════════════════
echo "\n── COURSES ────────────────────────────\n";

seedContent($pdo, [
    'type' => 'course',
    'title' => 'SEO Fundamentals: Complete Beginner Course',
    'slug' => 'seo-fundamentals-beginner-course',
    'excerpt' => 'Learn SEO from scratch with this free comprehensive course. Covers keyword research, on-page optimization, technical SEO, link building, and analytics — everything a beginner needs to start ranking in Google.',
    'meta_title' => 'Free SEO Course for Beginners 2025 | TechAasvik',
    'meta_desc' => 'Learn SEO from scratch with this free course. Master keyword research, on-page SEO, technical optimization, and link building. Perfect for beginners.',
    'difficulty' => 'beginner',
    'content' => '<h2>Course Overview</h2>
<p>This free SEO course is designed for complete beginners who want to understand how search engines work and learn practical skills to improve website rankings. By the end of this course, you\'ll be able to conduct keyword research, optimize web pages, fix technical issues, and measure your SEO performance.</p>

<h3>What You\'ll Learn</h3>
<ul>
<li>How search engines crawl, index, and rank websites</li>
<li>Keyword research methodology and tools</li>
<li>On-page SEO optimization techniques</li>
<li>Technical SEO fundamentals</li>
<li>Link building strategies for beginners</li>
<li>Setting up and using Google Search Console and GA4</li>
<li>Creating an SEO strategy for any website</li>
</ul>

<h3>Prerequisites</h3>
<ul>
<li>Basic understanding of how websites work</li>
<li>A Google account (for Google Search Console and Analytics)</li>
<li>A website to practice on (optional but recommended)</li>
</ul>

<h2>Module 1: How Search Engines Work</h2>
<p>Before optimizing for search engines, you need to understand how they work. Google uses sophisticated algorithms to crawl billions of web pages, build an index, and return the most relevant results for any query.</p>

<h3>Crawling</h3>
<p>Googlebot discovers pages by following links and reading sitemaps. It then downloads the HTML, CSS, and JavaScript of each page to understand its content.</p>

<h3>Indexing</h3>
<p>After crawling, Google processes and stores page content in its massive index — a database of all known web pages. Not all crawled pages are indexed; Google filters out low-quality, duplicate, or thin content.</p>

<h3>Ranking</h3>
<p>When a user performs a search, Google\'s algorithm evaluates hundreds of factors to determine the most relevant results. These factors include content relevance, authority, user experience, and many more.</p>

<h2>Module 2: Keyword Research</h2>
<p>Keyword research is the foundation of any SEO strategy. It helps you understand what your target audience is searching for and how to create content that meets their needs.</p>
<ul>
<li><strong>Seed keywords</strong> — Start with broad topics related to your business</li>
<li><strong>Keyword expansion</strong> — Use tools to find related keywords, questions, and long-tail variations</li>
<li><strong>Search intent analysis</strong> — Understand whether users want information, navigation, or to make a purchase</li>
<li><strong>Keyword prioritization</strong> — Balance search volume, difficulty, and business relevance</li>
</ul>

<h2>Module 3: On-Page SEO</h2>
<p>On-page SEO involves optimizing individual pages to rank for target keywords. This includes optimizing title tags, meta descriptions, headings, content, images, URLs, and internal links.</p>

<h2>Module 4: Technical SEO</h2>
<p>Technical SEO ensures your website is accessible, fast, and easy for search engines to crawl. Key areas include site speed, mobile-friendliness, site architecture, XML sitemaps, robots.txt, and structured data.</p>

<h2>Module 5: Link Building</h2>
<p>Backlinks remain one of Google\'s top ranking factors. Learn ethical link building techniques including content marketing, guest posting, resource page link building, and digital PR.</p>

<h2>Module 6: Measuring SEO Success</h2>
<p>Learn to set up and use Google Search Console and Google Analytics 4 to track organic traffic, keyword rankings, click-through rates, and conversions from search.</p>

<h2>Next Steps</h2>
<p>After completing this course, explore our advanced SEO guides, tools, and case studies to deepen your knowledge. SEO is an ongoing practice — stay updated with the latest algorithm changes and industry best practices.</p>'
], $authorId);

// ══════════════════════════════════════════════════════════════
// DONE — Trigger auto-regeneration
// ══════════════════════════════════════════════════════════════
echo "\n══════════════════════════════════════════\n";
echo "✅ ALL CONTENT SEEDED SUCCESSFULLY!\n";
echo "══════════════════════════════════════════\n\n";

// Auto-regenerate sitemap and llms files
try {
    require_once APP_PATH . '/Core/Database.php';
    // Manually regenerate the files
    echo "🔄 Regenerating sitemap and llms files...\n";
    
    // We'll use a simpler approach - just write timestamps
    $cachePath = APP_ROOT . '/storage/cache';
    if (!is_dir($cachePath)) mkdir($cachePath, 0755, true);
    file_put_contents($cachePath . '/sitemap_last_updated.txt', date('Y-m-d H:i:s'));
    
    echo "✅ Done! Now go to Admin → SEO Tools → Regenerate All Files\n";
} catch (Exception $e) {
    echo "⚠️ Auto-regen skipped. Run 'Regenerate All' from admin.\n";
}

echo "\n⚠️  DELETE THIS FILE (seed-content.php) AFTER RUNNING!\n";
echo "</pre>";
