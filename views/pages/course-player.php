<!-- Course Player v2 — Submodule sidebar + AJAX content panel -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken      = Auth::csrfToken();
$moduleNum      = $moduleNum      ?? 1;
$submoduleNum   = $submoduleNum   ?? 1;
$totalModules   = $totalModules   ?? 10;
$freeCount      = $freeCount      ?? 5;
$isPaid         = $isPaid         ?? false;
$module         = $module         ?? [];
$allModules     = $allModules     ?? [];
$submodules     = $submodules     ?? [];
$currentSub     = $currentSub     ?? [];
$progress       = $progress       ?? [];
$completedSubKeys = $completedSubKeys ?? [];
$videoEnabled   = $videoEnabled   ?? false;
$priceOrig      = $priceOrig      ?? 999;
$priceSale      = $priceSale      ?? 199;
$processingFeePct = $processingFeePct ?? 1.5;
$cert           = $cert           ?? null;
$overallScore   = $overallScore   ?? 0;
$grade          = $grade          ?? 'Pass';
$courseSlug     = 'ai-marketing-course';
$enrollment     = $enrollment     ?? [];

// Progress counts
$doneModules = count(array_filter($progress, fn($p) => $p['completed']));
$totalSubs   = count($allModules) * 5;
$doneSubs    = count($completedSubKeys);
$pct         = $totalSubs > 0 ? round($doneSubs / $totalSubs * 100) : 0;
?>
<style>
/* ── Player Layout ─────────────────────────────────────────────── */
.player-wrap {
  display: grid;
  grid-template-columns: 300px 1fr;
  min-height: calc(100vh - 64px);
  background: var(--bg-base);
}
.player-sidebar {
  background: var(--bg-surface);
  border-right: 1px solid var(--border-subtle);
  position: sticky; top: 64px;
  height: calc(100vh - 64px);
  overflow-y: auto; flex-shrink: 0;
  display: flex; flex-direction: column;
}
.player-sidebar::-webkit-scrollbar { width: 4px; }
.player-sidebar::-webkit-scrollbar-thumb { background: var(--border-subtle); border-radius: 2px; }
.player-main { overflow-y: auto; height: calc(100vh - 64px); }

/* ── Sidebar Module Items ──────────────────────────────────────── */
.mod-group { border-bottom: 1px solid var(--border-subtle); }
.mod-header {
  display: flex; align-items: center; gap:10px;
  padding: 12px 16px; cursor: pointer;
  transition: background 0.15s;
  user-select: none;
}
.mod-header:hover { background: var(--bg-elevated); }
.mod-header.active { background: rgba(99,102,241,0.06); }
.mod-num {
  width: 26px; height: 26px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; flex-shrink: 0;
  background: var(--bg-elevated); color: var(--text-muted);
}
.mod-num.done { background: #34d399; color: #fff; }
.mod-num.active { background: #6366f1; color: #fff; }
.mod-num.locked { background: var(--bg-elevated); color: var(--border-subtle); }
.mod-title-text { font-size: 12px; font-weight: 600; color: var(--text-primary); line-height: 1.35; flex: 1; }
.mod-chevron { font-size: 10px; color: var(--text-muted); transition: transform 0.2s; flex-shrink: 0; }
.mod-group.expanded .mod-chevron { transform: rotate(90deg); }

/* ── Submodule Items ───────────────────────────────────────────── */
.sub-list { display: none; }
.mod-group.expanded .sub-list { display: block; }
.sub-item {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 16px 8px 52px;
  cursor: pointer; text-decoration: none;
  transition: background 0.15s; position: relative;
  font-size: 12px; color: var(--text-secondary);
}
.sub-item:hover { background: var(--bg-elevated); color: var(--text-primary); }
.sub-item.active { color: #6366f1; font-weight: 600; }
.sub-item.done::after {
  content: '✓'; position: absolute; right: 12px;
  font-size: 11px; color: #34d399; font-weight: 700;
}
.sub-item.locked { opacity: 0.45; cursor: default; }
.sub-type-badge {
  font-size: 10px; padding: 2px 6px; border-radius: 4px;
  background: rgba(99,102,241,0.1); color: #6366f1; font-weight: 600;
  flex-shrink: 0;
}
.sub-type-badge.quiz { background: rgba(245,158,11,0.1); color: #f59e0b; }

/* ── Content Panel ─────────────────────────────────────────────── */
.player-content { padding: 32px 40px; max-width: 820px; }
.sub-header {
  display: flex; align-items: center; gap: 12px;
  margin-bottom: 28px; padding-bottom: 20px;
  border-bottom: 1px solid var(--border-subtle);
}
.sub-badge {
  padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700;
  background: rgba(99,102,241,0.1); color: #6366f1; text-transform: uppercase; letter-spacing: 0.06em;
}
.sub-badge.quiz { background: rgba(245,158,11,0.1); color: #f59e0b; }
.sub-complete-btn {
  margin-top: 28px; padding: 12px 24px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff; border: none; border-radius: 8px;
  font-size: 14px; font-weight: 600; cursor: pointer;
  transition: opacity 0.2s;
}
.sub-complete-btn:hover { opacity: 0.9; }
.sub-complete-btn:disabled { opacity: 0.5; cursor: default; }

/* ── Infographic / Visual Cards ───────────────────────────────── */
.visual-card {
  background: linear-gradient(135deg, rgba(99,102,241,0.05), rgba(139,92,246,0.03));
  border: 1px solid rgba(99,102,241,0.12); border-radius: 12px;
  padding: 24px; margin: 20px 0;
}
.visual-card h4 { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; }
.visual-steps { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
.visual-step {
  display: flex; align-items: center; gap: 8px;
  background: var(--bg-elevated); border-radius: 8px;
  padding: 8px 14px; font-size: 12px; font-weight: 600; color: var(--text-primary);
}
.visual-step-num {
  width: 22px; height: 22px; border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff; font-size: 10px; font-weight: 700;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ── Quiz Styles ───────────────────────────────────────────────── */
.quiz-wrap { max-width: 640px; }
.quiz-q { margin-bottom: 24px; }
.quiz-q-text { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 12px; line-height: 1.5; }
.quiz-option {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 12px 16px; border: 1.5px solid var(--border-subtle); border-radius: 10px;
  cursor: pointer; transition: all 0.15s; margin-bottom: 8px;
}
.quiz-option:hover { border-color: #6366f1; background: rgba(99,102,241,0.03); }
.quiz-option.selected { border-color: #6366f1; background: rgba(99,102,241,0.07); }
.quiz-option.correct { border-color: #34d399; background: rgba(52,211,153,0.08); }
.quiz-option.wrong { border-color: #f87171; background: rgba(248,113,113,0.08); }
.quiz-radio {
  width: 18px; height: 18px; border-radius: 50%;
  border: 2px solid var(--border-subtle);
  flex-shrink: 0; margin-top: 1px;
  transition: all 0.15s;
}
.quiz-option.selected .quiz-radio { border-color: #6366f1; background: #6366f1; }
.quiz-result {
  text-align: center; padding: 28px;
  border-radius: 12px; margin-top: 20px;
}
.quiz-result.pass { background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2); }
.quiz-result.fail { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.2); }
.quiz-score-big { font-size: 48px; font-weight: 900; line-height: 1; }

/* ── Grade Badge ───────────────────────────────────────────────── */
.grade-badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 4px 14px; border-radius: 100px; font-size: 13px; font-weight: 700;
}
.grade-A { background: rgba(52,211,153,0.15); color: #059669; }
.grade-B { background: rgba(99,102,241,0.12); color: #6366f1; }
.grade-C { background: rgba(245,158,11,0.12); color: #d97706; }
.grade-Pass { background: rgba(100,116,139,0.12); color: #64748b; }

/* ── Mobile Sidebar ────────────────────────────────────────────── */
.mob-menu-btn {
  display: none;
  position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
  background: #6366f1; color: #fff;
  border: none; border-radius: 100px; padding: 12px 24px;
  font-size: 14px; font-weight: 600; z-index: 910; cursor: pointer;
  box-shadow: 0 4px 20px rgba(99,102,241,0.4);
  gap: 8px; align-items: center;
}
.mob-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.5); z-index: 890; backdrop-filter: blur(2px);
}
@media (max-width: 900px) {
  .player-wrap { grid-template-columns: 1fr; }
  .player-sidebar {
    position: fixed; left: -100%; width: 300px; top: 64px;
    height: calc(100vh - 64px); z-index: 900; transition: left 0.28s cubic-bezier(.4,0,.2,1);
  }
  .player-sidebar.mob-open { left: 0; }
  .player-main { height: auto; overflow-y: visible; }
  .player-content { padding: 20px 16px 80px; }
  .mob-menu-btn { display: flex; }
  .mob-overlay.show { display: block; }
}
@media (max-width: 480px) {
  .sub-header { flex-wrap: wrap; gap: 8px; }
  .visual-steps { flex-direction: column; }
}
</style>

<!-- Mobile overlay -->
<div class="mob-overlay" id="mobOverlay" onclick="closeSidebar()"></div>

<div class="player-wrap" id="playerWrap">

  <!-- ── LEFT SIDEBAR ─────────────────────────────────────────── -->
  <aside class="player-sidebar" id="playerSidebar">

    <!-- Course info + progress -->
    <div style="padding:16px;border-bottom:1px solid var(--border-subtle);">
      <a href="/courses/<?= $courseSlug ?>" style="font-size:11px;color:var(--text-muted);text-decoration:none;display:block;margin-bottom:8px;">← Back to Course</a>
      <div style="font-size:13px;font-weight:700;color:var(--text-primary);line-height:1.3;">AI Marketing & ChatGPT SEO</div>

      <!-- Grade badge if score exists -->
      <?php if ($overallScore > 0): ?>
      <div style="margin-top:8px;">
        <span class="grade-badge grade-<?= htmlspecialchars($grade) ?>">
          Grade <?= htmlspecialchars($grade) ?> · <?= $overallScore ?>%
        </span>
      </div>
      <?php endif; ?>

      <!-- Progress bar -->
      <div style="margin-top:10px;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-bottom:4px;">
          <span>Progress</span><span><?= $doneSubs ?>/<?= $totalSubs ?> lessons</span>
        </div>
        <div style="height:5px;background:var(--bg-elevated);border-radius:3px;overflow:hidden;">
          <div id="progressBar" style="width:<?= $pct ?>%;height:100%;background:linear-gradient(90deg,#6366f1,#34d399);transition:width 0.5s;"></div>
        </div>
      </div>
    </div>

    <!-- Module + Submodule tree -->
    <nav style="flex:1;overflow-y:auto;padding:8px 0;" id="modNav">
      <?php foreach ($allModules as $mod):
        $mNum     = $mod['num'];
        $isDone   = !empty($progress[$mNum]['completed']);
        $isLocked = $mNum > $freeCount && !$isPaid;
        $isCurrent= $mNum === $moduleNum;
        $modSubs  = []; // We'll generate submodule keys on client side for display
        // Submodule items for this module
        $modSubKeys = array_map(fn($s) => $mNum . '-' . $s, range(1, 5));
        $allSubDone = !$isLocked && count(array_filter($modSubKeys, fn($k) => in_array($k, $completedSubKeys))) === 5;
      ?>
      <div class="mod-group <?= $isCurrent ? 'expanded' : '' ?>" id="modGroup<?= $mNum ?>">
        <div class="mod-header <?= $isCurrent ? 'active' : '' ?>"
             onclick="<?= $isLocked ? "showPaywall()" : "toggleMod($mNum)" ?>">
          <div class="mod-num <?= $isDone || $allSubDone ? 'done' : ($isCurrent ? 'active' : ($isLocked ? 'locked' : '')) ?>">
            <?php if ($isDone || $allSubDone): ?>✓
            <?php elseif ($isLocked): ?>🔒
            <?php else: ?><?= $mNum ?>
            <?php endif; ?>
          </div>
          <div style="flex:1;">
            <div class="mod-title-text"><?= htmlspecialchars($mod['title']) ?></div>
            <div style="font-size:10px;color:var(--text-muted);margin-top:2px;"><?= $mod['duration'] ?? '45 min' ?> · 5 lessons</div>
          </div>
          <?php if (!$isLocked): ?>
          <span class="mod-chevron">▶</span>
          <?php endif; ?>
        </div>

        <?php if (!$isLocked): ?>
        <div class="sub-list" id="subList<?= $mNum ?>">
          <?php
          $subTitles = [
            1 => ['Overview & Mindset', "AI's Impact on Marketing", 'Search & Content Shift', 'Automation & Personalization', 'Quiz'],
            2 => ['ChatGPT Basics for Marketers', 'Context Engineering', 'The CRAFT Framework', 'Building Reusable Workflows', 'Quiz'],
            3 => ['Research Mindset', 'Customer & Competitor Intel', 'Mining Reviews & Gaps', 'Positioning Framework', 'Quiz'],
            4 => ['Keyword Strategy with AI', 'Topical Authority Clusters', 'AI Content Briefs', 'Content Refresh System', 'Quiz'],
            5 => ['AI Content at Scale', 'E-E-A-T & Brand Voice', 'Multimedia & Repurposing', '30-Day Content System', 'Quiz'],
            6 => ['GEO Fundamentals', 'AI Overview Optimization', 'Entity Signals & Schema', 'AEO Answer Engineering', 'Quiz'],
            7 => ['Google Performance Max', 'Meta Advantage+ Ads', 'AI Bidding & Signals', 'ROAS Scaling System', 'Quiz'],
            8 => ['CRO with AI', 'Lead Scoring & Nurturing', 'n8n Workflow Automation', 'Email Sequences', 'Quiz'],
            9 => ['GA4 Setup & Events', 'Attribution Models', 'CAC, LTV & North Star', 'Marketing Diagnosis', 'Quiz'],
            10 => ['Build Your AI Marketing OS', 'Capstone: Real Business Plan', 'AI Safety & Ethics', 'Career Roadmap & Next Steps', 'Quiz'],
          ];
          $thisSubs = $subTitles[$mNum] ?? ['Part 1','Part 2','Part 3','Part 4','Quiz'];
          foreach ($thisSubs as $sIdx => $sTitle):
            $sNum    = $sIdx + 1;
            $subKey  = $mNum . '-' . $sNum;
            $isQuiz  = $sIdx === 4;
            $isDoneS = in_array($subKey, $completedSubKeys);
            $isCurS  = $mNum === $moduleNum && $sNum === $submoduleNum;
            $href    = "/courses/{$courseSlug}/learn/{$mNum}/{$sNum}";
          ?>
          <a href="<?= $href ?>"
             class="sub-item <?= $isDoneS ? 'done' : '' ?> <?= $isCurS ? 'active' : '' ?>"
             title="<?= htmlspecialchars($sTitle) ?>">
            <?= $isQuiz ? '📝' : '📖' ?>
            <span style="flex:1;line-height:1.3;"><?= htmlspecialchars($sTitle) ?></span>
            <?php if ($isQuiz): ?><span class="sub-type-badge quiz">Quiz</span><?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Locked module teaser -->
        <div style="padding:8px 16px 8px 52px;">
          <div style="font-size:11px;color:var(--text-muted);padding:8px;background:var(--bg-elevated);border-radius:6px;">
            🔒 Unlock for ₹<?= number_format($priceSale) ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </nav>

    <!-- Upgrade CTA for free users -->
    <?php if (!$isPaid): ?>
    <div style="padding:12px;border-top:1px solid var(--border-subtle);">
      <a href="/courses/<?= $courseSlug ?>/enroll" style="display:block;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">
        🔓 Unlock All 10 Modules — ₹<?= number_format($priceSale) ?>
      </a>
    </div>
    <?php endif; ?>
  </aside>

  <!-- ── RIGHT CONTENT PANEL ──────────────────────────────────── -->
  <main class="player-main" id="playerMain">
    <div class="player-content" id="playerContent">

      <!-- Submodule header -->
      <div class="sub-header">
        <span class="sub-badge <?= ($currentSub['type'] ?? '') === 'quiz' ? 'quiz' : '' ?>">
          <?= ($currentSub['type'] ?? 'lesson') === 'quiz' ? '📝 Quiz' : '📖 Lesson' ?>
        </span>
        <div>
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
            Module <?= $moduleNum ?> · Lesson <?= $submoduleNum ?>/5
          </div>
          <h1 style="font-size:20px;font-weight:800;color:var(--text-primary);line-height:1.3;margin:0;">
            <?= htmlspecialchars($currentSub['title'] ?? $module['title'] ?? 'Loading...') ?>
          </h1>
        </div>

        <?php if ($overallScore > 0): ?>
        <div style="margin-left:auto;text-align:right;">
          <div style="font-size:10px;color:var(--text-muted);">Your Grade</div>
          <span class="grade-badge grade-<?= htmlspecialchars($grade) ?>" style="font-size:15px;">
            <?= htmlspecialchars($grade) ?>
          </span>
        </div>
        <?php endif; ?>
      </div>

      <!-- ── LESSON CONTENT or QUIZ ── -->
      <?php $subKey = $moduleNum . '-' . $submoduleNum; ?>
      <?php $isQuizSub = ($currentSub['type'] ?? '') === 'quiz'; ?>

      <?php if ($isQuizSub): ?>
        <!-- QUIZ SUBMODULE -->
        <?php include APP_ROOT . '/views/partials/course-quiz-inline.php'; ?>
      <?php else: ?>
        <!-- LESSON CONTENT with visual -->
        <?php include APP_ROOT . '/views/partials/course-submodule-content.php'; ?>

        <!-- Mark complete button -->
        <?php $isDoneNow = in_array($subKey, $completedSubKeys); ?>
        <div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
          <?php if ($isDoneNow): ?>
          <button class="sub-complete-btn" disabled style="background:var(--bg-elevated);color:var(--text-muted);">✓ Completed</button>
          <?php else: ?>
          <button class="sub-complete-btn" id="markDoneBtn" onclick="markSubDone('<?= $subKey ?>')">
            Mark as Complete →
          </button>
          <?php endif; ?>

          <!-- Navigation arrows -->
          <div style="display:flex;gap:8px;">
            <?php if ($submoduleNum > 1): ?>
            <a href="/courses/<?= $courseSlug ?>/learn/<?= $moduleNum ?>/<?= $submoduleNum - 1 ?>"
               style="padding:10px 16px;border:1px solid var(--border-subtle);border-radius:8px;font-size:13px;font-weight:600;color:var(--text-primary);text-decoration:none;">← Prev</a>
            <?php endif; ?>
            <?php if ($submoduleNum < 5): ?>
            <a href="/courses/<?= $courseSlug ?>/learn/<?= $moduleNum ?>/<?= $submoduleNum + 1 ?>"
               style="padding:10px 20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Next →</a>
            <?php elseif ($moduleNum < $totalModules): ?>
            <?php $nextMod = $moduleNum + 1; $nextLocked = $nextMod > $freeCount && !$isPaid; ?>
            <?php if ($nextLocked): ?>
            <a href="/courses/<?= $courseSlug ?>/enroll"
               style="padding:10px 20px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">🔓 Unlock Module <?= $nextMod ?> →</a>
            <?php else: ?>
            <a href="/courses/<?= $courseSlug ?>/learn/<?= $nextMod ?>/1"
               style="padding:10px 20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Next Module →</a>
            <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </main>
</div>

<!-- Mobile sidebar toggle button -->
<button class="mob-menu-btn" id="mobMenuBtn" onclick="toggleSidebar()">
  ☰ Course Modules
</button>

<script>
const CSRF = '<?= $csrfToken ?>';
const courseSlug = '<?= $courseSlug ?>';

// ── Sidebar accordion ──────────────────────────────────────────
function toggleMod(n) {
  const g = document.getElementById('modGroup' + n);
  if (!g) return;
  g.classList.toggle('expanded');
}

// ── Mobile sidebar ─────────────────────────────────────────────
function toggleSidebar() {
  document.getElementById('playerSidebar').classList.toggle('mob-open');
  document.getElementById('mobOverlay').classList.toggle('show');
}
function closeSidebar() {
  document.getElementById('playerSidebar').classList.remove('mob-open');
  document.getElementById('mobOverlay').classList.remove('show');
}

// ── Mark submodule complete ────────────────────────────────────
function markSubDone(subKey) {
  const btn = document.getElementById('markDoneBtn');
  if (!btn) return;
  btn.disabled = true; btn.textContent = 'Saving…';

  fetch('/courses/submodule-complete', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ csrf_token: CSRF, sub_key: subKey })
  })
  .then(r => r.json())
  .then(d => {
    if (d.success) {
      btn.textContent = '✓ Completed';
      btn.style.background = '#34d399';

      // Mark sidebar item as done
      document.querySelectorAll('.sub-item').forEach(el => {
        if (el.getAttribute('href') && el.getAttribute('href').includes(subKey.replace('-','/'))) {
          el.classList.add('done');
        }
      });

      // Update grade badge if returned
      if (d.grade && d.score > 0) {
        updateGrade(d.grade, d.score);
      }

      // Increment progress bar
      updateProgressBar();

      // Auto-advance after 1s
      setTimeout(() => {
        const parts = subKey.split('-');
        const mod = parseInt(parts[0]); const sub = parseInt(parts[1]);
        if (sub < 5) {
          window.location = `/courses/${courseSlug}/learn/${mod}/${sub + 1}`;
        }
      }, 900);
    }
  })
  .catch(() => { btn.disabled = false; btn.textContent = 'Mark as Complete →'; });
}

function updateGrade(grade, score) {
  document.querySelectorAll('.grade-badge').forEach(el => {
    el.textContent = 'Grade ' + grade + ' · ' + score + '%';
    el.className = 'grade-badge grade-' + grade;
  });
}

function updateProgressBar() {
  const bar = document.getElementById('progressBar');
  if (!bar) return;
  const current = parseFloat(bar.style.width) || 0;
  const total = <?= count($allModules) * 5 ?>;
  const newPct = Math.min(100, current + (100 / total));
  bar.style.width = newPct.toFixed(1) + '%';
}

// ── Paywall prompt ─────────────────────────────────────────────
function showPaywall() {
  if (confirm('This module requires full access. Unlock all 10 modules for ₹<?= $priceSale ?>?')) {
    window.location = '/courses/<?= $courseSlug ?>/enroll';
  }
}

// Close sidebar when clicking a sub-link on mobile
document.querySelectorAll('.sub-item').forEach(el => {
  el.addEventListener('click', () => {
    if (window.innerWidth <= 900) closeSidebar();
  });
});
</script>
