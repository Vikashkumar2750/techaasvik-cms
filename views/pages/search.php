<!-- Search Results Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <h1 style="font-size:var(--text-3xl);margin-bottom:var(--space-6);">
    <?php if ($query): ?>
    Search results for <span class="gradient-text">"<?= e($query) ?>"</span>
    <?php else: ?>
    Search TechAasvik
    <?php endif; ?>
  </h1>

  <!-- Search Form -->
  <form action="/search" method="get" style="margin-bottom:var(--space-8);">
    <div class="search-bar" style="max-width:600px;">
      <svg class="search-bar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search articles, glossary, tools…" autofocus id="searchQuery">
    </div>
  </form>

  <?php if ($query): ?>

  <?php if (!empty($results)): ?>
  <p style="font-size:var(--text-sm);color:var(--text-muted);margin-bottom:var(--space-6);">
    Found <strong><?= number_format($total) ?></strong> results for "<?= e($query) ?>"
  </p>

  <div style="display:flex;flex-direction:column;gap:20px;">
    <?php foreach ($results as $result): ?>
    <div style="padding:var(--space-5);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);transition:border-color 0.2s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
      <div style="display:flex;gap:10px;align-items:center;margin-bottom:8px;">
        <span class="type-badge type-<?= $result['type'] ?>"><?= str_replace('_', ' ', ucfirst($result['type'])) ?></span>
        <?php if (!empty($result['published_at'])): ?>
        <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= format_date($result['published_at']) ?></span>
        <?php endif; ?>
      </div>
      <h2 style="font-size:var(--text-lg);margin-bottom:6px;">
        <a href="<?= e(content_url($result)) ?>" style="color:var(--text-primary);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--brand-400)'" onmouseout="this.style.color='var(--text-primary)'"><?= e($result['title']) ?></a>
      </h2>
      <?php if (!empty($result['excerpt'])): ?>
      <p style="font-size:var(--text-sm);color:var(--text-secondary);margin:0;"><?= str_truncate($result['excerpt'], 180) ?></p>
      <?php endif; ?>
      <a href="<?= e(content_url($result)) ?>" style="font-size:var(--text-xs);color:var(--text-muted);margin-top:8px;display:inline-block;">
        www.techaasvik.com<?= content_url($result) ?>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

  <?php \Core\View::partial('pagination', ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'baseUrl' => '/search']) ?>

  <?php else: ?>
  <div style="text-align:center;padding:60px 20px;">
    <div style="font-size:48px;margin-bottom:16px;">🔍</div>
    <h2 style="font-size:var(--text-xl);">No results found for "<?= e($query) ?>"</h2>
    <p style="color:var(--text-muted);margin:12px 0 24px;">Try a different keyword or browse our content sections below.</p>
    <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
      <a href="/blog" class="btn btn-secondary">Browse Blog</a>
      <a href="/glossary" class="btn btn-secondary">Browse Glossary</a>
      <a href="/tools" class="btn btn-secondary">Browse Tools</a>
    </div>
  </div>
  <?php endif; ?>

  <?php else: ?>
  <!-- No query yet — show popular searches -->
  <div style="margin-top:var(--space-8);">
    <p style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-muted);margin-bottom:12px;">Popular searches:</p>
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
      <?php foreach (['SEO guide', 'Google Ads', 'keyword research', 'meta ads', 'GA4', 'content marketing', 'AEO', 'GEO', 'ChatGPT prompts', 'ROI calculator', 'SERP features'] as $popular): ?>
      <a href="/search?q=<?= urlencode($popular) ?>" class="tag"><?= $popular ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>
