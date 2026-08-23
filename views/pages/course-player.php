<!-- Course Player — Left sidebar + Right content -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken  = Auth::csrfToken();
$moduleNum  = $moduleNum  ?? 1;
$totalModules = $totalModules ?? 10;
$freeCount  = $freeCount  ?? 5;
$isPaid     = $isPaid     ?? false;
$module     = $module     ?? [];
$allModules = $allModules ?? [];
$progress   = $progress   ?? [];
$videoEnabled = $videoEnabled ?? false;
$priceOrig  = $priceOrig  ?? 999;
$priceSale  = $priceSale  ?? 199;
$cert       = $cert       ?? null;
$courseSlug = 'ai-marketing-course';
?>

<!-- Override layout padding for full-height player -->
<style>
.player-wrap { display:grid; grid-template-columns:280px 1fr; min-height:calc(100vh - 64px); }
.player-sidebar {
  background:var(--bg-surface);
  border-right:1px solid var(--border-subtle);
  position:sticky; top:64px; height:calc(100vh - 64px);
  overflow-y:auto; flex-shrink:0;
}
.player-sidebar::-webkit-scrollbar { width:4px; }
.player-sidebar::-webkit-scrollbar-thumb { background:var(--border-subtle); border-radius:2px; }
.player-main { overflow-y:auto; height:calc(100vh - 64px); }
.mod-item { display:flex; align-items:center; gap:10px; padding:10px 16px; border-left:3px solid transparent; cursor:pointer; transition:all 0.15s; text-decoration:none; }
.mod-item:hover { background:var(--bg-elevated); }
.mod-item.active { border-left-color:var(--brand-primary); background:rgba(99,102,241,0.06); }
.mod-item.locked { opacity:0.5; cursor:default; }
.quiz-option { display:flex; align-items:flex-start; gap:10px; padding:14px; border:1px solid var(--border-subtle); border-radius:var(--radius-md); cursor:pointer; transition:all 0.2s; margin-bottom:8px; }
.quiz-option:hover { border-color:var(--brand-primary); background:rgba(99,102,241,0.04); }
.quiz-option.selected { border-color:var(--brand-primary); background:rgba(99,102,241,0.08); }
.quiz-option.correct { border-color:#34d399; background:rgba(52,211,153,0.08); }
.quiz-option.wrong { border-color:#f87171; background:rgba(248,113,113,0.08); }
@media(max-width:900px){
  .player-wrap { grid-template-columns:1fr; }
  .player-sidebar { position:fixed; left:-100%; width:280px; top:64px; height:calc(100vh - 64px); z-index:900; transition:left 0.3s; }
  .player-sidebar.mob-open { left:0; }
  .player-main { height:auto; overflow-y:visible; }
}
</style>

<div class="player-wrap" id="playerWrap">

  <!-- ── LEFT SIDEBAR ── -->
  <aside class="player-sidebar" id="playerSidebar">
    <!-- Course header -->
    <div style="padding:var(--space-4);border-bottom:1px solid var(--border-subtle);">
      <a href="/courses/<?= $courseSlug ?>" style="font-size:11px;color:var(--text-muted);text-decoration:none;display:block;margin-bottom:6px;">← Back to Course</a>
      <div style="font-size:13px;font-weight:700;color:var(--text-primary);line-height:1.3;">AI Marketing &amp; ChatGPT SEO</div>
      <?php
      $done = count(array_filter($progress, fn($p) => $p['completed']));
      $pct  = $totalModules > 0 ? round($done / $totalModules * 100) : 0;
      ?>
      <div style="margin-top:10px;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-bottom:4px;">
          <span>Progress</span><span><?= $done ?>/<?= $totalModules ?> modules</span>
        </div>
        <div style="height:4px;background:var(--bg-elevated);border-radius:2px;overflow:hidden;">
          <div style="width:<?= $pct ?>%;height:100%;background:linear-gradient(90deg,#6366f1,#34d399);transition:width 0.5s;"></div>
        </div>
      </div>
    </div>

    <!-- Module List -->
    <nav style="padding:var(--space-2) 0;">
      <?php foreach($allModules as $mod): ?>
      <?php
        $mNum    = $mod['num'];
        $isDone  = !empty($progress[$mNum]['completed']);
        $isLocked = $mNum > $freeCount && !$isPaid;
        $isCurrent = $mNum === $moduleNum;
        $href    = $isLocked ? '#' : "/courses/{$courseSlug}/learn/{$mNum}";
        $classes = 'mod-item' . ($isCurrent ? ' active' : '') . ($isLocked ? ' locked' : '');
      ?>
      <a href="<?= $href ?>" class="<?= $classes ?>"
         <?= $isLocked ? 'onclick="showPaywall();return false;"' : '' ?>
         id="mod-nav-<?= $mNum ?>">
        <div style="width:26px;height:26px;border-radius:50%;border:2px solid <?= $isDone ? '#34d399' : ($isCurrent ? 'var(--brand-primary)' : 'var(--border-subtle)') ?>;background:<?= $isDone ? 'rgba(52,211,153,0.1)' : ($isCurrent ? 'rgba(99,102,241,0.1)' : 'transparent') ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:700;color:<?= $isDone ? '#34d399' : ($isCurrent ? 'var(--brand-primary)' : 'var(--text-muted)') ?>;">
          <?= $isDone ? '✓' : ($isLocked ? '🔒' : $mNum) ?>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:12px;font-weight:<?= $isCurrent ? '700' : '500' ?>;color:<?= $isCurrent ? 'var(--text-primary)' : 'var(--text-secondary)' ?>;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($mod['title']) ?></div>
          <div style="font-size:10px;color:var(--text-muted);margin-top:1px;"><?= $mod['duration'] ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </nav>

    <?php if (!$isPaid): ?>
    <div style="margin:var(--space-3);padding:var(--space-4);background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:var(--radius-lg);">
      <div style="font-size:12px;font-weight:700;margin-bottom:6px;">🔓 Unlock All Modules</div>
      <p style="font-size:11px;color:var(--text-muted);line-height:1.5;margin-bottom:10px;">Get modules 6–10 + certificate for just ₹<?= number_format($priceSale) ?></p>
      <a href="/courses/<?= $courseSlug ?>/enroll" class="btn btn-primary" style="width:100%;justify-content:center;text-align:center;font-size:12px;padding:8px;" id="btn-sidebar-unlock">Unlock ₹<?= number_format($priceSale) ?></a>
    </div>
    <?php endif; ?>
  </aside>

  <!-- ── RIGHT CONTENT ── -->
  <main class="player-main" id="playerMain">
    <div style="max-width:800px;margin:0 auto;padding:var(--space-8) var(--space-6);">

      <!-- Mobile: Hamburger -->
      <button onclick="document.getElementById('playerSidebar').classList.toggle('mob-open')" style="display:none;background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:8px 12px;font-size:13px;color:var(--text-secondary);margin-bottom:var(--space-5);cursor:pointer;" class="mob-menu-btn" id="playerMobMenu">
        ☰ Modules
      </button>

      <!-- Module Header -->
      <div style="margin-bottom:var(--space-6);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:var(--space-3);flex-wrap:wrap;">
          <span style="background:var(--bg-surface);border:1px solid var(--border-subtle);font-size:11px;color:var(--text-muted);padding:3px 10px;border-radius:100px;">Module <?= $moduleNum ?> of <?= $totalModules ?></span>
          <?php if($module['free']): ?>
          <span style="background:rgba(52,211,153,0.15);color:#34d399;font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;">FREE</span>
          <?php else: ?>
          <span style="background:rgba(99,102,241,0.1);color:var(--brand-400);font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;">PREMIUM</span>
          <?php endif; ?>
          <span style="font-size:11px;color:var(--text-muted);">⏱ <?= $module['duration'] ?></span>
        </div>
        <h1 style="font-size:var(--text-2xl);line-height:1.2;margin-bottom:var(--space-2);"><?= htmlspecialchars($module['title']) ?></h1>
        <p style="font-size:var(--text-base);color:var(--text-muted);font-style:italic;"><?= htmlspecialchars($module['tagline']) ?></p>
      </div>

      <!-- Video Placeholder (if enabled) -->
      <?php if($videoEnabled): ?>
      <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;margin-bottom:var(--space-6);">
        <div style="text-align:center;">
          <div style="font-size:48px;margin-bottom:8px;">▶</div>
          <p style="font-size:14px;color:var(--text-muted);">Video coming soon</p>
        </div>
      </div>
      <?php endif; ?>

      <!-- Module Content -->
      <div class="prose" id="moduleContent">
        <?= $module['content'] ?? '<p>Content loading...</p>' ?>
      </div>

      <!-- Lesson Checklist -->
      <div style="margin-top:var(--space-8);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5);">
        <h3 style="font-size:var(--text-sm);font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:var(--space-4);">📋 Lessons in This Module</h3>
        <?php foreach($module['lessons'] as $j => $lesson): ?>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-subtle);<?= $j===count($module['lessons'])-1?'border-bottom:none;':'' ?>">
          <div style="width:22px;height:22px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--text-muted);flex-shrink:0;"><?= $j+1 ?></div>
          <span style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;"><?= htmlspecialchars($lesson['title']) ?></span>
          <span style="font-size:11px;color:var(--text-muted);"><?= $lesson['duration'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Quiz Section -->
      <?php $modProgress = $progress[$moduleNum] ?? []; ?>
      <div id="quizSection" style="margin-top:var(--space-8);">
        <?php if (!empty($modProgress['quiz_passed'])): ?>
        <!-- Already passed -->
        <div style="background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.25);border-radius:var(--radius-xl);padding:var(--space-5);text-align:center;">
          <div style="font-size:32px;margin-bottom:8px;">✅</div>
          <div style="font-size:var(--text-base);font-weight:700;color:#34d399;">Quiz Passed — <?= $modProgress['quiz_score'] ?>%</div>
          <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">You've completed this module.</div>
        </div>
        <?php else: ?>
        <!-- Quiz -->
        <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-6);" id="quizBox">
          <h3 style="font-size:var(--text-lg);font-weight:700;margin-bottom:var(--space-2);">🧠 Module Quiz</h3>
          <p style="font-size:13px;color:var(--text-muted);margin-bottom:var(--space-5);">Answer all questions. Score 60% or above to complete this module.</p>
          <form id="quizForm" onsubmit="submitQuiz(event)">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="module" value="<?= $moduleNum ?>">
            <?php foreach($module['quiz'] as $qi => $q): ?>
            <div style="margin-bottom:var(--space-6);" id="quiz-q-<?= $qi ?>">
              <p style="font-size:var(--text-base);font-weight:600;margin-bottom:var(--space-3);line-height:1.5;"><?= ($qi+1) ?>. <?= htmlspecialchars($q['q']) ?></p>
              <?php foreach($q['options'] as $oi => $opt): ?>
              <div class="quiz-option" onclick="selectOption(<?= $qi ?>, <?= $oi ?>, this)" id="opt-<?= $qi ?>-<?= $oi ?>">
                <input type="radio" name="answers[<?= $qi ?>]" value="<?= $oi ?>" style="flex-shrink:0;margin-top:2px;" required>
                <span style="font-size:var(--text-sm);color:var(--text-secondary);"><?= htmlspecialchars($opt) ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <div id="quizError" style="display:none;color:#f87171;font-size:13px;margin-bottom:var(--space-3);"></div>
            <button type="submit" class="btn btn-primary" style="padding:12px 28px;" id="quizSubmit">Submit Quiz →</button>
          </form>
          <!-- Quiz Result (hidden initially) -->
          <div id="quizResult" style="display:none;margin-top:var(--space-5);padding:var(--space-5);border-radius:var(--radius-lg);text-align:center;"></div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Certificate Banner (if all done) -->
      <?php if ($cert): ?>
      <div style="margin-top:var(--space-8);background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(139,92,246,0.06));border:1px solid rgba(99,102,241,0.3);border-radius:var(--radius-xl);padding:var(--space-6);text-align:center;">
        <div style="font-size:48px;margin-bottom:12px;">🎓</div>
        <h3 style="font-size:var(--text-xl);margin-bottom:8px;">Course Complete!</h3>
        <p style="color:var(--text-muted);margin-bottom:var(--space-4);">Your certificate has been emailed. You can also view/download it here.</p>
        <a href="/certificate/<?= $cert['cert_uid'] ?>" target="_blank" class="btn btn-primary" style="padding:12px 28px;" id="btn-view-cert">🎓 View &amp; Download Certificate</a>
      </div>
      <?php endif; ?>

      <!-- Navigation -->
      <div style="display:flex;justify-content:space-between;margin-top:var(--space-10);padding-top:var(--space-6);border-top:1px solid var(--border-subtle);">
        <?php if($moduleNum > 1): ?>
        <a href="/courses/<?= $courseSlug ?>/learn/<?= $moduleNum-1 ?>" class="btn btn-ghost" id="btn-prev-module">← Previous Module</a>
        <?php else: ?>
        <div></div>
        <?php endif; ?>

        <?php if($moduleNum < $totalModules): ?>
          <?php $nextNum = $moduleNum + 1; $nextLocked = $nextNum > $freeCount && !$isPaid; ?>
          <?php if($nextLocked): ?>
          <button onclick="showPaywall()" class="btn btn-primary" id="btn-next-paywall">Next Module 🔒</button>
          <?php else: ?>
          <a href="/courses/<?= $courseSlug ?>/learn/<?= $nextNum ?>" class="btn btn-primary" id="btn-next-module">Next Module →</a>
          <?php endif; ?>
        <?php else: ?>
        <span class="btn btn-primary" style="opacity:0.5;">Course Complete 🎓</span>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>

<!-- Paywall Modal -->
<div id="paywallModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;align-items:center;justify-content:center;padding:var(--space-4);" onclick="if(event.target===this)this.style.display='none'">
  <div class="card" style="max-width:440px;width:100%;padding:var(--space-7);text-align:center;">
    <div style="font-size:48px;margin-bottom:12px;">🔒</div>
    <h2 style="font-size:var(--text-xl);margin-bottom:8px;">Premium Module</h2>
    <p style="color:var(--text-muted);margin-bottom:var(--space-4);">Modules 6–10 require the full course. Unlock all 10 modules + certificate.</p>
    <div style="margin-bottom:var(--space-5);">
      <span style="font-size:32px;font-weight:900;">₹<?= number_format($priceSale) ?></span>
      <span style="font-size:18px;color:var(--text-muted);text-decoration:line-through;margin-left:8px;">₹<?= number_format($priceOrig) ?></span>
    </div>
    <a href="/courses/<?= $courseSlug ?>/enroll" class="btn btn-primary" style="width:100%;justify-content:center;font-size:16px;padding:14px;margin-bottom:10px;" id="btn-paywall-unlock">Unlock Full Course ₹<?= number_format($priceSale) ?></a>
    <button onclick="document.getElementById('paywallModal').style.display='none'" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:14px;">Maybe later</button>
  </div>
</div>

<script>
function showPaywall(){ document.getElementById('paywallModal').style.display='flex'; }

function selectOption(qi, oi, el){
  // Deselect siblings
  document.querySelectorAll('[id^="opt-'+qi+'-"]').forEach(o=>o.classList.remove('selected'));
  el.classList.add('selected');
  el.querySelector('input[type=radio]').checked=true;
}

async function submitQuiz(e){
  e.preventDefault();
  const btn=document.getElementById('quizSubmit');
  const err=document.getElementById('quizError');
  btn.disabled=true; btn.textContent='Grading...';
  err.style.display='none';

  const form=e.target;
  const data=new FormData(form);

  try{
    const res=await fetch('/courses/quiz',{method:'POST',body:data});
    const json=await res.json();
    if(!json.success){ err.textContent=json.error||'Error submitting quiz.'; err.style.display='block'; btn.disabled=false; btn.textContent='Submit Quiz →'; return; }

    // Show result
    const result=document.getElementById('quizResult');
    const passed=json.passed;
    result.style.display='block';
    result.style.background=passed?'rgba(52,211,153,0.08)':'rgba(248,113,113,0.08)';
    result.style.border='1px solid '+(passed?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)');
    result.innerHTML=`
      <div style="font-size:36px;margin-bottom:8px;">${passed?'🎉':'😊'}</div>
      <div style="font-size:20px;font-weight:800;color:${passed?'#34d399':'#f87171'};margin-bottom:8px;">${json.score}% — ${passed?'Passed!':'Not quite yet'}</div>
      <div style="font-size:14px;color:var(--text-muted);margin-bottom:16px;">${json.correct} of ${json.total} correct</div>
      ${passed
        ? `${json.cert_uid ? '<div style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3);border-radius:10px;padding:14px;margin-bottom:14px;"><div style="font-size:14px;font-weight:600;margin-bottom:6px;">🎓 Course Complete! Certificate Ready!</div><a href="/certificate/'+json.cert_uid+'" target="_blank" class="btn btn-primary" style="font-size:13px;padding:8px 20px;">View Certificate</a></div>' : ''}
          <a href="/courses/ai-marketing-course/learn/${Math.min(<?= $moduleNum ?>+1, <?= $totalModules ?>)}" class="btn btn-primary" style="padding:12px 24px;">Next Module →</a>`
        : `<button onclick="location.reload()" class="btn btn-ghost" style="padding:10px 20px;">Try Again</button>`
      }
    `;
    document.getElementById('quizBox').querySelector('form').style.display='none';
    result.scrollIntoView({behavior:'smooth', block:'center'});

    // Update sidebar progress dot
    if(passed){
      const navDot=document.querySelector('#mod-nav-<?= $moduleNum ?> div');
      if(navDot){ navDot.textContent='✓'; navDot.style.borderColor='#34d399'; navDot.style.color='#34d399'; }
    }
  } catch(ex){
    err.textContent='Network error. Please try again.';
    err.style.display='block';
  }
  btn.disabled=false; btn.textContent='Submit Quiz →';
}

// Mobile sidebar
document.getElementById('playerMobMenu') && (document.getElementById('playerMobMenu').style.display='flex');
document.addEventListener('click', e=>{
  const sb=document.getElementById('playerSidebar');
  if(sb && sb.classList.contains('mob-open') && !sb.contains(e.target) && !document.getElementById('playerMobMenu')?.contains(e.target)){
    sb.classList.remove('mob-open');
  }
});
</script>
