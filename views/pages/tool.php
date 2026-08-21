<!-- Single Tool Page -->
<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16);">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Tools','url'=>'/tools'],['name'=>$tool['title']]]]) ?>

  <div style="margin-top:var(--space-4);margin-bottom:var(--space-8);">
    <span class="badge badge-brand" style="margin-bottom:var(--space-3);">⚙️ Free Tool</span>
    <h1 style="margin-bottom:var(--space-4);"><?= e($tool['title']) ?></h1>
    <?php if (!empty($tool['excerpt'])): ?>
    <p style="font-size:var(--text-lg);color:var(--text-secondary);max-width:700px;"><?= e($tool['excerpt']) ?></p>
    <?php endif; ?>
  </div>

  <?php
  // ══════════════════════════════════════════════════════
  // INTERACTIVE TOOL INTERFACES
  // Each tool slug gets its own custom interactive UI
  // ══════════════════════════════════════════════════════
  $slug = $tool['slug'] ?? '';
  $hasInteractiveTool = in_array($slug, [
      'meta-tag-generator',
      'word-counter',
      'readability-checker',
      'google-serp-preview',
      'open-graph-generator',
      'schema-markup-generator',
  ]);
  ?>

  <?php if ($slug === 'meta-tag-generator'): ?>
  <!-- ══════════════════════════════════════════════════════
       META TAG GENERATOR — Interactive SERP Preview Tool
  ══════════════════════════════════════════════════════ -->
  <div class="layout-content-sidebar">
    <div>
      <!-- Input Form -->
      <div class="card" style="padding:var(--space-6);margin-bottom:var(--space-6);">
        <h2 style="font-size:var(--text-lg);margin-bottom:var(--space-5);display:flex;align-items:center;gap:8px;">
          <span>🏷️</span> Generate Meta Tags
        </h2>

        <div style="display:flex;flex-direction:column;gap:var(--space-5);">
          <!-- Page Title -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;color:var(--text-primary);">
              Page Title <span style="color:var(--text-muted);font-weight:400;">(50-60 characters recommended)</span>
            </label>
            <input type="text" id="metaTitle" class="form-input" placeholder="e.g., Complete SEO Guide 2026 — TechAasvik" maxlength="120"
                   oninput="updatePreview()" style="width:100%;">
            <div style="display:flex;justify-content:space-between;margin-top:4px;">
              <span id="titleCount" style="font-size:11px;color:var(--text-muted);">0 / 60 characters</span>
              <span id="titleStatus" style="font-size:11px;">—</span>
            </div>
          </div>

          <!-- Meta Description -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;color:var(--text-primary);">
              Meta Description <span style="color:var(--text-muted);font-weight:400;">(150-160 characters recommended)</span>
            </label>
            <textarea id="metaDesc" class="form-input" rows="3" placeholder="e.g., Learn everything about SEO in 2026. Technical SEO, on-page optimization, link building, and more." maxlength="300"
                      oninput="updatePreview()" style="width:100%;resize:vertical;"></textarea>
            <div style="display:flex;justify-content:space-between;margin-top:4px;">
              <span id="descCount" style="font-size:11px;color:var(--text-muted);">0 / 160 characters</span>
              <span id="descStatus" style="font-size:11px;">—</span>
            </div>
          </div>

          <!-- URL -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;color:var(--text-primary);">
              Page URL
            </label>
            <input type="url" id="metaUrl" class="form-input" placeholder="https://example.com/page" oninput="updatePreview()" style="width:100%;">
          </div>

          <!-- Keywords -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;color:var(--text-primary);">
              Focus Keywords <span style="color:var(--text-muted);font-weight:400;">(comma separated)</span>
            </label>
            <input type="text" id="metaKeywords" class="form-input" placeholder="e.g., SEO guide, search engine optimization, SEO 2026" oninput="updatePreview()" style="width:100%;">
          </div>

          <!-- OG Image -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;color:var(--text-primary);">
              OG Image URL <span style="color:var(--text-muted);font-weight:400;">(1200x630 recommended)</span>
            </label>
            <input type="url" id="metaOgImage" class="form-input" placeholder="https://example.com/og-image.jpg" style="width:100%;">
          </div>

          <!-- Author -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);">
            <div>
              <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;">Author</label>
              <input type="text" id="metaAuthor" class="form-input" placeholder="Author name" style="width:100%;">
            </div>
            <div>
              <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;">Language</label>
              <select id="metaLang" class="form-input" style="width:100%;">
                <option value="en">English</option>
                <option value="hi">Hindi</option>
                <option value="en-IN">English (India)</option>
              </select>
            </div>
          </div>

          <!-- Robots -->
          <div>
            <label style="display:block;font-size:var(--text-sm);font-weight:var(--fw-semibold);margin-bottom:6px;">Robots Directives</label>
            <div style="display:flex;gap:var(--space-4);flex-wrap:wrap;">
              <label style="font-size:var(--text-sm);display:flex;align-items:center;gap:6px;cursor:pointer;">
                <input type="checkbox" id="robotsIndex" checked> Index
              </label>
              <label style="font-size:var(--text-sm);display:flex;align-items:center;gap:6px;cursor:pointer;">
                <input type="checkbox" id="robotsFollow" checked> Follow
              </label>
              <label style="font-size:var(--text-sm);display:flex;align-items:center;gap:6px;cursor:pointer;">
                <input type="checkbox" id="robotsNoarchive"> No Archive
              </label>
              <label style="font-size:var(--text-sm);display:flex;align-items:center;gap:6px;cursor:pointer;">
                <input type="checkbox" id="robotsNosnippet"> No Snippet
              </label>
            </div>
          </div>

          <button onclick="generateCode()" class="btn btn-primary" style="margin-top:var(--space-2);">
            🏷️ Generate Meta Tags
          </button>
        </div>
      </div>

      <!-- Generated Code -->
      <div id="generatedCodeBox" class="card" style="padding:var(--space-6);display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-4);">
          <h2 style="font-size:var(--text-lg);margin:0;display:flex;align-items:center;gap:8px;">
            <span>📋</span> Generated HTML
          </h2>
          <button onclick="copyCode()" class="btn btn-secondary" style="font-size:var(--text-sm);padding:6px 16px;" id="copyBtn">
            📋 Copy Code
          </button>
        </div>
        <pre style="background:var(--bg-elevated);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-4);overflow-x:auto;font-size:12px;line-height:1.6;color:var(--text-secondary);"><code id="generatedCode"></code></pre>
      </div>
    </div>

    <!-- SERP Preview Sidebar -->
    <aside style="position:sticky;top:90px;">
      <!-- Google SERP Preview -->
      <div class="card" style="padding:var(--space-5);margin-bottom:var(--space-4);">
        <h3 style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-4);">Google SERP Preview</h3>
        <div style="background:#fff;border-radius:8px;padding:16px;font-family:Arial,sans-serif;">
          <div id="serpUrl" style="font-size:12px;color:#202124;margin-bottom:2px;">https://example.com</div>
          <div id="serpTitle" style="font-size:18px;color:#1a0dab;line-height:1.3;margin-bottom:4px;cursor:pointer;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Your Page Title</div>
          <div id="serpDesc" style="font-size:13px;color:#4d5156;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">Your meta description will appear here. Write compelling copy to encourage clicks.</div>
        </div>
      </div>

      <!-- SEO Score -->
      <div class="card" style="padding:var(--space-5);margin-bottom:var(--space-4);">
        <h3 style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-4);">SEO Score</h3>
        <div id="seoChecks" style="display:flex;flex-direction:column;gap:8px;">
          <div class="seo-check" data-check="title-length" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-title">⚪</span> Title length (50-60 chars)
          </div>
          <div class="seo-check" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-desc">⚪</span> Description length (150-160 chars)
          </div>
          <div class="seo-check" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-url">⚪</span> URL provided
          </div>
          <div class="seo-check" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-keywords">⚪</span> Focus keywords set
          </div>
          <div class="seo-check" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-keyword-title">⚪</span> Keyword in title
          </div>
          <div class="seo-check" style="display:flex;align-items:center;gap:8px;font-size:12px;">
            <span id="check-keyword-desc">⚪</span> Keyword in description
          </div>
        </div>
        <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border-subtle);text-align:center;">
          <span id="seoScore" style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--text-muted);">0</span>
          <span style="font-size:var(--text-sm);color:var(--text-muted);">/ 6</span>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:var(--space-3);">Related Tools</h3>
        <nav style="display:flex;flex-direction:column;gap:var(--space-2);">
          <a href="/tools" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">← All Tools</a>
          <a href="/learn/seo" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">📚 SEO Guide</a>
          <a href="/glossary" style="font-size:var(--text-sm);color:var(--text-secondary);text-decoration:none;">📖 Glossary</a>
        </nav>
      </div>
    </aside>
  </div>

  <script>
  function updatePreview() {
    const title = document.getElementById('metaTitle').value || 'Your Page Title';
    const desc  = document.getElementById('metaDesc').value || 'Your meta description will appear here. Write compelling copy to encourage clicks.';
    const url   = document.getElementById('metaUrl').value || 'https://example.com';
    const keywords = document.getElementById('metaKeywords').value.toLowerCase();

    // SERP Preview
    document.getElementById('serpTitle').textContent = title.substring(0, 70);
    document.getElementById('serpDesc').textContent  = desc.substring(0, 160);
    document.getElementById('serpUrl').textContent    = url;

    // Character counts
    const titleLen = document.getElementById('metaTitle').value.length;
    const descLen  = document.getElementById('metaDesc').value.length;

    document.getElementById('titleCount').textContent = titleLen + ' / 60 characters';
    document.getElementById('descCount').textContent  = descLen + ' / 160 characters';

    // Status indicators
    const titleOk = titleLen >= 50 && titleLen <= 60;
    const titleWarn = titleLen > 0 && (titleLen < 50 || titleLen > 60);
    document.getElementById('titleStatus').textContent = titleLen === 0 ? '—' : titleOk ? '✅ Perfect' : titleLen > 60 ? '⚠️ Too long' : '⚠️ Too short';
    document.getElementById('titleStatus').style.color = titleOk ? '#4ade80' : '#fbbf24';

    const descOk = descLen >= 150 && descLen <= 160;
    const descWarn = descLen > 0 && (descLen < 150 || descLen > 160);
    document.getElementById('descStatus').textContent = descLen === 0 ? '—' : descOk ? '✅ Perfect' : descLen > 160 ? '⚠️ Too long' : '⚠️ Too short';
    document.getElementById('descStatus').style.color = descOk ? '#4ade80' : '#fbbf24';

    // SEO Checks
    let score = 0;
    const checks = [
      ['check-title',        titleOk],
      ['check-desc',         descOk],
      ['check-url',          url.length > 10],
      ['check-keywords',     keywords.length > 0],
      ['check-keyword-title', keywords && title.toLowerCase().includes(keywords.split(',')[0]?.trim())],
      ['check-keyword-desc',  keywords && desc.toLowerCase().includes(keywords.split(',')[0]?.trim())],
    ];
    checks.forEach(([id, ok]) => {
      document.getElementById(id).textContent = ok ? '🟢' : '🔴';
      if (ok) score++;
    });
    document.getElementById('seoScore').textContent = score;
    document.getElementById('seoScore').style.color = score >= 5 ? '#4ade80' : score >= 3 ? '#fbbf24' : '#f87171';
  }

  function generateCode() {
    const title    = document.getElementById('metaTitle').value;
    const desc     = document.getElementById('metaDesc').value;
    const url      = document.getElementById('metaUrl').value;
    const keywords = document.getElementById('metaKeywords').value;
    const ogImage  = document.getElementById('metaOgImage').value;
    const author   = document.getElementById('metaAuthor').value;
    const lang     = document.getElementById('metaLang').value;
    const index    = document.getElementById('robotsIndex').checked;
    const follow   = document.getElementById('robotsFollow').checked;
    const noarch   = document.getElementById('robotsNoarchive').checked;
    const nosnip   = document.getElementById('robotsNosnippet').checked;

    let robots = [];
    robots.push(index ? 'index' : 'noindex');
    robots.push(follow ? 'follow' : 'nofollow');
    if (noarch) robots.push('noarchive');
    if (nosnip) robots.push('nosnippet');

    let code = '';
    code += '<!-- Basic Meta Tags -->\n';
    code += '<meta charset="UTF-8">\n';
    code += '<meta name="viewport" content="width=device-width, initial-scale=1.0">\n';
    if (title) code += '<title>' + esc(title) + '</title>\n';
    if (desc)  code += '<meta name="description" content="' + esc(desc) + '">\n';
    if (keywords) code += '<meta name="keywords" content="' + esc(keywords) + '">\n';
    if (author) code += '<meta name="author" content="' + esc(author) + '">\n';
    code += '<meta name="robots" content="' + robots.join(', ') + '">\n';
    code += '<meta http-equiv="content-language" content="' + lang + '">\n';
    if (url) code += '<link rel="canonical" href="' + esc(url) + '">\n';

    code += '\n<!-- Open Graph / Facebook -->\n';
    code += '<meta property="og:type" content="website">\n';
    if (title) code += '<meta property="og:title" content="' + esc(title) + '">\n';
    if (desc)  code += '<meta property="og:description" content="' + esc(desc) + '">\n';
    if (url)   code += '<meta property="og:url" content="' + esc(url) + '">\n';
    if (ogImage) code += '<meta property="og:image" content="' + esc(ogImage) + '">\n';

    code += '\n<!-- Twitter Card -->\n';
    code += '<meta name="twitter:card" content="summary_large_image">\n';
    if (title) code += '<meta name="twitter:title" content="' + esc(title) + '">\n';
    if (desc)  code += '<meta name="twitter:description" content="' + esc(desc) + '">\n';
    if (ogImage) code += '<meta name="twitter:image" content="' + esc(ogImage) + '">\n';

    document.getElementById('generatedCode').textContent = code;
    document.getElementById('generatedCodeBox').style.display = 'block';
    document.getElementById('generatedCodeBox').scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function esc(str) {
    return str.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  function copyCode() {
    const code = document.getElementById('generatedCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
      const btn = document.getElementById('copyBtn');
      btn.textContent = '✅ Copied!';
      setTimeout(() => btn.textContent = '📋 Copy Code', 2000);
    });
  }
  </script>

  <!-- ── Tool Content (800+ words for SEO) ── -->
  <div class="prose" style="margin-top:var(--space-12);max-width:800px;">
    <h2>How to Use the Meta Tag Generator</h2>
    <p>Creating perfect meta tags shouldn't require coding knowledge. Our free Meta Tag Generator helps you create optimized HTML meta tags for any web page in seconds. Here's how to use it effectively:</p>
    
    <h3>Step 1: Enter Your Page Title</h3>
    <p>Type your page title in the first field. The ideal title length is <strong>50-60 characters</strong> — long enough to be descriptive, short enough to display fully in Google search results. Our tool shows a real-time character counter with status indicators (green for perfect length, yellow for too short or too long).</p>
    <p>Best practices for title tags:</p>
    <ul>
      <li>Include your primary keyword within the first 60 characters</li>
      <li>Place the most important keywords at the beginning of the title</li>
      <li>Use power words like "Complete," "Guide," "Free," or the current year (2026) to improve click-through rate</li>
      <li>Make each page title unique across your entire website</li>
      <li>Follow the format: <code>Primary Keyword — Secondary Keyword | Brand Name</code></li>
    </ul>

    <h3>Step 2: Write a Compelling Meta Description</h3>
    <p>The meta description is your page's advertisement in search results. While Google doesn't always use your meta description (it may generate its own from page content), a well-written description increases CTR by <strong>5-10% on average</strong>.</p>
    <p>Write your description in <strong>150-160 characters</strong>. Include your primary keyword naturally and add a clear call-to-action. Think of it like writing ad copy — every word should encourage the searcher to click.</p>

    <h3>Step 3: Add Your URL and Keywords</h3>
    <p>Enter your page's canonical URL to generate proper Open Graph tags and canonical link elements. Add your focus keywords (comma-separated) — the tool checks whether your primary keyword appears in both the title and description for optimal SEO alignment.</p>

    <h3>Step 4: Configure Open Graph & Twitter Cards</h3>
    <p>Social media meta tags control how your page appears when shared on Facebook, Twitter, LinkedIn, and WhatsApp. Add an OG image URL (recommended size: 1200×630 pixels) to ensure your shared links look professional with a rich preview image.</p>

    <h3>Step 5: Set Robots Directives</h3>
    <p>Choose how search engines should handle your page: index (allow search engines to include it in results), follow (allow crawling of links on the page), noarchive (prevent cached versions), or nosnippet (prevent text snippets in results).</p>

    <h3>Step 6: Generate and Copy</h3>
    <p>Click "Generate Meta Tags" to create your complete HTML code. The generated output includes basic meta tags, Open Graph tags for Facebook, and Twitter Card tags. Click "Copy Code" to copy everything to your clipboard, then paste it into your page's <code>&lt;head&gt;</code> section.</p>

    <h2>Why Meta Tags Matter for SEO in 2026</h2>
    <p>Meta tags are foundational HTML elements that communicate your page's content, purpose, and context to search engines and social media platforms. While Google has confirmed that meta keywords don't influence rankings, other meta tags remain critically important:</p>
    <ul>
      <li><strong>Title tags</strong> are a confirmed Google ranking factor and the most visible element in search results</li>
      <li><strong>Meta descriptions</strong> directly influence click-through rate — pages with optimized descriptions get 5.8% more clicks than those without (Backlinko study)</li>
      <li><strong>Open Graph tags</strong> control your content's appearance on social media, affecting social engagement and referral traffic</li>
      <li><strong>Canonical tags</strong> prevent duplicate content issues that can dilute your search rankings</li>
      <li><strong>Robots meta tags</strong> give you granular control over how search engines crawl and index your pages</li>
    </ul>

    <h2>Benefits of Using This Tool</h2>
    <ul>
      <li><strong>Live SERP preview:</strong> See exactly how your page will appear in Google search results before publishing</li>
      <li><strong>Real-time SEO scoring:</strong> Get instant feedback on 6 critical SEO factors — title length, description length, URL, keywords, and keyword placement</li>
      <li><strong>Complete tag generation:</strong> Creates basic meta tags, Open Graph (Facebook/LinkedIn), and Twitter Card tags in one click</li>
      <li><strong>Character counting:</strong> Visual indicators show whether your title and description are the optimal length</li>
      <li><strong>No signup required:</strong> 100% free, runs entirely in your browser — your data is never sent to any server</li>
      <li><strong>Copy with one click:</strong> Generated code is ready to paste directly into your HTML <code>&lt;head&gt;</code> section</li>
    </ul>

    <h2>Meta Tag Best Practices for 2026</h2>

    <h3>Title Tag Optimization</h3>
    <p>Google displays approximately 600 pixels of title text in search results (roughly 50-60 characters). Titles longer than this get truncated with an ellipsis (...), which can hurt click-through rates. Our tool's character counter helps you stay within the optimal range.</p>
    <p>According to a 2026 analysis by Ahrefs, pages with title tags between 50-60 characters have the highest average CTR in Google search results. Titles that include numbers, brackets, or power words ("complete," "ultimate," "free") also tend to outperform generic titles.</p>

    <h3>Meta Description Best Practices</h3>
    <p>While Google doesn't always use the meta description you provide (studies show Google rewrites meta descriptions for approximately 63% of queries), having a well-crafted description serves as a strong fallback and increases the likelihood of your intended copy appearing.</p>
    <p>The ideal meta description:</p>
    <ul>
      <li>Contains 150-160 characters (Google truncates longer descriptions)</li>
      <li>Includes the primary keyword naturally (Google bolds matching keywords in results)</li>
      <li>Contains a clear call-to-action (Learn, Discover, Get, Try, Download)</li>
      <li>Is unique per page — never use the same description across multiple pages</li>
      <li>Accurately describes the page content — misleading descriptions increase bounce rate</li>
    </ul>

    <h3>Open Graph Tag Importance</h3>
    <p>When someone shares your URL on Facebook, WhatsApp, LinkedIn, or Twitter, the platform reads your Open Graph tags to generate a rich link preview. Pages with properly configured OG tags receive <strong>2-3× more engagement</strong> on social media compared to pages with missing or incorrect OG data.</p>

    <h2>Frequently Asked Questions</h2>
    
    <h3>Are meta keywords still important for SEO?</h3>
    <p>No. Google has confirmed that it does not use the meta keywords tag as a ranking signal. However, some other search engines (like Yandex) may still consider it. Our tool includes a keywords field primarily for organizational purposes and for search engines that do use it.</p>

    <h3>Can I use the same meta description on multiple pages?</h3>
    <p>This is not recommended. Duplicate meta descriptions across pages make it harder for Google to differentiate your pages and can lead to the wrong page ranking for a query. Each page should have a unique, relevant meta description.</p>

    <h3>How often should I update my meta tags?</h3>
    <p>Review your meta tags quarterly or whenever you notice a significant drop in CTR for a page in Google Search Console. Also update meta tags when you refresh content, rebrand, or change your targeting strategy.</p>

    <h3>Do meta tags affect page load speed?</h3>
    <p>No. Meta tags are lightweight HTML elements in the <code>&lt;head&gt;</code> section that have zero impact on page load performance. They are parsed by search engine crawlers and social media platforms, not rendered visually on the page.</p>

    <h3>What's the difference between Open Graph and Twitter Card tags?</h3>
    <p>Open Graph tags (og:title, og:description, og:image) are used by Facebook, LinkedIn, WhatsApp, and most social platforms. Twitter Card tags (twitter:card, twitter:title, twitter:image) are specific to Twitter/X. When Twitter Card tags are absent, Twitter falls back to Open Graph tags. We recommend including both for maximum compatibility.</p>
  </div>

  <?php elseif ($slug === 'word-counter'): ?>
  <!-- ══════════════════════════════════════════════════════
       WORD COUNTER & TEXT ANALYZER
  ══════════════════════════════════════════════════════ -->
  <div class="card" style="padding:var(--space-6);">
    <h2 style="font-size:var(--text-lg);margin-bottom:var(--space-5);">📝 Word Counter & Text Analyzer</h2>
    <textarea id="wcText" class="form-input" rows="8" placeholder="Paste or type your text here..." oninput="analyzeText()" style="width:100%;resize:vertical;"></textarea>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-4);margin-top:var(--space-5);">
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcWords" style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Words</div>
      </div>
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcChars" style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Characters</div>
      </div>
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcSentences" style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Sentences</div>
      </div>
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcReadTime" style="font-size:var(--text-2xl);font-weight:var(--fw-bold);color:var(--brand-primary);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Min Read</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:var(--space-4);margin-top:var(--space-4);">
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcParagraphs" style="font-size:var(--text-xl);font-weight:var(--fw-bold);color:var(--accent-400);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Paragraphs</div>
      </div>
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcAvgWord" style="font-size:var(--text-xl);font-weight:var(--fw-bold);color:var(--accent-400);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Avg Word Length</div>
      </div>
      <div class="card" style="padding:var(--space-4);text-align:center;background:var(--bg-elevated);">
        <div id="wcAvgSentence" style="font-size:var(--text-xl);font-weight:var(--fw-bold);color:var(--accent-400);">0</div>
        <div style="font-size:var(--text-xs);color:var(--text-muted);margin-top:2px;">Avg Sentence Length</div>
      </div>
    </div>
  </div>
  <script>
  function analyzeText() {
    const text = document.getElementById('wcText').value;
    const words = text.trim() ? text.trim().split(/\s+/) : [];
    const chars = text.length;
    const sentences = text.trim() ? text.split(/[.!?]+/).filter(s => s.trim().length > 0) : [];
    const paragraphs = text.trim() ? text.split(/\n\n+/).filter(p => p.trim().length > 0) : [];
    const readTime = Math.max(1, Math.ceil(words.length / 200));
    const avgWord = words.length ? (words.reduce((a,w) => a + w.length, 0) / words.length).toFixed(1) : 0;
    const avgSentence = sentences.length ? Math.round(words.length / sentences.length) : 0;

    document.getElementById('wcWords').textContent = words.length;
    document.getElementById('wcChars').textContent = chars;
    document.getElementById('wcSentences').textContent = sentences.length;
    document.getElementById('wcReadTime').textContent = readTime;
    document.getElementById('wcParagraphs').textContent = paragraphs.length;
    document.getElementById('wcAvgWord').textContent = avgWord;
    document.getElementById('wcAvgSentence').textContent = avgSentence;
  }
  </script>

  <?php else: ?>
  <!-- ══════════════════════════════════════════════════════
       DEFAULT TOOL VIEW (DB content + info sidebar)
  ══════════════════════════════════════════════════════ -->
  <div class="layout-content-sidebar">
    <div>
      <?php if (!empty($tool['content'])): ?>
      <div class="prose"><?= $tool['content'] ?></div>
      <?php endif; ?>
    </div>
    <aside style="position:sticky;top:100px;">
      <div class="card" style="padding:var(--space-5);">
        <h3 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:var(--space-4);">Tool Info</h3>
        <div style="display:flex;flex-direction:column;gap:var(--space-3);">
          <?php if ($tool['difficulty'] ?? ''): ?>
          <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);">
            <span style="color:var(--text-muted);">Difficulty</span>
            <span style="color:var(--text-primary);font-weight:var(--fw-medium);"><?= ucfirst($tool['difficulty']) ?></span>
          </div>
          <?php endif; ?>
          <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);">
            <span style="color:var(--text-muted);">Price</span>
            <span style="color:var(--accent-400);font-weight:var(--fw-semibold);">Free</span>
          </div>
        </div>
      </div>
      <div class="card" style="padding:var(--space-5);margin-top:var(--space-4);">
        <a href="/tools" class="btn btn-secondary" style="width:100%;">← Browse All Tools</a>
      </div>
    </aside>
  </div>
  <?php endif; ?>

</div>
