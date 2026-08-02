<?php
/**
 * Breadcrumb Partial
 * Usage: View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'], ...]])
 */
$crumbs = $crumbs ?? [];
if (empty($crumbs)) return;
?>
<nav aria-label="Breadcrumb" class="breadcrumb">
  <ol itemscope itemtype="https://schema.org/BreadcrumbList" style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;list-style:none;padding:0;margin:0;">
    <?php foreach ($crumbs as $i => $crumb): ?>
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <?php if (isset($crumb['url']) && $i < count($crumbs) - 1): ?>
        <a href="<?= e($crumb['url']) ?>" itemprop="item" style="color:var(--text-muted);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">
          <span itemprop="name"><?= e($crumb['name']) ?></span>
        </a>
      <?php else: ?>
        <span class="breadcrumb-current" itemprop="name" aria-current="page"><?= e($crumb['name']) ?></span>
      <?php endif; ?>
      <meta itemprop="position" content="<?= $i + 1 ?>">
      <?php if ($i < count($crumbs) - 1): ?>
        <span class="breadcrumb-sep" aria-hidden="true">/</span>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ol>
</nav>
