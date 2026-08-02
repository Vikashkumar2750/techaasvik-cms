<!-- Single Post / Article View -->
<div class="container" style="padding-top:var(--space-8);padding-bottom:var(--space-16);">

  <!-- Breadcrumb -->
  <?php
  $type = $post['type'] ?? 'post';
  $crumbs = [['name' => 'Home', 'url' => '/']];
  if ($type === 'post') $crumbs[] = ['name' => 'Blog', 'url' => '/blog'];
  elseif ($type === 'news_article') $crumbs[] = ['name' => 'News', 'url' => '/news'];
  $crumbs[] = ['name' => $post['title']];
  \Core\View::partial('breadcrumb', ['crumbs' => $crumbs]);
  ?>

  <div style="display:grid;grid-template-columns:1fr 280px;gap:var(--space-12);align-items:start;">

    <!-- ── Main Content ── -->
    <article id="mainArticle">

      <!-- Article Header -->
      <header style="margin-bottom:var(--space-8);">
        <?php if (!empty($post['difficulty'])): ?>
        <span class="badge badge-brand" style="margin-bottom:var(--space-4);"><?= ucfirst($post['difficulty']) ?></span>
        <?php endif; ?>

        <h1 style="margin-bottom:var(--space-5);"><?= e($post['title']) ?></h1>

        <?php if (!empty($post['excerpt'])): ?>
        <p style="font-size:var(--text-xl);color:var(--text-secondary);line-height:var(--leading-relaxed);margin-bottom:var(--space-6);"><?= e($post['excerpt']) ?></p>
        <?php endif; ?>

        <!-- Meta Row -->
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:var(--space-5);padding:var(--space-4) 0;border-top:1px solid var(--border-subtle);border-bottom:1px solid var(--border-subtle);">
          <?php if (!empty($post['author_name'])): ?>
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--brand-600);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#fff;flex-shrink:0;">
              <?= str_initials($post['author_name']) ?>
            </div>
            <div>
              <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--text-primary);"><?= e($post['author_name']) ?></div>
              <?php if (!empty($post['author_slug'])): ?>
              <a href="/authors/<?= e($post['author_slug']) ?>" style="font-size:var(--text-xs);color:var(--text-muted);">View Profile</a>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($post['published_at'])): ?>
          <div style="font-size:var(--text-sm);color:var(--text-muted);">
            <time datetime="<?= date('c', strtotime($post['published_at'])) ?>"><?= format_date($post['published_at']) ?></time>
          </div>
          <?php endif; ?>

          <?php if (!empty($post['updated_at']) && $post['updated_at'] !== $post['published_at']): ?>
          <div style="font-size:var(--text-xs);color:var(--text-muted);">
            Updated: <time datetime="<?= date('c', strtotime($post['updated_at'])) ?>"><?= format_date($post['updated_at']) ?></time>
          </div>
          <?php endif; ?>

          <?php if (!empty($post['read_time'])): ?>
          <span style="font-size:var(--text-sm);color:var(--text-muted);">⏱ <?= $post['read_time'] ?> min read</span>
          <?php endif; ?>

          <?php if (!empty($post['word_count'])): ?>
          <span style="font-size:var(--text-xs);color:var(--text-muted);"><?= number_format($post['word_count']) ?> words</span>
          <?php endif; ?>
        </div>
      </header>

      <!-- Featured Image -->
      <?php if (!empty($post['featured_image_id'])): ?>
      <figure style="margin:0 0 var(--space-8);">
        <img src="/assets/images/static/placeholder.jpg"
             alt="<?= e($post['title']) ?>"
             style="width:100%;border-radius:var(--radius-xl);aspect-ratio:16/9;object-fit:cover;"
             loading="eager">
      </figure>
      <?php endif; ?>

      <!-- TOC -->
      <?php \Core\View::partial('toc', ['post' => $post]) ?>

      <!-- Article Body -->
      <div class="prose" id="articleBody">
        <?= $post['content'] ?? '<p>Content coming soon.</p>' ?>
      </div>

      <!-- Tags -->
      <?php if (!empty($post['tags'])): ?>
      <div style="margin-top:var(--space-8);padding-top:var(--space-6);border-top:1px solid var(--border-subtle);">
        <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-3);">Tags</p>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          <?php foreach ($post['tags'] as $tag): ?>
          <a href="/tag/<?= e($tag['slug']) ?>" class="tag">#<?= e($tag['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Author Box -->
      <?php if (!empty($post['author_name'])): ?>
      <div class="author-box" style="margin-top:var(--space-10);">
        <div style="width:64px;height:64px;border-radius:50%;background:var(--brand-600);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:24px;color:#fff;flex-shrink:0;">
          <?= str_initials($post['author_name']) ?>
        </div>
        <div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <span class="author-box-name"><?= e($post['author_name']) ?></span>
            <?php if (!empty($post['author_bio'])): ?>
            <span class="badge badge-brand">Expert Author</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($post['author_bio'])): ?>
          <p class="author-box-bio"><?= str_truncate($post['author_bio'], 200) ?></p>
          <?php endif; ?>
          <?php if (!empty($post['author_slug'])): ?>
          <a href="/authors/<?= e($post['author_slug']) ?>" class="btn btn-secondary btn-sm" style="margin-top:8px;">View All Articles →</a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- FAQ Section -->
      <?php if (!empty($faqs)): ?>
      <section style="margin-top:var(--space-12);" aria-label="Frequently Asked Questions">
        <h2 style="margin-bottom:var(--space-6);">Frequently Asked Questions</h2>
        <div style="display:flex;flex-direction:column;gap:16px;">
          <?php foreach ($faqs as $i => $faq): ?>
          <details style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);overflow:hidden;" id="faq-<?= $i ?>">
            <summary style="padding:16px 20px;cursor:pointer;font-weight:var(--fw-semibold);color:var(--text-primary);list-style:none;display:flex;justify-content:space-between;align-items:center;">
              <?= e($faq['question']) ?>
              <span style="color:var(--text-muted);font-size:20px;transition:transform 0.2s;flex-shrink:0;">+</span>
            </summary>
            <div style="padding:0 20px 16px;color:var(--text-secondary);font-size:var(--text-sm);line-height:var(--leading-relaxed);">
              <?= e($faq['answer']) ?>
            </div>
          </details>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- Share Bar -->
      <div style="margin-top:var(--space-10);padding:var(--space-5);background:var(--bg-surface);border-radius:var(--radius-xl);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <p style="font-size:var(--text-sm);font-weight:var(--fw-semibold);margin:0;">Found this helpful? Share it →</p>
        <div style="display:flex;gap:8px;">
          <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">𝕏 Twitter</a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">in LinkedIn</a>
          <button onclick="navigator.clipboard.writeText(window.location.href);this.textContent='✅ Copied!'" class="btn btn-secondary btn-sm">🔗 Copy Link</button>
        </div>
      </div>

    </article>

    <!-- ── Sidebar ── -->
    <aside style="position:sticky;top:calc(var(--header-height) + 24px);">

      <!-- Newsletter CTA -->
      <div style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.05));border:1px solid rgba(99,102,241,0.2);border-radius:var(--radius-xl);padding:var(--space-5);margin-bottom:var(--space-5);">
        <h3 style="font-size:var(--text-base);margin-bottom:var(--space-2);">📧 Weekly Digest</h3>
        <p style="font-size:var(--text-xs);color:var(--text-muted);margin-bottom:var(--space-4);">Join 10,000+ marketers. No spam.</p>
        <form id="sidebarNewsletter" novalidate>
          <input type="email" name="email" placeholder="your@email.com" class="form-input" style="margin-bottom:8px;" required id="sidebarEmail">
          <button type="submit" class="btn btn-primary w-full">Subscribe →</button>
        </form>
      </div>

      <!-- Related Posts -->
      <?php if (!empty($related)): ?>
      <div>
        <p style="font-size:var(--text-xs);font-weight:var(--fw-semibold);text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin-bottom:var(--space-4);">Related Articles</p>
        <div style="display:flex;flex-direction:column;gap:12px;">
          <?php foreach ($related as $rel): ?>
          <a href="<?= e(content_url($rel)) ?>" style="display:flex;gap:10px;text-decoration:none;padding:10px;border-radius:var(--radius-lg);border:1px solid var(--border-subtle);transition:border-color 0.2s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
            <div style="width:44px;height:44px;border-radius:var(--radius-md);background:var(--bg-elevated);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:18px;">📄</div>
            <span style="font-size:var(--text-xs);font-weight:var(--fw-medium);color:var(--text-primary);line-height:1.4;"><?= e(mb_strimwidth($rel['title'], 0, 70, '…')) ?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Free Audit CTA -->
      <div style="margin-top:var(--space-6);background:var(--brand-600);border-radius:var(--radius-xl);padding:var(--space-5);text-align:center;">
        <div style="font-size:28px;margin-bottom:8px;">🎯</div>
        <h3 style="font-size:var(--text-sm);color:#fff;margin-bottom:6px;">Free Marketing Audit</h3>
        <p style="font-size:var(--text-xs);color:rgba(255,255,255,0.7);margin-bottom:12px;">Get a personalized SEO & marketing growth plan.</p>
        <a href="/free-audit" class="btn btn-secondary btn-sm w-full" style="justify-content:center;">Get Free Audit →</a>
      </div>

    </aside>

  </div>
</div>
