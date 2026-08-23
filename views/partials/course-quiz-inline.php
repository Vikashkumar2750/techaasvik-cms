<?php
/**
 * course-quiz-inline.php — Inline quiz for a submodule
 * Available vars: $moduleNum, $subKey, $progress, $csrfToken, $courseSlug
 */
$qModNum    = $moduleNum ?? 1;
$subKey     = $qModNum . '-5'; // quiz is always submodule 5
$hasPassed  = !empty($progress[$qModNum]['quiz_passed']);
$prevScore  = !empty($progress[$qModNum]['quiz_score']) ? (int)$progress[$qModNum]['quiz_score'] : null;
$passMark   = 60; // 60% to pass
?>

<div class="quiz-wrap" id="quizWrap">

  <?php if ($hasPassed): ?>
  <!-- Already passed -->
  <div class="quiz-result pass" style="margin-bottom:24px;">
    <div class="quiz-score-big" style="color:#059669;">✓</div>
    <div style="font-size:18px;font-weight:700;color:#059669;margin-top:8px;">Module Quiz Passed!</div>
    <div style="color:var(--text-muted);margin-top:6px;">
      Your score: <strong><?= $prevScore ?>%</strong>
      &nbsp;·&nbsp;
      Grade: <strong><?= \Models\CourseProgress::scoreToGrade((float)$prevScore) ?></strong>
    </div>
    <div style="margin-top:16px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
      <button onclick="showQuiz()" style="padding:10px 20px;border:1px solid #059669;background:transparent;color:#059669;border-radius:8px;font-weight:600;cursor:pointer;">
        Retake Quiz
      </button>
      <?php $nextMod = $qModNum + 1; ?>
      <?php if ($nextMod <= 10): ?>
      <a href="/courses/<?= $courseSlug ?? 'ai-marketing-course' ?>/learn/<?= $nextMod ?>/1"
         style="padding:10px 20px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:8px;font-weight:700;text-decoration:none;">
        Next Module →
      </a>
      <?php endif; ?>
    </div>
  </div>
  <div id="quizFormArea" style="display:none;">
  <?php else: ?>
  <div id="quizFormArea">
  <?php endif; ?>

    <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:16px 20px;margin-bottom:24px;">
      <div style="font-size:14px;font-weight:700;color:#d97706;">📝 Module <?= $qModNum ?> Quiz</div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">5 questions · Pass mark: 60% · Your best score counts</div>
    </div>

    <form id="quizForm" onsubmit="submitQuiz(event)">
      <input type="hidden" name="csrf_token" value="<?= $csrfToken ?? '' ?>">
      <input type="hidden" name="module" value="<?= $qModNum ?>">
      <input type="hidden" name="sub_key" value="<?= $subKey ?>">

      <?php
      // Get quiz questions from controller - we pass them via a PHP variable
      // If $quizQuestions isn't set, we'll load from a static map
      $quizMap = [
        1 => [
          ['q' => 'What is the most important shift AI has caused in search?', 'options' => ['More keywords rank', 'AI answers queries directly, reducing click-through', 'Google now shows more ads', 'SEO is no longer needed'], 'answer' => 1],
          ['q' => 'Which skill is most important for AI-native marketers?', 'options' => ['Graphic design', 'Context engineering and critical thinking', 'Social media posting', 'Video editing'], 'answer' => 1],
          ['q' => 'What do humans still do better than AI in marketing?', 'options' => ['Processing large datasets', 'Building genuine relationships and contextual judgment', 'Writing first drafts', 'Running A/B tests'], 'answer' => 1],
          ['q' => 'AI agents in marketing primarily help with:', 'options' => ['Creating logos', 'Multi-step autonomous tasks across tools', 'Only email writing', 'Printing brochures'], 'answer' => 1],
          ['q' => 'Your personal AI marketing workflow should start with:', 'options' => ['Tool selection', "Understanding your audience's real problems", 'Budget allocation', 'Competitor spying'], 'answer' => 1],
        ],
        2 => [
          ['q' => 'Context engineering differs from prompt engineering in that it focuses on:', 'options' => ['Writing shorter prompts', 'Providing complete context for reliable AI output', 'Using only one prompt', 'Avoiding instructions'], 'answer' => 1],
          ['q' => 'Which CRAFT element sets boundaries for AI output?', 'options' => ['Role', 'Context', 'Constraints', 'Format'], 'answer' => 2],
          ['q' => 'Iterative prompting means:', 'options' => ['Writing one perfect prompt', 'Refining prompts based on output across multiple steps', 'Using the same prompt repeatedly', 'Copying prompts from others'], 'answer' => 1],
          ['q' => 'When fact-checking AI output, you should:', 'options' => ['Trust it if it sounds confident', 'Verify against primary sources', 'Only check statistics', 'Never use AI for factual content'], 'answer' => 1],
          ['q' => 'A reusable marketing workflow is valuable because:', 'options' => ['It looks impressive', 'It produces consistent, predictable outputs at scale', 'It requires less thinking', 'AI prefers routines'], 'answer' => 1],
        ],
        3 => [
          ['q' => 'The most underused AI application in marketing is:', 'options' => ['Blog writing', 'Deep customer & competitor research', 'Image generation', 'Email subject lines'], 'answer' => 1],
          ['q' => 'Jobs-to-Be-Done research helps you understand:', 'options' => ['Which tools competitors use', 'What outcome customers actually want, not just features', 'Competitor ad budgets', 'SEO rankings'], 'answer' => 1],
          ['q' => 'Mining customer reviews is valuable for:', 'options' => ['Finding negative press', 'Discovering real customer language for messaging', 'Tracking competitor revenue', 'Finding their email list'], 'answer' => 1],
          ['q' => 'A content gap analysis reveals:', 'options' => ['How much content to publish', "Topics your competitors rank for that you don't", "Your website's load speed", 'Best posting times'], 'answer' => 1],
          ['q' => 'A positioning framework helps you:', 'options' => ['Copy competitor messaging', 'Differentiate clearly and target the right audience segment', 'Set ad budgets', 'Choose fonts'], 'answer' => 1],
        ],
        4 => [
          ['q' => 'Topical authority in SEO means:', 'options' => ['Having the most backlinks', 'Comprehensively covering a topic so search engines trust your site', 'Posting daily', 'Buying guest posts'], 'answer' => 1],
          ['q' => 'Search intent is most important for:', 'options' => ['Domain authority', 'Matching content to what users actually want at each stage', 'Meta description length', 'Image size'], 'answer' => 1],
          ['q' => 'Entity-based SEO focuses on:', 'options' => ['Exact-match keywords', 'Named things (people, places, concepts) and their relationships', 'URL structure', 'Social signals'], 'answer' => 1],
          ['q' => 'An AI-generated content brief should include:', 'options' => ['Only a word count target', 'Target keywords, intent, structure, entities, and competitor gaps', 'Just a title', "The writer's bio"], 'answer' => 1],
          ['q' => 'Content refresh with AI is most valuable for:', 'options' => ['New articles with no traffic', 'Pages that ranked but have declining traffic', 'Pages that never ranked', 'Homepage only'], 'answer' => 1],
        ],
        5 => [
          ['q' => 'E-E-A-T stands for:', 'options' => ['Engagement, Earnings, Authority, Trust', 'Experience, Expertise, Authoritativeness, Trustworthiness', 'Email, Efficiency, AI, Traffic', 'Engagement, Effort, Analytics, Traffic'], 'answer' => 1],
          ['q' => 'The biggest risk of AI-generated content for SEO is:', 'options' => ['It is too expensive', 'Low-value, mass-produced content that signals spam', 'AI content is always incorrect', 'Google bans all AI content'], 'answer' => 1],
          ['q' => 'Brand voice in AI content ensures:', 'options' => ['Content is always positive', 'Output sounds like your brand, not generic AI', 'AI writes faster', 'SEO scores improve automatically'], 'answer' => 1],
          ['q' => 'Repurposing content means:', 'options' => ["Reposting the same content everywhere", "Adapting core ideas for each platform's format and audience", 'Translating content', 'Deleting old content'], 'answer' => 1],
          ['q' => 'A 30-day content system primarily solves:', 'options' => ['Budget problems', 'Inconsistency and reactive content creation', 'Hiring needs', 'Domain authority'], 'answer' => 1],
        ],
        6 => [
          ['q' => 'GEO (Generative Engine Optimization) focuses on:', 'options' => ['Traditional SEO ranking', 'Getting your content cited by AI answer engines', 'Google Maps ranking', 'Video SEO'], 'answer' => 1],
          ['q' => "Google's AI Overviews pull content that is:", 'options' => ['Most recently published', 'Authoritative, structured, and clearly answers the query', 'Longest article', 'From .edu domains only'], 'answer' => 1],
          ['q' => 'Entity signals for AI visibility include:', 'options' => ['Keyword density', 'Brand mentions, citations, and structured data across the web', 'Image alt text only', 'Meta keywords'], 'answer' => 1],
          ['q' => 'OAI-SearchBot refers to:', 'options' => ['A Google bot', "OpenAI's crawler for ChatGPT Search", 'A Bing crawler', "Meta's AI bot"], 'answer' => 1],
          ['q' => 'The best way to track AI visibility is:', 'options' => ['Monthly Google rankings', 'Systematic testing of AI queries and recording mentions/citations', 'Social media monitoring only', 'Checking Alexa rank'], 'answer' => 1],
        ],
        7 => [
          ['q' => 'Performance Max campaigns work best when:', 'options' => ['You restrict all targeting manually', 'You provide rich conversion data and creative assets', 'Budget is very low', 'You use broad match keywords only'], 'answer' => 1],
          ['q' => 'Meta Advantage+ Shopping is designed for:', 'options' => ['B2B lead generation', 'eCommerce with automated targeting and creative testing', 'App installs only', 'Brand awareness only'], 'answer' => 1],
          ['q' => 'The most important signal for AI bidding is:', 'options' => ['Ad frequency', 'High-quality conversion data from your website', 'Competitor bids', 'Ad copy length'], 'answer' => 1],
          ['q' => 'ROAS stands for:', 'options' => ['Return on Ad Spend', 'Rate of Ad Success', 'Revenue Over All Sources', 'Reach of Ad Sets'], 'answer' => 0],
          ['q' => 'Scaling a profitable campaign primarily requires:', 'options' => ['Duplicating it exactly', 'Incrementally increasing budget while monitoring efficiency metrics', 'Changing the offer', 'Reducing targeting'], 'answer' => 1],
        ],
        8 => [
          ['q' => 'CRO (Conversion Rate Optimization) is about:', 'options' => ['Getting more traffic', "Improving what percentage of visitors take your desired action", 'Reducing ad spend', 'Improving page speed only'], 'answer' => 1],
          ['q' => 'Lead qualification with AI helps:', 'options' => ['Generate more leads', 'Identify which leads are most likely to convert', 'Reduce email costs', 'Replace the sales team'], 'answer' => 1],
          ['q' => 'n8n is used for:', 'options' => ['Graphic design', 'No-code workflow automation with AI steps', 'SEO analysis', 'Email hosting'], 'answer' => 1],
          ['q' => 'The correct order for a nurture sequence is:', 'options' => ['Sell → Build trust → Welcome', 'Welcome → Build trust → Offer', 'Offer → Sell → Retarget', 'Retarget → Welcome → Offer'], 'answer' => 1],
          ['q' => 'Human approval steps in AI automation are important for:', 'options' => ['Slowing things down', 'Brand safety, accuracy, and preventing automated errors', 'Increasing costs', 'Reducing output'], 'answer' => 1],
        ],
        9 => [
          ['q' => 'The best attribution model for understanding full customer journey is:', 'options' => ['Last-click', 'Data-driven attribution', 'First-click', 'Linear'], 'answer' => 1],
          ['q' => 'CAC stands for:', 'options' => ['Content Acquisition Cost', 'Customer Acquisition Cost', 'Campaign Ad Cost', 'Conversion Average Count'], 'answer' => 1],
          ['q' => "GA4's biggest difference from Universal Analytics is:", 'options' => ['No bounce rate', 'Event-based measurement (not session-based)', 'No conversion tracking', 'Only for apps'], 'answer' => 1],
          ['q' => 'LTV (Lifetime Value) helps you decide:', 'options' => ['Which posts to publish', 'How much you can profitably spend to acquire a customer', 'Which social platform to use', 'Email frequency'], 'answer' => 1],
          ['q' => 'Marketing diagnosis means:', 'options' => ['Finding the highest traffic channel', "Systematically identifying what's broken and prescribing a fix", 'Increasing ad budget', 'A/B testing everything'], 'answer' => 1],
        ],
        10 => [
          ['q' => 'An AI Marketing Operating System (OS) is:', 'options' => ['A software product', 'A complete, connected system of research, content, ads, automation and analytics', 'Just a content calendar', 'A CRM tool'], 'answer' => 1],
          ['q' => 'The capstone project requires you to work on:', 'options' => ['A fictional company', 'One real business you can actually implement your work for', "TechAasvik's business", 'Any hypothetical market'], 'answer' => 1],
          ['q' => 'The most important output of a marketing capstone is:', 'options' => ['A long report', 'A working, implementable system with real data', 'A presentation deck', 'A list of AI tools'], 'answer' => 1],
          ['q' => 'AI safety in marketing primarily means:', 'options' => ['Not using AI', 'Verifying AI output, checking for bias, and maintaining human oversight', 'Only using paid AI tools', 'Avoiding social media'], 'answer' => 1],
          ['q' => 'After completing this course, you are equipped to:', 'options' => ['Only use ChatGPT', 'Build and manage a complete AI-powered marketing system for any business', 'Design logos with AI', 'Replace all marketing staff with AI'], 'answer' => 1],
        ],
      ];
      $questions = $quizMap[$qModNum] ?? $quizMap[1];
      ?>

      <?php foreach ($questions as $qi => $q): ?>
      <div class="quiz-q" id="qq<?= $qi ?>">
        <div class="quiz-q-text"><?= $qi + 1 ?>. <?= htmlspecialchars($q['q']) ?></div>
        <?php foreach ($q['options'] as $oi => $opt): ?>
        <div class="quiz-option" onclick="selectOption(this, <?= $qi ?>)"
             data-qi="<?= $qi ?>" data-oi="<?= $oi ?>">
          <div class="quiz-radio"></div>
          <span><?= htmlspecialchars($opt) ?></span>
          <input type="hidden" name="answers[<?= $qi ?>]" value="" class="ans-input" data-qi="<?= $qi ?>">
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

      <div style="margin-top:24px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <button type="submit" id="quizSubmitBtn"
                style="padding:13px 28px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;">
          Submit Quiz →
        </button>
        <div style="font-size:12px;color:var(--text-muted);">Answer all 5 questions to submit</div>
      </div>
    </form>

    <!-- Result panel (hidden until submit) -->
    <div id="quizResult" style="display:none;"></div>

  </div><!-- /quizFormArea -->
</div><!-- /quizWrap -->

<script>
const quizAnswers  = {};
const correctAnswers = <?= json_encode(array_column($questions, 'answer')) ?>;
const totalQ = <?= count($questions) ?>;
const PASS_MARK = <?= $passMark ?>;
const moduleNum = <?= $qModNum ?>;
const qSubKey   = '<?= $subKey ?>';
let   submitted = false;

function selectOption(el, qi) {
  if (submitted) return;
  const group = el.closest('.quiz-q').querySelectorAll('.quiz-option');
  group.forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  quizAnswers[qi] = parseInt(el.dataset.oi);
}

async function submitQuiz(e) {
  e.preventDefault();
  if (Object.keys(quizAnswers).length < totalQ) {
    alert('Please answer all ' + totalQ + ' questions first.');
    return;
  }
  submitted = true;
  const btn = document.getElementById('quizSubmitBtn');
  btn.disabled = true; btn.textContent = 'Submitting…';

  // Show correct/wrong
  let correct = 0;
  document.querySelectorAll('.quiz-q').forEach((qEl, qi) => {
    const opts = qEl.querySelectorAll('.quiz-option');
    const userAns = quizAnswers[qi];
    opts.forEach((o, oi) => {
      if (oi === correctAnswers[qi]) o.classList.add('correct');
      else if (oi === userAns && oi !== correctAnswers[qi]) o.classList.add('wrong');
    });
    if (userAns === correctAnswers[qi]) correct++;
  });

  const score = Math.round((correct / totalQ) * 100);
  const passed = score >= PASS_MARK;

  // Submit to server
  try {
    const resp = await fetch('/courses/quiz', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        csrf_token: '<?= $csrfToken ?? '' ?>',
        module: moduleNum,
        sub_key: qSubKey,
        score: score,
        passed: passed ? 1 : 0,
        answers: JSON.stringify(quizAnswers)
      })
    });
    const d = await resp.json();

    // Also mark submodule complete if passed
    if (passed) {
      await fetch('/courses/submodule-complete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: '<?= $csrfToken ?? '' ?>', sub_key: qSubKey })
      });
    }

    showResult(score, correct, passed, d.grade ?? '', d.score ?? 0);
  } catch(err) {
    showResult(score, correct, passed, '', 0);
  }
}

function showResult(score, correct, passed, grade, overall) {
  const gradeHtml = (grade && overall > 0)
    ? `<div style="margin-top:8px;font-size:13px;color:var(--text-muted)">Overall Grade: <strong class="grade-badge grade-${grade}" style="padding:2px 10px;">${grade} · ${overall}%</strong></div>`
    : '';

  const nextMod = moduleNum + 1;
  const nextBtn = nextMod <= 10 && passed
    ? `<a href="/courses/<?= $courseSlug ?? 'ai-marketing-course' ?>/learn/${nextMod}/1" style="display:inline-block;margin-top:12px;padding:12px 24px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border-radius:10px;font-weight:700;text-decoration:none;">Next Module →</a>`
    : (passed ? '' : `<button onclick="retakeQuiz()" style="margin-top:12px;padding:12px 24px;border:1.5px solid #f59e0b;color:#d97706;background:transparent;border-radius:10px;font-weight:700;cursor:pointer;">Try Again</button>`);

  document.getElementById('quizResult').style.display = 'block';
  document.getElementById('quizResult').innerHTML = `
    <div class="quiz-result ${passed ? 'pass' : 'fail'}">
      <div class="quiz-score-big" style="color:${passed ? '#059669' : '#ef4444'}">${score}%</div>
      <div style="font-size:18px;font-weight:800;margin-top:8px;color:var(--text-primary)">
        ${passed ? '🎉 Quiz Passed!' : '😕 Not Quite'}
      </div>
      <div style="color:var(--text-muted);margin-top:6px;">${correct}/${totalQ} correct · Pass mark: ${PASS_MARK}%</div>
      ${gradeHtml}
      ${nextBtn}
    </div>
  `;
  document.getElementById('quizResult').scrollIntoView({behavior:'smooth', block:'center'});
}

function retakeQuiz() {
  submitted = false;
  Object.keys(quizAnswers).forEach(k => delete quizAnswers[k]);
  document.querySelectorAll('.quiz-option').forEach(o => {
    o.classList.remove('selected','correct','wrong');
  });
  document.getElementById('quizResult').style.display = 'none';
  document.getElementById('quizSubmitBtn').disabled = false;
  document.getElementById('quizSubmitBtn').textContent = 'Submit Quiz →';
}

function showQuiz() {
  document.getElementById('quizFormArea').style.display = 'block';
}
</script>
