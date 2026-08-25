<!-- Course Landing Page — AI Marketing & ChatGPT SEO -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$freeCount = $freeCount ?? 5;
$priceOrig = $priceOrig ?? 999;
$priceSale = $priceSale ?? 199;
$showEnroll = isset($_GET['enroll']);
?>

<!-- Hero -->
<div style="background:linear-gradient(160deg,rgba(99,102,241,0.08),rgba(139,92,246,0.04),transparent);border-bottom:1px solid var(--border-subtle);padding:var(--space-16) 0 var(--space-12);">
  <div class="container">
    <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Courses','url'=>'/courses'],['name'=>'AI Marketing & ChatGPT SEO']]]) ?>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:var(--space-12);margin-top:var(--space-6);align-items:start;">
      <!-- Left: Course Info -->
      <div>
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:var(--space-4);flex-wrap:wrap;">
          <span style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:100px;letter-spacing:0.06em;text-transform:uppercase;">⚡ Advanced</span>
          <span style="font-size:12px;color:var(--text-muted);">10 Modules · 5 Free + 5 Paid</span>
          <div style="display:flex;align-items:center;gap:4px;">
            <span style="color:#fbbf24;">★★★★★</span>
            <span style="font-size:13px;color:var(--text-muted);">4.9 (247 students)</span>
          </div>
        </div>

        <h1 style="font-size:var(--text-4xl);line-height:1.15;margin-bottom:var(--space-5);max-width:700px;">
          AI Marketing &amp; ChatGPT SEO:<br>The Complete Practical System
        </h1>

        <p style="font-size:var(--text-lg);color:var(--text-secondary);line-height:var(--leading-relaxed);max-width:620px;margin-bottom:var(--space-6);">
          Not just theory. Build a real AI Marketing Operating System — from customer research and AI-powered SEO to paid ads, automation, analytics, and a certified capstone project.
        </p>

        <!-- Key Stats Bar -->
        <div style="display:flex;gap:var(--space-6);flex-wrap:wrap;margin-bottom:var(--space-8);">
          <div style="display:flex;align-items:center;gap:8px;font-size:var(--text-sm);color:var(--text-secondary);">
            <span style="font-size:18px;">🎓</span><span>Certificate on completion</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:var(--text-sm);color:var(--text-secondary);">
            <span style="font-size:18px;">⏱</span><span>~9 hours total</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:var(--text-sm);color:var(--text-secondary);">
            <span style="font-size:18px;">📱</span><span>Learn at your pace</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:var(--text-sm);color:var(--text-secondary);">
            <span style="font-size:18px;">✅</span><span>Quiz after each module</span>
          </div>
        </div>

        <!-- What You'll Build -->
        <div style="background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5) var(--space-6);max-width:620px;">
          <h3 style="font-size:var(--text-sm);font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:var(--space-3);">What you'll build</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-2);">
            <?php foreach([
              'AI Market Intelligence Report', 'Topical Map + Content Plan',
              '30-Day Multi-Channel Content System', 'AI Search Visibility Audit',
              'Performance Max Campaign Strategy', 'Lead-to-Conversion Automation',
              'Marketing Analytics Dashboard', 'Complete AI Marketing OS (Capstone)',
            ] as $outcome): ?>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:var(--text-sm);color:var(--text-secondary);">
              <span style="color:var(--accent-400);flex-shrink:0;margin-top:2px;">✓</span>
              <span><?= htmlspecialchars($outcome) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Right: Enrollment Card -->
      <div style="position:sticky;top:90px;">
        <div class="card" style="padding:var(--space-6);border:1px solid rgba(99,102,241,0.3);box-shadow:0 20px 60px rgba(99,102,241,0.15);">
          <!-- Pricing -->
          <div style="text-align:center;margin-bottom:var(--space-5);">
            <div style="display:flex;align-items:baseline;justify-content:center;gap:10px;margin-bottom:6px;">
              <span style="font-size:40px;font-weight:900;color:var(--text-primary);">₹<?= number_format($priceSale) ?></span>
              <span style="font-size:20px;color:var(--text-muted);text-decoration:line-through;">₹<?= number_format($priceOrig) ?></span>
            </div>
            <span style="display:inline-block;background:rgba(52,211,153,0.15);color:#34d399;font-size:12px;font-weight:700;padding:4px 14px;border-radius:100px;">Save <?= round((($priceOrig-$priceSale)/$priceOrig)*100) ?>% — Limited Time</span>
          </div>

          <?php if ($enrollment && $enrollment['payment_status'] === 'paid'): ?>
          <!-- Already enrolled + paid -->
          <a href="/courses/ai-marketing-course/learn/1" class="btn btn-primary" style="width:100%;justify-content:center;text-align:center;font-size:16px;padding:14px;" id="btn-continue-course">
            ▶ Continue Course
          </a>
          <?php elseif ($enrollment): ?>
          <!-- Enrolled free -->
          <a href="/courses/ai-marketing-course/learn/1" class="btn btn-primary" style="width:100%;justify-content:center;text-align:center;font-size:15px;padding:13px;margin-bottom:10px;" id="btn-continue-free">
            ▶ Continue Free Modules
          </a>
          <a href="/courses/ai-marketing-course/enroll" class="btn btn-gradient" style="width:100%;justify-content:center;text-align:center;font-size:15px;padding:13px;" id="btn-upgrade">
            🔓 Unlock Full Course ₹<?= number_format($priceSale) ?>
          </a>
          <?php else: ?>
          <!-- Not enrolled -->
          <button onclick="document.getElementById('enrollModal').classList.add('active')" class="btn btn-primary" style="width:100%;justify-content:center;font-size:16px;padding:14px;margin-bottom:10px;" id="btn-start-free">
            🆓 Start Free — <?= $freeCount ?> Modules
          </button>
          <a href="/courses/ai-marketing-course/enroll" class="btn" style="width:100%;justify-content:center;text-align:center;font-size:14px;padding:12px;background:var(--bg-elevated);color:var(--text-secondary);border:1px solid var(--border-subtle);" id="btn-buy-full">
            🔓 Buy Full Course ₹<?= number_format($priceSale) ?>
          </a>
          <?php endif; ?>

          <div style="margin-top:var(--space-4);display:flex;flex-direction:column;gap:var(--space-2);">
            <?php foreach(['First 5 modules free forever', 'Certificate emailed on completion', 'Quiz after each module', 'Lifetime access after purchase'] as $ft): ?>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
              <span style="color:var(--accent-400);">✓</span><?= $ft ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Module Syllabus -->
<div class="container" style="padding-top:var(--space-14);padding-bottom:var(--space-6);">
  <h2 style="font-size:var(--text-2xl);margin-bottom:var(--space-2);">📚 Course Curriculum</h2>
  <p style="color:var(--text-muted);margin-bottom:var(--space-8);">10 modules · Problem → Concept → AI Workflow → Demo → Template → Assignment → QA → Business Outcome</p>

  <div style="display:flex;flex-direction:column;gap:var(--space-3);" id="syllabus">
    <?php foreach($modules as $i => $mod): ?>
    <div style="border:1px solid var(--border-subtle);border-radius:var(--radius-lg);overflow:hidden;<?= $mod['free'] ? '' : 'opacity:0.85;' ?>">
      <div style="display:flex;align-items:center;gap:var(--space-4);padding:var(--space-4) var(--space-5);background:var(--bg-surface);cursor:pointer;" onclick="toggleModule(<?= $i ?>)" id="mod-header-<?= $i ?>">
        <div style="width:40px;height:40px;border-radius:10px;background:<?= $mod['free'] ? 'rgba(99,102,241,0.15)' : 'rgba(255,255,255,0.05)' ?>;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;"><?= $mod['emoji'] ?></div>
        <div style="flex:1;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;flex-wrap:wrap;">
            <span style="font-size:11px;font-weight:700;color:var(--text-muted);">Module <?= $mod['num'] ?></span>
            <?php if($mod['free']): ?>
            <span style="background:rgba(52,211,153,0.15);color:#34d399;font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;">FREE</span>
            <?php else: ?>
            <span style="background:rgba(99,102,241,0.12);color:var(--brand-400);font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;">🔒 PAID</span>
            <?php endif; ?>
            <span style="font-size:11px;color:var(--text-muted);">· <?= $mod['duration'] ?></span>
          </div>
          <div style="font-size:var(--text-base);font-weight:var(--fw-semibold);"><?= htmlspecialchars($mod['title']) ?></div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($mod['tagline']) ?></div>
        </div>
        <span style="font-size:18px;color:var(--text-muted);transition:transform 0.2s;" id="chevron-<?= $i ?>">›</span>
      </div>
      <div id="mod-lessons-<?= $i ?>" style="display:none;padding:0 var(--space-5) var(--space-4);background:var(--bg-elevated);">
        <?php foreach($mod['lessons'] as $j => $lesson): ?>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-subtle);<?= $j === count($mod['lessons'])-1 ? 'border-bottom:none;' : '' ?>">
          <span style="width:22px;height:22px;border-radius:50%;background:var(--bg-surface);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--text-muted);flex-shrink:0;"><?= $j+1 ?></span>
          <span style="font-size:var(--text-sm);color:var(--text-secondary);flex:1;"><?= htmlspecialchars($lesson['title']) ?></span>
          <span style="font-size:11px;color:var(--text-muted);"><?= $lesson['duration'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Registration Modal -->
<div id="enrollModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9999;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px);" onclick="if(event.target===this)closeModal()">
  <div class="card" style="max-width:460px;width:100%;padding:40px 36px;position:relative;animation:slideUp 0.25s ease;border-radius:20px;">
    <button onclick="closeModal()" style="position:absolute;top:16px;right:18px;background:none;border:none;font-size:22px;color:var(--text-muted);cursor:pointer;line-height:1;padding:4px;">✕</button>

    <!-- Form state -->
    <div id="enrollFormWrap">
      <div style="text-align:center;margin-bottom:28px;">
        <div style="font-size:40px;margin-bottom:10px;">🎓</div>
        <h2 style="font-size:var(--text-xl);margin-bottom:6px;">Start Learning for Free</h2>
        <p style="font-size:var(--text-sm);color:var(--text-muted);">First <?= $freeCount ?> modules free. Certificate on full completion.</p>
      </div>
      <form id="enrollForm" onsubmit="submitEnroll(event)">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:7px;">Your Name *</label>
          <input type="text" name="name" id="enroll-name" class="form-input" placeholder="Rahul Sharma" required style="width:100%;padding:12px 14px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:7px;">Email Address *</label>
          <input type="email" name="email" id="enroll-email" class="form-input" placeholder="you@email.com" required style="width:100%;padding:12px 14px;">
        </div>
        <div style="margin-bottom:24px;">
          <label style="display:block;font-size:13px;font-weight:600;margin-bottom:7px;">Phone Number *</label>
          <input type="tel" name="phone" id="enroll-phone" class="form-input" placeholder="+91 9876543210" required style="width:100%;padding:12px 14px;">
        </div>
        <div id="enrollError" style="display:none;color:#f87171;font-size:13px;margin-bottom:14px;background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);border-radius:8px;padding:10px 14px;"></div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:14px;" id="enrollSubmit">
          Start Free Course →
        </button>
      </form>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:18px;">
        <p style="font-size:11px;color:var(--text-muted);">No spam, ever. Unsubscribe anytime.</p>
        <a href="/courses/login" style="font-size:12px;color:var(--brand-400);font-weight:600;">Login →</a>
      </div>
    </div>

    <!-- Pending email verification state -->
    <div id="enrollPendingWrap" style="display:none;text-align:center;padding:8px 0;">
      <div style="font-size:52px;margin-bottom:16px;">📬</div>
      <h2 style="font-size:20px;font-weight:800;margin-bottom:10px;">Check your email!</h2>
      <p id="enrollPendingMsg" style="font-size:14px;color:var(--text-muted);margin-bottom:24px;line-height:1.6;"></p>
      <div style="background:var(--bg-elevated);border-radius:12px;padding:18px 20px;text-align:left;margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:13px;color:var(--text-secondary);"><span style="background:var(--brand-500);color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;flex-shrink:0;">1</span> Open your inbox (check Spam too)</div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:13px;color:var(--text-secondary);"><span style="background:var(--brand-500);color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;flex-shrink:0;">2</span> Click <strong>"Verify &amp; Set Password"</strong></div>
        <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:var(--text-secondary);"><span style="background:var(--brand-500);color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;flex-shrink:0;">3</span> Set your password &amp; start learning!</div>
      </div>
      <p style="font-size:12px;color:var(--text-muted);">Link valid for 24 hours</p>
    </div>
  </div>
</div>

<style>
#enrollModal.active { display:flex !important; }
@keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@media(max-width:768px){
  .course-hero-grid { grid-template-columns:1fr !important; }
  .course-hero-grid > div:last-child { position:static !important; }
}
</style>

<script>
function toggleModule(i){
  const lessons=document.getElementById('mod-lessons-'+i);
  const chevron=document.getElementById('chevron-'+i);
  const open=lessons.style.display==='block';
  lessons.style.display=open?'none':'block';
  chevron.style.transform=open?'rotate(0deg)':'rotate(90deg)';
}

function closeModal() {
  document.getElementById('enrollModal').classList.remove('active');
}

async function submitEnroll(e){
  e.preventDefault();
  const btn=document.getElementById('enrollSubmit');
  const err=document.getElementById('enrollError');
  btn.disabled=true; btn.textContent='Please wait...';
  err.style.display='none';

  const form=e.target;
  const data=new FormData(form);

  try{
    const res=await fetch('/courses/register',{method:'POST',body:data});
    const json=await res.json();
    if(json.success){
      if(json.pending){
        // Show email verification pending state
        document.getElementById('enrollFormWrap').style.display='none';
        document.getElementById('enrollPendingWrap').style.display='block';
        document.getElementById('enrollPendingMsg').textContent = json.message || 'Please check your inbox.';
      } else if(json.redirect){
        window.location.href=json.redirect;
      }
    } else {
      err.textContent=json.error||'Something went wrong. Try again.';
      err.style.display='block';
      btn.disabled=false; btn.textContent='Start Free Course →';
    }
  } catch(ex){
    err.textContent='Network error. Please try again.';
    err.style.display='block';
    btn.disabled=false; btn.textContent='Start Free Course →';
  }
}

// Auto-open modal if ?enroll=1
<?php if($showEnroll): ?>
document.getElementById('enrollModal').classList.add('active');
<?php endif; ?>
</script>
