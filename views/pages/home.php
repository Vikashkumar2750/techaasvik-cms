<!-- ═══════════════════════════════════════════════════════════
     HOMEPAGE V2 — TECHAASVIK AI MARKETING PLATFORM
     
     Data from HomeController (unchanged):
       $latestPosts, $pillars, $caseStudies, $tools, $topCategories
     
     Form endpoints (handled by existing main.js — NOT modified):
       #heroNewsletter → POST /lead/newsletter
       #auditForm      → POST /lead/audit
     
     Page-specific scripts loaded at bottom (NOT globally):
       Three.js r160, GSAP 3.12 + ScrollTrigger, homepage-v2.js
     ═══════════════════════════════════════════════════════════ -->


<!-- ╔══════════════════════════════════════════════════════════╗
     ║  ACT I — THE SHIFT                                      ║
     ╚══════════════════════════════════════════════════════════╝ -->

<!-- ── 1. HERO ─────────────────────────────────────────────── -->
<section class="hv2-hero hv2-dark" id="hv2-hero">
  <!-- 3D Canvas — desktop only, hidden on mobile + reduced-motion via CSS -->
  <div class="hv2-hero-canvas" id="hv2-hero-canvas" aria-hidden="true"></div>
  <!-- Mobile/reduced-motion fallback gradient -->
  <div class="hv2-hero-gradient-bg" aria-hidden="true"></div>
  
  <div class="container hv2-hero-content">
    <div class="hv2-hero-badge">AI-Driven Digital Marketing</div>
    
    <h1 class="hv2-hero-title">
      Search. Content.<br>Performance.
      <span class="hv2-gradient-text">Connected Through Intelligence.</span>
    </h1>
    
    <p class="hv2-hero-subtitle">
      Techaasvik builds the AI-assisted marketing platform where SEO, AEO, GEO, 
      content strategy, performance advertising, and analytics work as one 
      connected system.
    </p>
    
    <div class="hv2-hero-actions">
      <a href="#hv2-ai-engine" class="btn btn-gradient btn-lg" id="heroExploreEngine">
        Explore the AI Engine ↓
      </a>
      <a href="#hv2-final-cta" class="btn btn-secondary btn-lg" id="heroGetAudit">
        Get Free AI Audit →
      </a>
    </div>
  </div>
</section>


<!-- ── 2. MARKETING EVOLVED ────────────────────────────────── -->
<section class="hv2-evolved" id="hv2-evolved">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">The Evolution</span>
      <h2 class="hv2-section-title">Marketing Has Evolved</h2>
      <p class="hv2-section-desc">
        From fragmented tools and siloed campaigns to AI-assisted, 
        connected marketing workflows.
      </p>
    </div>
    
    <div class="hv2-evolved-grid">
      <!-- Traditional Approach -->
      <div class="hv2-evolved-old">
        <h3 class="hv2-compare-heading">Traditional Approach</h3>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◻</span>
          <div>
            <strong>Disconnected Platforms</strong>
            <p>Separate tools for SEO, ads, content, and analytics with no shared intelligence</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◻</span>
          <div>
            <strong>Manual Keyword Research</strong>
            <p>Periodic spreadsheet-based analysis disconnected from content strategy</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◻</span>
          <div>
            <strong>Siloed Campaign Management</strong>
            <p>Search, social, and display campaigns managed independently</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◻</span>
          <div>
            <strong>Periodic Manual Reporting</strong>
            <p>Monthly reports assembled from multiple disconnected dashboards</p>
          </div>
        </div>
      </div>
      
      <!-- Divider -->
      <div class="hv2-evolved-divider" aria-hidden="true">
        <div class="hv2-evolved-arrow">→</div>
      </div>
      
      <!-- AI-Assisted Approach -->
      <div class="hv2-evolved-new">
        <h3 class="hv2-compare-heading">AI-Assisted Approach</h3>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◼</span>
          <div>
            <strong>Connected Marketing System</strong>
            <p>Integrated platform where search, content, and advertising inform each other</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◼</span>
          <div>
            <strong>Semantic Entity Mapping</strong>
            <p>AI-assisted topic discovery tied to content creation and search optimization</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◼</span>
          <div>
            <strong>Cross-Channel Optimization</strong>
            <p>Campaigns that share data and adjust across search, social, and display</p>
          </div>
        </div>
        
        <div class="hv2-compare-item">
          <span class="hv2-compare-icon">◼</span>
          <div>
            <strong>Real-Time Attribution</strong>
            <p>Automated, data-driven attribution connecting marketing to business outcomes</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ── 3. SEARCH EVOLUTION ─────────────────────────────────── -->
<section class="hv2-search" id="hv2-search">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Search Landscape</span>
      <h2 class="hv2-section-title">Search Is No Longer Just SEO</h2>
      <p class="hv2-section-desc">
        People discover brands through Google, ChatGPT, Perplexity, and AI Overviews. 
        Visibility now requires coverage across all four discovery layers.
      </p>
    </div>
    
    <div class="hv2-search-landscape">
      <div class="hv2-search-node" id="hv2-seo-node">
        <div class="hv2-search-node-icon">🔍</div>
        <div class="hv2-search-node-label">Traditional SEO</div>
        <p class="hv2-search-node-desc">Technical health, on-page optimization, backlink authority, and crawlability</p>
        <a href="/learn/seo" class="hv2-search-node-link">Explore SEO →</a>
      </div>
      
      <div class="hv2-search-node" id="hv2-aeo-node">
        <div class="hv2-search-node-icon">💬</div>
        <div class="hv2-search-node-label">Answer Engine (AEO)</div>
        <p class="hv2-search-node-desc">Featured snippets, People Also Ask, and direct answer placement</p>
        <a href="/learn/aeo-complete-guide" class="hv2-search-node-link">Explore AEO →</a>
      </div>
      
      <div class="hv2-search-node" id="hv2-geo-node">
        <div class="hv2-search-node-icon">🤖</div>
        <div class="hv2-search-node-label">Generative Engine (GEO)</div>
        <p class="hv2-search-node-desc">Citation and visibility in ChatGPT, Gemini, Perplexity, and AI assistants</p>
        <a href="/learn/geo-complete-guide" class="hv2-search-node-link">Explore GEO →</a>
      </div>
      
      <div class="hv2-search-node" id="hv2-aio-node">
        <div class="hv2-search-node-icon">⚡</div>
        <div class="hv2-search-node-label">AI Overviews</div>
        <p class="hv2-search-node-desc">Entity authority and direct answer placement in AI-generated summaries</p>
        <a href="/learn/ai-marketing" class="hv2-search-node-link">Explore AI Search →</a>
      </div>
    </div>
  </div>
</section>


<!-- ╔══════════════════════════════════════════════════════════╗
     ║  ACT II — THE ENGINE                                    ║
     ╚══════════════════════════════════════════════════════════╝ -->

<!-- ── 4. PERFORMANCE ──────────────────────────────────────── -->
<section class="hv2-performance" id="hv2-performance">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Performance Marketing</span>
      <h2 class="hv2-section-title">AI-Assisted Performance Marketing</h2>
      <p class="hv2-section-desc">
        From manual bid management to AI-driven campaign optimization 
        across Google and Meta advertising platforms.
      </p>
    </div>
    
    <div class="hv2-perf-grid">
      <div class="hv2-perf-card">
        <div class="hv2-perf-card-icon">📢</div>
        <h4>Google Performance Max</h4>
        <p>AI-optimized campaigns across Search, Shopping, Display, YouTube, and Discovery from a single campaign</p>
      </div>
      
      <div class="hv2-perf-card">
        <div class="hv2-perf-card-icon">📱</div>
        <h4>Meta Advantage+</h4>
        <p>Machine learning-driven audience targeting, creative optimization, and automated placements across Facebook and Instagram</p>
      </div>
      
      <div class="hv2-perf-card">
        <div class="hv2-perf-card-icon">🎯</div>
        <h4>Automated Creative Testing</h4>
        <p>AI-assisted creative generation and multivariate testing to identify high-performing ad combinations</p>
      </div>
      
      <div class="hv2-perf-card">
        <div class="hv2-perf-card-icon">📊</div>
        <h4>Cross-Platform Attribution</h4>
        <p>Data-driven attribution models connecting advertising spend to conversions across channels</p>
      </div>
    </div>
    
    <div class="hv2-perf-links">
      <a href="/learn/google-ads-complete-guide" class="btn btn-secondary btn-sm">Google Ads Guide →</a>
      <a href="/learn/meta-ads-complete-guide" class="btn btn-secondary btn-sm">Meta Ads Guide →</a>
    </div>
  </div>
</section>


<!-- ── 5. CONTENT ENGINE ───────────────────────────────────── -->
<section class="hv2-content-engine" id="hv2-content-engine">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Content Intelligence</span>
      <h2 class="hv2-section-title">AI-Assisted Content Engine</h2>
      <p class="hv2-section-desc">
        From topic research to distribution — a connected content workflow 
        that maintains E-E-A-T quality at every stage.
      </p>
    </div>
    
    <div class="hv2-pipeline">
      <div class="hv2-pipeline-stage">
        <div class="hv2-pipeline-num">1</div>
        <h4>Semantic Topic Discovery</h4>
        <p>AI-assisted topic cluster analysis and content gap identification</p>
        <span class="hv2-pipeline-arrow" aria-hidden="true">→</span>
      </div>
      
      <div class="hv2-pipeline-stage">
        <div class="hv2-pipeline-num">2</div>
        <h4>AI First-Draft Generation</h4>
        <p>Structured draft creation with source citations and outline optimization</p>
        <span class="hv2-pipeline-arrow" aria-hidden="true">→</span>
      </div>
      
      <div class="hv2-pipeline-stage">
        <div class="hv2-pipeline-num">3</div>
        <h4>Human E-E-A-T Review</h4>
        <p>Expert fact-checking, experience verification, and authority validation</p>
        <span class="hv2-pipeline-arrow" aria-hidden="true">→</span>
      </div>
      
      <div class="hv2-pipeline-stage">
        <div class="hv2-pipeline-num">4</div>
        <h4>Omnichannel Distribution</h4>
        <p>Automated publishing, syndication, and cross-platform content adaptation</p>
      </div>
    </div>
    
    <div class="hv2-content-links">
      <a href="/learn/content-marketing" class="btn btn-secondary btn-sm">Content Marketing Guide →</a>
      <a href="/tools" class="btn btn-secondary btn-sm">Explore Free Tools →</a>
    </div>
  </div>
</section>


<!-- ── 6. AI MARKETING ENGINE — VISUAL CLIMAX ──────────────── -->
<section class="hv2-ai-engine hv2-dark" id="hv2-ai-engine">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">The Core</span>
      <h2 class="hv2-section-title hv2-title-xl">The AI Marketing Engine</h2>
      <p class="hv2-section-desc">
        Five interconnected stages that transform marketing from disconnected campaigns 
        into a continuously learning, self-optimizing system.
      </p>
    </div>
    
    <div class="hv2-engine-flow">
      <div class="hv2-engine-step" data-stage="1">
        <div class="hv2-engine-step-marker">
          <span class="hv2-engine-step-num">01</span>
          <span class="hv2-engine-step-line" aria-hidden="true"></span>
        </div>
        <div class="hv2-engine-step-body">
          <h4>Data &amp; Intelligence</h4>
          <p>Customer sentiment analysis, competitor gap detection, and market signal processing</p>
        </div>
      </div>
      
      <div class="hv2-engine-step" data-stage="2">
        <div class="hv2-engine-step-marker">
          <span class="hv2-engine-step-num">02</span>
          <span class="hv2-engine-step-line" aria-hidden="true"></span>
        </div>
        <div class="hv2-engine-step-body">
          <h4>Predictive Strategy</h4>
          <p>Topical authority modeling, audience segmentation, and opportunity scoring</p>
        </div>
      </div>
      
      <div class="hv2-engine-step" data-stage="3">
        <div class="hv2-engine-step-marker">
          <span class="hv2-engine-step-num">03</span>
          <span class="hv2-engine-step-line" aria-hidden="true"></span>
        </div>
        <div class="hv2-engine-step-body">
          <h4>Omnichannel Execution</h4>
          <p>Coordinated deployment across AI search, automated ads, and content syndication</p>
        </div>
      </div>
      
      <div class="hv2-engine-step" data-stage="4">
        <div class="hv2-engine-step-marker">
          <span class="hv2-engine-step-num">04</span>
          <span class="hv2-engine-step-line" aria-hidden="true"></span>
        </div>
        <div class="hv2-engine-step-body">
          <h4>Measurement &amp; Attribution</h4>
          <p>GA4 data-driven models connecting marketing activity to CAC, LTV, and revenue</p>
        </div>
      </div>
      
      <div class="hv2-engine-step" data-stage="5">
        <div class="hv2-engine-step-marker">
          <span class="hv2-engine-step-num">05</span>
        </div>
        <div class="hv2-engine-step-body">
          <h4>Continuous Learning</h4>
          <p>Machine learning feedback loops that automatically refine targeting, bidding, and content</p>
        </div>
      </div>
    </div>
    
    <!-- Feedback loop indicator: connects stage 5 back to stage 1 -->
    <div class="hv2-engine-loop">
      <div class="hv2-engine-loop-line" aria-hidden="true"></div>
      <span class="hv2-engine-loop-label">↺ Feeds back into Data &amp; Intelligence</span>
    </div>
  </div>
</section>


<!-- ╔══════════════════════════════════════════════════════════╗
     ║  ACT III — THE ECOSYSTEM                                ║
     ╚══════════════════════════════════════════════════════════╝ -->

<!-- ── 7. SERVICES ─────────────────────────────────────────── -->
<!--
  NOTE: HomeController does not expose service data.
  These are navigation labels linking to /services, NOT a duplicate dataset.
  Structurally compatible with existing /services routes.
-->
<section class="hv2-services" id="hv2-services">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Services</span>
      <h2 class="hv2-section-title">Connected Marketing Services</h2>
      <p class="hv2-section-desc">
        Every channel, every platform — managed as one connected system.
      </p>
    </div>
    
    <!-- Search Intelligence group -->
    <div class="hv2-service-group">
      <div class="hv2-service-group-label">Search Intelligence</div>
      <div class="hv2-service-group-cards hv2-service-group-cards--3">
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">🔍</div>
          <h4>SEO</h4>
          <p>Technical optimization, authority building, and organic growth</p>
        </a>
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">💬</div>
          <h4>AEO</h4>
          <p>Answer engine optimization for featured snippets and direct answers</p>
        </a>
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">🤖</div>
          <h4>GEO</h4>
          <p>Generative engine optimization for AI assistant visibility</p>
        </a>
      </div>
    </div>
    
    <!-- Performance group -->
    <div class="hv2-service-group">
      <div class="hv2-service-group-label">Performance</div>
      <div class="hv2-service-group-cards hv2-service-group-cards--2">
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">📢</div>
          <h4>Google Ads</h4>
          <p>Search, Shopping, Display, and YouTube campaign management</p>
        </a>
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">📱</div>
          <h4>Meta Ads</h4>
          <p>Facebook and Instagram advertising strategy and execution</p>
        </a>
      </div>
    </div>
    
    <!-- Intelligence Layer group -->
    <div class="hv2-service-group">
      <div class="hv2-service-group-label">Intelligence Layer</div>
      <div class="hv2-service-group-cards hv2-service-group-cards--3">
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">✍️</div>
          <h4>Content Marketing</h4>
          <p>Strategy, creation, distribution, and performance measurement</p>
        </a>
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">📊</div>
          <h4>Analytics &amp; GA4</h4>
          <p>Implementation, custom reporting, and attribution modeling</p>
        </a>
        <a href="/services" class="hv2-service-card">
          <div class="hv2-service-card-icon">⚡</div>
          <h4>AI Marketing Strategy</h4>
          <p>AI integration roadmap, automation, and prompt engineering</p>
        </a>
      </div>
    </div>
    
    <div class="hv2-services-cta">
      <a href="/services" class="btn btn-secondary">Explore All Services →</a>
    </div>
  </div>
</section>


<!-- ── 8. PORTFOLIO / PROOF ────────────────────────────────── -->
<?php if (!empty($caseStudies)): ?>
<section class="hv2-portfolio hv2-dark" id="hv2-portfolio">
  <div class="container">
    <div class="hv2-section-header">
      <span class="hv2-eyebrow">Results</span>
      <h2 class="hv2-section-title">Real Campaign Results</h2>
      <p class="hv2-section-desc">
        Case studies from real digital marketing campaigns.
      </p>
    </div>
    
    <div class="hv2-case-grid">
      <?php foreach ($caseStudies as $cs): ?>
      <article class="hv2-case-card">
        <div class="hv2-case-card-badge">Case Study</div>
        <h3>
          <a href="/case-studies/<?= e($cs['slug']) ?>"><?= e($cs['title']) ?></a>
        </h3>
        <?php if (!empty($cs['excerpt'])): ?>
        <p><?= str_truncate($cs['excerpt'], 120) ?></p>
        <?php endif; ?>
        <a href="/case-studies/<?= e($cs['slug']) ?>" class="hv2-case-card-link">Read Case Study →</a>
      </article>
      <?php endforeach; ?>
    </div>
    
    <div class="hv2-services-cta">
      <a href="/case-studies" class="btn btn-secondary">All Case Studies →</a>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ── 9. FREE LEARNING & TOOLS ────────────────────────────── -->
<section class="hv2-learning" id="hv2-learning">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Knowledge</span>
      <h2 class="hv2-section-title">Free Learning &amp; Tools</h2>
      <p class="hv2-section-desc">
        Expert-authored guides, interactive tools, and a comprehensive marketing glossary.
      </p>
    </div>
    
    <!-- Tab Navigation -->
    <div class="hv2-tabs" role="tablist">
      <button class="hv2-tab-btn active" data-tab="hv2-panel-articles" type="button" role="tab" aria-selected="true">Latest Articles</button>
      <button class="hv2-tab-btn" data-tab="hv2-panel-pillars" type="button" role="tab" aria-selected="false">Knowledge Pillars</button>
      <button class="hv2-tab-btn" data-tab="hv2-panel-tools" type="button" role="tab" aria-selected="false">Free Tools</button>
    </div>
    
    <!-- Tab: Latest Articles -->
    <div class="hv2-tab-panel active" id="hv2-panel-articles" role="tabpanel">
      <?php if (!empty($latestPosts)): ?>
      <div class="hv2-learning-grid">
        <?php foreach ($latestPosts as $post): ?>
        <a href="/blog/<?= e($post['slug']) ?>" class="hv2-learning-card">
          <div class="hv2-learning-card-meta">
            <?= format_date($post['published_at']) ?>
            <?php if (!empty($post['read_time'])): ?>
             · <?= $post['read_time'] ?> min read
            <?php endif; ?>
          </div>
          <h4><?= e($post['title']) ?></h4>
          <?php if (!empty($post['excerpt'])): ?>
          <p><?= str_truncate($post['excerpt'], 100) ?></p>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </div>
      <div style="text-align:center;margin-top:var(--space-6);">
        <a href="/blog" class="btn btn-secondary btn-sm">All Articles →</a>
      </div>
      <?php else: ?>
      <p style="text-align:center;color:var(--text-muted);padding:var(--space-8) 0;">Articles coming soon.</p>
      <?php endif; ?>
    </div>
    
    <!-- Tab: Knowledge Pillars -->
    <div class="hv2-tab-panel" id="hv2-panel-pillars" role="tabpanel">
      <?php if (!empty($pillars)): ?>
      <div class="hv2-learning-grid">
        <?php foreach ($pillars as $pillar): ?>
        <a href="/learn/<?= e($pillar['slug']) ?>" class="hv2-learning-card">
          <h4><?= e($pillar['title']) ?></h4>
          <?php if (!empty($pillar['excerpt'])): ?>
          <p><?= str_truncate($pillar['excerpt'], 100) ?></p>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </div>
      <div style="text-align:center;margin-top:var(--space-6);">
        <a href="/learn" class="btn btn-secondary btn-sm">All Knowledge Pillars →</a>
      </div>
      <?php else: ?>
      <p style="text-align:center;color:var(--text-muted);padding:var(--space-8) 0;">Knowledge pillars coming soon.</p>
      <?php endif; ?>
    </div>
    
    <!-- Tab: Free Tools -->
    <div class="hv2-tab-panel" id="hv2-panel-tools" role="tabpanel">
      <?php if (!empty($tools)): ?>
      <div class="hv2-learning-grid">
        <?php foreach ($tools as $tool): ?>
        <a href="/tools/<?= e($tool['slug']) ?>" class="hv2-learning-card">
          <h4><?= e($tool['title']) ?></h4>
          <?php if (!empty($tool['excerpt'])): ?>
          <p><?= str_truncate($tool['excerpt'], 80) ?></p>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </div>
      <div style="text-align:center;margin-top:var(--space-6);">
        <a href="/tools" class="btn btn-secondary btn-sm">All Free Tools →</a>
      </div>
      <?php else: ?>
      <p style="text-align:center;color:var(--text-muted);padding:var(--space-8) 0;">Tools coming soon.</p>
      <?php endif; ?>
    </div>
    
    <!-- Glossary CTA -->
    <div class="hv2-glossary-cta">
      <div>
        <strong style="color:var(--text-primary);display:block;margin-bottom:4px;">Marketing Glossary</strong>
        <p>Comprehensive A–Z digital marketing terminology reference</p>
      </div>
      <a href="/glossary" class="btn btn-secondary btn-sm">Browse Glossary →</a>
    </div>
  </div>
</section>


<!-- ── 10. EBOOK / KNOWLEDGE COMMERCE (PLACEHOLDER) ────────── -->
<!--
  NO fake ebook products. This is an extensible placeholder structure
  designed to be replaced later with real CMS-driven ebook data
  (e.g. content_type = 'ebook') from a future ecommerce phase.
-->
<section class="hv2-ebook" id="hv2-ebook">
  <div class="container">
    <div class="hv2-section-header hv2-section-header--centered">
      <span class="hv2-eyebrow">Coming Soon</span>
      <h2 class="hv2-section-title">Knowledge Commerce</h2>
    </div>
    
    <div class="hv2-ebook-placeholder">
      <h3>Premium digital publications are coming.</h3>
      <p>
        In-depth playbooks, frameworks, and implementation guides for 
        AI-driven digital marketing — authored by practitioners, not aggregators.
      </p>
      <div class="hv2-ebook-coming">Coming Soon</div>
    </div>
  </div>
</section>


<!-- ╔══════════════════════════════════════════════════════════╗
     ║  ACT IV — ACTION                                        ║
     ╚══════════════════════════════════════════════════════════╝ -->

<!-- ── 11. FINAL CTA — AI AUDIT ────────────────────────────── -->
<section class="hv2-final-cta hv2-dark" id="hv2-final-cta">
  <div class="container">
    <div class="hv2-cta-content">
      <span class="hv2-eyebrow">Free Assessment</span>
      <h2>Build Your AI Marketing Engine</h2>
      <p>
        Our team will analyze your current digital marketing setup and deliver a 
        personalized AI-readiness assessment with actionable recommendations.
      </p>
      
      <!-- Audit form — IDs preserved for existing main.js handler -->
      <form id="auditForm" class="hv2-audit-form" novalidate>
        <input type="text"  name="name"    placeholder="Your Name"    class="form-input" id="auditName"    required aria-label="Your name">
        <input type="email" name="email"   placeholder="Your Email"   class="form-input" id="auditEmail"   required aria-label="Your email">
        <input type="url"   name="website" placeholder="Your Website" class="form-input" id="auditWebsite"          aria-label="Your website">
        <button type="submit" class="btn btn-gradient btn-lg">Get Free Audit →</button>
      </form>
      <p id="auditMsg" style="font-size:var(--text-sm);margin-top:10px;display:none;"></p>
      
      <div class="hv2-audit-checks">
        <span class="hv2-audit-check"><span class="hv2-audit-check-icon">✓</span> SEO technical audit</span>
        <span class="hv2-audit-check"><span class="hv2-audit-check-icon">✓</span> Competitor analysis</span>
        <span class="hv2-audit-check"><span class="hv2-audit-check-icon">✓</span> Content assessment</span>
        <span class="hv2-audit-check"><span class="hv2-audit-check-icon">✓</span> AI readiness score</span>
      </div>
    </div>
  </div>
</section>


<!-- ── NEWSLETTER (preserving #heroNewsletter ID for main.js) ── -->
<section class="hv2-newsletter">
  <div class="container hv2-newsletter-inner">
    <h2>Weekly Marketing Intelligence</h2>
    <p>Curated AI marketing insights, algorithm updates, and strategy frameworks. No spam.</p>
    <form class="newsletter-form" id="heroNewsletter" novalidate>
      <input type="email" name="email" placeholder="your@email.com" class="form-input" required id="heroEmail" aria-label="Email address">
      <button type="submit" class="btn btn-primary">Subscribe →</button>
    </form>
    <p class="hv2-newsletter-fine">Free forever · Unsubscribe anytime · No spam</p>
  </div>
</section>


<!-- ══════════════════════════════════════════════════════════
     HOMEPAGE V2 — PAGE-SPECIFIC SCRIPTS
     Loaded ONLY on this page (not in main.php / globally).
     main.js handles newsletter + audit form submissions.
     ══════════════════════════════════════════════════════════ -->
<?php
  $_hv2JsV = @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/js/homepage-v2.js') ?: '1';
?>

<!-- Three.js r160 — lightweight CDN (deferred, used for desktop 3D only) -->
<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js" defer></script>

<!-- GSAP 3.12 + ScrollTrigger — scroll-driven animations (deferred) -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>

<!-- Homepage V2 Controller — namespaced, DOM-gated -->
<script src="/assets/js/homepage-v2.js?v=<?= $_hv2JsV ?>" defer></script>
