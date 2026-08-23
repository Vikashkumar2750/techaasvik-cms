<?php
/**
 * Submodule content partial
 * Available vars: $moduleNum, $submoduleNum, $currentSub, $module
 */
$subKey = ($moduleNum ?? 1) . '-' . ($submoduleNum ?? 1);
$title  = $currentSub['title'] ?? '';
$mod    = $module ?? [];

// Infographic data per submodule key
$infographics = [
    '1-1' => ['title' => 'The AI Marketing Shift', 'steps' => ['AI answers queries directly', 'Search intent changes', 'Zero-click searches rise', 'Content must be AI-readable', 'Human judgment > rote tasks']],
    '1-2' => ['title' => 'What AI Changed in Marketing', 'steps' => ['Content generation speed 10x', 'Personalisation at scale', 'Research in seconds', 'Ad copy testing automated', 'Predictive insights available']],
    '1-3' => ['title' => 'Search & Content Transformation', 'steps' => ['Featured snippets → AI Overviews', 'Keywords → Entities & topics', 'Links → Citations in AI answers', 'Long-form → Answer-first content', 'SEO + GEO + AEO combined']],
    '1-4' => ['title' => 'Automation & Personalization', 'steps' => ['AI agents handle multi-step tasks', 'Hyper-personalised email at scale', 'Predictive lead scoring', 'Dynamic content generation', 'Always-on campaign optimisation']],
    '2-1' => ['title' => 'ChatGPT Basics for Marketers', 'steps' => ['Understand model limitations', 'Set role context first', 'Be specific with your ask', 'Iterate on outputs', 'Fact-check everything']],
    '2-2' => ['title' => 'Context Engineering Framework', 'steps' => ['Goal → what do you need?', 'Role → who is ChatGPT playing?', 'Audience → who reads this?', 'Constraints → what to avoid?', 'Format → how should it look?']],
    '2-3' => ['title' => 'The CRAFT Framework', 'steps' => ['C — Context', 'R — Role', 'A — Action', 'F — Format', 'T — Tone/Constraints']],
    '2-4' => ['title' => 'Building Reusable Workflows', 'steps' => ['Document your best prompts', 'Create prompt templates', 'Add variables for reuse', 'Test across content types', 'Build a prompt library']],
    '3-1' => ['title' => 'The Research-First Mindset', 'steps' => ['Research before creating', 'Know your audience deeply', 'Map their journey', 'Find real pain points', 'Use AI to synthesise data']],
    '3-2' => ['title' => 'Customer & Competitor Intel', 'steps' => ['Interview customers', 'Mine online reviews', 'Analyse competitor content', 'Map content gaps', 'Find positioning opportunities']],
    '3-3' => ['title' => 'Mining Reviews & Gaps', 'steps' => ['Amazon & G2 reviews', 'Reddit & Quora threads', 'YouTube comments', 'Trustpilot feedback', 'Extract VOC language']],
    '3-4' => ['title' => 'Positioning Framework', 'steps' => ['Define your differentiation', 'Identify target segment', 'Map key messages', 'Proof points & evidence', 'Create positioning statement']],
    '4-1' => ['title' => 'AI Keyword Strategy', 'steps' => ['Seed keyword brainstorm', 'Intent classification', 'Volume vs. difficulty', 'Long-tail clusters', 'Topical map creation']],
    '4-2' => ['title' => 'Topical Authority Clusters', 'steps' => ['1 pillar page per topic', '5–10 cluster articles each', 'Internal linking structure', 'Cover every subtopic', 'Update quarterly']],
    '4-3' => ['title' => 'AI Content Brief Structure', 'steps' => ['Target keyword + intent', 'Outline with H2/H3s', 'Must-answer questions', 'Entities to include', 'Competitor gaps to cover']],
    '4-4' => ['title' => 'Content Refresh System', 'steps' => ['Identify declining pages', 'Run content audit', 'Update stats & examples', 'Add new sections', 'Improve internal links']],
    '5-1' => ['title' => 'AI Content at Scale', 'steps' => ['Create content templates', 'Use AI for first drafts', 'Human editing & review', 'Fact-check all claims', 'Publish + distribute']],
    '5-2' => ['title' => 'E-E-A-T + Brand Voice', 'steps' => ['Experience signals', 'Expert author credentials', 'Authoritative sources cited', 'Trustworthy site signals', 'Consistent brand tone']],
    '5-3' => ['title' => 'Multimedia Repurposing', 'steps' => ['Blog → LinkedIn thread', 'Blog → short-form video', 'Podcast → show notes', 'Video → Twitter thread', 'Report → infographic']],
    '5-4' => ['title' => '30-Day Content System', 'steps' => ['Week 1: Pillar content', 'Week 2: Social repurpose', 'Week 3: Email newsletter', 'Week 4: SEO update + analyse', 'Repeat monthly']],
    '6-1' => ['title' => 'GEO Fundamentals', 'steps' => ['AI reads structured content', 'Clear, direct answers win', 'Citations matter', 'Brand mentions across web', 'Schema markup essential']],
    '6-2' => ['title' => 'AI Overview Optimisation', 'steps' => ['Answer questions directly', 'Use FAQ structure', 'Add structured data', 'Build topical authority', 'Get cited by others']],
    '6-3' => ['title' => 'Entity Signals & Schema', 'steps' => ['Entity consistency across web', 'Wikipedia + Wikidata presence', 'Schema.org markup', 'Knowledge Panel optimisation', 'Brand search volume']],
    '6-4' => ['title' => 'AEO Answer Engineering', 'steps' => ['Identify "People Also Ask"', 'Write direct answers (40–60 words)', 'Add supporting context', 'Use ordered & unordered lists', 'Monitor AI answer appearances']],
    '7-1' => ['title' => 'Google Performance Max', 'steps' => ['Provide rich conversion data', 'Upload diverse creatives', 'Set smart bidding goals', 'Use audience signals', 'Monitor asset performance']],
    '7-2' => ['title' => 'Meta Advantage+ Shopping', 'steps' => ['Connect product catalogue', 'Let AI find best audiences', 'Test multiple creatives', 'Optimise for purchase events', 'Scale winning ad sets']],
    '7-3' => ['title' => 'AI Bidding & Signals', 'steps' => ['Maximise conversions bidding', 'Target CPA / Target ROAS', 'Feed quality conversion data', 'Avoid over-constraining', 'Allow learning period (2 weeks)']],
    '7-4' => ['title' => 'ROAS Scaling System', 'steps' => ['Identify winning campaigns', 'Increase budget 15–20%/week', 'Monitor efficiency closely', 'Expand to new audiences', 'Test new creative batches']],
    '8-1' => ['title' => 'CRO with AI', 'steps' => ['Run heatmap analysis', 'Test landing page variations', 'AI-generated copy variants', 'Form optimisation', 'Mobile UX improvements']],
    '8-2' => ['title' => 'Lead Scoring & Nurturing', 'steps' => ['Define ICP criteria', 'Score by engagement signals', 'AI predicts conversion probability', 'Segment by score', 'Personalise follow-up']],
    '8-3' => ['title' => 'n8n Workflow Automation', 'steps' => ['Trigger: new lead/event', 'Enrich data via APIs', 'AI step: classify/score', 'Route to correct sequence', 'Human approval if needed']],
    '8-4' => ['title' => 'Email Nurture Sequences', 'steps' => ['Welcome email (instant)', 'Value email (day 2)', 'Education email (day 4)', 'Social proof (day 7)', 'Offer email (day 10)']],
    '9-1' => ['title' => 'GA4 Setup & Events', 'steps' => ['GA4 property setup', 'Define key events', 'Enable Enhanced Measurement', 'Connect Search Console', 'Set up conversions']],
    '9-2' => ['title' => 'Attribution Models', 'steps' => ['Last-click (legacy)', 'First-click (awareness)', 'Linear (equal credit)', 'Time-decay (recent weighted)', 'Data-driven (AI-powered) ✓']],
    '9-3' => ['title' => 'CAC, LTV & North Star Metric', 'steps' => ['CAC = Total spend ÷ new customers', 'LTV = Avg order × frequency × lifetime', 'LTV:CAC ratio goal ≥ 3:1', 'North Star = 1 key growth metric', 'Review monthly']],
    '9-4' => ['title' => 'Marketing Diagnosis Framework', 'steps' => ['What is the symptom?', 'Which funnel stage breaks?', 'Root cause analysis', 'Hypothesis & test', 'Measure → iterate']],
    '10-1' => ['title' => 'Your AI Marketing OS', 'steps' => ['Research system', 'Content system', 'Distribution system', 'Paid media system', 'Analytics system']],
    '10-2' => ['title' => 'Capstone: Real Business Plan', 'steps' => ['Pick a real business', 'Run full market research', 'Build content strategy', 'Create ad strategy', 'Set up automations']],
    '10-3' => ['title' => 'AI Safety & Ethics', 'steps' => ['Verify all AI outputs', 'Check for bias', 'Maintain human oversight', 'Disclose AI use', 'Protect user data']],
    '10-4' => ['title' => 'Career Roadmap', 'steps' => ['AI Marketing Specialist', 'Growth Marketer', 'Marketing Technologist', 'CMO / Head of Growth', 'AI Marketing Consultant']],
];

$info = $infographics[$subKey] ?? ['title' => $title, 'steps' => []];
?>

<!-- Infographic / Visual Card -->
<?php if (!empty($info['steps'])): ?>
<div class="visual-card">
  <h4>⚡ <?= htmlspecialchars($info['title']) ?></h4>
  <div class="visual-steps">
    <?php foreach ($info['steps'] as $si => $step): ?>
    <div class="visual-step">
      <div class="visual-step-num"><?= $si + 1 ?></div>
      <?= htmlspecialchars($step) ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Module text content (includes the full module content file) -->
<?php
$contentFile = APP_ROOT . "/views/partials/course-module-{$moduleNum}.php";
if (file_exists($contentFile)) {
    include $contentFile;
} else {
    echo '<p style="color:var(--text-muted);font-style:italic;">Content for this module is loading...</p>';
}
?>
