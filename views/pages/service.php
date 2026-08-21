<!-- Single Service Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Services','url'=>'/services'],['name'=>ucwords(str_replace('-', ' ', $slug ?? 'Service'))]]]) ?>

  <?php
  // Static service content data
  $serviceData = [
    'seo' => [
      'icon' => '🔍',
      'title' => 'SEO Services — Search Engine Optimization',
      'subtitle' => 'Rank Higher, Drive Organic Traffic, and Dominate Google Search Results',
      'features' => [
        ['icon' => '⚙️', 'title' => 'Technical SEO Audit', 'desc' => 'Comprehensive site audit covering crawlability, indexing, Core Web Vitals, schema markup, site architecture, and 200+ technical checkpoints.'],
        ['icon' => '📝', 'title' => 'On-Page SEO', 'desc' => 'Content optimization, keyword research, meta tags, internal linking strategy, heading structure, and E-E-A-T signal enhancement.'],
        ['icon' => '🔗', 'title' => 'Off-Page SEO & Link Building', 'desc' => 'White-hat link acquisition through digital PR, guest posting, broken link building, and HARO outreach campaigns.'],
        ['icon' => '📍', 'title' => 'Local SEO', 'desc' => 'Google Business Profile optimization, local citations, review management, and local pack ranking strategies.'],
        ['icon' => '🛒', 'title' => 'E-Commerce SEO', 'desc' => 'Product page optimization, category structure, faceted navigation, product schema, and marketplace SEO (Amazon, Flipkart).'],
        ['icon' => '📊', 'title' => 'SEO Analytics & Reporting', 'desc' => 'Custom dashboards, keyword tracking, competitor analysis, and monthly performance reports with actionable insights.'],
      ],
      'process' => ['Discovery & Audit', 'Keyword Research & Strategy', 'Technical Optimization', 'Content Creation & Optimization', 'Link Building & Authority', 'Monitoring & Reporting'],
      'stats' => [['value' => '340%', 'label' => 'Avg. Traffic Increase'], ['value' => '85%', 'label' => 'Client Retention Rate'], ['value' => '4.5x', 'label' => 'Average ROI'], ['value' => '200+', 'label' => 'Keywords Ranked']],
    ],
    'google-ads' => [
      'icon' => '📢',
      'title' => 'Google Ads Management — PPC Advertising',
      'subtitle' => 'Maximize ROI with Expertly Managed Google Ads Campaigns',
      'features' => [
        ['icon' => '🔍', 'title' => 'Search Campaigns', 'desc' => 'High-intent keyword targeting on Google Search. Responsive search ads, ad extensions, and Quality Score optimization for maximum visibility.'],
        ['icon' => '🖼️', 'title' => 'Display & Remarketing', 'desc' => 'Visual banner ads across 3M+ websites in the Google Display Network. Smart remarketing to re-engage past visitors and recover abandoned carts.'],
        ['icon' => '🛍️', 'title' => 'Shopping Campaigns', 'desc' => 'Product listing ads with images, prices, and reviews. Google Merchant Center optimization for maximum ROAS on e-commerce products.'],
        ['icon' => '🎬', 'title' => 'YouTube Advertising', 'desc' => 'Video ad campaigns on YouTube reaching 2B+ users. In-stream, bumper, discovery, and Shorts ads for brand awareness and conversions.'],
        ['icon' => '🤖', 'title' => 'Performance Max', 'desc' => 'AI-powered campaigns across all Google channels. Machine learning optimization for bids, audiences, and creative combinations.'],
        ['icon' => '📈', 'title' => 'Conversion Tracking & Analytics', 'desc' => 'Full funnel tracking setup with Google Ads + GA4 integration. Attribution modeling, A/B testing, and ROI analysis.'],
      ],
      'process' => ['Account Audit & Strategy', 'Keyword Research & Planning', 'Campaign Setup & Structuring', 'Ad Copy & Creative Development', 'Bid & Budget Optimization', 'A/B Testing & Scaling'],
      'stats' => [['value' => '5.2x', 'label' => 'Average ROAS'], ['value' => '42%', 'label' => 'Lower CPA vs Industry'], ['value' => '₹10Cr+', 'label' => 'Ad Spend Managed'], ['value' => '150+', 'label' => 'Campaigns Optimized']],
    ],
    'meta-ads' => [
      'icon' => '📱',
      'title' => 'Meta Ads — Facebook & Instagram Advertising',
      'subtitle' => 'Find Your Ideal Customer with Hyper-Targeted Facebook & Instagram Campaigns Powered by Meta\'s AI.',
      'features' => [
        ['icon' => '🎯', 'title' => 'Audience Research & Targeting', 'desc' => 'Deep audience research using Meta\'s interest targeting, behavioral segments, demographic layers, and custom audiences built from your CRM, website visitors, and app users.'],
        ['icon' => '🤖', 'title' => 'Advantage+ AI Campaigns', 'desc' => 'Meta\'s AI-powered Advantage+ Shopping and Advantage+ Audience campaigns that automatically find your best customers and optimize delivery for maximum ROAS.'],
        ['icon' => '🎨', 'title' => 'Creative Strategy & Production', 'desc' => 'High-converting ad creatives — static images, carousels, video ads, Reels ads, and Stories ads. Thumb-stopping visuals and direct-response copywriting that drives action.'],
        ['icon' => '🔄', 'title' => 'Retargeting & Lookalike Audiences', 'desc' => 'Multi-stage retargeting sequences that re-engage past visitors, cart abandoners, and video viewers. Lookalike audience expansion using your best customers as seeds.'],
        ['icon' => '📊', 'title' => 'Full-Funnel Campaign Strategy', 'desc' => 'Top-of-funnel awareness, middle-funnel consideration, and bottom-funnel conversion campaigns working together. Attribution setup across the complete customer journey.'],
        ['icon' => '📈', 'title' => 'Meta Ads Analytics & Reporting', 'desc' => 'Weekly performance reports covering ROAS, CPA, CPL, frequency, reach, and creative performance. A/B testing framework for continuous campaign improvement.'],
      ],
      'process' => ['Account Audit & Pixel Setup', 'Audience Research & Strategy', 'Creative Development', 'Campaign Launch & Structuring', 'A/B Testing & Optimization', 'Scaling & Monthly Reporting'],
      'stats' => [['value' => '4.8x', 'label' => 'Average ROAS Delivered'], ['value' => '38%', 'label' => 'Lower Cost Per Lead'], ['value' => '3B+', 'label' => 'Meta Monthly Active Users'], ['value' => '100+', 'label' => 'Meta Campaigns Managed']],
    ],
    'social-media' => [
      'icon' => '📱',
      'title' => 'Social Media Marketing',
      'subtitle' => 'Build Brand Awareness, Engage Your Audience, and Drive Revenue from Social',
      'features' => [
        ['icon' => '📋', 'title' => 'Strategy Development', 'desc' => 'Platform-specific strategies for Facebook, Instagram, LinkedIn, Twitter/X, and emerging platforms. Audience research and competitive analysis.'],
        ['icon' => '🎨', 'title' => 'Content Creation', 'desc' => 'Scroll-stopping creatives — graphic design, short-form video (Reels, Shorts), carousels, stories, and branded templates.'],
        ['icon' => '👥', 'title' => 'Community Management', 'desc' => 'Daily engagement, comment moderation, DM handling, and community building. Proactive audience interaction and brand voice management.'],
        ['icon' => '💰', 'title' => 'Paid Social Advertising', 'desc' => 'Meta Ads, LinkedIn Ads, and Twitter Ads management. Audience targeting, lookalike audiences, and retargeting campaigns.'],
        ['icon' => '🤝', 'title' => 'Influencer Marketing', 'desc' => 'Influencer identification, outreach, campaign management, and performance tracking. Micro and macro influencer collaborations.'],
        ['icon' => '📊', 'title' => 'Analytics & Reporting', 'desc' => 'Monthly social media reports with engagement metrics, growth analysis, content performance, and competitor benchmarking.'],
      ],
      'process' => ['Social Audit & Research', 'Strategy & Content Calendar', 'Content Creation & Scheduling', 'Community Engagement', 'Paid Campaign Management', 'Monthly Performance Review'],
      'stats' => [['value' => '3.5x', 'label' => 'Engagement Increase'], ['value' => '250%', 'label' => 'Follower Growth'], ['value' => '45%', 'label' => 'Higher Reach'], ['value' => '50+', 'label' => 'Brands Managed']],
    ],
    'content-marketing' => [
      'icon' => '✍️',
      'title' => 'Content Marketing Services',
      'subtitle' => 'Strategic Content That Attracts, Engages, and Converts Your Ideal Customers',
      'features' => [
        ['icon' => '🎯', 'title' => 'Content Strategy', 'desc' => 'Data-driven content planning aligned with your business goals. Buyer persona research, content gap analysis, and editorial calendar development.'],
        ['icon' => '📝', 'title' => 'Blog & Article Writing', 'desc' => 'SEO-optimized, expert-written articles by subject matter specialists. Long-form guides, listicles, how-tos, and thought leadership pieces.'],
        ['icon' => '📊', 'title' => 'Infographics & Visual Content', 'desc' => 'Data visualization, infographic design, social media graphics, and interactive content that earns backlinks and social shares.'],
        ['icon' => '🎬', 'title' => 'Video Content Production', 'desc' => 'Explainer videos, tutorials, product demos, customer testimonials, and short-form social content optimized for each platform.'],
        ['icon' => '📧', 'title' => 'Email Content & Newsletters', 'desc' => 'Nurture sequences, drip campaigns, newsletter content, and promotional emails that drive opens, clicks, and conversions.'],
        ['icon' => '📈', 'title' => 'Content Performance Analytics', 'desc' => 'Traffic analysis, engagement metrics, conversion tracking, content attribution, and ROI reporting for every piece of content.'],
      ],
      'process' => ['Audience Research & Strategy', 'Topic Research & Planning', 'Content Creation & Review', 'SEO Optimization & Publishing', 'Distribution & Promotion', 'Performance Analysis & Iteration'],
      'stats' => [['value' => '3x', 'label' => 'More Leads Generated'], ['value' => '62%', 'label' => 'Lower Cost vs Outbound'], ['value' => '500+', 'label' => 'Articles Published'], ['value' => '10M+', 'label' => 'Content Views']],
    ],
    'email-marketing' => [
      'icon' => '📧',
      'title' => 'Email Marketing & Automation',
      'subtitle' => 'Nurture Leads, Retain Customers, and Drive Revenue with Personalized Email Campaigns',
      'features' => [
        ['icon' => '🎯', 'title' => 'Email Strategy & Planning', 'desc' => 'Full email marketing strategy including segmentation, personalization frameworks, sending frequency optimization, and deliverability best practices.'],
        ['icon' => '🔄', 'title' => 'Marketing Automation', 'desc' => 'Automated workflows for welcome series, abandoned cart recovery, post-purchase follow-ups, re-engagement campaigns, and lead scoring.'],
        ['icon' => '✍️', 'title' => 'Email Design & Copywriting', 'desc' => 'Mobile-responsive email templates, persuasive copywriting, A/B subject line testing, and dynamic content personalization.'],
        ['icon' => '📋', 'title' => 'List Management & Segmentation', 'desc' => 'List hygiene, subscriber segmentation by behavior and demographics, preference centers, and GDPR-compliant list management.'],
        ['icon' => '🧪', 'title' => 'A/B Testing & Optimization', 'desc' => 'Subject line testing, send time optimization, content variations, CTA testing, and continuous improvement based on data.'],
        ['icon' => '📊', 'title' => 'Performance Analytics', 'desc' => 'Open rates, click-through rates, conversion tracking, revenue attribution, and actionable monthly reports.'],
      ],
      'process' => ['Email Audit & Strategy', 'List Segmentation & Cleanup', 'Template Design & Content', 'Automation Workflow Setup', 'A/B Testing & Optimization', 'Monthly Reporting & Analysis'],
      'stats' => [['value' => '₹42', 'label' => 'ROI Per ₹1 Spent'], ['value' => '35%', 'label' => 'Avg. Open Rate'], ['value' => '4.5%', 'label' => 'Click-Through Rate'], ['value' => '25%', 'label' => 'Revenue Increase']],
    ],
    'analytics' => [
      'icon' => '📊',
      'title' => 'Analytics & Reporting Services',
      'subtitle' => 'Track, Measure, and Optimize Every Aspect of Your Digital Marketing Performance',
      'features' => [
        ['icon' => '⚙️', 'title' => 'GA4 Setup & Configuration', 'desc' => 'Complete Google Analytics 4 implementation — data streams, enhanced measurement, custom events, conversion tracking, and cross-domain setup.'],
        ['icon' => '🏷️', 'title' => 'Tag Management', 'desc' => 'Google Tag Manager setup, custom triggers, marketing pixel implementation (Meta, LinkedIn, Twitter), and server-side tagging for accuracy.'],
        ['icon' => '📊', 'title' => 'Custom Dashboards', 'desc' => 'Looker Studio (Data Studio) dashboards connecting GA4, Search Console, Google Ads, and social media data for unified reporting.'],
        ['icon' => '🎯', 'title' => 'Conversion Tracking', 'desc' => 'End-to-end conversion funnel setup, micro and macro conversion tracking, attribution modeling, and lead source analysis.'],
        ['icon' => '🔍', 'title' => 'Data Analysis & Insights', 'desc' => 'Monthly deep-dive analysis, trend identification, anomaly detection, competitor benchmarking, and actionable recommendations.'],
        ['icon' => '📈', 'title' => 'CRO & A/B Testing', 'desc' => 'Conversion rate optimization through data-backed hypotheses, A/B testing (Google Optimize, VWO), heatmaps, and user behavior analysis.'],
      ],
      'process' => ['Analytics Audit', 'Tracking Implementation', 'Dashboard Creation', 'Data Collection & QA', 'Analysis & Insights', 'Optimization Recommendations'],
      'stats' => [['value' => '100%', 'label' => 'Tracking Accuracy'], ['value' => '35%', 'label' => 'Better Decision Making'], ['value' => '28%', 'label' => 'Conversion Uplift'], ['value' => '50+', 'label' => 'Dashboards Built']],
    ],

    // ── Emerging / 2026 Services ──────────────────────────────

    'geo' => [
      'icon' => '🤖',
      'title' => 'GEO — Generative Engine Optimization',
      'subtitle' => 'Get Your Brand Cited by ChatGPT, Gemini, Perplexity & AI Overviews. GEO is the New SEO for the AI Era.',
      'features' => [
        ['icon' => '🧠', 'title' => 'AI Overview Optimization', 'desc' => 'Optimize your content to appear in Google\'s AI Overviews (formerly SGE). Structured content, authoritative sources, and E-E-A-T signals that AI models prefer to cite.'],
        ['icon' => '💬', 'title' => 'ChatGPT & Perplexity Citations', 'desc' => 'Strategic content and brand-building to get cited by ChatGPT, Perplexity AI, and other LLMs when users ask questions in your industry. We track and measure AI mention share.'],
        ['icon' => '📝', 'title' => 'LLM-Friendly Content Architecture', 'desc' => 'Restructure your content with clear definitions, direct answers, structured data, and authoritative citations — the exact format that LLMs extract and surface to users.'],
        ['icon' => '🔗', 'title' => 'Brand Entity Optimization', 'desc' => 'Build your brand as a recognized entity across Wikipedia, Wikidata, knowledge graphs, and top-tier publications so AI systems confidently include you in answers.'],
        ['icon' => '📊', 'title' => 'GEO Tracking & AI Mention Monitoring', 'desc' => 'Monitor your brand\'s appearance in AI-generated answers using custom GEO tracking tools. Measure AI visibility score, citation frequency, and competitive AI share-of-voice.'],
        ['icon' => '🚀', 'title' => 'AI-Ready PR & Digital Authority', 'desc' => 'Build authoritative backlinks from high-trust publications, earn expert quotes, and create content that positions you as the go-to source in your niche for AI systems.'],
      ],
      'process' => ['GEO Audit & AI Visibility Scan', 'Brand Entity & Knowledge Graph Setup', 'LLM-Optimized Content Restructuring', 'Authority & Citation Building', 'AI Overview & Perplexity Targeting', 'GEO Monitoring & Iteration'],
      'stats' => [['value' => '3.2x', 'label' => 'AI Visibility Increase'], ['value' => '68%', 'label' => 'Brands Now Use GEO'], ['value' => '40%', 'label' => 'Search via AI by 2026'], ['value' => '500+', 'label' => 'AI Citations Earned']],
    ],

    'aeo' => [
      'icon' => '🎙️',
      'title' => 'AEO — Answer Engine Optimization',
      'subtitle' => 'Win Featured Snippets, Voice Search & AI Chatbot Answers. Make Your Content the Definitive Answer in Your Niche.',
      'features' => [
        ['icon' => '⭐', 'title' => 'Featured Snippet Optimization', 'desc' => 'Capture Position Zero on Google for high-value queries. We identify snippet opportunities, restructure content in paragraph, list, and table formats that Google selects.'],
        ['icon' => '🔊', 'title' => 'Voice Search Optimization', 'desc' => 'Optimize for conversational queries used on Google Assistant, Alexa, Siri, and Cortana. Long-tail, question-based keyword targeting with natural language content.'],
        ['icon' => '❓', 'title' => 'People Also Ask (PAA) Domination', 'desc' => 'Systematically target PAA boxes for your key topics. Expand your Google real estate by appearing in multiple PAA dropdowns on the same SERP.'],
        ['icon' => '📋', 'title' => 'Structured Data & Schema Markup', 'desc' => 'FAQ schema, HowTo schema, Q&A schema, and speakable schema implementation. Tell search engines and AI exactly what your content answers.'],
        ['icon' => '🤖', 'title' => 'Conversational Content Strategy', 'desc' => 'Develop comprehensive Q&A content hubs that answer every question in your industry funnel — from awareness to purchase — across voice, text, and AI chat interfaces.'],
        ['icon' => '📈', 'title' => 'AEO Analytics & SERP Tracking', 'desc' => 'Track featured snippet wins/losses, voice search ranking, PAA appearances, and AI Overview inclusions. Custom AEO dashboard with weekly reporting.'],
      ],
      'process' => ['Query Research & Intent Mapping', 'Snippet & PAA Opportunity Analysis', 'Conversational Content Creation', 'Schema Markup Implementation', 'Voice & AI Optimization', 'SERP Monitoring & Iteration'],
      'stats' => [['value' => '214%', 'label' => 'Featured Snippet Wins'], ['value' => '58%', 'label' => 'Voice Search Queries Are Questions'], ['value' => '2.5x', 'label' => 'More Clicks from Position Zero'], ['value' => '1B+', 'label' => 'Voice Searches Per Day']],
    ],

    'ai-marketing' => [
      'icon' => '⚡',
      'title' => 'AI Marketing & Automation',
      'subtitle' => 'Leverage Artificial Intelligence to Hyper-Personalize Campaigns, Automate Workflows, and Scale Your Marketing Faster.',
      'features' => [
        ['icon' => '🎯', 'title' => 'AI-Powered Personalization', 'desc' => 'Deploy machine learning to deliver hyper-personalized content, product recommendations, and offers to each user based on behavior, preferences, and purchase history.'],
        ['icon' => '🔮', 'title' => 'Predictive Analytics & Audience Targeting', 'desc' => 'Use predictive models to identify your next best customers, forecast campaign performance, and optimize budget allocation before you spend a rupee.'],
        ['icon' => '🤖', 'title' => 'Chatbot & Conversational Marketing', 'desc' => 'AI chatbots on WhatsApp, website, and social media that qualify leads, answer queries, and nurture prospects 24/7. Build intelligent funnels that convert while you sleep.'],
        ['icon' => '✍️', 'title' => 'AI Content Creation Workflows', 'desc' => 'Strategic AI-assisted content production at scale — blog articles, ad copy, email sequences, social posts — reviewed by experts for quality, accuracy, and brand voice.'],
        ['icon' => '⚙️', 'title' => 'Marketing Automation & Workflows', 'desc' => 'End-to-end automation of email nurture sequences, lead scoring, CRM updates, social scheduling, and reporting using tools like HubSpot, Zapier, and Make.'],
        ['icon' => '📊', 'title' => 'AI Analytics & Performance Intelligence', 'desc' => 'AI-powered dashboards that surface insights, detect anomalies, and recommend optimizations automatically. Spend less time analyzing, more time acting.'],
      ],
      'process' => ['AI Readiness Audit', 'Use Case Prioritization', 'Tool Stack Selection & Setup', 'AI Campaign Implementation', 'Automation Workflow Deployment', 'Continuous Learning & Optimization'],
      'stats' => [['value' => '80%', 'label' => 'Reduction in Repetitive Tasks'], ['value' => '6.2x', 'label' => 'Faster Campaign Deployment'], ['value' => '45%', 'label' => 'Higher Personalization Revenue'], ['value' => '10x', 'label' => 'Content Output Increase']],
    ],

    'video-marketing' => [
      'icon' => '🎬',
      'title' => 'Video & Reels Marketing',
      'subtitle' => 'Short-Form. Long Impact. Dominate YouTube, Instagram Reels, LinkedIn Video & Shorts to Drive Massive Engagement and Revenue.',
      'features' => [
        ['icon' => '📺', 'title' => 'YouTube SEO & Channel Growth', 'desc' => 'Full YouTube channel management — keyword research, video SEO (titles, descriptions, tags, chapters), thumbnail optimization, playlist strategy, and subscriber growth campaigns.'],
        ['icon' => '🎞️', 'title' => 'Reels & Shorts Strategy', 'desc' => 'High-engagement short-form video strategy for Instagram Reels, YouTube Shorts, and LinkedIn Video. Trending audio, hook optimization, and viral content frameworks.'],
        ['icon' => '🎬', 'title' => 'Video Production & Editing', 'desc' => 'End-to-end video production including scriptwriting, filming direction, professional editing, motion graphics, subtitles, and platform-specific formatting (16:9, 9:16, 1:1).'],
        ['icon' => '📢', 'title' => 'Video Ad Campaigns', 'desc' => 'YouTube In-stream ads, Bumper ads, Discovery ads, and Shorts ads. Meta video ad campaigns and LinkedIn video ads optimized for awareness, traffic, and conversions.'],
        ['icon' => '🌐', 'title' => 'Multi-Platform Distribution', 'desc' => 'Repurpose one video into 10 platform-native assets — long-form YouTube, Instagram Reels, LinkedIn clips, WhatsApp Status, YouTube Shorts, Twitter/X videos, and more.'],
        ['icon' => '📊', 'title' => 'Video Analytics & Performance', 'desc' => 'YouTube Studio analytics, watch time optimization, CTR improvement, audience retention analysis, and monthly video performance reports with actionable insights.'],
      ],
      'process' => ['Video Audit & Strategy', 'Content Calendar & Scripting', 'Production & Editing', 'SEO Optimization & Upload', 'Multi-Platform Distribution', 'Analytics & Performance Review'],
      'stats' => [['value' => '88%', 'label' => 'Marketers Get ROI from Video'], ['value' => '3x', 'label' => 'More Engagement vs Text'], ['value' => '2B+', 'label' => 'YouTube Monthly Users'], ['value' => '500M', 'label' => 'Daily Reels Plays']],
    ],

    'cro' => [
      'icon' => '📈',
      'title' => 'CRO — Conversion Rate Optimization',
      'subtitle' => 'Turn More Visitors into Paying Customers Without Spending More on Ads. Scientific Testing That Maximizes Revenue from Existing Traffic.',
      'features' => [
        ['icon' => '🔬', 'title' => 'CRO Audit & Heuristic Analysis', 'desc' => 'Comprehensive audit of your website using LIFT model, PURE framework, and UX best practices. Identify conversion blockers, friction points, and quick-win opportunities.'],
        ['icon' => '🌡️', 'title' => 'Heatmaps & Session Recordings', 'desc' => 'Hotjar, Microsoft Clarity, and FullStory analysis to understand exactly how users interact with your pages — where they click, scroll, drop off, and rage-click.'],
        ['icon' => '🧪', 'title' => 'A/B & Multivariate Testing', 'desc' => 'Rigorous statistical A/B testing using VWO, Optimizely, or Google Optimize. Test headlines, CTAs, layouts, pricing displays, form lengths, and page structures.'],
        ['icon' => '🎯', 'title' => 'Landing Page Optimization', 'desc' => 'High-converting landing page design and copy — above-the-fold optimization, value proposition clarity, social proof placement, and CTA hierarchy that drives action.'],
        ['icon' => '📋', 'title' => 'Form & Checkout Optimization', 'desc' => 'Reduce form abandonment with smart field reduction, inline validation, progress indicators, and trust signals. E-commerce checkout optimization to recover lost revenue.'],
        ['icon' => '📊', 'title' => 'CRO Analytics & Funnel Analysis', 'desc' => 'Full-funnel analysis using GA4, identifying micro-conversion bottlenecks, segment-level drop-off, and behavioral patterns that reveal high-value conversion opportunities.'],
      ],
      'process' => ['Data Collection & Funnel Audit', 'User Research & Heuristic Analysis', 'Hypothesis Generation & Prioritization', 'Test Design & Implementation', 'Statistical Analysis & Learning', 'Scale Winners & Iterate'],
      'stats' => [['value' => '223%', 'label' => 'Average CRO ROI'], ['value' => '2-3x', 'label' => 'Conversion Rate Improvement'], ['value' => '49%', 'label' => 'Businesses Run A/B Tests'], ['value' => '0%', 'label' => 'Extra Ad Spend Needed']],
    ],

    'programmatic' => [
      'icon' => '🖥️',
      'title' => 'Programmatic & Display Advertising',
      'subtitle' => 'Reach the Right Audience at the Right Moment Across 50,000+ Premium Publisher Websites with AI-Powered Real-Time Bidding.',
      'features' => [
        ['icon' => '⚡', 'title' => 'Real-Time Bidding (RTB)', 'desc' => 'Automated auction-based ad buying that places your ads in milliseconds on premium inventory. AI-powered bidding strategies that maximize reach and minimize cost per impression.'],
        ['icon' => '🎯', 'title' => 'Advanced Audience Targeting', 'desc' => 'First-party data activation, contextual targeting, behavioral segments, demographic targeting, intent-based audiences, and lookalike modeling using your best customers.'],
        ['icon' => '🖼️', 'title' => 'Creative Management & DCO', 'desc' => 'Dynamic Creative Optimization (DCO) automatically assembles personalized ad creatives for each user — combining the best headline, image, and CTA in real-time.'],
        ['icon' => '📊', 'title' => 'DMP & Data Integration', 'desc' => 'Data Management Platform setup to unify first, second, and third-party data. CRM data onboarding, audience segmentation, and customer journey mapping across channels.'],
        ['icon' => '🛡️', 'title' => 'Brand Safety & Viewability', 'desc' => 'Whitelist/blacklist management, category exclusions, fraud detection (IVT filtering), viewability optimization (MOAT, IAS, DoubleVerify), and premium PMP deals.'],
        ['icon' => '📈', 'title' => 'Cross-Channel Attribution', 'desc' => 'Multi-touch attribution models that accurately credit programmatic\'s role in conversions. Identity resolution, data clean rooms, and unified cross-channel performance reporting.'],
      ],
      'process' => ['Audience & Inventory Strategy', 'DSP & DMP Setup', 'Creative Development & DCO', 'Campaign Launch & Targeting', 'Real-Time Optimization', 'Attribution & Performance Analysis'],
      'stats' => [['value' => '50K+', 'label' => 'Publisher Sites Reached'], ['value' => '89%', 'label' => 'Programmatic Share of Display'], ['value' => '3.5x', 'label' => 'Better Targeting vs Traditional'], ['value' => '<1ms', 'label' => 'Real-Time Bidding Speed']],
    ],
  ];

  $svc = $serviceData[$slug ?? ''] ?? null;
  ?>

  <?php if ($svc): ?>

  <!-- Hero Section -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-12);">
    <div style="display:flex;align-items:center;gap:var(--space-4);margin-bottom:var(--space-4);">
      <span style="font-size:48px;"><?= $svc['icon'] ?></span>
      <div>
        <span class="badge badge-brand" style="margin-bottom:var(--space-2);display:inline-block;">🎯 Service</span>
        <h1 style="margin:0;font-size:var(--text-3xl);"><?= e($svc['title']) ?></h1>
      </div>
    </div>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;line-height:1.7;">
      <?= e($svc['subtitle']) ?>
    </p>
  </div>

  <!-- Stats Bar -->
  <?php if (!empty($svc['stats'])): ?>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-4);margin-bottom:var(--space-12);">
    <?php foreach ($svc['stats'] as $stat): ?>
    <div class="card" style="padding:var(--space-5);text-align:center;border-color:rgba(99,102,241,0.15);background:linear-gradient(135deg,rgba(99,102,241,0.04),rgba(139,92,246,0.02));">
      <div style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);margin-bottom:var(--space-1);"><?= $stat['value'] ?></div>
      <div style="font-size:var(--text-xs);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;"><?= e($stat['label']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- What We Offer -->
  <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-8);text-align:center;">What We Offer</h2>
  <div class="grid grid-3 gap-6" style="margin-bottom:var(--space-14);">
    <?php foreach ($svc['features'] as $f): ?>
    <div class="card card-interactive" style="padding:var(--space-6);display:flex;flex-direction:column;">
      <div style="font-size:32px;margin-bottom:var(--space-3);"><?= $f['icon'] ?></div>
      <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);margin-bottom:var(--space-2);"><?= e($f['title']) ?></h3>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);line-height:1.65;flex:1;"><?= e($f['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Our Process -->
  <?php if (!empty($svc['process'])): ?>
  <div style="margin-bottom:var(--space-14);">
    <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-8);text-align:center;">Our Process</h2>
    <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;justify-content:center;">
      <?php foreach ($svc['process'] as $i => $step): ?>
      <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) var(--space-5);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);">
        <span style="width:28px;height:28px;border-radius:50%;background:var(--brand-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;"><?= $i + 1 ?></span>
        <span style="font-size:var(--text-sm);font-weight:var(--fw-medium);"><?= e($step) ?></span>
      </div>
      <?php if ($i < count($svc['process']) - 1): ?>
      <span style="color:var(--text-muted);font-size:18px;display:flex;align-items:center;">→</span>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php elseif (!empty($page) && !empty($page['content'])): ?>
  <!-- CMS Content -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">🎯 Service</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($page['title'] ?? ucwords(str_replace('-', ' ', $slug ?? ''))) ?></h1>
  </div>
  <div class="prose"><?= $page['content'] ?></div>
  <?php else: ?>
  <!-- Fallback -->
  <div style="margin-top:var(--space-4);margin-bottom:var(--space-10);">
    <span class="badge badge-brand">🎯 Service</span>
    <h1><?= e(ucwords(str_replace('-', ' ', $slug ?? 'Digital Marketing Service'))) ?></h1>
  </div>
  <?php endif; ?>

  <!-- CTA Section -->
  <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-10);align-items:start;margin-top:var(--space-8);">
    <div class="card" style="padding:var(--space-8);text-align:center;background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(139,92,246,0.04));border-color:rgba(99,102,241,0.15);">
      <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-3);">Ready to Get Started?</h2>
      <p style="color:var(--text-secondary);margin-bottom:var(--space-6);max-width:500px;margin-left:auto;margin-right:auto;">
        Get a free consultation and custom strategy tailored to your business goals. No commitment required.
      </p>
      <div style="display:flex;gap:var(--space-3);justify-content:center;flex-wrap:wrap;">
        <a href="/contact" class="btn btn-primary btn-lg">Get Free Consultation →</a>
        <a href="/services" class="btn btn-secondary btn-lg">← All Services</a>
      </div>
    </div>

    <aside style="position:sticky;top:100px;">
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-base);font-weight:var(--fw-semibold);margin-bottom:var(--space-4);">Related Resources</h3>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:var(--space-2);">
          <li><a href="/learn" style="font-size:var(--text-sm);color:var(--brand-primary);">📚 Knowledge Center</a></li>
          <li><a href="/blog" style="font-size:var(--text-sm);color:var(--brand-primary);">📝 Latest Blog Posts</a></li>
          <li><a href="/tools" style="font-size:var(--text-sm);color:var(--brand-primary);">🛠️ Free Marketing Tools</a></li>
          <li><a href="/case-studies" style="font-size:var(--text-sm);color:var(--brand-primary);">📊 Case Studies</a></li>
          <li><a href="/glossary" style="font-size:var(--text-sm);color:var(--brand-primary);">📖 Marketing Glossary</a></li>
        </ul>
      </div>
    </aside>
  </div>

</div>
