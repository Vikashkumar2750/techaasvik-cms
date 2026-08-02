<?php
/**
 * Pagination Partial
 * Usage: View::partial('pagination', ['total'=>$total, 'page'=>$page, 'perPage'=>$perPage, 'baseUrl'=>'/blog'])
 */
$total   = (int)($total   ?? 0);
$page    = (int)($page    ?? 1);
$perPage = (int)($perPage ?? 20);
$baseUrl = $baseUrl ?? '';
$pages   = (int)ceil($total / $perPage);

if ($pages <= 1) return;

$start = max(1, $page - 2);
$end   = min($pages, $page + 2);

function pageUrl(string $base, int $p): string {
    $qs = http_build_query(array_merge($_GET, ['page' => $p]));
    return rtrim($base, '?') . '?' . $qs;
}
?>
<nav class="pagination" aria-label="Pagination">
  <!-- Prev -->
  <?php if ($page > 1): ?>
  <a href="<?= e(pageUrl($baseUrl, $page - 1)) ?>" aria-label="Previous page">‹</a>
  <?php else: ?>
  <span class="disabled" aria-disabled="true">‹</span>
  <?php endif; ?>

  <!-- First -->
  <?php if ($start > 1): ?>
  <a href="<?= e(pageUrl($baseUrl, 1)) ?>">1</a>
  <?php if ($start > 2): ?><span style="color:var(--text-muted);">…</span><?php endif; ?>
  <?php endif; ?>

  <!-- Pages -->
  <?php for ($i = $start; $i <= $end; $i++): ?>
  <?php if ($i === $page): ?>
  <span class="active" aria-current="page"><?= $i ?></span>
  <?php else: ?>
  <a href="<?= e(pageUrl($baseUrl, $i)) ?>"><?= $i ?></a>
  <?php endif; ?>
  <?php endfor; ?>

  <!-- Last -->
  <?php if ($end < $pages): ?>
  <?php if ($end < $pages - 1): ?><span style="color:var(--text-muted);">…</span><?php endif; ?>
  <a href="<?= e(pageUrl($baseUrl, $pages)) ?>"><?= $pages ?></a>
  <?php endif; ?>

  <!-- Next -->
  <?php if ($page < $pages): ?>
  <a href="<?= e(pageUrl($baseUrl, $page + 1)) ?>" aria-label="Next page">›</a>
  <?php else: ?>
  <span class="disabled" aria-disabled="true">›</span>
  <?php endif; ?>
</nav>
<p style="text-align:center;color:var(--text-muted);font-size:13px;margin-top:8px;">
  Showing <?= (($page-1)*$perPage)+1 ?>–<?= min($page*$perPage,$total) ?> of <?= number_format($total) ?> results
</p>
