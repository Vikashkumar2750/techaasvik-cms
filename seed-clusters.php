<?php
/**
 * Seed SEO sub-topics (cluster pages) + Site Pages
 * Run once via admin update route
 */
return [
    'clusters' => [
        [
            'slug'        => 'on-page-seo',
            'title'       => 'On-Page SEO: Complete Optimization Guide 2026',
            'excerpt'     => 'Master every on-page SEO element — from title tags and meta descriptions to content optimization, header structure, and internal linking strategies that directly improve rankings.',
            'difficulty'  => 'beginner',
            'menu_order'  => 1,
            'read_time'   => 15,
            'content'     => <<<'HTML'
<h2 id="section-1">What is On-Page SEO?</h2>
<p>On-page SEO refers to all optimizations you make <strong>directly on your web pages</strong> to improve their search engine rankings and earn more relevant organic traffic. Unlike off-page SEO (backlinks) or technical SEO (site infrastructure), on-page SEO focuses on content and HTML source code elements that you have full control over.</p>
<p>In 2026, on-page SEO has evolved beyond keyword stuffing. Google's algorithms now evaluate <strong>content quality, topical depth, user experience signals, and entity-based relevance</strong>. This guide covers every actionable on-page optimization technique.</p>

<h2 id="section-2">Title Tag Optimization</h2>
<p>The title tag is the single most impactful on-page ranking factor. It appears as the clickable headline in Google search results and directly influences both rankings and click-through rate (CTR).</p>
<h3>Title Tag Best Practices</h3>
<ul>
<li><strong>Length:</strong> Keep titles between 50-60 characters (approximately 600 pixels wide)</li>
<li><strong>Primary keyword first:</strong> Place your target keyword within the first 3-5 words</li>
<li><strong>Unique per page:</strong> Every page must have a distinct title — never duplicate across your site</li>
<li><strong>Power words:</strong> Include modifiers like "Complete," "2026," "Guide," "Best," "Free" to boost CTR</li>
<li><strong>Brand suffix:</strong> Add your brand name at the end using a pipe or dash separator: <code>Primary Keyword — Brand</code></li>
</ul>

<h2 id="section-3">Meta Description Optimization</h2>
<p>While meta descriptions aren't a direct ranking factor, they significantly impact CTR. Google bolds matching keywords in the description, and a compelling description can increase clicks by <strong>5.8% on average</strong> (Backlinko, 2026).</p>
<ul>
<li>Write 150-160 characters that accurately summarize the page</li>
<li>Include your primary keyword naturally (it gets bolded in results)</li>
<li>Add a clear call-to-action: "Learn how," "Discover," "Get started"</li>
<li>Make it compelling — treat it like ad copy for your page</li>
</ul>

<h2 id="section-4">Header Structure (H1-H6)</h2>
<p>Headers create a hierarchical structure that helps both users and search engines understand your content organization.</p>
<ul>
<li><strong>One H1 per page:</strong> Your main title, containing the primary keyword</li>
<li><strong>H2s for main sections:</strong> Use question-based H2s for featured snippet opportunities</li>
<li><strong>H3-H4 for subsections:</strong> Create logical depth without skipping levels</li>
<li><strong>Never skip levels:</strong> Don't jump from H2 to H4 — maintain proper hierarchy</li>
<li><strong>Include keywords naturally:</strong> Don't force keywords into every heading</li>
</ul>

<h2 id="section-5">Content Optimization</h2>
<p>Content quality is the foundation of on-page SEO. Google's E-E-A-T framework (Experience, Expertise, Authoritativeness, Trustworthiness) evaluates whether your content deserves to rank.</p>
<h3>Keyword Integration</h3>
<ul>
<li>Use primary keyword in the first 100 words</li>
<li>Include semantically related terms (LSI keywords) throughout</li>
<li>Target keyword density: 1-2% (natural, not forced)</li>
<li>Use keyword variations and synonyms</li>
</ul>
<h3>Content Depth</h3>
<ul>
<li>Cover the topic comprehensively — address all user questions</li>
<li>Include original data, case studies, and expert insights</li>
<li>Use visual elements: images, infographics, tables, and charts</li>
<li>Update content regularly with current statistics and examples</li>
</ul>

<h2 id="section-6">Internal Linking Strategy</h2>
<p>Internal links distribute PageRank across your site, establish topical hierarchy, and help users discover related content. Every important page should be reachable within <strong>3 clicks from the homepage</strong>.</p>
<ul>
<li><strong>Descriptive anchor text:</strong> Use keyword-rich anchors, not "click here"</li>
<li><strong>Link to relevant pages:</strong> Only link when it adds value for the reader</li>
<li><strong>Pillar-cluster model:</strong> Link sub-topic pages back to the main topic page and vice versa</li>
<li><strong>Fix orphan pages:</strong> Every published page should have at least one internal link pointing to it</li>
<li><strong>Optimal count:</strong> Include 3-5 internal links per 1,000 words of content</li>
</ul>

<h2 id="section-7">Image Optimization</h2>
<p>Images can drive significant traffic through Google Image Search and improve on-page engagement.</p>
<ul>
<li><strong>Alt text:</strong> Describe the image accurately, include keywords when natural</li>
<li><strong>File names:</strong> Use descriptive filenames like <code>on-page-seo-checklist.png</code></li>
<li><strong>Compression:</strong> Use WebP format and compress to reduce file size without quality loss</li>
<li><strong>Dimensions:</strong> Set explicit width and height attributes to prevent CLS</li>
<li><strong>Lazy loading:</strong> Add <code>loading="lazy"</code> for below-the-fold images</li>
</ul>
HTML,
        ],
        [
            'slug'        => 'off-page-seo',
            'title'       => 'Off-Page SEO & Link Building Strategy 2026',
            'excerpt'     => 'Build authority through strategic backlink acquisition, digital PR, brand mentions, and social signals. Learn white-hat link building techniques that compound over time.',
            'difficulty'  => 'intermediate',
            'menu_order'  => 2,
            'read_time'   => 18,
            'content'     => <<<'HTML'
<h2 id="section-1">What is Off-Page SEO?</h2>
<p>Off-page SEO encompasses all optimization activities that happen <strong>outside your website</strong> to improve its search engine rankings. The primary focus is building your site's <strong>authority, relevance, and trustworthiness</strong> through high-quality backlinks, brand mentions, and social proof.</p>
<p>Google's algorithm uses over 200 ranking factors, and backlinks remain one of the top 3 most influential signals. However, in 2026, the quality and relevance of links matter far more than quantity.</p>

<h2 id="section-2">Link Building Fundamentals</h2>
<p>A backlink is a link from another website pointing to your site. Search engines treat these as "votes of confidence" — the more high-quality votes you receive, the more authoritative your site appears.</p>
<h3>Link Quality Factors</h3>
<ul>
<li><strong>Domain Authority (DA):</strong> Links from DA 40+ sites carry significantly more weight</li>
<li><strong>Topical Relevance:</strong> Links from sites in your industry/niche are most valuable</li>
<li><strong>Editorial Placement:</strong> In-content links > sidebar/footer links</li>
<li><strong>Follow vs. Nofollow:</strong> Follow links pass PageRank; nofollow links still provide brand exposure</li>
<li><strong>Anchor Text:</strong> Diverse, natural anchor text distribution is essential</li>
</ul>

<h2 id="section-3">Content-Led Link Building</h2>
<p>Creating linkable assets is the most sustainable link building strategy. When you publish genuinely useful content, other sites naturally reference and link to it.</p>
<h3>Linkable Asset Types</h3>
<ul>
<li><strong>Original research and data studies</strong> — Industry surveys, data analyses, benchmark reports</li>
<li><strong>Comprehensive guides</strong> — The definitive resource on a topic (like this one)</li>
<li><strong>Free tools and calculators</strong> — Interactive resources that solve specific problems</li>
<li><strong>Infographics and visual data</strong> — Shareable visual content with embed codes</li>
<li><strong>Expert roundups and interviews</strong> — Featuring industry leaders who share with their audiences</li>
</ul>

<h2 id="section-4">Digital PR for Link Building</h2>
<p>Digital PR combines traditional public relations with SEO objectives. The goal is to earn coverage (and links) from authoritative news sites and publications.</p>
<ul>
<li><strong>Newsjacking:</strong> Provide expert commentary on trending stories in your industry</li>
<li><strong>Data-driven stories:</strong> Analyze proprietary data and pitch findings to journalists</li>
<li><strong>HARO/Connectively:</strong> Respond to journalist queries to earn quoted mentions with backlinks</li>
<li><strong>Press releases:</strong> Announce genuinely newsworthy events (product launches, partnerships, milestones)</li>
</ul>

<h2 id="section-5">Guest Posting Strategy</h2>
<p>Guest posting remains effective when done strategically — targeting relevant, high-authority publications your audience reads.</p>
<ul>
<li>Target sites with DA 30+ and genuine organic traffic</li>
<li>Write content that genuinely serves the host site's audience</li>
<li>Include 1-2 contextual links back to your site (not more)</li>
<li>Build relationships with editors for repeat opportunities</li>
<li>Avoid guest post farms and paid link schemes</li>
</ul>

<h2 id="section-6">Brand Signals & Social Proof</h2>
<p>Google increasingly uses brand signals as a ranking factor. Strong brands tend to rank higher because they represent trustworthiness.</p>
<ul>
<li><strong>Brand mentions:</strong> Unlinked brand mentions still provide authority signals</li>
<li><strong>Social media presence:</strong> Active social profiles validate your brand's legitimacy</li>
<li><strong>Google Business Profile:</strong> A verified, active GBP strengthens brand trust signals</li>
<li><strong>Reviews and testimonials:</strong> Third-party reviews on platforms like G2, Clutch, and Google Maps</li>
</ul>

<h2 id="section-7">Measuring Off-Page SEO Success</h2>
<ul>
<li><strong>Referring domains:</strong> Track new unique domains linking to your site monthly</li>
<li><strong>Domain Authority/Rating:</strong> Monitor your DA/DR trend over time (Ahrefs/Moz)</li>
<li><strong>Branded search volume:</strong> Increasing brand searches indicate growing authority</li>
<li><strong>Organic rankings:</strong> Track keyword position improvements after link building campaigns</li>
</ul>
HTML,
        ],
        [
            'slug'        => 'technical-seo',
            'title'       => 'Technical SEO: Site Health & Performance Guide 2026',
            'excerpt'     => 'Ensure search engines can crawl, index, and render your website perfectly. Master Core Web Vitals, structured data, crawl budget optimization, and site architecture.',
            'difficulty'  => 'advanced',
            'menu_order'  => 3,
            'read_time'   => 20,
            'content'     => <<<'HTML'
<h2 id="section-1">What is Technical SEO?</h2>
<p>Technical SEO is the process of optimizing your website's <strong>infrastructure, performance, and crawlability</strong> to help search engines efficiently discover, crawl, render, and index your pages. Without a solid technical foundation, even the best content won't rank.</p>
<p>In 2026, technical SEO is more important than ever. Google's rendering engine processes JavaScript, evaluates Core Web Vitals as ranking signals, and uses sophisticated crawl budget algorithms.</p>

<h2 id="section-2">Core Web Vitals (2026 Thresholds)</h2>
<p>Core Web Vitals are Google's page experience metrics that directly impact search rankings:</p>
<ul>
<li><strong>Largest Contentful Paint (LCP):</strong> Must be ≤ 2.5 seconds — measures loading performance of the largest visible element</li>
<li><strong>Interaction to Next Paint (INP):</strong> Must be ≤ 200ms — measures responsiveness to user interactions (replaced FID in 2024)</li>
<li><strong>Cumulative Layout Shift (CLS):</strong> Must be ≤ 0.1 — measures visual stability of page elements</li>
</ul>
<h3>How to Improve LCP</h3>
<ul>
<li>Optimize server response time (TTFB < 800ms)</li>
<li>Implement CDN for static assets</li>
<li>Compress and serve images in WebP/AVIF format</li>
<li>Preload critical resources with <code>&lt;link rel="preload"&gt;</code></li>
<li>Remove render-blocking CSS and JavaScript</li>
</ul>

<h2 id="section-3">Crawlability & Indexation</h2>
<p>If Google can't find and crawl your pages, they can't rank. Crawl optimization ensures search engines can efficiently discover all your important content.</p>
<ul>
<li><strong>XML Sitemap:</strong> Submit to Google Search Console. Include only indexable, canonical URLs. Update dynamically when content changes</li>
<li><strong>Robots.txt:</strong> Control which pages Googlebot can crawl. Never block CSS/JS files. Use <code>Crawl-delay</code> only if necessary</li>
<li><strong>Robots meta tags:</strong> Use <code>noindex</code> for thin/duplicate pages, <code>nofollow</code> for untrusted links</li>
<li><strong>Canonical tags:</strong> Specify the preferred URL version with <code>rel="canonical"</code> on every page</li>
<li><strong>Pagination:</strong> Use <code>rel="next"</code> and <code>rel="prev"</code> for paginated content series</li>
</ul>

<h2 id="section-4">Structured Data (Schema.org)</h2>
<p>Structured data helps search engines understand the context and meaning of your content. It enables rich results (rich snippets) in search results — FAQ accordions, star ratings, recipe cards, and more.</p>
<h3>Essential Schema Types for 2026</h3>
<ul>
<li><strong>Article/BlogPosting:</strong> For blog posts and articles</li>
<li><strong>FAQPage:</strong> For pages with frequently asked questions (triggers FAQ rich results)</li>
<li><strong>HowTo:</strong> For step-by-step instructions</li>
<li><strong>Product:</strong> For e-commerce product pages with price, availability, and reviews</li>
<li><strong>LocalBusiness:</strong> For businesses serving local customers</li>
<li><strong>BreadcrumbList:</strong> For navigation breadcrumbs</li>
<li><strong>Organization:</strong> For your company/brand information</li>
</ul>

<h2 id="section-5">Site Architecture</h2>
<p>A flat, logical site architecture helps both users and search engines navigate your content efficiently.</p>
<ul>
<li><strong>3-click rule:</strong> Every important page should be reachable within 3 clicks from the homepage</li>
<li><strong>URL structure:</strong> Use short, descriptive, keyword-rich URLs: <code>/learn/seo/on-page-seo</code></li>
<li><strong>Breadcrumbs:</strong> Implement breadcrumb navigation on every page</li>
<li><strong>Internal linking:</strong> Create a hub-and-spoke model linking topic pages to sub-topic pages</li>
<li><strong>Orphan pages:</strong> Every published page needs at least one internal link pointing to it</li>
</ul>

<h2 id="section-6">Mobile-First Indexing</h2>
<p>Google uses the mobile version of your site for indexing and ranking. Your mobile experience must be flawless:</p>
<ul>
<li>Responsive design that works on all screen sizes (320px to 2560px+)</li>
<li>Mobile page speed under 3 seconds on 4G connections</li>
<li>Touch-friendly buttons with minimum 48×48px tap targets</li>
<li>No intrusive interstitials (popups that block content)</li>
<li>Same content on mobile and desktop (don't hide content on mobile)</li>
</ul>

<h2 id="section-7">Technical SEO Audit Checklist</h2>
<ol>
<li>Run Screaming Frog crawl to identify broken links, redirect chains, missing meta tags</li>
<li>Check Core Web Vitals in Google Search Console and PageSpeed Insights</li>
<li>Verify XML sitemap is submitted and includes all indexable URLs</li>
<li>Test robots.txt with Google's Robots Testing Tool</li>
<li>Validate structured data with Google's Rich Results Test</li>
<li>Check mobile usability in Search Console</li>
<li>Review HTTPS implementation (no mixed content)</li>
<li>Analyze crawl stats in Search Console for crawl budget issues</li>
</ol>
HTML,
        ],
        [
            'slug'        => 'local-seo-guide',
            'title'       => 'Local SEO for Indian Businesses 2026',
            'excerpt'     => 'Dominate local search results with Google Business Profile optimization, local citations, review management, and location-specific content strategies for Indian markets.',
            'difficulty'  => 'beginner',
            'menu_order'  => 4,
            'read_time'   => 14,
            'content'     => <<<'HTML'
<h2 id="section-1">What is Local SEO?</h2>
<p>Local SEO optimizes your online presence to attract customers from <strong>location-specific searches</strong>. When someone searches "digital marketing agency near me" or "SEO services Delhi," local SEO determines which businesses appear in the Google Map Pack and local organic results.</p>
<p>For Indian businesses, local SEO is the highest-ROI digital marketing investment. With <strong>46% of all Google searches having local intent</strong>, businesses that optimize for local search capture ready-to-buy customers at the moment of decision.</p>

<h2 id="section-2">Google Business Profile Optimization</h2>
<p>Your Google Business Profile (GBP) is the foundation of local SEO. It powers your listing in Google Maps and the Local Pack (3-pack) that appears for local searches.</p>
<h3>Complete Every Field</h3>
<ul>
<li><strong>Business name:</strong> Exact legal business name (no keyword stuffing)</li>
<li><strong>Category:</strong> Choose the most specific primary category + up to 9 secondary categories</li>
<li><strong>Address:</strong> Exact, consistent address matching all other listings (NAP consistency)</li>
<li><strong>Phone:</strong> Local phone number (not toll-free) for Indian businesses</li>
<li><strong>Hours:</strong> Keep hours accurate, including special hours for holidays</li>
<li><strong>Description:</strong> 750-character description with natural keyword inclusion</li>
<li><strong>Photos:</strong> Upload 10+ high-quality photos (exterior, interior, team, products)</li>
</ul>

<h2 id="section-3">NAP Consistency & Citations</h2>
<p>NAP (Name, Address, Phone number) consistency across the web is a critical local ranking factor. Google cross-references your business information across directories to verify legitimacy.</p>
<h3>Essential Indian Directories</h3>
<ul>
<li><strong>JustDial</strong> — India's largest local search engine</li>
<li><strong>Sulekha</strong> — Service provider directory</li>
<li><strong>IndiaMART</strong> — B2B marketplace (for B2B businesses)</li>
<li><strong>TradeIndia</strong> — B2B trade directory</li>
<li><strong>Yellow Pages India</strong> — Business directory</li>
<li><strong>Google Maps, Apple Maps, Bing Places</strong> — Essential search engine listings</li>
</ul>

<h2 id="section-4">Review Management Strategy</h2>
<p>Reviews are the second most important local ranking factor (after GBP optimization). Google explicitly uses review quantity, quality, and recency in its local algorithm.</p>
<ul>
<li><strong>Ask systematically:</strong> Send WhatsApp follow-ups after service completion with a direct Google review link</li>
<li><strong>Respond to ALL reviews</strong> within 24 hours — both positive and negative</li>
<li><strong>Handle negative reviews professionally:</strong> Acknowledge the issue, apologize, offer to resolve offline</li>
<li><strong>Never buy fake reviews:</strong> Google actively detects and penalizes fake review patterns</li>
<li><strong>Diversify platforms:</strong> Get reviews on JustDial, Facebook, and industry-specific platforms too</li>
</ul>

<h2 id="section-5">Local Content Strategy</h2>
<p>Create content that targets location-specific keywords to rank for local searches.</p>
<ul>
<li><strong>City-specific service pages:</strong> Create unique pages for each city you serve (e.g., "SEO Services in Mumbai")</li>
<li><strong>Local case studies:</strong> Showcase results from local clients with location mentions</li>
<li><strong>Local guides:</strong> "Best Coworking Spaces in Bangalore" — useful local content that earns links</li>
<li><strong>Event coverage:</strong> Cover local industry events and conferences</li>
</ul>

<h2 id="section-6">Local Schema Markup</h2>
<p>Implement structured data to help Google understand your local business information:</p>
<ul>
<li><strong>LocalBusiness schema:</strong> Include name, address, phone, hours, geo coordinates</li>
<li><strong>GeoCoordinates:</strong> Exact latitude and longitude of your business location</li>
<li><strong>AggregateRating:</strong> Display your average review rating in search results</li>
<li><strong>Service area:</strong> Specify which cities/regions you serve</li>
</ul>
HTML,
        ],
        [
            'slug'        => 'voice-search-seo',
            'title'       => 'Voice Search SEO & Conversational Optimization 2026',
            'excerpt'     => 'Optimize for voice search queries on Google Assistant, Alexa, and Siri. Learn conversational keyword strategies, featured snippet optimization, and FAQ schema for voice results.',
            'difficulty'  => 'advanced',
            'menu_order'  => 5,
            'read_time'   => 12,
            'content'     => <<<'HTML'
<h2 id="section-1">The Rise of Voice Search in India</h2>
<p>Voice search has transformed how Indians interact with search engines. With <strong>35% of Indian mobile searches now voice-initiated</strong> (Google India, 2026), optimizing for voice is no longer optional — it's essential.</p>
<p>Key drivers of voice search adoption in India:</p>
<ul>
<li><strong>Multilingual queries:</strong> Google Assistant supports 9 Indian languages, making search accessible to non-English speakers</li>
<li><strong>Smartphone penetration:</strong> 750+ million smartphone users, many preferring voice over typing</li>
<li><strong>Smart speakers:</strong> Growing adoption of Google Home and Alexa devices in Indian households</li>
<li><strong>Convenience:</strong> Voice is 3.7× faster than typing for search queries</li>
</ul>

<h2 id="section-2">How Voice Search Differs from Text Search</h2>
<p>Voice queries are fundamentally different from typed queries:</p>
<ul>
<li><strong>Longer queries:</strong> Average voice query is 29 words vs. 3-4 words for text</li>
<li><strong>Conversational tone:</strong> "What's the best SEO strategy for small businesses?" vs. "SEO strategy small business"</li>
<li><strong>Question-based:</strong> 70% of voice searches begin with who, what, where, when, why, or how</li>
<li><strong>Local intent:</strong> 58% of voice searches are "near me" queries</li>
<li><strong>Single answer:</strong> Voice assistants typically read only the #1 result (Position Zero)</li>
</ul>

<h2 id="section-3">Optimizing Content for Voice Search</h2>
<h3>Target Conversational Keywords</h3>
<ul>
<li>Research question-based keywords using AnswerThePublic, AlsoAsked, and Google's "People Also Ask"</li>
<li>Include natural language phrases your audience actually speaks</li>
<li>Target long-tail conversational queries: "How much does SEO cost for a small business in India?"</li>
</ul>
<h3>Write for Featured Snippets (Position Zero)</h3>
<p>Voice assistants pull answers from featured snippets. To win Position Zero:</p>
<ul>
<li><strong>Answer directly:</strong> Provide a clear, concise answer in 40-60 words immediately after the question</li>
<li><strong>Use question-as-heading:</strong> Make the question an H2 or H3, answer immediately below</li>
<li><strong>Format for snippets:</strong> Use numbered lists for processes, bullet lists for features, tables for comparisons</li>
<li><strong>Already rank on page 1:</strong> 99.58% of featured snippets come from page 1 results</li>
</ul>

<h2 id="section-4">FAQ Schema for Voice Results</h2>
<p>FAQPage schema markup tells Google your page contains questions and answers, increasing the chance of appearing in voice search results and FAQ rich results.</p>
<ul>
<li>Add FAQPage schema to pages with 3+ Q&A pairs</li>
<li>Write clear, complete answers (2-3 sentences each)</li>
<li>Match questions to actual voice queries your audience uses</li>
<li>Validate with Google's Rich Results Test before publishing</li>
</ul>

<h2 id="section-5">Local Voice Search Optimization</h2>
<p>Since 58% of voice searches have local intent, local businesses have a massive opportunity:</p>
<ul>
<li><strong>Optimize Google Business Profile:</strong> Complete all fields — voice assistants pull from GBP data</li>
<li><strong>"Near me" optimization:</strong> Include neighborhood, city, and landmark references in content</li>
<li><strong>Operating hours:</strong> Keep GBP hours accurate — "Is [business] open now?" is a top voice query</li>
<li><strong>FAQ page:</strong> Create a comprehensive FAQ addressing common customer questions</li>
</ul>

<h2 id="section-6">Voice Search SEO Checklist</h2>
<ol>
<li>Research conversational long-tail keywords for your niche</li>
<li>Add question-based H2/H3 headings throughout your content</li>
<li>Provide direct answers in 40-60 words after each question heading</li>
<li>Implement FAQPage schema markup on relevant pages</li>
<li>Optimize for featured snippets (lists, tables, direct answers)</li>
<li>Ensure mobile page speed is under 3 seconds</li>
<li>Complete and optimize Google Business Profile for local voice queries</li>
<li>Create content in conversational, natural language tone</li>
</ol>
HTML,
        ],
    ],
    'pages' => [
        ['slug' => 'homepage', 'title' => 'Homepage', 'content' => '<p>Homepage content managed via admin.</p>'],
        ['slug' => 'about', 'title' => 'About TechAasvik', 'content' => '<p>About page content.</p>'],
        ['slug' => 'contact', 'title' => 'Contact Us', 'content' => '<p>Contact page content.</p>'],
        ['slug' => 'terms-of-service', 'title' => 'Terms of Service', 'content' => '<p>Terms of service content.</p>'],
        ['slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'content' => '<p>Privacy policy content.</p>'],
        ['slug' => 'disclaimer', 'title' => 'Disclaimer', 'content' => '<p>Disclaimer content.</p>'],
        ['slug' => 'editorial-policy', 'title' => 'Editorial Policy', 'content' => '<p>Editorial policy content.</p>'],
    ],
];
