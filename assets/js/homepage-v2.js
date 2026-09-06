/**
 * TECHAASVIK HOMEPAGE V7.1 — Cinematic 3D Marketing Architecture
 * 
 * ONE PERSISTENT AI MARKETING CORE
 * + Kinetic Typography
 * + Scroll-Driven Transformation
 * + Meaningful Signals
 * + Cinematic Camera
 * + Continuous Visual Narrative
 *
 * Architecture:
 *   - Single fixed-position WebGL canvas spanning entire page
 *   - ONE Core (Icosahedron) that evolves: dormant → awakening → connected → intelligent → complete
 *   - Camera lerps between section-specific positions
 *   - Local sectionProgress (0→1) per section, all reversible
 *   - Engine markers (poles, rings, loop) exist from init, hidden until Engine section
 *
 * Preserves: CMS, routes, forms (#auditForm, #heroNewsletter), payments, auth, database.
 */
(function () {
  'use strict';

  /* ═══════════════════════════════════════════════════════════
     GATE + FEATURE DETECTION + NAMESPACE
     ═══════════════════════════════════════════════════════════ */
  if (!document.getElementById('hv2-hero')) return;

  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var isMobile = window.innerWidth < 768 || ('ontouchstart' in window && window.innerWidth < 1024);
  var hasThree, hasGSAP;

  var HV2 = window.TechAasvikHomepageV2 = {};

  /* ═══════════════════════════════════════════════════════════
     UTILITIES
     ═══════════════════════════════════════════════════════════ */
  function lerp(a, b, t) { return a + (b - a) * t; }
  function clamp(v, mn, mx) { return Math.max(mn, Math.min(mx, v)); }
  function smoothstep(e0, e1, x) {
    var t = clamp((x - e0) / (e1 - e0), 0, 1);
    return t * t * (3 - 2 * t);
  }

  function createLabelSprite(text, color, size) {
    var c = document.createElement('canvas');
    var ctx = c.getContext('2d');
    c.width = 256; c.height = 64;
    ctx.clearRect(0, 0, 256, 64);
    ctx.font = '600 24px Inter, -apple-system, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillStyle = color || 'rgba(165,180,252,0.85)';
    ctx.fillText(text, 128, 32);
    var tex = new THREE.CanvasTexture(c);
    tex.needsUpdate = true;
    var mat = new THREE.SpriteMaterial({ map: tex, transparent: true, opacity: 0.7, depthTest: false, depthWrite: false });
    var sprite = new THREE.Sprite(mat);
    sprite.scale.set(size || 0.65, 0.16, 1);
    return sprite;
  }

  /* ═══════════════════════════════════════════════════════════
     1. PERSISTENT 3D SCENE
     One renderer, one scene, one camera, one Core.
     All objects created at init. Engine markers hidden until needed.
     ═══════════════════════════════════════════════════════════ */
  HV2.Scene = (function () {
    var container, scene, camera, renderer, animationId;
    var isRunning = false;
    var group, core, innerKernel, centralLight;
    var nodes = [];
    var clock = new THREE.Clock();

    // Engine-specific (hidden until Engine section)
    var topPoleMesh, bottomPoleMesh, upperRingMesh, lowerRingMesh;
    var loopCurve, loopLineMesh, signalPulseMesh;
    var radialNodes = [];

    // Mouse tracking
    var mouseTarget = { x: 0, y: 0 };
    var mouseCurrent = { x: 0, y: 0 };

    // Camera targets (smoothly lerped)
    var camT = { x: 0, y: 0.3, z: 6.5 };
    var camLookT = { x: 0, y: 0, z: 0 };
    var camLookC = { x: 0, y: 0, z: 0 };

    // Core visual targets
    var coreT = { emissive: 0.25, opacity: 0.20, kernelOp: 0.12, light: 0.2, scale: 1.0 };

    // Engine marker visibility (0=hidden, 1=fully visible)
    var engVis = 0, engVisCur = 0;

    // Signal photon state
    var sigActive = false, sigPos = 0;

    // Node definitions: 6 marketing channels
    var nodeDefs = [
      { id: 'seo',       label: 'SEO',       color: 0x6366f1, dormant: [ 1.30,  0.45, -0.20], docked: [ 0.55,  0.20,  0.10] },
      { id: 'aeo',       label: 'AEO',       color: 0x8b5cf6, dormant: [ 1.50, -0.50,  0.30], docked: [ 0.50, -0.25,  0.15] },
      { id: 'geo',       label: 'GEO',       color: 0x8b5cf6, dormant: [ 1.65,  0.30,  0.50], docked: [ 0.45,  0.15,  0.35] },
      { id: 'content',   label: 'Content',   color: 0x6366f1, dormant: [-1.80,  0.65, -0.30], docked: [-0.55,  0.22, -0.10] },
      { id: 'ads',       label: 'Ads',       color: 0x06b6d4, dormant: [-1.90, -0.60,  0.40], docked: [-0.50, -0.20,  0.15] },
      { id: 'analytics', label: 'Analytics', color: 0x10b981, dormant: [ 0.00, -1.80, -0.40], docked: [ 0.00, -0.55,  0.00] }
    ];

    function init() {
      if (isMobile || reducedMotion || !hasThree) return;
      container = document.getElementById('hv2-persistent-canvas');
      if (!container) return;

      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(38, window.innerWidth / window.innerHeight, 0.1, 50);
      camera.position.set(0, 0.3, 6.5);
      camera.lookAt(0, 0, 0);

      renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
      renderer.setClearColor(0x000000, 0);
      container.appendChild(renderer.domElement);

      group = new THREE.Group();
      group.position.x = 2.5; // Core offset to right — camera stays at origin
      scene.add(group);

      /* ── Core Icosahedron ── */
      core = new THREE.Mesh(
        new THREE.IcosahedronGeometry(0.52, 0),
        new THREE.MeshStandardMaterial({
          color: 0x6366f1, roughness: 0.25, metalness: 0.85,
          emissive: 0x4f46e5, emissiveIntensity: 0.25,
          wireframe: true, transparent: true, opacity: 0.20
        })
      );
      group.add(core);

      /* ── Inner Kernel ── */
      innerKernel = new THREE.Mesh(
        new THREE.SphereGeometry(0.36, 16, 16),
        new THREE.MeshBasicMaterial({
          color: 0x8b5cf6, transparent: true, opacity: 0.12,
          blending: THREE.AdditiveBlending, depthWrite: false
        })
      );
      group.add(innerKernel);

      /* ── Central Light ── */
      centralLight = new THREE.PointLight(0x8b5cf6, 0.2, 5.0);
      group.add(centralLight);

      /* ── Scene Lights ── */
      var kl = new THREE.DirectionalLight(0xffffff, 1.1); kl.position.set(3, 5, 4); scene.add(kl);
      var fl = new THREE.DirectionalLight(0x6366f1, 0.4); fl.position.set(-4, -2, -2); scene.add(fl);
      var rl = new THREE.PointLight(0x06b6d4, 1.0, 8.0); rl.position.set(0, 3, -3); scene.add(rl);
      scene.add(new THREE.AmbientLight(0x080d16, 0.4));

      /* ── Satellite Nodes + Synaptic Lines ── */
      nodeDefs.forEach(function (def) {
        var ng = new THREE.Group();
        var mesh = new THREE.Mesh(
          new THREE.OctahedronGeometry(0.055, 0),
          new THREE.MeshStandardMaterial({
            color: def.color, emissive: def.color, emissiveIntensity: 0.35,
            roughness: 0.3, metalness: 0.8
          })
        );
        ng.add(mesh);
        var sprite = createLabelSprite(def.label, 'rgba(199,210,254,0.85)', 0.55);
        sprite.position.set(0, 0.12, 0);
        ng.add(sprite);
        ng.position.set(def.dormant[0], def.dormant[1], def.dormant[2]);
        group.add(ng);

        var lGeo = new THREE.BufferGeometry().setFromPoints([
          new THREE.Vector3(0, 0, 0),
          new THREE.Vector3(def.dormant[0], def.dormant[1], def.dormant[2])
        ]);
        var line = new THREE.Line(lGeo, new THREE.LineBasicMaterial({
          color: 0x818cf8, transparent: true, opacity: 0, depthWrite: false
        }));
        group.add(line);

        nodes.push({
          group: ng, mesh: mesh, sprite: sprite, def: def, line: line,
          tgt: { x: def.dormant[0], y: def.dormant[1], z: def.dormant[2] },
          emTgt: 0.35, synOpTgt: 0, scaleTgt: 1.0
        });
      });

      /* ── Engine Markers (invisible until Engine section) ── */
      var pGeo = new THREE.OctahedronGeometry(0.065, 0);

      // Top pole (Data & Intelligence)
      topPoleMesh = new THREE.Mesh(pGeo, new THREE.MeshStandardMaterial({
        color: 0x38bdf8, emissive: 0x06b6d4, emissiveIntensity: 0.3,
        roughness: 0.2, metalness: 0.9, transparent: true, opacity: 0
      }));
      topPoleMesh.position.set(0, 0.68, 0);
      group.add(topPoleMesh);

      // Upper ring (Strategy)
      var rPts = [];
      for (var a = 0; a <= 64; a++) {
        var th = (a / 64) * Math.PI * 2;
        rPts.push(new THREE.Vector3(Math.cos(th) * 0.62, 0.28, Math.sin(th) * 0.62));
      }
      upperRingMesh = new THREE.Line(
        new THREE.BufferGeometry().setFromPoints(rPts),
        new THREE.LineBasicMaterial({ color: 0x8b5cf6, transparent: true, opacity: 0 })
      );
      group.add(upperRingMesh);

      // Radial nodes (Execution) — 3 equidistant
      [0, Math.PI * 2 / 3, Math.PI * 4 / 3].forEach(function (ang) {
        var bx = Math.cos(ang) * 0.95, bz = Math.sin(ang) * 0.95;
        var bMesh = new THREE.Mesh(
          new THREE.OctahedronGeometry(0.045, 0),
          new THREE.MeshStandardMaterial({ color: 0x818cf8, emissive: 0x6366f1, emissiveIntensity: 0.2, transparent: true, opacity: 0 })
        );
        bMesh.position.set(bx, 0, bz);
        group.add(bMesh);
        var bLine = new THREE.Line(
          new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(0, 0, 0), new THREE.Vector3(bx, 0, bz)]),
          new THREE.LineBasicMaterial({ color: 0x6366f1, transparent: true, opacity: 0 })
        );
        group.add(bLine);
        radialNodes.push({ mesh: bMesh, line: bLine });
      });

      // Lower ring (Measurement)
      var lrP = [];
      for (var b = 0; b <= 64; b++) {
        var th2 = (b / 64) * Math.PI * 2;
        lrP.push(new THREE.Vector3(Math.cos(th2) * 0.62, -0.28, Math.sin(th2) * 0.62));
      }
      lowerRingMesh = new THREE.Line(
        new THREE.BufferGeometry().setFromPoints(lrP),
        new THREE.LineBasicMaterial({ color: 0x10b981, transparent: true, opacity: 0 })
      );
      group.add(lowerRingMesh);

      // Bottom pole (Learning)
      bottomPoleMesh = new THREE.Mesh(pGeo.clone(), new THREE.MeshStandardMaterial({
        color: 0x8b5cf6, emissive: 0x8b5cf6, emissiveIntensity: 0.3,
        roughness: 0.2, metalness: 0.9, transparent: true, opacity: 0
      }));
      bottomPoleMesh.position.set(0, -0.68, 0);
      group.add(bottomPoleMesh);

      // Return loop curve (Learning → Data)
      loopCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(0, -0.68, 0),
        new THREE.Vector3(0.85, -0.45, 0.35),
        new THREE.Vector3(1.10, 0, 0.45),
        new THREE.Vector3(0.85, 0.45, 0.35),
        new THREE.Vector3(0, 0.68, 0)
      ]);
      loopLineMesh = new THREE.Line(
        new THREE.BufferGeometry().setFromPoints(loopCurve.getPoints(64)),
        new THREE.LineBasicMaterial({ color: 0x8b5cf6, transparent: true, opacity: 0, depthWrite: false })
      );
      group.add(loopLineMesh);

      // Signal photon
      signalPulseMesh = new THREE.Mesh(
        new THREE.SphereGeometry(0.05, 16, 16),
        new THREE.MeshBasicMaterial({ color: 0x38bdf8, transparent: true, opacity: 0, blending: THREE.AdditiveBlending, depthWrite: false })
      );
      group.add(signalPulseMesh);

      /* ── Event Listeners ── */
      window.addEventListener('resize', onResize);
      window.addEventListener('mousemove', function (e) {
        mouseTarget.x = (e.clientX / window.innerWidth - 0.5) * 2;
        mouseTarget.y = (e.clientY / window.innerHeight - 0.5) * 2;
      });

      start();
    }

    function onResize() {
      if (!renderer || !camera) return;
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }

    /* ── Public API for choreography modules ── */
    function setCamera(x, y, z, lx, ly, lz) {
      camT.x = x; camT.y = y; camT.z = z;
      if (lx !== undefined) { camLookT.x = lx; camLookT.y = ly; camLookT.z = lz; }
    }
    function setCoreVisuals(em, op, kOp, li, sc) {
      coreT.emissive = em; coreT.opacity = op; coreT.kernelOp = kOp; coreT.light = li;
      if (sc !== undefined) coreT.scale = sc;
    }
    function setNode(i, x, y, z, em, synOp, sc) {
      if (i < 0 || i >= nodes.length) return;
      var n = nodes[i];
      n.tgt.x = x; n.tgt.y = y; n.tgt.z = z;
      if (em !== undefined) n.emTgt = em;
      if (synOp !== undefined) n.synOpTgt = synOp;
      if (sc !== undefined) n.scaleTgt = sc;
    }
    function setAllNodesDocked(t) {
      nodes.forEach(function (n, i) {
        var d = n.def.dormant, dk = n.def.docked;
        setNode(i,
          d[0] + (dk[0] - d[0]) * t,
          d[1] + (dk[1] - d[1]) * t,
          d[2] + (dk[2] - d[2]) * t,
          0.35 + t * 0.45,
          t * 0.75,
          1.0
        );
      });
    }
    function setEngVis(v) { engVis = v; }
    function setSignal(active, pos) { sigActive = active; sigPos = pos || 0; }

    /* ── Render Loop ── */
    function animate() {
      if (!isRunning) return;
      animationId = requestAnimationFrame(animate);

      var t = clock.getElapsedTime();
      var L = 0.045; // lerp speed

      // Core ambient rotation
      core.rotation.y = t * 0.04;
      core.rotation.x = t * 0.02;
      innerKernel.scale.setScalar(coreT.scale * (1.0 + Math.sin(t * 1.5) * 0.04));

      // Camera smooth lerp
      camera.position.x = lerp(camera.position.x, camT.x, L);
      camera.position.y = lerp(camera.position.y, camT.y, L);
      camera.position.z = lerp(camera.position.z, camT.z, L);
      camLookC.x = lerp(camLookC.x, camLookT.x, L);
      camLookC.y = lerp(camLookC.y, camLookT.y, L);
      camLookC.z = lerp(camLookC.z, camLookT.z, L);
      camera.lookAt(camLookC.x, camLookC.y, camLookC.z);

      // Mouse parallax
      mouseCurrent.x = lerp(mouseCurrent.x, mouseTarget.x, 0.03);
      mouseCurrent.y = lerp(mouseCurrent.y, mouseTarget.y, 0.03);
      group.rotation.y += mouseCurrent.x * 0.003;
      group.rotation.x += -mouseCurrent.y * 0.002;

      // Core visual lerp
      core.material.emissiveIntensity = lerp(core.material.emissiveIntensity, coreT.emissive, 0.06);
      core.material.opacity = lerp(core.material.opacity, coreT.opacity, 0.06);
      innerKernel.material.opacity = lerp(innerKernel.material.opacity, coreT.kernelOp, 0.06);
      centralLight.intensity = lerp(centralLight.intensity, coreT.light, 0.06);

      // Node lerp
      nodes.forEach(function (n) {
        n.group.position.x = lerp(n.group.position.x, n.tgt.x, 0.055);
        n.group.position.y = lerp(n.group.position.y, n.tgt.y, 0.055);
        n.group.position.z = lerp(n.group.position.z, n.tgt.z, 0.055);
        n.mesh.material.emissiveIntensity = lerp(n.mesh.material.emissiveIntensity, n.emTgt, 0.06);
        // Update synapse line endpoint
        var pa = n.line.geometry.attributes.position;
        pa.setXYZ(1, n.group.position.x, n.group.position.y, n.group.position.z);
        pa.needsUpdate = true;
        n.line.material.opacity = lerp(n.line.material.opacity, n.synOpTgt, 0.06);
        var cs = n.group.scale.x;
        n.group.scale.setScalar(lerp(cs, n.scaleTgt, 0.06));
      });

      // Engine markers lerp
      engVisCur = lerp(engVisCur, engVis, 0.05);
      topPoleMesh.material.opacity = engVisCur;
      bottomPoleMesh.material.opacity = engVisCur;
      upperRingMesh.material.opacity = engVisCur * 0.6;
      lowerRingMesh.material.opacity = engVisCur * 0.5;
      radialNodes.forEach(function (rn) {
        rn.mesh.material.opacity = engVisCur;
        rn.line.material.opacity = engVisCur * 0.5;
      });
      loopLineMesh.material.opacity = engVisCur * 0.3;
      if (engVisCur > 0.01) {
        upperRingMesh.rotation.y = -t * 0.05;
        lowerRingMesh.rotation.y = t * 0.05;
      }

      // Signal photon
      if (sigActive && loopCurve) {
        signalPulseMesh.material.opacity = lerp(signalPulseMesh.material.opacity, 0.95, 0.1);
        var pt = loopCurve.getPointAt(clamp(sigPos, 0.001, 0.999));
        signalPulseMesh.position.copy(pt);
      } else {
        signalPulseMesh.material.opacity = lerp(signalPulseMesh.material.opacity, 0, 0.08);
      }

      renderer.render(scene, camera);
    }

    function start() { if (!isRunning) { isRunning = true; clock.start(); animate(); } }
    function pause() { isRunning = false; if (animationId) cancelAnimationFrame(animationId); }

    return {
      init: init, start: start, pause: pause,
      setCamera: setCamera, setCoreVisuals: setCoreVisuals,
      setNode: setNode, setAllNodesDocked: setAllNodesDocked,
      setEngVis: setEngVis, setSignal: setSignal,
      getTopPole: function () { return topPoleMesh; },
      getBottomPole: function () { return bottomPoleMesh; },
      getRadials: function () { return radialNodes; },
      getUpperRing: function () { return upperRingMesh; },
      getLowerRing: function () { return lowerRingMesh; },
      getLoopLine: function () { return loopLineMesh; },
      getCentralLight: function () { return centralLight; },
      getCore: function () { return core; },
      getKernel: function () { return innerKernel; }
    };
  })();


  /* ═══════════════════════════════════════════════════════════
     2. TYPOGRAPHY SYSTEM
     T1: Kinetic Words — per-word opacity/Y/blur/spacing
     T2: Morph — FRAGMENTED → CONNECTED → INTELLIGENT
     T3: Progressive Build — SEO + AEO + GEO + AI SEARCH
     T4: Mask Reveal — clip-path editorial wipe
     ═══════════════════════════════════════════════════════════ */
  HV2.Typography = (function () {

    /* T1: Kinetic Words — hero title progression */
    function updateHeroWords(p) {
      var words = document.querySelectorAll('#hv2HeroTitle .hv2-word');
      if (!words.length) return;

      words.forEach(function (w) {
        var state = w.getAttribute('data-hero');
        var wordP = 0;

        if (!state || state === 'h0') {
          // Proposition words: visible from 0, sharpen by 0.12
          wordP = smoothstep(0, 0.12, p);
        } else if (state === 'h2') {
          wordP = smoothstep(0.18, 0.30, p);
        } else if (state === 'h3') {
          wordP = smoothstep(0.30, 0.42, p);
        } else if (state === 'h4') {
          wordP = smoothstep(0.42, 0.54, p);
        } else if (state === 'h5') {
          wordP = smoothstep(0.54, 0.66, p);
        } else if (state === 'h6') {
          wordP = smoothstep(0.66, 0.78, p);
        }

        w.style.opacity = String(0.08 + wordP * 0.92);
        w.style.transform = 'translateY(' + ((1 - wordP) * 18) + 'px)';
        w.style.filter = 'blur(' + ((1 - wordP) * 3) + 'px)';
      });

      // Resolution text — H7
      var res = document.getElementById('hv2HeroResolution');
      if (res) {
        var rP = smoothstep(0.80, 0.92, p);
        res.style.opacity = String(rP);
        res.style.transform = 'translateY(' + ((1 - rP) * 20) + 'px)';
        res.style.filter = 'blur(' + ((1 - rP) * 4) + 'px)';
      }

      // Subtitle + actions fade out as capability words take over
      var sub = document.querySelector('.hv2-hero-subtitle');
      var acts = document.querySelector('.hv2-hero-actions');
      if (sub) {
        var subOp = p < 0.5 ? 1 : Math.max(0, 1 - (p - 0.5) * 3);
        sub.style.opacity = String(subOp);
      }
      if (acts) {
        var actOp = p < 0.55 ? 1 : Math.max(0, 1 - (p - 0.55) * 3);
        acts.style.opacity = String(actOp);
      }
    }

    /* T2: Word Morph — FRAGMENTED → CONNECTED → INTELLIGENT */
    function updateMorph(p) {
      var states = document.querySelectorAll('#hv2EvolvedMorph .hv2-morph-state');
      if (!states.length) return;

      states.forEach(function (s) {
        var type = s.getAttribute('data-morph');
        var sOp = 0, sSpacing = '0em', sBlur = 0, sY = 0;

        if (type === 'fragmented') {
          if (p < 0.33) {
            sOp = smoothstep(0, 0.15, p);
            sSpacing = (0.3 - p * 0.6) + 'em'; // letters spread
            sBlur = 0;
            sY = 0;
          } else {
            sOp = Math.max(0, 1 - (p - 0.33) * 4);
            sSpacing = '-0.05em';
            sY = -10;
          }
        } else if (type === 'connected') {
          if (p >= 0.28 && p < 0.66) {
            sOp = smoothstep(0.28, 0.40, p);
            sSpacing = ((0.40 - Math.min(p, 0.40)) * 0.8) + 'em';
          } else if (p >= 0.66) {
            sOp = Math.max(0, 1 - (p - 0.66) * 4);
            sY = -10;
          }
        } else if (type === 'intelligent') {
          sOp = smoothstep(0.60, 0.75, p);
        }

        s.style.opacity = String(clamp(sOp, 0, 1));
        s.style.letterSpacing = sSpacing;
        s.style.filter = sBlur > 0 ? 'blur(' + sBlur + 'px)' : '';
        s.style.transform = 'translateY(' + sY + 'px)';
      });
    }

    /* T3: Progressive Build — SEO + AEO + GEO + AI SEARCH */
    function updateProgressiveBuild(p) {
      var items = document.querySelectorAll('#hv2SearchBuild .hv2-prog-item, #hv2SearchBuild .hv2-prog-plus');
      if (!items.length) return;

      items.forEach(function (el) {
        var stage = parseInt(el.getAttribute('data-stage'), 10);
        var threshold = stage * 0.22;
        var itemP = smoothstep(threshold, threshold + 0.18, p);
        el.style.opacity = String(0.05 + itemP * 0.95);
        el.style.transform = 'translateX(' + ((1 - itemP) * 20) + 'px)';
        el.style.filter = 'blur(' + ((1 - itemP) * 2) + 'px)';
      });
    }

    /* T4: Mask Reveal — editorial wipe for engine steps */
    function updateMaskReveal(el, progress) {
      if (!el) return;
      el.style.clipPath = 'inset(0 ' + ((1 - progress) * 100) + '% 0 0)';
    }

    function init() {
      // Set initial states for hero words
      var words = document.querySelectorAll('#hv2HeroTitle .hv2-word');
      words.forEach(function (w) {
        w.style.opacity = '0.08';
        w.style.filter = 'blur(3px)';
        w.style.transition = 'none';
      });
      var res = document.getElementById('hv2HeroResolution');
      if (res) { res.style.opacity = '0'; }
    }

    return {
      init: init,
      updateHeroWords: updateHeroWords,
      updateMorph: updateMorph,
      updateProgressiveBuild: updateProgressiveBuild,
      updateMaskReveal: updateMaskReveal
    };
  })();


  /* ═══════════════════════════════════════════════════════════
     3. HERO CHOREOGRAPHY (H0 → H7)
     Core: dormant → awakening → nodes migrate → connected → active
     Camera: wide push-in
     Typography: cinematic word progression
     ═══════════════════════════════════════════════════════════ */
  HV2.Hero = (function () {
    function update(p) {
      // Typography
      HV2.Typography.updateHeroWords(p);

      // 3D Scene — skip if not available
      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: push-in from wide to medium
      var camZ = lerp(6.5, 5.2, smoothstep(0, 0.7, p));
      var camX = lerp(0.8, 0.5, smoothstep(0, 0.8, p));
      var camY = lerp(0.3, 0.15, smoothstep(0, 0.6, p));
      HV2.Scene.setCamera(camX, camY, camZ, lerp(0.3, 0.1, p), 0, 0);

      // H0-H1 (0.00-0.18): Core awakens
      var wakeT = smoothstep(0, 0.18, p);
      // H2 (0.18-0.30): SEO node migrates
      var seoT = smoothstep(0.18, 0.30, p);
      // H3 (0.30-0.42): AEO + GEO migrate
      var triadT = smoothstep(0.30, 0.42, p);
      // H4 (0.42-0.54): Content + Ads + Analytics converge
      var convT = smoothstep(0.42, 0.54, p);
      // H5 (0.54-0.66): Connection — all nodes dock
      var connT = smoothstep(0.54, 0.66, p);
      // H6 (0.66-0.78): Intelligence activates
      var intelT = smoothstep(0.66, 0.78, p);
      // H7 (0.78-1.00): Hold — full state
      var holdT = smoothstep(0.78, 0.92, p);

      // Core visuals: dormant → full
      var totalActivity = (wakeT + seoT + triadT + convT + connT + intelT) / 6;
      HV2.Scene.setCoreVisuals(
        0.25 + totalActivity * 0.55,           // emissive
        0.20 + totalActivity * 0.30,           // opacity
        0.12 + totalActivity * 0.18,           // kernel
        0.2 + totalActivity * 0.8 + holdT * 0.5, // light
        1.0                                     // scale
      );

      // Node positions: migrate from dormant toward docked
      var defs = HV2.Scene; // reference
      // SEO
      var nd = nodes_dormant_docked;
      setNodeProgress(0, seoT * connT > 0 ? Math.max(seoT, connT) : seoT);
      setNodeProgress(1, triadT * connT > 0 ? Math.max(triadT, connT) : triadT);
      setNodeProgress(2, triadT * connT > 0 ? Math.max(triadT, connT) : triadT);
      setNodeProgress(3, convT * connT > 0 ? Math.max(convT, connT) : convT);
      setNodeProgress(4, convT * connT > 0 ? Math.max(convT, connT) : convT);
      setNodeProgress(5, convT * connT > 0 ? Math.max(convT, connT) : convT);

      // Engine markers hidden during hero
      HV2.Scene.setEngVis(0);
      HV2.Scene.setSignal(false, 0);
    }

    // Helper: interpolate node between dormant and docked
    function setNodeProgress(i, t) {
      var def = HV2.Scene.getNodeDefs ? null : null; // fallback
      // Use the nodeDefs from Scene closure via direct access
      var d = nodeDefsRef[i].dormant;
      var dk = nodeDefsRef[i].docked;
      HV2.Scene.setNode(i,
        d[0] + (dk[0] - d[0]) * t,
        d[1] + (dk[1] - d[1]) * t,
        d[2] + (dk[2] - d[2]) * t,
        0.35 + t * 0.45,  // emissive
        t * 0.75,          // synapse opacity
        1.0                // scale
      );
    }

    return { update: update };
  })();

  // Local reference to node definitions for choreography helpers
  var nodeDefsRef = [
    { dormant: [ 1.30,  0.45, -0.20], docked: [ 0.55,  0.20,  0.10] },
    { dormant: [ 1.50, -0.50,  0.30], docked: [ 0.50, -0.25,  0.15] },
    { dormant: [ 1.65,  0.30,  0.50], docked: [ 0.45,  0.15,  0.35] },
    { dormant: [-1.80,  0.65, -0.30], docked: [-0.55,  0.22, -0.10] },
    { dormant: [-1.90, -0.60,  0.40], docked: [-0.50, -0.20,  0.15] },
    { dormant: [ 0.00, -1.80, -0.40], docked: [ 0.00, -0.55,  0.00] }
  ];

  function setNodeProgress(i, t) {
    var d = nodeDefsRef[i].dormant;
    var dk = nodeDefsRef[i].docked;
    HV2.Scene.setNode(i,
      d[0] + (dk[0] - d[0]) * t,
      d[1] + (dk[1] - d[1]) * t,
      d[2] + (dk[2] - d[2]) * t,
      0.35 + t * 0.45,
      t * 0.75,
      1.0
    );
  }

  // Fix Hero.update to use the global helper
  HV2.Hero.update = function (p) {
    HV2.Typography.updateHeroWords(p);
    if (isMobile || reducedMotion || !hasThree) return;

    var camZ = lerp(6.5, 5.2, smoothstep(0, 0.7, p));
    var camX = lerp(0, 0.3, smoothstep(0, 0.8, p));
    var camY = lerp(0.3, 0.15, smoothstep(0, 0.6, p));
    HV2.Scene.setCamera(camX, camY, camZ, 0, 0, 0);

    var wakeT = smoothstep(0, 0.18, p);
    var seoT = smoothstep(0.18, 0.30, p);
    var triadT = smoothstep(0.30, 0.42, p);
    var convT = smoothstep(0.42, 0.54, p);
    var connT = smoothstep(0.54, 0.66, p);
    var intelT = smoothstep(0.66, 0.78, p);
    var holdT = smoothstep(0.78, 0.92, p);

    var act = (wakeT + seoT + triadT + convT + connT + intelT) / 6;
    HV2.Scene.setCoreVisuals(
      0.25 + act * 0.55,
      0.20 + act * 0.30,
      0.12 + act * 0.18,
      0.2 + act * 0.8 + holdT * 0.5,
      1.0
    );

    setNodeProgress(0, Math.max(seoT, connT));
    setNodeProgress(1, Math.max(triadT, connT));
    setNodeProgress(2, Math.max(triadT, connT));
    setNodeProgress(3, Math.max(convT, connT));
    setNodeProgress(4, Math.max(convT, connT));
    setNodeProgress(5, Math.max(convT, connT));

    HV2.Scene.setEngVis(0);
    HV2.Scene.setSignal(false, 0);
  };


  /* ═══════════════════════════════════════════════════════════
     4. EVOLUTION CHOREOGRAPHY
     FRAGMENTED → CONNECTED → INTELLIGENT
     Nodes spread → converge → signals begin
     ═══════════════════════════════════════════════════════════ */
  HV2.Evolution = (function () {
    function update(p) {
      // Typography morph
      HV2.Typography.updateMorph(p);

      // DOM items
      var oldItems = document.querySelectorAll('.hv2-evolved-old .hv2-compare-item');
      var newItems = document.querySelectorAll('.hv2-evolved-new .hv2-compare-item');
      var arrow = document.querySelector('.hv2-evolved-arrow');

      oldItems.forEach(function (el) {
        el.style.opacity = p > 0.4 ? '0.4' : '0.8';
      });
      newItems.forEach(function (el, i) {
        var th = 0.25 + i * 0.12;
        var itemP = smoothstep(th, th + 0.1, p);
        el.style.opacity = String(0.3 + itemP * 0.7);
        el.style.transform = 'translateX(' + ((1 - itemP) * 12) + 'px)';
      });
      if (arrow) arrow.style.color = p > 0.5 ? '#818cf8' : '';

      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: slightly reposition to show node spatial relationships
      HV2.Scene.setCamera(
        lerp(0.3, 0.5, p), lerp(0.15, 0.1, p), lerp(5.2, 4.8, p),
        0, 0, 0
      );

      // FRAGMENTED state (p < 0.33): nodes spread outward from docked
      // CONNECTED state (0.33-0.66): nodes converge to docked
      // INTELLIGENT state (0.66-1): nodes settled, Core glows stronger
      var fragT = 1 - smoothstep(0, 0.33, p); // 1 at start, 0 at 0.33
      var connectedT = smoothstep(0.33, 0.60, p);
      var intellT = smoothstep(0.60, 0.85, p);

      for (var i = 0; i < 6; i++) {
        var d = nodeDefsRef[i].dormant;
        var dk = nodeDefsRef[i].docked;
        // Fragmented: halfway between dormant and docked
        var fragPos = 0.5 + fragT * 0.3; // starts at 0.8 (spread), moves to 0.5
        var finalDock = lerp(fragPos, 1.0, connectedT);
        setNodeProgress(i, finalDock);
      }

      // Core intensity builds through evolution
      HV2.Scene.setCoreVisuals(
        0.45 + connectedT * 0.25 + intellT * 0.2,
        0.35 + connectedT * 0.15,
        0.22 + intellT * 0.1,
        0.6 + connectedT * 0.4 + intellT * 0.4,
        1.0
      );

      HV2.Scene.setEngVis(0);
      HV2.Scene.setSignal(false, 0);
    }
    return { update: update };
  })();


  /* ═══════════════════════════════════════════════════════════
     5. SEARCH CHOREOGRAPHY (S0 → S4)
     Progressive: SEO → AEO → GEO → AI SEARCH
     Each capability connects to the persistent Core
     ═══════════════════════════════════════════════════════════ */
  HV2.Search = (function () {
    function update(p) {
      // Typography progressive build
      HV2.Typography.updateProgressiveBuild(p);

      // DOM node cards
      var nodeEls = [
        document.getElementById('hv2-seo-node'),
        document.getElementById('hv2-aeo-node'),
        document.getElementById('hv2-geo-node'),
        document.getElementById('hv2-aio-node')
      ];
      nodeEls.forEach(function (el, idx) {
        if (!el) return;
        var th = idx * 0.22;
        if (p >= th) el.classList.add('active');
        else el.classList.remove('active');
      });

      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: subtle lateral shift to show search network
      HV2.Scene.setCamera(
        lerp(0.5, 0.8, p), 0.1, lerp(4.8, 4.5, p),
        0, 0, 0
      );

      // Nodes stay docked but SEO/AEO/GEO pulse sequentially
      for (var i = 0; i < 6; i++) {
        setNodeProgress(i, 1.0); // all docked
      }

      // Sequential node emphasis
      var seoP = smoothstep(0, 0.22, p);
      var aeoP = smoothstep(0.22, 0.44, p);
      var geoP = smoothstep(0.44, 0.66, p);
      var aiP = smoothstep(0.66, 0.88, p);

      // SEO node (index 0) pulses
      HV2.Scene.setNode(0, nodeDefsRef[0].docked[0], nodeDefsRef[0].docked[1], nodeDefsRef[0].docked[2],
        0.8 + seoP * 0.5, 0.75 + seoP * 0.25, 1.0 + seoP * 0.3);
      // AEO (index 1)
      HV2.Scene.setNode(1, nodeDefsRef[1].docked[0], nodeDefsRef[1].docked[1], nodeDefsRef[1].docked[2],
        0.8 + aeoP * 0.5, 0.75 + aeoP * 0.25, 1.0 + aeoP * 0.3);
      // GEO (index 2)
      HV2.Scene.setNode(2, nodeDefsRef[2].docked[0], nodeDefsRef[2].docked[1], nodeDefsRef[2].docked[2],
        0.8 + geoP * 0.5, 0.75 + geoP * 0.25, 1.0 + geoP * 0.3);

      // Core responds to search intelligence
      var searchTotal = (seoP + aeoP + geoP + aiP) / 4;
      HV2.Scene.setCoreVisuals(
        0.65 + searchTotal * 0.3,
        0.50 + searchTotal * 0.1,
        0.30 + searchTotal * 0.05,
        1.0 + searchTotal * 0.5,
        1.0
      );

      HV2.Scene.setEngVis(0);
      HV2.Scene.setSignal(false, 0);
    }
    return { update: update };
  })();


  /* ═══════════════════════════════════════════════════════════
     6. PERFORMANCE CHOREOGRAPHY (P0 → P5)
     Signal: Core → outward → returns → Core responds
     ═══════════════════════════════════════════════════════════ */
  HV2.Performance = (function () {
    function update(p) {
      // DOM cards
      var cards = [
        document.getElementById('perfCard1'),
        document.getElementById('perfCard2'),
        document.getElementById('perfCard3'),
        document.getElementById('perfCard4')
      ];
      cards.forEach(function (card, idx) {
        if (!card) return;
        var th = idx * 0.20;
        if (p >= th) card.classList.add('active');
        else card.classList.remove('active');
      });

      // Signal track SVG
      var pulse = document.getElementById('hv2PerfSignalDot');
      if (pulse) {
        var trackLen = 300;
        var cy = p * trackLen;
        pulse.setAttribute('cy', String(clamp(cy, 6, trackLen - 6)));
        pulse.style.opacity = (p > 0.02 && p < 0.95) ? '1' : '0';
      }

      // Attribution return loop
      var perfLoop = document.getElementById('hv2PerfLoop');
      if (perfLoop) {
        if (p >= 0.70) perfLoop.classList.add('active');
        else perfLoop.classList.remove('active');
      }

      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: track downward with signal
      HV2.Scene.setCamera(
        lerp(0.8, 0.5, p), lerp(0.1, -0.1, p), lerp(4.5, 4.3, p),
        0, 0, 0
      );

      // Nodes stay docked, Ads node (4) pulses with performance data
      for (var i = 0; i < 6; i++) setNodeProgress(i, 1.0);
      var adsP = smoothstep(0.2, 0.6, p);
      HV2.Scene.setNode(4, nodeDefsRef[4].docked[0], nodeDefsRef[4].docked[1], nodeDefsRef[4].docked[2],
        0.8 + adsP * 0.6, 0.75, 1.0 + adsP * 0.2);
      // Analytics node (5) responds to measurement
      var anaP = smoothstep(0.5, 0.85, p);
      HV2.Scene.setNode(5, nodeDefsRef[5].docked[0], nodeDefsRef[5].docked[1], nodeDefsRef[5].docked[2],
        0.8 + anaP * 0.6, 0.75, 1.0 + anaP * 0.2);

      // Core responds when signal returns
      var returnP = smoothstep(0.7, 0.95, p);
      HV2.Scene.setCoreVisuals(
        0.75 + returnP * 0.3,
        0.55 + returnP * 0.1,
        0.32 + returnP * 0.08,
        1.2 + returnP * 0.6,
        1.0
      );

      HV2.Scene.setEngVis(0);
      HV2.Scene.setSignal(false, 0);
    }
    return { update: update };
  })();


  /* ═══════════════════════════════════════════════════════════
     7. CONTENT CHOREOGRAPHY
     Pipeline signal flows through stages, feeds back to Core
     ═══════════════════════════════════════════════════════════ */
  HV2.Content = (function () {
    function update(p) {
      // DOM pipeline stages
      var stages = [
        document.getElementById('contentStage1'),
        document.getElementById('contentStage2'),
        document.getElementById('contentStage3'),
        document.getElementById('contentStage4')
      ];
      stages.forEach(function (st, idx) {
        if (!st) return;
        var th = idx * 0.22;
        if (p >= th) {
          st.classList.add('active');
          // Previous stages dim
          if (idx > 0 && stages[idx - 1]) {
            stages[idx - 1].style.opacity = '0.6';
          }
        } else {
          st.classList.remove('active');
          st.style.opacity = '';
        }
      });

      // Pipeline signal dot
      var pipeDot = document.getElementById('hv2ContentSignalDot');
      if (pipeDot) {
        pipeDot.style.left = (p * 100) + '%';
        pipeDot.style.opacity = (p > 0.02 && p < 0.95) ? '1' : '0';
      }

      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: follow pipeline direction
      HV2.Scene.setCamera(
        lerp(0.5, 0.3, p), lerp(-0.1, 0.05, p), lerp(4.3, 4.2, p),
        0, 0, 0
      );

      // Nodes all docked, Content node (3) pulses
      for (var i = 0; i < 6; i++) setNodeProgress(i, 1.0);
      var contentP = smoothstep(0.1, 0.7, p);
      HV2.Scene.setNode(3, nodeDefsRef[3].docked[0], nodeDefsRef[3].docked[1], nodeDefsRef[3].docked[2],
        0.8 + contentP * 0.6, 0.75, 1.0 + contentP * 0.2);

      // Core accumulates content intelligence
      var feedbackP = smoothstep(0.7, 0.95, p);
      HV2.Scene.setCoreVisuals(
        0.80 + feedbackP * 0.2,
        0.58 + feedbackP * 0.07,
        0.35 + feedbackP * 0.05,
        1.4 + feedbackP * 0.4,
        1.0
      );

      HV2.Scene.setEngVis(0);
      HV2.Scene.setSignal(false, 0);
    }
    return { update: update };
  })();


  /* ═══════════════════════════════════════════════════════════
     8. AI ENGINE CHOREOGRAPHY (E0 → E7)
     THE CLIMAX: Full 5-stage closed loop
     Core has accumulated all intelligence from previous sections
     Camera: cinematic orbit
     ═══════════════════════════════════════════════════════════ */
  HV2.Engine = (function () {
    var captionPill, captionText;

    function update(p) {
      if (!captionPill) {
        captionPill = document.getElementById('hv2CaptionPill');
        captionText = document.getElementById('hv2CaptionText');
      }

      // Stage progress values
      var s1T = smoothstep(0.06, 0.18, p);  // E0-E1: Data
      var s2T = smoothstep(0.18, 0.32, p);  // E2: Strategy
      var s3T = smoothstep(0.32, 0.46, p);  // E3: Execution
      var s4T = smoothstep(0.46, 0.60, p);  // E4: Measurement
      var s5T = smoothstep(0.60, 0.74, p);  // E5: Learning
      var loopT = smoothstep(0.74, 0.88, p); // E6: Return loop
      var holdT = smoothstep(0.88, 0.96, p); // E7: Final hold

      // DOM steps sync
      var steps = [
        document.getElementById('engineStep1'),
        document.getElementById('engineStep2'),
        document.getElementById('engineStep3'),
        document.getElementById('engineStep4'),
        document.getElementById('engineStep5')
      ];
      var thresholds = [0.06, 0.18, 0.32, 0.46, 0.60];
      steps.forEach(function (step, i) {
        if (!step) return;
        if (p >= thresholds[i]) {
          step.classList.add('active');
          step.style.opacity = '1';
        } else {
          step.classList.remove('active');
          step.style.opacity = '0.25';
        }
      });

      // SVG return loop
      var loopEl = document.getElementById('hv2EngineLoop');
      var pulsePath = document.getElementById('hv2LoopPulse');
      if (loopEl && pulsePath) {
        if (p >= 0.74) {
          loopEl.classList.add('active');
          pulsePath.style.strokeDashoffset = String(320 - loopT * 320);
        } else {
          loopEl.classList.remove('active');
          pulsePath.style.strokeDashoffset = '320';
        }
      }

      // Engine headline
      var engHeadline = document.getElementById('hv2EngineHeadline');
      if (engHeadline) {
        if (holdT > 0.1) {
          engHeadline.textContent = 'ONE CONNECTED MARKETING SYSTEM';
          engHeadline.style.opacity = String(holdT);
        } else if (loopT > 0.5) {
          engHeadline.textContent = 'CONTINUOUS INTELLIGENCE';
          engHeadline.style.opacity = String(loopT);
        } else {
          engHeadline.style.opacity = '0';
        }
      }

      // Spatial caption
      if (captionPill && captionText) {
        if (holdT > 0.5)     { captionPill.textContent = 'HARMONIC'; captionText.textContent = 'One Connected Marketing System'; }
        else if (loopT > 0.1) { captionPill.textContent = 'FEEDBACK'; captionText.textContent = '↺ Learning Feeds Back Into Data'; }
        else if (s5T > 0.1)   { captionPill.textContent = 'STAGE 05'; captionText.textContent = 'Continuous Machine Learning'; }
        else if (s4T > 0.1)   { captionPill.textContent = 'STAGE 04'; captionText.textContent = 'Measurement & Attribution'; }
        else if (s3T > 0.1)   { captionPill.textContent = 'STAGE 03'; captionText.textContent = 'Omnichannel Execution'; }
        else if (s2T > 0.1)   { captionPill.textContent = 'STAGE 02'; captionText.textContent = 'Predictive Strategy'; }
        else if (s1T > 0.1)   { captionPill.textContent = 'STAGE 01'; captionText.textContent = 'Data & Intelligence Ingestion'; }
        else                  { captionPill.textContent = 'READY';    captionText.textContent = 'AI Marketing Engine'; }
      }

      if (isMobile || reducedMotion || !hasThree) return;

      // Camera: cinematic orbit 0° → 25° → settle 10°
      var orbitAngle;
      if (p < 0.74) {
        orbitAngle = p * 0.45; // 0 to ~0.33 radians (~19°)
      } else if (p < 0.88) {
        orbitAngle = 0.33 + loopT * 0.12; // peak at 0.45 radians (~25°)
      } else {
        orbitAngle = lerp(0.45, 0.18, holdT); // settle to ~10°
      }
      var orbitR = 4.1;
      HV2.Scene.setCamera(
        Math.sin(orbitAngle) * orbitR,
        lerp(0, 0.15, holdT),
        Math.cos(orbitAngle) * orbitR,
        0, 0, 0
      );

      // Nodes stay docked
      for (var i = 0; i < 6; i++) setNodeProgress(i, 1.0);

      // Engine markers visibility
      HV2.Scene.setEngVis(smoothstep(0, 0.12, p));

      // Engine marker activations
      var tp = HV2.Scene.getTopPole();
      if (tp) {
        tp.material.emissiveIntensity = 0.3 + s1T * 1.2;
        tp.scale.setScalar(1.0 + s1T * 0.35);
      }
      var ur = HV2.Scene.getUpperRing();
      if (ur) ur.material.opacity = engVisCur * (0.3 + s2T * 0.55);
      var rads = HV2.Scene.getRadials();
      rads.forEach(function (rn) {
        rn.mesh.material.emissiveIntensity = 0.2 + s3T * 0.85;
        rn.line.material.opacity = engVisCur * (0.2 + s3T * 0.65);
      });
      var lr = HV2.Scene.getLowerRing();
      if (lr) lr.material.opacity = engVisCur * (0.2 + s4T * 0.65);
      var bp = HV2.Scene.getBottomPole();
      if (bp) {
        bp.material.emissiveIntensity = 0.3 + s5T * 1.3;
        bp.scale.setScalar(1.0 + s5T * 0.4);
      }

      // Return loop signal
      if (loopT > 0) {
        HV2.Scene.setSignal(true, loopT);
        var ll = HV2.Scene.getLoopLine();
        if (ll) {
          ll.material.opacity = 0.3 + loopT * 0.6;
          ll.material.color.setHex(0xc084fc);
        }
        // Flash when returning to Data
        if (loopT > 0.85 && tp) {
          tp.material.emissiveIntensity = 1.5 + (loopT - 0.85) * 4.0;
        }
      } else {
        HV2.Scene.setSignal(false, 0);
        var ll2 = HV2.Scene.getLoopLine();
        if (ll2) ll2.material.color.setHex(0x8b5cf6);
      }

      // Core climax
      var totalEngine = (s1T + s2T + s3T + s4T + s5T) / 5;
      HV2.Scene.setCoreVisuals(
        0.80 + totalEngine * 0.4 + holdT * 0.3,
        0.55 + totalEngine * 0.2 + holdT * 0.15,
        0.35 + totalEngine * 0.1 + holdT * 0.1,
        1.5 + totalEngine * 0.8 + holdT * 1.0,
        1.0
      );
    }

    // engVisCur reference — use the smoothstepped value
    var engVisCur = 0;

    return { update: function (p) { engVisCur = smoothstep(0, 0.12, p); update(p); } };
  })();


  /* ═══════════════════════════════════════════════════════════
     9. GSAP SCROLL ORCHESTRATION
     One ScrollTrigger per section.
     All animation reversible via sectionProgress 0→1→0.
     ═══════════════════════════════════════════════════════════ */
  HV2.Motion = (function () {
    function init() {
      if (reducedMotion || !hasGSAP) return;
      gsap.registerPlugin(ScrollTrigger);

      /* ── 1. Hero: pinned for cinematic progression ── */
      ScrollTrigger.create({
        trigger: '#hv2-hero',
        start: 'top top',
        end: '+=120%',
        pin: true,
        pinSpacing: true,
        scrub: 0.5,
        onUpdate: function (self) { HV2.Hero.update(self.progress); }
      });

      // Hero initial entrance (non-scrubbed)
      gsap.fromTo('.hv2-hero-badge', { y: 15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, delay: 0.1 });
      gsap.fromTo('.hv2-hero-subtitle', { y: 15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, delay: 0.4 });
      gsap.fromTo('.hv2-hero-actions', { y: 15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, delay: 0.6 });

      /* ── 2. Marketing Evolution ── */
      ScrollTrigger.create({
        trigger: '#hv2-evolved',
        start: 'top 80%',
        end: 'bottom 30%',
        scrub: 0.6,
        onUpdate: function (self) { HV2.Evolution.update(self.progress); }
      });

      /* ── 3. Search ── */
      ScrollTrigger.create({
        trigger: '#hv2-search',
        start: 'top 75%',
        end: 'bottom 35%',
        scrub: 0.5,
        onUpdate: function (self) { HV2.Search.update(self.progress); }
      });

      /* ── 4. Performance ── */
      ScrollTrigger.create({
        trigger: '#hv2-performance',
        start: 'top 75%',
        end: 'bottom 35%',
        scrub: 0.5,
        onUpdate: function (self) { HV2.Performance.update(self.progress); }
      });

      /* ── 5. Content ── */
      ScrollTrigger.create({
        trigger: '#hv2-content-engine',
        start: 'top 75%',
        end: 'bottom 35%',
        scrub: 0.5,
        onUpdate: function (self) { HV2.Content.update(self.progress); }
      });

      /* ── 6. AI Engine: pinned climax ── */
      if (!isMobile) {
        ScrollTrigger.create({
          trigger: '#hv2-ai-engine',
          start: 'top 10%',
          end: '+=120%',
          pin: true,
          pinSpacing: true,
          scrub: 0.6,
          onUpdate: function (self) { HV2.Engine.update(self.progress); }
        });
      } else {
        // Mobile: natural stagger
        var mSteps = document.querySelectorAll('.hv2-engine-step');
        mSteps.forEach(function (step) {
          gsap.fromTo(step, { opacity: 0.25, y: 15 }, {
            opacity: 1, y: 0, duration: 0.5,
            scrollTrigger: { trigger: step, start: 'top 85%' }
          });
        });
        gsap.fromTo('#hv2EngineLoop', { opacity: 0, y: 10 }, {
          opacity: 1, y: 0, duration: 0.6,
          scrollTrigger: { trigger: '#hv2EngineLoop', start: 'top 90%' }
        });
      }

      /* ── 7. Final CTA: Core settles ── */
      ScrollTrigger.create({
        trigger: '#hv2-final-cta',
        start: 'top 80%',
        end: 'bottom bottom',
        scrub: 0.5,
        onUpdate: function (self) {
          if (isMobile || reducedMotion || !hasThree) return;
          // Camera pulls back, Core settles
          var p = self.progress;
          HV2.Scene.setCamera(0, lerp(0, 0.2, p), lerp(4.5, 5.5, p), 0, 0, 0);
          HV2.Scene.setCoreVisuals(0.65, 0.45, 0.30, 1.0, 1.0);
          HV2.Scene.setEngVis(lerp(1, 0.3, p));
          HV2.Scene.setSignal(false, 0);
          for (var i = 0; i < 6; i++) setNodeProgress(i, 1.0);
        }
      });

      /* ── 8. Calm sections: Services, Portfolio, Learning ── */
      gsap.from('.hv2-service-group', {
        y: 25, opacity: 0, stagger: 0.12,
        scrollTrigger: { trigger: '.hv2-services', start: 'top 80%' }
      });
      gsap.from('.hv2-case-card', {
        y: 30, opacity: 0, stagger: 0.10,
        scrollTrigger: { trigger: '.hv2-portfolio', start: 'top 80%' }
      });
    }

    return { init: init };
  })();


  /* ═══════════════════════════════════════════════════════════
     10. INTERSECTION OBSERVER — WebGL Sleep/Wake
     Watches 3D-active sections. Pauses render when none visible.
     ═══════════════════════════════════════════════════════════ */
  HV2.initCanvasObservers = function () {
    if (isMobile || reducedMotion) return;

    var sections3D = document.querySelectorAll('#hv2-hero, #hv2-evolved, #hv2-search, #hv2-performance, #hv2-content-engine, #hv2-ai-engine, #hv2-final-cta');
    var visibleCount = 0;

    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) visibleCount++;
        else visibleCount--;
      });
      visibleCount = Math.max(0, visibleCount);
      if (visibleCount > 0) HV2.Scene.start();
      else HV2.Scene.pause();
    }, { threshold: 0.05 });

    sections3D.forEach(function (s) { obs.observe(s); });
  };


  /* ═══════════════════════════════════════════════════════════
     11. TAB SWITCHING — Learning Hub
     ═══════════════════════════════════════════════════════════ */
  HV2.initTabs = function () {
    var btns = document.querySelectorAll('.hv2-tab-btn');
    var panels = document.querySelectorAll('.hv2-tab-panel');
    if (!btns.length) return;

    btns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var tgt = btn.getAttribute('data-tab');
        btns.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
        panels.forEach(function (p) { p.classList.remove('active'); });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        var panel = document.getElementById(tgt);
        if (panel) panel.classList.add('active');
      });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     12. SERVICE CARD TILT + MOUSE INTERACTION
     ═══════════════════════════════════════════════════════════ */
  HV2.initCardTilt = function () {
    if (isMobile || reducedMotion) return;

    document.querySelectorAll('.hv2-service-card').forEach(function (card) {
      card.addEventListener('pointermove', function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5;
        var y = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = 'perspective(600px) rotateY(' + (x * 4) + 'deg) rotateX(' + (-y * 4) + 'deg) translateY(-4px)';
      });
      card.addEventListener('pointerleave', function () { card.style.transform = ''; });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     BOOTSTRAP
     ═══════════════════════════════════════════════════════════ */
  function init() {
    hasThree = typeof THREE !== 'undefined';
    hasGSAP = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

    HV2.Scene.init();
    HV2.Typography.init();
    HV2.Motion.init();
    HV2.initCanvasObservers();
    HV2.initTabs();
    HV2.initCardTilt();
  }

  if (document.readyState === 'complete') init();
  else window.addEventListener('load', init);

})();
