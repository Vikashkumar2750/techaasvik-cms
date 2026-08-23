<!-- Admin: Course Settings -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$s = $settings ?? [];
$activeTab = $_GET['tab'] ?? 'pricing';
$tabs = ['pricing' => '💰 Pricing', 'cert' => '🎓 Certificate', 'email' => '📧 Email SMTP', 'razorpay' => '💳 Razorpay', 'video' => '🎬 Video'];
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">⚙ Course Settings</h1>
    <p class="admin-page-subtitle">Manage pricing, certificate design, SMTP, Razorpay &amp; video settings</p>
  </div>
  <a href="/techaasvik_admin/course" class="admin-btn admin-btn-ghost">← Course Dashboard</a>
</div>

<?php if ($flash = ($flash ?? null)): ?>
<div style="padding:12px 16px;border-radius:8px;margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(52,211,153,0.1)':'rgba(248,113,113,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(52,211,153,0.3)':'rgba(248,113,113,0.3)' ?>;color:<?= $flash['type']==='success'?'#34d399':'#f87171' ?>;">
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Tabs -->
<div style="display:flex;gap:4px;border-bottom:1px solid var(--admin-border);margin-bottom:24px;overflow-x:auto;">
  <?php foreach($tabs as $key => $label): ?>
  <a href="?tab=<?= $key ?>" style="padding:10px 18px;font-size:13px;font-weight:600;border-bottom:2px solid <?= $activeTab===$key?'var(--admin-primary)':'transparent' ?>;color:<?= $activeTab===$key?'var(--admin-primary)':'var(--admin-text-muted)' ?>;text-decoration:none;white-space:nowrap;"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<form method="POST" action="/techaasvik_admin/course/settings/save" enctype="multipart/form-data">
  <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

  <?php if($activeTab === 'pricing'): ?>
  <!-- PRICING TAB -->
  <div class="admin-table-wrapper" style="max-width:600px;padding:24px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">💰 Course Pricing</h3>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Original Price (₹) <span style="color:var(--admin-text-muted);font-weight:400;">— shown as strikethrough</span></label>
      <input type="number" name="course_price_original" class="admin-input" value="<?= htmlspecialchars($s['course_price_original']??'999') ?>" step="1" required>
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Sale Price (₹) <span style="color:var(--admin-text-muted);font-weight:400;">— actual charge</span></label>
      <input type="number" name="course_price_sale" class="admin-input" value="<?= htmlspecialchars($s['course_price_sale']??'199') ?>" step="1" required>
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Free Modules Count <span style="color:var(--admin-text-muted);font-weight:400;">— modules accessible without payment</span></label>
      <input type="number" name="free_modules_count" class="admin-input" value="<?= htmlspecialchars($s['free_modules_count']??'5') ?>" min="0" max="10">
    </div>
    <div style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:8px;padding:14px;margin-bottom:20px;font-size:13px;color:var(--admin-text-muted);">
      💡 Current display: <strong>₹<?= $s['course_price_sale']??199 ?></strong> (was ₹<?= $s['course_price_original']??999 ?>) · <?= round((($s['course_price_original']??999)-($s['course_price_sale']??199))/($s['course_price_original']??999)*100) ?>% off
    </div>
    <input type="hidden" name="video_enabled" value="<?= $s['video_enabled']??'0' ?>">
    <input type="hidden" name="cert_signatory_name" value="<?= htmlspecialchars($s['cert_signatory_name']??'') ?>">
    <input type="hidden" name="smtp_provider" value="<?= htmlspecialchars($s['smtp_provider']??'') ?>">
    <input type="hidden" name="smtp_host" value="<?= htmlspecialchars($s['smtp_host']??'') ?>">
    <input type="hidden" name="smtp_port" value="<?= htmlspecialchars($s['smtp_port']??'') ?>">
    <input type="hidden" name="smtp_encryption" value="<?= htmlspecialchars($s['smtp_encryption']??'') ?>">
    <input type="hidden" name="smtp_user" value="<?= htmlspecialchars($s['smtp_user']??'') ?>">
    <input type="hidden" name="smtp_from_name" value="<?= htmlspecialchars($s['smtp_from_name']??'') ?>">
    <input type="hidden" name="smtp_from_email" value="<?= htmlspecialchars($s['smtp_from_email']??'') ?>">
    <button type="submit" class="admin-btn admin-btn-primary">Save Pricing</button>
  </div>

  <?php elseif($activeTab === 'cert'): ?>
  <!-- CERTIFICATE TAB -->
  <div class="admin-table-wrapper" style="max-width:600px;padding:24px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">🎓 Certificate Settings</h3>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Signatory Name <span style="color:var(--admin-text-muted);font-weight:400;">— appears below signature on cert</span></label>
      <input type="text" name="cert_signatory_name" class="admin-input" value="<?= htmlspecialchars($s['cert_signatory_name']??'TechAasvik Editorial Team') ?>">
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Certificate Logo <span style="color:var(--admin-text-muted);font-weight:400;">— PNG/SVG, max 2MB, appears top-left</span></label>
      <?php if(!empty($s['cert_logo_path'])): ?>
      <div style="margin-bottom:8px;padding:8px;background:var(--admin-bg-elevated);border-radius:6px;">
        <img src="<?= htmlspecialchars($s['cert_logo_path']) ?>" alt="Current logo" style="height:40px;">
        <div style="font-size:11px;color:var(--admin-text-muted);margin-top:4px;">Current logo</div>
      </div>
      <?php endif; ?>
      <input type="file" name="cert_logo" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="admin-input">
    </div>
    <div style="margin-bottom:20px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Signature Image <span style="color:var(--admin-text-muted);font-weight:400;">— PNG, max 2MB, appears bottom-right</span></label>
      <?php if(!empty($s['cert_signature_path'])): ?>
      <div style="margin-bottom:8px;padding:8px;background:var(--admin-bg-elevated);border-radius:6px;">
        <img src="<?= htmlspecialchars($s['cert_signature_path']) ?>" alt="Current sig" style="height:40px;">
        <div style="font-size:11px;color:var(--admin-text-muted);margin-top:4px;">Current signature</div>
      </div>
      <?php endif; ?>
      <input type="file" name="cert_signature" accept="image/png,image/jpeg" class="admin-input">
    </div>
    <a href="/courses/ai-marketing-course" target="_blank" class="admin-btn admin-btn-ghost" style="margin-right:10px;">Preview Certificate →</a>
    <input type="hidden" name="course_price_original" value="<?= htmlspecialchars($s['course_price_original']??'') ?>">
    <input type="hidden" name="course_price_sale" value="<?= htmlspecialchars($s['course_price_sale']??'') ?>">
    <input type="hidden" name="free_modules_count" value="<?= htmlspecialchars($s['free_modules_count']??'') ?>">
    <input type="hidden" name="video_enabled" value="<?= $s['video_enabled']??'0' ?>">
    <input type="hidden" name="smtp_provider" value="<?= htmlspecialchars($s['smtp_provider']??'') ?>">
    <input type="hidden" name="smtp_host" value="<?= htmlspecialchars($s['smtp_host']??'') ?>">
    <input type="hidden" name="smtp_port" value="<?= htmlspecialchars($s['smtp_port']??'') ?>">
    <input type="hidden" name="smtp_encryption" value="<?= htmlspecialchars($s['smtp_encryption']??'') ?>">
    <input type="hidden" name="smtp_user" value="<?= htmlspecialchars($s['smtp_user']??'') ?>">
    <input type="hidden" name="smtp_from_name" value="<?= htmlspecialchars($s['smtp_from_name']??'') ?>">
    <input type="hidden" name="smtp_from_email" value="<?= htmlspecialchars($s['smtp_from_email']??'') ?>">
    <button type="submit" class="admin-btn admin-btn-primary">Save Certificate Settings</button>
  </div>

  <?php elseif($activeTab === 'email'): ?>
  <!-- EMAIL / SMTP TAB -->
  <div class="admin-table-wrapper" style="max-width:600px;padding:24px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">📧 Email / SMTP Settings</h3>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Provider</label>
      <select name="smtp_provider" class="admin-input" id="smtpProvider" onchange="setPreset(this.value)">
        <?php foreach($providers??[] as $key => $p): ?>
        <option value="<?= $key ?>" <?= ($s['smtp_provider']??'')===$key?'selected':'' ?>><?= ucfirst($key) ?> (<?= $p['host'] ?: 'custom' ?>:<?= $p['port'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:12px;margin-bottom:16px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">SMTP Host</label>
        <input type="text" name="smtp_host" id="smtpHost" class="admin-input" value="<?= htmlspecialchars($s['smtp_host']??'smtp.hostinger.com') ?>">
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Port</label>
        <input type="number" name="smtp_port" id="smtpPort" class="admin-input" value="<?= htmlspecialchars($s['smtp_port']??'587') ?>">
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Encryption</label>
        <select name="smtp_encryption" id="smtpEnc" class="admin-input">
          <option value="tls" <?= ($s['smtp_encryption']??'tls')==='tls'?'selected':'' ?>>TLS</option>
          <option value="ssl" <?= ($s['smtp_encryption']??'')==='ssl'?'selected':'' ?>>SSL</option>
          <option value="" <?= ($s['smtp_encryption']??'')==''?'selected':'' ?>>None</option>
        </select>
      </div>
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">SMTP Username / Email</label>
      <input type="email" name="smtp_user" class="admin-input" value="<?= htmlspecialchars($s['smtp_user']??'') ?>" placeholder="noreply@yourdomain.com">
    </div>
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">SMTP Password <span style="color:var(--admin-text-muted);font-weight:400;">— leave blank to keep current</span></label>
      <input type="password" name="smtp_pass" class="admin-input" placeholder="••••••••" autocomplete="new-password">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">From Name</label>
        <input type="text" name="smtp_from_name" class="admin-input" value="<?= htmlspecialchars($s['smtp_from_name']??'TechAasvik') ?>">
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">From Email</label>
        <input type="email" name="smtp_from_email" class="admin-input" value="<?= htmlspecialchars($s['smtp_from_email']??'') ?>" placeholder="noreply@techaasvik.com">
      </div>
    </div>

    <!-- Test Email -->
    <div style="padding:16px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:20px;">
      <h4 style="font-size:13px;font-weight:700;margin-bottom:10px;">🧪 Test SMTP Connection</h4>
      <div style="display:flex;gap:8px;">
        <input type="email" id="testEmailAddr" class="admin-input" placeholder="your@email.com" style="flex:1;">
        <button type="button" onclick="testSmtp()" class="admin-btn admin-btn-ghost" id="testSmtpBtn">Send Test</button>
      </div>
      <div id="smtpTestResult" style="font-size:12px;margin-top:8px;display:none;"></div>
    </div>
    <input type="hidden" name="course_price_original" value="<?= htmlspecialchars($s['course_price_original']??'') ?>">
    <input type="hidden" name="course_price_sale" value="<?= htmlspecialchars($s['course_price_sale']??'') ?>">
    <input type="hidden" name="free_modules_count" value="<?= htmlspecialchars($s['free_modules_count']??'') ?>">
    <input type="hidden" name="video_enabled" value="<?= $s['video_enabled']??'0' ?>">
    <input type="hidden" name="cert_signatory_name" value="<?= htmlspecialchars($s['cert_signatory_name']??'') ?>">
    <button type="submit" class="admin-btn admin-btn-primary">Save Email Settings</button>
  </div>

  <?php elseif($activeTab === 'razorpay'): ?>
  <!-- RAZORPAY TAB -->
  <div class="admin-table-wrapper" style="max-width:600px;padding:24px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:8px;">💳 Razorpay Configuration</h3>
    <div style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.2);border-radius:8px;padding:14px;margin-bottom:20px;font-size:13px;color:var(--admin-text-muted);">
      🔒 <strong>Security:</strong> Razorpay keys are loaded from the <code>.env</code> file on your server — <strong>never stored in the database</strong>. To update keys, edit <code>.env</code> on Hostinger File Manager.
    </div>

    <div style="padding:16px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:16px;">
      <div style="font-size:12px;color:var(--admin-text-muted);margin-bottom:4px;">Current Key ID (from .env)</div>
      <div style="font-family:monospace;font-size:14px;font-weight:600;"><?= htmlspecialchars(env('RAZORPAY_KEY_ID', 'Not configured')) ?></div>
    </div>

    <div style="padding:16px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:16px;">
      <div style="font-size:12px;color:var(--admin-text-muted);margin-bottom:4px;">Secret Key</div>
      <div style="font-family:monospace;font-size:14px;">••••••••••••••••••••</div>
      <div style="font-size:11px;color:var(--admin-text-muted);margin-top:4px;">Hidden for security</div>
    </div>

    <div style="padding:16px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:20px;">
      <div style="font-size:12px;color:var(--admin-text-muted);margin-bottom:4px;">Webhook Secret</div>
      <div style="font-family:monospace;font-size:14px;">••••••••••••••••••••</div>
    </div>

    <div style="background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:8px;padding:14px;font-size:13px;">
      <strong>Webhook URL</strong> (add this in Razorpay Dashboard → Webhooks):<br>
      <code style="font-size:12px;color:var(--admin-primary);">https://techaasvik.com/courses/webhook</code>
      <br><br>
      <strong>Event to enable:</strong> <code>payment.captured</code>
    </div>
  </div>

  <?php elseif($activeTab === 'video'): ?>
  <!-- VIDEO TAB -->
  <div class="admin-table-wrapper" style="max-width:600px;padding:24px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">🎬 Video Settings</h3>
    <div style="padding:16px;background:var(--admin-bg-elevated);border-radius:8px;margin-bottom:20px;">
      <label style="display:flex;align-items:center;gap:12px;cursor:pointer;">
        <input type="checkbox" name="video_enabled" value="1" <?= !empty($s['video_enabled'])&&$s['video_enabled']=='1'?'checked':'' ?> style="width:18px;height:18px;cursor:pointer;">
        <div>
          <div style="font-size:14px;font-weight:700;">Enable Video Player in Course</div>
          <div style="font-size:12px;color:var(--admin-text-muted);margin-top:2px;">When enabled, a video placeholder/player appears above each module's content. When disabled, no video section is shown.</div>
        </div>
      </label>
    </div>
    <div style="background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.2);border-radius:8px;padding:14px;font-size:13px;color:var(--admin-text-muted);margin-bottom:20px;">
      💡 Currently: <strong><?= ($s['video_enabled']??0)==1 ? 'Videos shown' : 'No video section (clean text layout)' ?></strong>. You can toggle this any time without affecting course content.
    </div>
    <input type="hidden" name="course_price_original" value="<?= htmlspecialchars($s['course_price_original']??'') ?>">
    <input type="hidden" name="course_price_sale" value="<?= htmlspecialchars($s['course_price_sale']??'') ?>">
    <input type="hidden" name="free_modules_count" value="<?= htmlspecialchars($s['free_modules_count']??'') ?>">
    <input type="hidden" name="cert_signatory_name" value="<?= htmlspecialchars($s['cert_signatory_name']??'') ?>">
    <input type="hidden" name="smtp_provider" value="<?= htmlspecialchars($s['smtp_provider']??'') ?>">
    <input type="hidden" name="smtp_host" value="<?= htmlspecialchars($s['smtp_host']??'') ?>">
    <input type="hidden" name="smtp_port" value="<?= htmlspecialchars($s['smtp_port']??'') ?>">
    <input type="hidden" name="smtp_encryption" value="<?= htmlspecialchars($s['smtp_encryption']??'') ?>">
    <input type="hidden" name="smtp_user" value="<?= htmlspecialchars($s['smtp_user']??'') ?>">
    <input type="hidden" name="smtp_from_name" value="<?= htmlspecialchars($s['smtp_from_name']??'') ?>">
    <input type="hidden" name="smtp_from_email" value="<?= htmlspecialchars($s['smtp_from_email']??'') ?>">
    <button type="submit" class="admin-btn admin-btn-primary">Save Video Settings</button>
  </div>
  <?php endif; ?>
</form>

<script>
const presets = <?= json_encode($providers ?? []) ?>;
function setPreset(p){
  if(!presets[p]) return;
  if(presets[p].host)  document.getElementById('smtpHost').value=presets[p].host;
  if(presets[p].port)  document.getElementById('smtpPort').value=presets[p].port;
  if(presets[p].encryption) {
    const enc=document.getElementById('smtpEnc');
    [...enc.options].forEach(o=>o.selected=o.value===presets[p].encryption);
  }
}

async function testSmtp(){
  const to=document.getElementById('testEmailAddr').value;
  const btn=document.getElementById('testSmtpBtn');
  const result=document.getElementById('smtpTestResult');
  if(!to){ result.textContent='Enter an email address.'; result.style.display='block'; result.style.color='#f87171'; return; }
  btn.disabled=true; btn.textContent='Sending...';
  result.style.display='none';
  const fd=new FormData();
  fd.append('_csrf_token','<?= htmlspecialchars($csrfToken) ?>');
  fd.append('test_email',to);
  try{
    const res=await fetch('/techaasvik_admin/course/settings/test-smtp',{method:'POST',body:fd});
    const json=await res.json();
    result.textContent=json.message;
    result.style.color=json.success?'#34d399':'#f87171';
    result.style.display='block';
  } catch(e){ result.textContent='Error.'; result.style.color='#f87171'; result.style.display='block'; }
  btn.disabled=false; btn.textContent='Send Test';
}
</script>
