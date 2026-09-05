/**
 * TECHAASVIK HOMEPAGE V2 — Namespaced Controller
 * File: assets/js/homepage-v2.js
 *
 * Initializes ONLY when homepage-specific DOM (#hv2-hero) exists.
 * Does NOT modify or depend on main.js architecture.
 *
 * Dependencies (loaded conditionally via <script> tags in home.php only):
 *   - Three.js r160  (CDN, defer)  — desktop + non-reduced-motion only
 *   - GSAP 3.12 + ScrollTrigger    — non-reduced-motion only
 *
 * main.js continues to handle:
 *   - #heroNewsletter → POST /lead/newsletter
 *   - #auditForm      → POST /lead/audit
 *   - Theme toggle, search, mobile menu, etc.
 */
(function () {
  'use strict';

  /* ═══════════════════════════════════════════════════════════
     DOM GATE — exit immediately if not on homepage
     ═══════════════════════════════════════════════════════════ */
  if (!document.getElementById('hv2-hero')) return;

  /* ═══════════════════════════════════════════════════════════
     FEATURE DETECTION
     ═══════════════════════════════════════════════════════════ */
  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var isMobile = window.innerWidth < 768 || ('ontouchstart' in window && window.innerWidth < 1024);
  var hasThree = typeof THREE !== 'undefined';
  var hasGSAP  = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

  /* ═══════════════════════════════════════════════════════════
     NAMESPACE
     ═══════════════════════════════════════════════════════════ */
  var HV2 = window.TechAasvikHomepageV2 = {};

  /* Internal state */
  var state = {
    canvasRunning: false,
    renderer: null,
    animationId: null
  };

  /* Canvas control references (set by initHeroCanvas) */
  var restartCanvas = null;
  var pauseCanvas   = null;

  /* ═══════════════════════════════════════════════════════════
     1. THREE.JS HERO — AI MARKETING CORE
     
     Premium, architectural visualization of connected marketing
     intelligence. 5 orbital layers: Search, Content, Performance,
     Data, Intelligence.
     
     Design principles:
       • Dark, sophisticated, minimal
       • Architectural / product-design oriented
       • Subtle orbital rings with muted indigo palette
       • No excessive particles, neon, or cyberpunk aesthetics
       • Gentle mouse parallax
     
     Runs ONLY on desktop + non-reduced-motion.
     ═══════════════════════════════════════════════════════════ */
  HV2.initHeroCanvas = function () {
    if (isMobile || prefersReducedMotion || !hasThree) return;

    var container = document.getElementById('hv2-hero-canvas');
    if (!container) return;

    /* ── Scene setup ── */
    var scene  = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(
      40,
      container.clientWidth / container.clientHeight,
      0.1,
      100
    );
    camera.position.set(0, 0.3, 6.5);

    var renderer = new THREE.WebGLRenderer({
      alpha: true,
      antialias: true,
      powerPreference: 'low-power'
    });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
    renderer.setClearColor(0x000000, 0);
    container.appendChild(renderer.domElement);

    state.renderer = renderer;

    /* ── Group for mouse parallax ── */
    var group = new THREE.Group();
    scene.add(group);

    /* ── Core: Subtle wireframe icosahedron ── */
    var coreGeo = new THREE.IcosahedronGeometry(0.5, 1);
    var coreMat = new THREE.MeshBasicMaterial({
      color: 0x818cf8,
      wireframe: true,
      transparent: true,
      opacity: 0.08
    });
    var core = new THREE.Mesh(coreGeo, coreMat);
    group.add(core);

    /* ── Inner ambient sphere ── */
    var ambientGeo = new THREE.SphereGeometry(0.42, 24, 24);
    var ambientMat = new THREE.MeshBasicMaterial({
      color: 0x6366f1,
      transparent: true,
      opacity: 0.025
    });
    group.add(new THREE.Mesh(ambientGeo, ambientMat));

    /* ── 5 Orbital rings ── */
    var layers = [
      { radius: 0.9,  color: 0x6366f1, tilt: [0.4, 0,    0.1]  },
      { radius: 1.2,  color: 0x818cf8, tilt: [0,   0.5,  0.15] },
      { radius: 1.5,  color: 0xa5b4fc, tilt: [0.3, 0.2,  0]    },
      { radius: 1.8,  color: 0x94a3b8, tilt: [0.1, 0,    0.4]  },
      { radius: 2.1,  color: 0x64748b, tilt: [0,   0.3,  0.25] }
    ];

    var rings = [];
    var nodes = [];

    layers.forEach(function (layer, idx) {
      /* Ring curve */
      var pts = [];
      for (var a = 0; a <= 128; a++) {
        var angle = (a / 128) * Math.PI * 2;
        pts.push(new THREE.Vector3(
          Math.cos(angle) * layer.radius,
          Math.sin(angle) * layer.radius,
          0
        ));
      }
      var ringGeo = new THREE.BufferGeometry().setFromPoints(pts);
      var ringMat = new THREE.LineBasicMaterial({
        color: layer.color,
        transparent: true,
        opacity: 0.06 + idx * 0.01
      });
      var ring = new THREE.Line(ringGeo, ringMat);
      ring.rotation.set(layer.tilt[0], layer.tilt[1], layer.tilt[2]);
      group.add(ring);
      rings.push(ring);

      /* Small node on ring */
      var nodeGeo = new THREE.SphereGeometry(0.025, 8, 8);
      var nodeMat = new THREE.MeshBasicMaterial({
        color: layer.color,
        transparent: true,
        opacity: 0.35
      });
      var node = new THREE.Mesh(nodeGeo, nodeMat);
      var startAngle = (Math.PI * 2 / 5) * idx;
      node.position.set(
        Math.cos(startAngle) * layer.radius,
        Math.sin(startAngle) * layer.radius,
        0
      );
      ring.add(node);
      nodes.push({
        mesh: node,
        radius: layer.radius,
        angle: startAngle,
        speed: 0.06 + idx * 0.015
      });
    });

    /* ── Mouse tracking ── */
    var mouseTarget  = { x: 0, y: 0 };
    var mouseCurrent = { x: 0, y: 0 };

    container.addEventListener('mousemove', function (e) {
      var rect = container.getBoundingClientRect();
      mouseTarget.x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
      mouseTarget.y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
    });
    container.addEventListener('mouseleave', function () {
      mouseTarget.x = 0;
      mouseTarget.y = 0;
    });

    /* ── Render loop ── */
    var clock = new THREE.Clock();

    function animate() {
      if (!state.canvasRunning) return;
      state.animationId = requestAnimationFrame(animate);

      var t = clock.getElapsedTime();

      /* Very slow core rotation */
      core.rotation.y = t * 0.05;
      core.rotation.x = t * 0.02;

      /* Subtle ambient pulse */
      ambientMat.opacity = 0.02 + Math.sin(t * 0.4) * 0.008;

      /* Orbit nodes */
      nodes.forEach(function (n) {
        n.angle += n.speed * 0.003;
        n.mesh.position.set(
          Math.cos(n.angle) * n.radius,
          Math.sin(n.angle) * n.radius,
          0
        );
      });

      /* Smooth mouse parallax */
      mouseCurrent.x += (mouseTarget.x - mouseCurrent.x) * 0.02;
      mouseCurrent.y += (mouseTarget.y - mouseCurrent.y) * 0.02;
      group.rotation.y =  mouseCurrent.x * 0.12;
      group.rotation.x = -mouseCurrent.y * 0.08;

      renderer.render(scene, camera);
    }

    state.canvasRunning = true;
    animate();

    /* Store control references for IntersectionObserver */
    restartCanvas = function () {
      if (!state.canvasRunning) {
        state.canvasRunning = true;
        animate();
      }
    };
    pauseCanvas = function () {
      state.canvasRunning = false;
      if (state.animationId) cancelAnimationFrame(state.animationId);
    };

    /* ── Resize (debounced) ── */
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (!container.clientWidth) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
      }, 200);
    });
  };


  /* ═══════════════════════════════════════════════════════════
     2. GSAP SCROLL ANIMATIONS
     Skipped entirely when prefers-reduced-motion is active.
     ═══════════════════════════════════════════════════════════ */
  HV2.initScrollAnimations = function () {
    if (prefersReducedMotion || !hasGSAP) return;

    gsap.registerPlugin(ScrollTrigger);

    /* ── Hero entrance ── */
    gsap.fromTo('.hv2-hero-badge',    { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.2 });
    gsap.fromTo('.hv2-hero-title',    { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.4 });
    gsap.fromTo('.hv2-hero-subtitle', { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.6 });
    gsap.fromTo('.hv2-hero-actions',  { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.8 });

    /* ── Marketing Evolved — scroll comparison ── */
    gsap.from('.hv2-evolved-old .hv2-compare-item', {
      x: -30, opacity: 0, stagger: 0.1,
      scrollTrigger: { trigger: '.hv2-evolved', start: 'top 80%' }
    });
    gsap.from('.hv2-evolved-new .hv2-compare-item', {
      x: 30, opacity: 0, stagger: 0.1,
      scrollTrigger: { trigger: '.hv2-evolved', start: 'top 75%' }
    });

    /* ── Search Evolution — node illumination ── */
    if (!isMobile) {
      var searchTl = gsap.timeline({
        scrollTrigger: {
          trigger: '.hv2-search',
          start: 'top 30%',
          end: 'bottom 60%',
          scrub: 1,
          pin: true,
          pinSpacing: true
        }
      });
      searchTl
        .from('.hv2-search-node:nth-child(1)', { opacity: 0.15, scale: 0.95, duration: 1 })
        .from('.hv2-search-node:nth-child(2)', { opacity: 0.15, scale: 0.95, duration: 1 }, '-=0.5')
        .from('.hv2-search-node:nth-child(3)', { opacity: 0.15, scale: 0.95, duration: 1 }, '-=0.5')
        .from('.hv2-search-node:nth-child(4)', { opacity: 0.15, scale: 0.95, duration: 1 }, '-=0.5');
    } else {
      gsap.from('.hv2-search-node', {
        y: 20, opacity: 0, stagger: 0.12,
        scrollTrigger: { trigger: '.hv2-search', start: 'top 80%' }
      });
    }

    /* ── Performance — card reveal ── */
    gsap.from('.hv2-perf-card', {
      y: 30, opacity: 0, stagger: 0.1,
      scrollTrigger: { trigger: '.hv2-performance', start: 'top 75%' }
    });

    /* ── Content pipeline — sequential stages ── */
    gsap.from('.hv2-pipeline-stage', {
      y: 20, opacity: 0, stagger: 0.15,
      scrollTrigger: { trigger: '.hv2-content-engine', start: 'top 75%' }
    });

    /* ── AI Engine flywheel (CLIMAX) ── */
    if (!isMobile) {
      /* Stages start semi-transparent, illuminate sequentially during scroll */
      gsap.set('.hv2-flywheel-stage', { opacity: 0.3, scale: 0.97 });

      var engineTl = gsap.timeline({
        scrollTrigger: {
          trigger: '.hv2-ai-engine',
          start: 'top 15%',
          end: '+=180%',
          scrub: 1,
          pin: true,
          pinSpacing: true
        }
      });

      var stages = document.querySelectorAll('.hv2-flywheel-stage');
      stages.forEach(function (stage, i) {
        engineTl.to(stage, {
          opacity: 1, scale: 1, duration: 1,
          ease: 'power2.out'
        }, i * 0.8);
      });
    } else {
      gsap.from('.hv2-flywheel-stage', {
        y: 30, opacity: 0, stagger: 0.12,
        scrollTrigger: { trigger: '.hv2-ai-engine', start: 'top 80%' }
      });
    }

    /* ── Services — staggered reveal ── */
    gsap.from('.hv2-service-card', {
      y: 30, opacity: 0, scale: 0.97, stagger: 0.06,
      scrollTrigger: { trigger: '.hv2-services', start: 'top 80%' }
    });

    /* ── Portfolio — slide up ── */
    gsap.from('.hv2-case-card', {
      y: 40, opacity: 0, stagger: 0.12,
      scrollTrigger: { trigger: '.hv2-portfolio', start: 'top 80%' }
    });

    /* ── Learning ── */
    gsap.from('.hv2-learning .hv2-tabs', {
      y: 20, opacity: 0,
      scrollTrigger: { trigger: '.hv2-learning', start: 'top 80%' }
    });

    /* ── Ebook placeholder ── */
    gsap.from('.hv2-ebook-placeholder', {
      y: 30, opacity: 0,
      scrollTrigger: { trigger: '.hv2-ebook', start: 'top 75%' }
    });

    /* ── Final CTA ── */
    gsap.from('.hv2-cta-content', {
      y: 30, opacity: 0,
      scrollTrigger: { trigger: '.hv2-final-cta', start: 'top 85%' }
    });
  };


  /* ═══════════════════════════════════════════════════════════
     3. INTERSECTION OBSERVER — WebGL sleep/wake
     Pauses the Three.js render loop when the hero canvas
     scrolls out of view. CPU/GPU usage drops to 0%.
     ═══════════════════════════════════════════════════════════ */
  HV2.initCanvasObserver = function () {
    var canvas = document.getElementById('hv2-hero-canvas');
    if (!canvas || !state.renderer) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          if (restartCanvas) restartCanvas();
        } else {
          if (pauseCanvas) pauseCanvas();
        }
      });
    }, { threshold: 0.05 });

    observer.observe(canvas);
  };


  /* ═══════════════════════════════════════════════════════════
     4. TAB SWITCHING — Learning Hub
     Pure JS, no library dependency.
     ═══════════════════════════════════════════════════════════ */
  HV2.initTabs = function () {
    var tabBtns   = document.querySelectorAll('.hv2-tab-btn');
    var tabPanels = document.querySelectorAll('.hv2-tab-panel');
    if (!tabBtns.length) return;

    tabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = btn.getAttribute('data-tab');

        tabBtns.forEach(function (b) { b.classList.remove('active'); });
        tabPanels.forEach(function (p) { p.classList.remove('active'); });

        btn.classList.add('active');
        var panel = document.getElementById(target);
        if (panel) panel.classList.add('active');
      });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     5. SERVICE CARD TILT — Desktop only, non-reduced-motion
     Subtle 3D perspective tilt on mouse move.
     ═══════════════════════════════════════════════════════════ */
  HV2.initCardTilt = function () {
    if (isMobile || prefersReducedMotion) return;

    document.querySelectorAll('.hv2-service-card').forEach(function (card) {
      card.addEventListener('pointermove', function (e) {
        var rect = card.getBoundingClientRect();
        var x = (e.clientX - rect.left) / rect.width - 0.5;
        var y = (e.clientY - rect.top) / rect.height - 0.5;
        card.style.transform =
          'perspective(600px) rotateY(' + (x * 5) + 'deg) rotateX(' +
          (-y * 5) + 'deg) translateY(-4px)';
      });

      card.addEventListener('pointerleave', function () {
        card.style.transform = '';
      });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     INITIALIZATION
     Waits for window.load to ensure all deferred scripts
     (Three.js, GSAP) have been parsed and executed.
     ═══════════════════════════════════════════════════════════ */
  function init() {
    /* Re-check library availability after load */
    hasThree = typeof THREE !== 'undefined';
    hasGSAP  = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

    HV2.initHeroCanvas();
    HV2.initScrollAnimations();
    HV2.initCanvasObserver();
    HV2.initTabs();
    HV2.initCardTilt();
  }

  if (document.readyState === 'complete') {
    init();
  } else {
    window.addEventListener('load', init);
  }

})();
