<?php // Module 9: AI Analytics, Attribution & Marketing Decisions ?>
<div class="module-content">
<div class="module-problem">
  <h2>🔴 The Problem</h2>
  <p>Most marketers have access to more data than ever — and are making worse decisions because of it. GA4 is confusing. Attribution is broken. Dashboards look impressive but don't drive action. AI doesn't fix this automatically; it amplifies whatever decision-making framework you already have. This module builds that framework.</p>
</div>
<div class="module-concept">
  <h2>💡 The Concept</h2>
  <h3>GA4 for Decision-Making</h3>
  <p>GA4 is event-based, not session-based. This means every interaction (scroll, click, form submit, video play, purchase) is an event you can measure. The key mental model: don't just report traffic — build funnels. Where do users enter? Where do they drop? What actions predict conversion?</p>
  <p><strong>Key GA4 concepts for marketers:</strong> Explorations (Funnel Exploration, Path Exploration), Audiences (for Google Ads), Events vs. Conversions, Attribution settings.</p>
  <h3>UTM Strategy</h3>
  <p>Every piece of traffic you generate should have a UTM: source, medium, campaign, content, term. Without consistent UTM discipline, attribution is meaningless. Build a UTM naming convention and enforce it across your team and AI automation tools.</p>
  <h3>Attribution Models</h3>
  <p>Attribution assigns credit for conversions to touchpoints. Models: Last-click (easiest, most misleading), First-click (good for awareness analysis), Linear (equal credit), Data-driven (Google's AI model — best for accounts with 30+ conversions/month). The honest answer: all models are wrong; data-driven is least wrong.</p>
  <h3>The Metrics That Matter</h3>
  <ul>
    <li><strong>CAC (Customer Acquisition Cost):</strong> Total spend ÷ New customers. The fundamental efficiency metric.</li>
    <li><strong>LTV (Lifetime Value):</strong> Average revenue × purchase frequency × retention. Must be &gt; CAC for sustainable business.</li>
    <li><strong>CPL (Cost Per Lead):</strong> Spend ÷ Leads. For B2B, compare to industry benchmarks.</li>
    <li><strong>ROAS (Return on Ad Spend):</strong> Revenue ÷ Ad spend. Target varies by margin and LTV.</li>
    <li><strong>Marketing Efficiency Ratio (MER):</strong> Total revenue ÷ Total ad spend. Holistic view across all channels.</li>
  </ul>
  <h3>AI-Powered Dashboard Building</h3>
  <p>Looker Studio (free) connects to GA4, Google Ads, Meta Ads, Search Console, and 700+ other sources. AI can help you: design dashboard architecture, write calculated fields, interpret anomalies, and generate weekly narrative summaries from raw data.</p>
  <h3>Marketing Diagnosis Framework</h3>
  <p>When performance drops, don't guess — diagnose. Check: Is the problem traffic volume? Traffic quality? Landing page conversion? Form completion? Lead quality? Sales conversion? Each problem has a different solution. AI can help pattern-match anomalies in data — but you need to ask the right questions.</p>
</div>
<div class="module-workflow">
  <h2>⚙️ Analytics Decision Workflow</h2>
  <pre class="code-block">Weekly Review (30 min):
→ Check MER / ROAS vs. targets
→ Check CAC trends (up/down/stable?)
→ Check top traffic sources (any anomalies?)
→ Check conversion rate by source
→ Flag any anomaly for investigation

Monthly Review (2 hrs):
→ Full funnel analysis (GA4 Exploration)
→ Attribution review (what's really working?)
→ Cohort analysis (are new customers performing?)
→ Competitive benchmarking
→ Next month plan adjustment</pre>
</div>
<div class="module-demo">
  <h2>🎬 Live Demo</h2>
  <p>We take 90 days of real marketing data (anonymised) and use AI to: identify the top 3 performance issues, diagnose root causes using the funnel framework, and build a prioritised action plan. We then build the Looker Studio dashboard that would make this analysis routine.</p>
</div>
<div class="module-template">
  <h2>📋 Template: Marketing Performance Report</h2>
  <div class="template-box">
    <p><strong>Period:</strong> [Month/Quarter]</p>
    <p><strong>Key Metrics:</strong> Sessions · Leads · Conversion Rate · CAC · ROAS · Revenue</p>
    <p><strong>vs. Previous Period:</strong> [% change for each metric]</p>
    <p><strong>Top 3 What Worked:</strong> [Specific campaigns/content with evidence]</p>
    <p><strong>Top 3 What Didn't:</strong> [With diagnosis and root cause]</p>
    <p><strong>Next Period Recommendations:</strong> [3 specific actions with expected impact]</p>
    <p><strong>Budget Allocation Recommendation:</strong> [By channel, with rationale]</p>
  </div>
</div>
<div class="module-assignment">
  <h2>✏️ Assignment</h2>
  <p>Take 90 days of real data from any business (yours, a client's, or a case study). Build a complete marketing performance report using the template above. Use AI to help interpret patterns and anomalies. Build a Looker Studio dashboard with at least 5 key metrics.</p>
</div>
<div class="module-qa">
  <h2>🔍 QA</h2>
  <ul>
    <li>Are all UTMs consistently applied? (Check for (not set) in GA4)</li>
    <li>Are conversion events firing correctly? (Check Realtime reports)</li>
    <li>Does your attribution model match how the business actually makes decisions?</li>
    <li>Can you explain every metric in the report to a non-marketer?</li>
  </ul>
</div>
<div class="module-outcome">
  <h2>💼 Business Outcome</h2>
  <p>You move from reporting what happened to diagnosing why and prescribing what to do next. That's the difference between a data analyst and a strategic marketer. Clients and employers pay significantly more for the latter.</p>
</div>
</div>
