<?php if (!empty($post)): ?>
<!-- Article TOC (generated from headings) -->
<nav class="toc" id="articleToc" aria-label="Table of Contents">
  <p class="toc-title">📋 Table of Contents</p>
  <ol class="toc-list" id="tocList">
    <!-- Populated by JS from h2/h3 headings -->
  </ol>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const content = document.getElementById('articleBody');
  const list    = document.getElementById('tocList');
  if (!content || !list) return;

  const headings = content.querySelectorAll('h2, h3');
  if (headings.length < 3) {
    document.getElementById('articleToc').style.display = 'none';
    return;
  }

  headings.forEach((h, i) => {
    if (!h.id) h.id = 'section-' + i;
    const li  = document.createElement('li');
    li.className = 'toc-item' + (h.tagName === 'H3' ? ' toc-h3' : '');
    const a   = document.createElement('a');
    a.href    = '#' + h.id;
    a.textContent = h.textContent;
    li.appendChild(a);
    list.appendChild(li);
  });

  // Highlight active section on scroll
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const id   = entry.target.id;
      const link = list.querySelector(`a[href="#${id}"]`);
      if (link) link.closest('.toc-item').classList.toggle('active', entry.isIntersecting);
    });
  }, { rootMargin: '-80px 0px -70% 0px' });

  headings.forEach(h => observer.observe(h));
});
</script>
<?php endif; ?>
