<!-- Course Enroll — Payment Page -->
<?php
use Core\Auth;
Auth::startSession();
$csrfToken = Auth::csrfToken();
$priceOrig = $priceOrig ?? 999;
$priceSale = $priceSale ?? 199;
$razorpayKeyId = $razorpayKeyId ?? '';
$enrollment = $enrollment ?? [];
$courseSlug = 'ai-marketing-course';
?>

<div class="container" style="padding-top:var(--space-14);padding-bottom:var(--space-16);max-width:900px;">

  <?php \Core\View::partial('breadcrumb', ['crumbs' => [['name'=>'Home','url'=>'/'],['name'=>'Courses','url'=>'/courses'],['name'=>'AI Marketing & ChatGPT SEO','url'=>'/courses/'.$courseSlug],['name'=>'Enroll']]]) ?>

  <div style="display:grid;grid-template-columns:1fr 360px;gap:var(--space-10);margin-top:var(--space-8);align-items:start;">

    <!-- Left: What you get -->
    <div>
      <h1 style="font-size:var(--text-3xl);margin-bottom:var(--space-4);">Unlock the Full AI Marketing Course</h1>
      <p style="color:var(--text-secondary);font-size:var(--text-lg);margin-bottom:var(--space-6);line-height:var(--leading-relaxed);">
        One payment. Lifetime access. Certificate on completion.
      </p>

      <!-- What's included -->
      <div style="display:flex;flex-direction:column;gap:var(--space-3);margin-bottom:var(--space-8);">
        <?php foreach([
          ['✅', 'All 10 modules unlocked immediately', 'Including 5 premium modules: GEO, Paid Ads, Automation, Analytics, Capstone'],
          ['🎓', 'Verified Certificate of Completion', 'Emailed to you + public verify URL you can share on LinkedIn'],
          ['🧠', 'Quiz after every module', 'Reinforce learning. Minimum 60% to progress.'],
          ['⚡', 'Practical projects in every module', 'Not just theory — real deliverables for real businesses'],
          ['♾', 'Lifetime access', 'Learn at your own pace. Access never expires.'],
        ] as [$emoji, $title, $desc]): ?>
        <div style="display:flex;gap:var(--space-4);align-items:flex-start;padding:var(--space-4);background:var(--bg-surface);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);">
          <span style="font-size:24px;flex-shrink:0;"><?= $emoji ?></span>
          <div>
            <div style="font-weight:700;font-size:var(--text-base);margin-bottom:3px;"><?= $title ?></div>
            <div style="font-size:13px;color:var(--text-muted);"><?= $desc ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Secure -->
      <div style="display:flex;align-items:center;gap:10px;padding:var(--space-4);background:rgba(52,211,153,0.05);border:1px solid rgba(52,211,153,0.15);border-radius:var(--radius-lg);">
        <span style="font-size:20px;">🔒</span>
        <div style="font-size:13px;color:var(--text-muted);">Secured by Razorpay. Your payment is encrypted and processed safely. We never store your card details.</div>
      </div>
    </div>

    <!-- Right: Payment Card -->
    <div style="position:sticky;top:90px;">
      <div class="card" style="padding:var(--space-6);border:1px solid rgba(99,102,241,0.25);">
        <div style="text-align:center;margin-bottom:var(--space-5);">
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">AI Marketing &amp; ChatGPT SEO</div>
          <div style="display:flex;align-items:baseline;justify-content:center;gap:10px;">
            <span style="font-size:42px;font-weight:900;color:var(--text-primary);">₹<?= number_format($priceSale) ?></span>
            <span style="font-size:20px;color:var(--text-muted);text-decoration:line-through;">₹<?= number_format($priceOrig) ?></span>
          </div>
          <span style="font-size:12px;background:rgba(52,211,153,0.12);color:#34d399;padding:3px 12px;border-radius:100px;font-weight:700;">
            Save ₹<?= number_format($priceOrig - $priceSale) ?> (<?= round(($priceOrig-$priceSale)/$priceOrig*100) ?>% off)
          </span>
        </div>

        <!-- Coupon -->
        <div style="margin-bottom:var(--space-4);">
          <div style="display:flex;gap:8px;" id="couponRow">
            <input type="text" id="couponInput" class="form-input" placeholder="Coupon code" style="flex:1;text-transform:uppercase;">
            <button onclick="applyCoupon()" class="btn btn-ghost" style="white-space:nowrap;padding:0 14px;" id="couponBtn">Apply</button>
          </div>
          <div id="couponMsg" style="font-size:12px;margin-top:6px;display:none;"></div>
        </div>

        <!-- Price Summary -->
        <div id="priceSummary" style="background:var(--bg-surface);border-radius:var(--radius-md);padding:var(--space-3);margin-bottom:var(--space-4);">
          <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-muted);margin-bottom:6px;">
            <span>Course price</span><span>₹<?= number_format($priceSale) ?></span>
          </div>
          <div id="discountRow" style="display:none;display:flex;justify-content:space-between;font-size:13px;color:#34d399;margin-bottom:6px;">
            <span>Coupon discount</span><span id="discountAmt">-₹0</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:700;border-top:1px solid var(--border-subtle);padding-top:8px;margin-top:2px;">
            <span>Total</span><span id="totalAmt">₹<?= number_format($priceSale) ?></span>
          </div>
        </div>

        <input type="hidden" id="appliedCouponId" value="">
        <input type="hidden" id="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">

        <button onclick="initiatePayment()" class="btn btn-primary" style="width:100%;justify-content:center;font-size:16px;padding:15px;" id="payBtn">
          🔓 Pay ₹<?= number_format($priceSale) ?> — Unlock Course
        </button>

        <p style="font-size:11px;color:var(--text-muted);text-align:center;margin-top:var(--space-3);">
          🔒 Secured by Razorpay · UPI · Cards · Netbanking · Wallets
        </p>

        <div style="margin-top:var(--space-4);padding-top:var(--space-3);border-top:1px solid var(--border-subtle);">
          <div style="font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:8px;">Payment methods</div>
          <div style="display:flex;justify-content:center;gap:10px;flex-wrap:wrap;">
            <?php foreach(['UPI', 'Cards', 'Net Banking', 'Wallets'] as $pm): ?>
            <span style="font-size:10px;font-weight:700;background:var(--bg-surface);border:1px solid var(--border-subtle);padding:3px 8px;border-radius:4px;color:var(--text-muted);"><?= $pm ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const CSRF   = document.getElementById('csrfToken').value;
let finalAmount = <?= (int)round($priceSale) ?>;
let couponId    = '';

async function applyCoupon(){
  const code = document.getElementById('couponInput').value.trim();
  const msg  = document.getElementById('couponMsg');
  const btn  = document.getElementById('couponBtn');
  if(!code){ msg.style.display='none'; return; }

  btn.disabled=true; btn.textContent='...';

  const fd = new FormData();
  fd.append('_csrf_token', CSRF);
  fd.append('coupon', code);

  try{
    const res  = await fetch('/courses/apply-coupon',{method:'POST',body:fd});
    const json = await res.json();
    msg.style.display='block';
    if(json.success){
      msg.style.color='#34d399';
      msg.textContent=`✓ ${json.discount_display} applied!`;
      finalAmount = json.final;
      couponId    = json.coupon_id;
      document.getElementById('appliedCouponId').value = couponId;
      // Update summary
      document.getElementById('discountRow').style.display='flex';
      document.getElementById('discountAmt').textContent='-₹'+Math.round(json.discount);
      document.getElementById('totalAmt').textContent='₹'+Math.round(json.final);
      document.getElementById('payBtn').textContent=`🔓 Pay ₹${Math.round(json.final)} — Unlock Course`;
    } else {
      msg.style.color='#f87171';
      msg.textContent=json.error||'Invalid coupon.';
      couponId=''; finalAmount=<?= (int)round($priceSale) ?>;
    }
  } catch(e){
    msg.style.color='#f87171'; msg.textContent='Error. Try again.'; msg.style.display='block';
  }
  btn.disabled=false; btn.textContent='Apply';
}

async function initiatePayment(){
  const btn=document.getElementById('payBtn');
  btn.disabled=true; btn.textContent='Initializing...';

  const fd=new FormData();
  fd.append('_csrf_token', CSRF);
  if(couponId) fd.append('coupon_id', couponId);

  try{
    const res=await fetch('/courses/create-order',{method:'POST',body:fd});
    const json=await res.json();

    if(!json.success){
      alert(json.error||'Failed to create order. Please try again.');
      btn.disabled=false; btn.textContent='🔓 Pay — Unlock Course';
      return;
    }

    // Handle 100% coupon discount
    if(json.free){ window.location.href=json.redirect; return; }

    const options={
      key:          json.key_id,
      amount:       json.amount,
      currency:     json.currency,
      name:         'TechAasvik',
      description:  'AI Marketing & ChatGPT SEO — Full Course',
      order_id:     json.order_id,
      prefill:{
        name:  json.name,
        email: json.email,
        contact: json.phone,
      },
      theme:{ color:'#6366f1' },
      modal:{ ondismiss: ()=>{ btn.disabled=false; btn.textContent='🔓 Pay — Unlock Course'; } },
      handler: async function(response){
        btn.textContent='Verifying payment...';
        const vfd=new FormData();
        vfd.append('_csrf_token', CSRF);
        vfd.append('razorpay_order_id',   response.razorpay_order_id);
        vfd.append('razorpay_payment_id', response.razorpay_payment_id);
        vfd.append('razorpay_signature',  response.razorpay_signature);

        const vres  = await fetch('/courses/verify-payment',{method:'POST',body:vfd});
        const vjson = await vres.json();

        if(vjson.success){
          window.location.href = vjson.redirect;
        } else {
          alert('Payment verification failed. Contact support with your payment ID: '+response.razorpay_payment_id);
          btn.disabled=false; btn.textContent='🔓 Pay — Unlock Course';
        }
      }
    };

    const rzp=new Razorpay(options);
    rzp.open();
  } catch(e){
    alert('Network error. Please try again.');
    btn.disabled=false; btn.textContent='🔓 Pay — Unlock Course';
  }
}
</script>
