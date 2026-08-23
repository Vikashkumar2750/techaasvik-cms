<?php // Module 2: ChatGPT for Marketers ?>
<div class="module-content">

<div class="module-problem">
  <h2>🔴 The Problem</h2>
  <p>The phrase "prompt engineering" created a misconception: that getting good AI output is about writing clever prompts. It's not. The real skill is <strong>context engineering</strong> — giving the AI everything it needs to produce reliably useful output, consistently, at scale. Most marketers get mediocre results because they give ChatGPT a one-line instruction and hope for the best.</p>
</div>

<div class="module-concept">
  <h2>💡 The Concept: From Prompt to Context</h2>
  <h3>What Context Engineering Actually Means</h3>
  <p>Context engineering is the practice of designing the full environment in which an AI operates — the role, the task constraints, the examples, the output format, and the feedback loop. Think of it as writing a detailed brief for a junior consultant who is brilliant but has no institutional knowledge about your business.</p>

  <h3>The 5-Part Prompt Structure (RCEOF)</h3>
  <ul>
    <li><strong>Role</strong> — Who is the AI acting as? ("You are a B2B content strategist with 10 years in SaaS marketing...")</li>
    <li><strong>Context</strong> — What does the AI need to know? (Company, audience, competitors, tone, recent work)</li>
    <li><strong>Examples</strong> — Show the AI what "good" looks like with 1–3 examples</li>
    <li><strong>Output format</strong> — Exactly how should the response be structured? (Bullet points, JSON, a table, a script)</li>
    <li><strong>Constraints</strong> — What should it avoid? (No jargon, under 300 words, don't mention competitor X)</li>
  </ul>

  <h3>Iterative Prompting vs. One-Shot Prompting</h3>
  <p>One-shot prompting ("write me a blog post about SEO") produces generic output. Iterative prompting treats the conversation as a collaborative drafting session:</p>
  <pre class="code-block">Step 1: Set context + request outline
Step 2: Review outline → refine direction  
Step 3: Request full draft of section 1
Step 4: "Make section 1 more specific to Indian SMBs"
Step 5: Request remaining sections
Step 6: Request a headline and meta description</pre>

  <h3>AI Research Workflows</h3>
  <p>ChatGPT with web search (or Perplexity) can do meaningful research. Use it for: summarising industry reports, finding statistics on a topic, pulling key quotes from competitors' public communications, and identifying trending questions in your niche.</p>

  <h3>Fact-Checking AI Output</h3>
  <p>AI hallucinates. This is not a bug — it is a fundamental property of how language models work. Your workflow must include verification:</p>
  <ul>
    <li>Any specific statistic → verify at primary source</li>
    <li>Any quote attributed to a person → search for original source</li>
    <li>Any product claims → verify on official website</li>
    <li>Legal/medical/financial claims → require expert review</li>
  </ul>
</div>

<div class="module-workflow">
  <h2>⚙️ The AI Workflow: Building Reusable Systems</h2>
  <p>A reusable workflow is a documented, tested prompt sequence that produces consistent output for a recurring task. Examples:</p>
  <ul>
    <li><strong>Blog workflow:</strong> Research prompt → Outline prompt → Section-by-section drafting → SEO optimisation prompt → Meta description prompt</li>
    <li><strong>LinkedIn workflow:</strong> "Extract 5 key insights from [article]. For each, write a LinkedIn post in a conversational tone, under 150 words, with a question at the end."</li>
    <li><strong>Email workflow:</strong> Brief input (offer, audience, desired action) → Subject line options → Email body → P.S. line</li>
  </ul>
</div>

<div class="module-demo">
  <h2>🎬 Live Demo</h2>
  <p>We'll build a complete LinkedIn content workflow for a digital marketing agency targeting Indian startups. Starting from a context document, we'll produce 5 LinkedIn posts, a newsletter intro, and 3 email subject lines — in under 25 minutes.</p>
  <p>The key demonstration: how adding examples and output schemas cuts editing time by 60–70%.</p>
</div>

<div class="module-template">
  <h2>📋 Template: Your 10 Reusable Marketing Workflows</h2>
  <div class="template-box">
    <p>For each workflow, document:</p>
    <table style="width:100%;font-size:13px;border-collapse:collapse;">
      <tr style="border-bottom:1px solid rgba(99,102,241,0.2);">
        <th style="padding:8px;text-align:left;">Workflow Name</th>
        <th style="padding:8px;text-align:left;">Trigger (when to use)</th>
        <th style="padding:8px;text-align:left;">Steps</th>
        <th style="padding:8px;text-align:left;">Output Quality Check</th>
      </tr>
      <tr><td style="padding:8px;">Blog Post</td><td style="padding:8px;">New topic brief</td><td style="padding:8px;">Research → Outline → Draft → SEO → Meta</td><td style="padding:8px;">Fact-check all stats</td></tr>
      <tr><td style="padding:8px;">LinkedIn</td><td style="padding:8px;">Weekly content</td><td style="padding:8px;">Topic → Hook → Body → CTA</td><td style="padding:8px;">Brand voice check</td></tr>
      <tr><td style="padding:8px;" colspan="4" style="color:#64748b;">+ 8 more (you define based on your role)</td></tr>
    </table>
  </div>
</div>

<div class="module-assignment">
  <h2>✏️ Assignment</h2>
  <p>Build and document 10 reusable marketing workflows in ChatGPT. For each workflow:</p>
  <ol>
    <li>Write a master prompt template with placeholders for variables</li>
    <li>Test it on 3 real examples</li>
    <li>Note what needed human editing each time</li>
    <li>Refine the prompt based on what consistently went wrong</li>
  </ol>
</div>

<div class="module-qa">
  <h2>🔍 QA: Judging AI Output Quality</h2>
  <ul>
    <li>Does it sound like your brand, or like generic AI?</li>
    <li>Are all facts verifiable?</li>
    <li>Is the output format exactly what was requested?</li>
    <li>Would you publish this without editing? (If yes, your standards may be too low.)</li>
  </ul>
</div>

<div class="module-outcome">
  <h2>💼 Business Outcome</h2>
  <p>After this module: you can produce a week's worth of marketing content in 2–3 hours (vs. a full week). Your prompts produce consistent, on-brand output. You spend your time on judgment and editing, not on first drafts.</p>
</div>

</div>
<?php // Shared styles are in module 1 ?>
