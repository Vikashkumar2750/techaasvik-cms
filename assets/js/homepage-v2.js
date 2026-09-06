/**
 * TECHAASVIK HOMEPAGE V2 — Namespaced Controller
 * File: assets/js/homepage-v2.js
 *
 * Implements Phase 2B according to the approved V6.1 Specification:
 * - Local sectionProgress = 0 → 1 per section (Hero, Evolved, Search, Performance, Content, AI Engine)
 * - Radically simplified architectural 3D primitives (Icosahedron Core, Octahedron Nodes, Synapses, Signals)
 * - 100% natural vertical scrolling (zero horizontal scroll hijacking)
 * - Real physical 3D closed loop in AI Marketing Engine: DATA → STRATEGY → EXECUTION → MEASUREMENT → LEARNING → DATA
 * - Restrained editorial color palette (Obsidian, Indigo, Violet, Soft White, Cyan, Emerald, Gold)
 * - 5-phase cinematic motion grammar (ENTER → TRANSFORM → SETTLE → HOLD → EXIT)
 * - Zero-dead-zone mobile architecture & full reduced-motion accessibility
 *
 * Preserves all CMS routes, forms, payments, auth, and database schemas.
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

  /* Shared billboard text sprite helper for Three.js */
  function createLabelSprite(text, color, size) {
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    canvas.width = 256;
    canvas.height = 64;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = '600 24px Inter, -apple-system, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillStyle = color || 'rgba(165, 180, 252, 0.85)';
    ctx.fillText(text, canvas.width / 2, canvas.height / 2);

    var texture = new THREE.CanvasTexture(canvas);
    texture.needsUpdate = true;

    var mat = new THREE.SpriteMaterial({
      map: texture,
      transparent: true,
      opacity: 0.75,
      depthTest: false,
      depthWrite: false
    });
    var sprite = new THREE.Sprite(mat);
    sprite.scale.set(size || 0.65, 0.16, 1);
    return sprite;
  }

  /* ═══════════════════════════════════════════════════════════
     1. THREE.JS HERO SCENE — AI MARKETING CORE (H0 → H7)
     Architectural visualization of fragmented disciplines converging.
     ═══════════════════════════════════════════════════════════ */
  HV2.HeroScene = (function () {
    var container, scene, camera, renderer, animationId;
    var isRunning = false;
    var group, core, innerKernel, centralLight;
    var nodes = [];
    var synapses = [];
    var clock = new THREE.Clock();

    // Mouse tracking
    var mouseTarget = { x: 0, y: 0 };
    var mouseCurrent = { x: 0, y: 0 };

    // Node definitions: 6 channels (SEO, AEO, GEO, Content, Ads, Analytics)
    var nodeDefs = [
      { id: 'seo',       label: 'SEO',       color: 0x6366f1, dormant: [ 1.30,  0.45, -0.20], docked: [ 0.55,  0.20,  0.10], phi: 0.1 },
      { id: 'aeo',       label: 'AEO',       color: 0x8b5cf6, dormant: [ 1.50, -0.50,  0.30], docked: [ 0.50, -0.25,  0.15], phi: 1.2 },
      { id: 'geo',       label: 'GEO',       color: 0x8b5cf6, dormant: [ 1.65,  0.30,  0.50], docked: [ 0.45,  0.15,  0.35], phi: 2.3 },
      { id: 'content',   label: 'Content',   color: 0x6366f1, dormant: [-1.80,  0.65, -0.30], docked: [-0.55,  0.22, -0.10], phi: 3.4 },
      { id: 'ads',       label: 'Ads',       color: 0x06b6d4, dormant: [-1.90, -0.60,  0.40], docked: [-0.50, -0.20,  0.15], phi: 4.5 },
      { id: 'analytics', label: 'Analytics', color: 0x10b981, dormant: [ 0.00, -1.80, -0.40], docked: [ 0.00, -0.55,  0.00], phi: 5.6 }
    ];

    function init() {
      if (isMobile || prefersReducedMotion || !hasThree) return;
      container = document.getElementById('hv2-hero-canvas');
      if (!container) return;

      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(38, container.clientWidth / container.clientHeight, 0.1, 50);
      camera.position.set(0.80, 0.30, 6.50);
      camera.lookAt(0.30, 0, 0);

      renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
      renderer.setClearColor(0x000000, 0);
      container.appendChild(renderer.domElement);

      group = new THREE.Group();
      scene.add(group);

      // 1. Central Core: Solid Icosahedron
      var coreGeo = new THREE.IcosahedronGeometry(0.52, 0);
      var coreMat = new THREE.MeshStandardMaterial({
        color: 0x6366f1,
        roughness: 0.25,
        metalness: 0.85,
        emissive: 0x4f46e5,
        emissiveIntensity: 0.25,
        wireframe: true,
        transparent: true,
        opacity: 0.25
      });
      core = new THREE.Mesh(coreGeo, coreMat);
      group.add(core);

      // Inner glowing kernel
      var kernelGeo = new THREE.SphereGeometry(0.36, 16, 16);
      var kernelMat = new THREE.MeshBasicMaterial({
        color: 0x8b5cf6,
        transparent: true,
        opacity: 0.15,
        blending: THREE.AdditiveBlending,
        depthWrite: false
      });
      innerKernel = new THREE.Mesh(kernelGeo, kernelMat);
      group.add(innerKernel);

      // Central activation point light
      centralLight = new THREE.PointLight(0x8b5cf6, 0.2, 5.0);
      centralLight.position.set(0, 0, 0);
      group.add(centralLight);

      // Key & Fill lights
      var keyLight = new THREE.DirectionalLight(0xffffff, 1.1);
      keyLight.position.set(3, 5, 4);
      scene.add(keyLight);

      var fillLight = new THREE.DirectionalLight(0x6366f1, 0.4);
      fillLight.position.set(-4, -2, -2);
      scene.add(fillLight);

      var rimLight = new THREE.PointLight(0x06b6d4, 1.0, 8.0);
      rimLight.position.set(0, 3, -3);
      scene.add(rimLight);

      var ambientLight = new THREE.AmbientLight(0x080d16, 0.4);
      scene.add(ambientLight);

      // 2. Satellite Nodes & Synapses
      nodeDefs.forEach(function (def) {
        var nodeGroup = new THREE.Group();

        var nodeGeo = new THREE.OctahedronGeometry(0.055, 0);
        var nodeMat = new THREE.MeshStandardMaterial({
          color: def.color,
          emissive: def.color,
          emissiveIntensity: 0.35,
          roughness: 0.3,
          metalness: 0.8
        });
        var nodeMesh = new THREE.Mesh(nodeGeo, nodeMat);
        nodeGroup.add(nodeMesh);

        var sprite = createLabelSprite(def.label, 'rgba(199, 210, 254, 0.85)', 0.55);
        sprite.position.set(0, 0.12, 0);
        nodeGroup.add(sprite);

        group.add(nodeGroup);

        // Synaptic line connecting node to Core origin
        var lineGeo = new THREE.BufferGeometry().setFromPoints([
          new THREE.Vector3(0, 0, 0),
          new THREE.Vector3(def.dormant[0], def.dormant[1], def.dormant[2])
        ]);
        var lineMat = new THREE.LineBasicMaterial({
          color: 0x818cf8,
          transparent: true,
          opacity: 0.0,
          depthWrite: false
        });
        var lineMesh = new THREE.Line(lineGeo, lineMat);
        group.add(lineMesh);

        nodes.push({
          group: nodeGroup,
          mesh: nodeMesh,
          sprite: sprite,
          def: def,
          currentPos: new THREE.Vector3().fromArray(def.dormant),
          lineMesh: lineMesh
        });
      });

      // Mouse listener
      container.addEventListener('mousemove', function (e) {
        var rect = container.getBoundingClientRect();
        mouseTarget.x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        mouseTarget.y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
      });
      container.addEventListener('mouseleave', function () {
        mouseTarget.x = 0;
        mouseTarget.y = 0;
      });

      // Resize listener
      window.addEventListener('resize', onResize);

      start();
    }

    function onResize() {
      if (!container || !renderer || !camera) return;
      camera.aspect = container.clientWidth / container.clientHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(container.clientWidth, container.clientHeight);
    }

    /* Update function called by GSAP ScrollTrigger with local heroProgress ∈ [0.0, 1.0] */
    function updateProgress(p) {
      if (!core) return;

      // H0 → H1 (0.00 - 0.25): Awakening
      var wakeT = Math.min(Math.max((p - 0.08) / 0.17, 0), 1);
      centralLight.intensity = 0.2 + wakeT * 0.4;
      core.material.opacity = 0.20 + wakeT * 0.25;
      innerKernel.material.opacity = 0.12 + wakeT * 0.18;

      // Camera dolly-in from (0.80, 0.30, 6.50) to (0.75, 0.25, 6.00)
      camera.position.x = 0.80 - wakeT * 0.05;
      camera.position.y = 0.30 - wakeT * 0.05;
      camera.position.z = 6.50 - wakeT * 0.50;

      // H2 (0.25 - 0.40): SEO node migrates
      var seoT = Math.min(Math.max((p - 0.20) / 0.20, 0), 1);
      // H3 (0.40 - 0.55): Search triad (AEO & GEO)
      var triadT = Math.min(Math.max((p - 0.35) / 0.20, 0), 1);
      // H4 (0.55 - 0.68): Inbound channel convergence (Content, Ads, Analytics)
      var convT = Math.min(Math.max((p - 0.50) / 0.18, 0), 1);
      // H5 (0.68 - 0.88): CINEMATIC PAUSE (HOLD) — full harmonic resonance
      var holdT = Math.min(Math.max((p - 0.68) / 0.20, 0), 1);

      if (holdT > 0) {
        centralLight.intensity = 0.6 + holdT * 0.6; // Reaches 1.2
        core.material.opacity = 0.45 + holdT * 0.20;
      }

      nodes.forEach(function (n) {
        var progressRate = 0;
        if (n.def.id === 'seo') progressRate = seoT;
        else if (n.def.id === 'aeo' || n.def.id === 'geo') progressRate = triadT;
        else progressRate = convT;

        // Position interpolation: dormant to docked
        var dx = n.def.dormant[0] + (n.def.docked[0] - n.def.dormant[0]) * progressRate;
        var dy = n.def.dormant[1] + (n.def.docked[1] - n.def.dormant[1]) * progressRate;
        var dz = n.def.dormant[2] + (n.def.docked[2] - n.def.dormant[2]) * progressRate;

        n.currentPos.set(dx, dy, dz);
        n.group.position.copy(n.currentPos);

        // Synapse line: redraw points and set opacity
        var posAttr = n.lineMesh.geometry.attributes.position;
        posAttr.setXYZ(1, dx, dy, dz);
        posAttr.needsUpdate = true;

        n.lineMesh.material.opacity = progressRate * 0.75;
        n.mesh.material.emissiveIntensity = 0.35 + progressRate * 0.45;
      });
    }

    function animate() {
      if (!isRunning) return;
      animationId = requestAnimationFrame(animate);

      var t = clock.getElapsedTime();

      // Ambient rotation of Core
      core.rotation.y = t * 0.04;
      core.rotation.x = t * 0.02;

      // Subtle breathing pulse on kernel
      innerKernel.scale.setScalar(1.0 + Math.sin(t * 1.5) * 0.04);

      // Mouse parallax with smooth exponential lerp
      mouseCurrent.x += (mouseTarget.x - mouseCurrent.x) * 0.035;
      mouseCurrent.y += (mouseTarget.y - mouseCurrent.y) * 0.035;
      group.rotation.y = mouseCurrent.x * 0.08;
      group.rotation.x = -mouseCurrent.y * 0.05;

      renderer.render(scene, camera);
    }

    function start() {
      if (!isRunning) {
        isRunning = true;
        clock.start();
        animate();
      }
    }

    function pause() {
      isRunning = false;
      if (animationId) cancelAnimationFrame(animationId);
    }

    return {
      init: init,
      start: start,
      pause: pause,
      updateProgress: updateProgress
    };
  })();


  /* ═══════════════════════════════════════════════════════════
     2. THREE.JS ENGINE SCENE — PHYSICAL CLOSED LOOP (E0 → E7)
     The 3D architecture itself executes the AI Marketing Engine:
     DATA → STRATEGY → EXECUTION → MEASUREMENT → LEARNING ↺ DATA
     ═══════════════════════════════════════════════════════════ */
  HV2.EngineScene = (function () {
    var container, scene, camera, renderer, animationId;
    var isRunning = false;
    var group, core, innerKernel, centralLight;
    var topPoleMesh, bottomPoleMesh, upperRingMesh, lowerRingMesh;
    var loopCurve, loopLineMesh, signalPulseMesh;
    var radialNodes = [];
    var clock = new THREE.Clock();

    // Captions elements
    var captionPill, captionText;

    function init() {
      if (isMobile || prefersReducedMotion || !hasThree) return;
      container = document.getElementById('hv2-engine-canvas');
      if (!container) return;

      captionPill = document.getElementById('hv2CaptionPill');
      captionText = document.getElementById('hv2CaptionText');

      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(38, container.clientWidth / container.clientHeight, 0.1, 50);
      camera.position.set(0, 0, 4.10);
      camera.lookAt(0, 0, 0);

      renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
      renderer.setClearColor(0x000000, 0);
      container.appendChild(renderer.domElement);

      group = new THREE.Group();
      scene.add(group);

      // 1. Primary AI Engine Core: Faceted Icosahedron
      var coreGeo = new THREE.IcosahedronGeometry(0.68, 0);
      var coreMat = new THREE.MeshStandardMaterial({
        color: 0x6366f1,
        roughness: 0.22,
        metalness: 0.85,
        emissive: 0x4f46e5,
        emissiveIntensity: 0.28,
        wireframe: true,
        transparent: true,
        opacity: 0.30
      });
      core = new THREE.Mesh(coreGeo, coreMat);
      group.add(core);

      // Inner dense intelligence kernel
      var kernelGeo = new THREE.SphereGeometry(0.46, 20, 20);
      var kernelMat = new THREE.MeshBasicMaterial({
        color: 0x8b5cf6,
        transparent: true,
        opacity: 0.16,
        blending: THREE.AdditiveBlending,
        depthWrite: false
      });
      innerKernel = new THREE.Mesh(kernelGeo, kernelMat);
      group.add(innerKernel);

      // Center dynamic point light
      centralLight = new THREE.PointLight(0x8b5cf6, 0.3, 6.0);
      group.add(centralLight);

      // 2. Stage 01: Top Polar Marker (Data & Intelligence) at (0, 0.68, 0)
      var poleGeo = new THREE.OctahedronGeometry(0.065, 0);
      var topPoleMat = new THREE.MeshStandardMaterial({
        color: 0x38bdf8,
        emissive: 0x06b6d4,
        emissiveIntensity: 0.3,
        roughness: 0.2,
        metalness: 0.9
      });
      topPoleMesh = new THREE.Mesh(poleGeo, topPoleMat);
      topPoleMesh.position.set(0, 0.68, 0);
      group.add(topPoleMesh);

      // 3. Stage 02: Upper Equator Ring (Predictive Strategy) at y = 0.28
      var ringPts = [];
      for (var a = 0; a <= 64; a++) {
        var theta = (a / 64) * Math.PI * 2;
        ringPts.push(new THREE.Vector3(Math.cos(theta) * 0.62, 0.28, Math.sin(theta) * 0.62));
      }
      var upperRingGeo = new THREE.BufferGeometry().setFromPoints(ringPts);
      var upperRingMat = new THREE.LineBasicMaterial({ color: 0x8b5cf6, transparent: true, opacity: 0.25 });
      upperRingMesh = new THREE.Line(upperRingGeo, upperRingMat);
      group.add(upperRingMesh);

      // 4. Stage 03: Omnichannel Radial Nodes & Splines (Execution)
      var branchAngles = [0, (Math.PI * 2) / 3, (Math.PI * 4) / 3];
      branchAngles.forEach(function (angle) {
        var bx = Math.cos(angle) * 0.95;
        var bz = Math.sin(angle) * 0.95;
        var bNodeGeo = new THREE.OctahedronGeometry(0.045, 0);
        var bNodeMat = new THREE.MeshStandardMaterial({ color: 0x818cf8, emissive: 0x6366f1, emissiveIntensity: 0.2 });
        var bNode = new THREE.Mesh(bNodeGeo, bNodeMat);
        bNode.position.set(bx, 0, bz);
        group.add(bNode);

        var bLineGeo = new THREE.BufferGeometry().setFromPoints([
          new THREE.Vector3(0, 0, 0),
          new THREE.Vector3(bx, 0, bz)
        ]);
        var bLineMat = new THREE.LineBasicMaterial({ color: 0x6366f1, transparent: true, opacity: 0.15 });
        var bLine = new THREE.Line(bLineGeo, bLineMat);
        group.add(bLine);

        radialNodes.push({ mesh: bNode, line: bLine });
      });

      // 5. Stage 04: Lower Equator Ring (Measurement & Attribution) at y = -0.28
      var lowerRingPts = [];
      for (var b = 0; b <= 64; b++) {
        var theta2 = (b / 64) * Math.PI * 2;
        lowerRingPts.push(new THREE.Vector3(Math.cos(theta2) * 0.62, -0.28, Math.sin(theta2) * 0.62));
      }
      var lowerRingGeo = new THREE.BufferGeometry().setFromPoints(lowerRingPts);
      var lowerRingMat = new THREE.LineBasicMaterial({ color: 0x10b981, transparent: true, opacity: 0.20 });
      lowerRingMesh = new THREE.Line(lowerRingGeo, lowerRingMat);
      group.add(lowerRingMesh);

      // 6. Stage 05: Bottom Polar Marker (Continuous Learning) at (0, -0.68, 0)
      var botPoleMat = new THREE.MeshStandardMaterial({
        color: 0x8b5cf6,
        emissive: 0x8b5cf6,
        emissiveIntensity: 0.3,
        roughness: 0.2,
        metalness: 0.9
      });
      bottomPoleMesh = new THREE.Mesh(poleGeo, botPoleMat);
      bottomPoleMesh.position.set(0, -0.68, 0);
      group.add(bottomPoleMesh);

      // 7. THE PHYSICAL CLOSED RETURN LOOP (LEARNING → DATA)
      // A luminous curved catenary spline arching 180° around the outer sphere
      loopCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(0.00, -0.68, 0.00),
        new THREE.Vector3(0.85, -0.45, 0.35),
        new THREE.Vector3(1.10,  0.00, 0.45),
        new THREE.Vector3(0.85,  0.45, 0.35),
        new THREE.Vector3(0.00,  0.68, 0.00)
      ]);
      var loopPts = loopCurve.getPoints(64);
      var loopGeo = new THREE.BufferGeometry().setFromPoints(loopPts);
      var loopMat = new THREE.LineBasicMaterial({
        color: 0x8b5cf6,
        transparent: true,
        opacity: 0.20,
        depthWrite: false
      });
      loopLineMesh = new THREE.Line(loopGeo, loopMat);
      group.add(loopLineMesh);

      // Photonic traveling pulse along the return loop
      var pulseGeo = new THREE.SphereGeometry(0.045, 16, 16);
      var pulseMat = new THREE.MeshBasicMaterial({
        color: 0x38bdf8,
        transparent: true,
        opacity: 0.0,
        blending: THREE.AdditiveBlending,
        depthWrite: false
      });
      signalPulseMesh = new THREE.Mesh(pulseGeo, pulseMat);
      group.add(signalPulseMesh);

      // Lighting
      var keyLight = new THREE.DirectionalLight(0xffffff, 1.2);
      keyLight.position.set(3, 5, 4);
      scene.add(keyLight);

      var fillLight = new THREE.DirectionalLight(0x6366f1, 0.5);
      fillLight.position.set(-4, -2, -2);
      scene.add(fillLight);

      var rimLight = new THREE.PointLight(0x06b6d4, 1.2, 8.0);
      rimLight.position.set(0, 3, -3);
      scene.add(rimLight);

      scene.add(new THREE.AmbientLight(0x080d16, 0.45));

      window.addEventListener('resize', onResize);
      start();
    }

    function onResize() {
      if (!container || !renderer || !camera) return;
      camera.aspect = container.clientWidth / container.clientHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(container.clientWidth, container.clientHeight);
    }

    /* Update function called by GSAP ScrollTrigger with local engineProgress ∈ [0.0, 1.0] */
    function updateProgress(p) {
      if (!core) return;

      // E0: Standby (0.00 - 0.08)
      // E1: Stage 01 Data (0.08 - 0.22)
      // E2: Stage 02 Strategy (0.22 - 0.36)
      // E3: Stage 03 Execution (0.36 - 0.50)
      // E4: Stage 04 Measurement (0.50 - 0.64)
      // E5: Stage 05 Learning (0.64 - 0.78)
      // E6: CLOSED LOOP FIRES (0.78 - 0.92)
      // E7: FULL HARMONIC RESONANCE (0.92 - 1.00)

      var stage1T = Math.min(Math.max((p - 0.08) / 0.14, 0), 1);
      var stage2T = Math.min(Math.max((p - 0.22) / 0.14, 0), 1);
      var stage3T = Math.min(Math.max((p - 0.36) / 0.14, 0), 1);
      var stage4T = Math.min(Math.max((p - 0.50) / 0.14, 0), 1);
      var stage5T = Math.min(Math.max((p - 0.64) / 0.14, 0), 1);
      var loopT   = Math.min(Math.max((p - 0.78) / 0.14, 0), 1);
      var holdT   = Math.min(Math.max((p - 0.92) / 0.08, 0), 1);

      // Top Pole (Data & Intelligence)
      topPoleMesh.material.emissiveIntensity = 0.3 + stage1T * 1.2;
      topPoleMesh.scale.setScalar(1.0 + stage1T * 0.35);

      // Upper Equator (Predictive Strategy)
      upperRingMesh.material.opacity = 0.20 + stage2T * 0.65;

      // Radial Execution Splines (Omnichannel Execution)
      radialNodes.forEach(function (rn) {
        rn.line.material.opacity = 0.15 + stage3T * 0.70;
        rn.mesh.material.emissiveIntensity = 0.20 + stage3T * 0.85;
      });

      // Lower Equator (Measurement & Attribution)
      lowerRingMesh.material.opacity = 0.20 + stage4T * 0.70;

      // Bottom Pole (Continuous Learning)
      bottomPoleMesh.material.emissiveIntensity = 0.3 + stage5T * 1.3;
      bottomPoleMesh.scale.setScalar(1.0 + stage5T * 0.40);

      // ↺ THE PHYSICAL RETURN LOOP
      if (loopT > 0) {
        loopLineMesh.material.opacity = 0.25 + loopT * 0.65;
        loopLineMesh.material.color.setHex(0xc084fc);

        // Move signal pulse along the Catmull-Rom spline
        signalPulseMesh.material.opacity = 0.95;
        var pt = loopCurve.getPointAt(loopT);
        signalPulseMesh.position.copy(pt);

        // Flash as it returns to Stage 01
        if (loopT > 0.85) {
          topPoleMesh.material.emissiveIntensity = 1.5 + (loopT - 0.85) * 4.0;
        }
      } else {
        loopLineMesh.material.opacity = 0.20;
        loopLineMesh.material.color.setHex(0x8b5cf6);
        signalPulseMesh.material.opacity = 0.0;
      }

      // System resonance & Central Light
      if (holdT > 0) {
        centralLight.intensity = 0.5 + holdT * 2.3; // Reaches 2.8 maximum radiance
        core.material.opacity = 0.35 + holdT * 0.35;
        innerKernel.material.opacity = 0.20 + holdT * 0.25;
      } else {
        centralLight.intensity = 0.3 + (stage1T + stage2T + stage3T + stage4T + stage5T) * 0.2;
      }

      // Update Spatial HUD Caption
      if (captionPill && captionText) {
        if (holdT > 0.5) {
          captionPill.textContent = 'HARMONIC';
          captionText.textContent = 'One Connected Marketing System';
        } else if (loopT > 0.1) {
          captionPill.textContent = 'FEEDBACK';
          captionText.textContent = '↺ Learning Feeds Back Into Data & Intelligence';
        } else if (stage5T > 0.1) {
          captionPill.textContent = 'STAGE 05';
          captionText.textContent = 'Continuous Machine Learning';
        } else if (stage4T > 0.1) {
          captionPill.textContent = 'STAGE 04';
          captionText.textContent = 'GA4 Measurement & Attribution';
        } else if (stage3T > 0.1) {
          captionPill.textContent = 'STAGE 03';
          captionText.textContent = 'Omnichannel Automated Execution';
        } else if (stage2T > 0.1) {
          captionPill.textContent = 'STAGE 02';
          captionText.textContent = 'Predictive Strategy & Authority Modeling';
        } else if (stage1T > 0.1) {
          captionPill.textContent = 'STAGE 01';
          captionText.textContent = 'Data & Market Intelligence Ingestion';
        } else {
          captionPill.textContent = 'READY';
          captionText.textContent = 'AI Marketing Engine Architecture';
        }
      }
    }

    function animate() {
      if (!isRunning) return;
      animationId = requestAnimationFrame(animate);

      var t = clock.getElapsedTime();

      // Core majestic rotation
      core.rotation.y = t * 0.03;
      core.rotation.x = t * 0.015;

      // Counter-rotate upper/lower rings
      upperRingMesh.rotation.y = -t * 0.05;
      lowerRingMesh.rotation.y = t * 0.05;

      renderer.render(scene, camera);
    }

    function start() {
      if (!isRunning) {
        isRunning = true;
        clock.start();
        animate();
      }
    }

    function pause() {
      isRunning = false;
      if (animationId) cancelAnimationFrame(animationId);
    }

    return {
      init: init,
      start: start,
      pause: pause,
      updateProgress: updateProgress
    };
  })();


  /* ═══════════════════════════════════════════════════════════
     3. GSAP SCROLL CHOREOGRAPHY (LOCAL SECTION PROGRESS 0 → 1)
     Deterministic, scrubbed, reversible forward & backward.
     ═══════════════════════════════════════════════════════════ */
  HV2.Motion = (function () {
    function init() {
      if (prefersReducedMotion || !hasGSAP) return;
      gsap.registerPlugin(ScrollTrigger);

      /* ── 1. Hero ScrollTrigger: H0 → H7 ── */
      ScrollTrigger.create({
        trigger: '#hv2-hero',
        start: 'top top',
        end: 'bottom top',
        scrub: 0.5,
        onUpdate: function (self) {
          HV2.HeroScene.updateProgress(self.progress);
        }
      });

      // Hero content entrance
      gsap.fromTo('.hv2-hero-badge',    { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.1 });
      gsap.fromTo('.hv2-hero-title',    { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.3 });
      gsap.fromTo('.hv2-hero-subtitle', { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.5 });
      gsap.fromTo('.hv2-hero-actions',  { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, delay: 0.7 });

      /* ── 2. Marketing Evolved: Traditional Silos vs AI-Assisted System ── */
      ScrollTrigger.create({
        trigger: '#hv2-evolved',
        start: 'top 80%',
        end: 'bottom 40%',
        scrub: 0.6,
        onUpdate: function (self) {
          var p = self.progress;
          var oldItems = document.querySelectorAll('.hv2-evolved-old .hv2-compare-item');
          var newItems = document.querySelectorAll('.hv2-evolved-new .hv2-compare-item');
          var arrow = document.querySelector('.hv2-evolved-arrow');

          // Traditional items dim slightly as AI items illuminate
          oldItems.forEach(function (el, i) {
            el.style.opacity = p > 0.4 ? '0.5' : '1.0';
          });
          newItems.forEach(function (el, i) {
            var threshold = 0.25 + i * 0.12;
            if (p >= threshold) {
              el.style.opacity = '1.0';
              el.style.transform = 'translateX(0)';
            } else {
              el.style.opacity = '0.35';
              el.style.transform = 'translateX(10px)';
            }
          });
          if (arrow) {
            arrow.style.color = p > 0.5 ? '#818cf8' : 'var(--text-muted)';
          }
        }
      });

      /* ── 3. Search Evolution: SEO → AEO → GEO → AI Overviews ── */
      ScrollTrigger.create({
        trigger: '#hv2-search',
        start: 'top 75%',
        end: 'bottom 45%',
        scrub: 0.5,
        onUpdate: function (self) {
          var p = self.progress;
          var nodes = [
            document.getElementById('hv2-seo-node'),
            document.getElementById('hv2-aeo-node'),
            document.getElementById('hv2-geo-node'),
            document.getElementById('hv2-aio-node')
          ];
          nodes.forEach(function (node, idx) {
            if (!node) return;
            var threshold = idx * 0.22;
            if (p >= threshold) {
              node.classList.add('active');
            } else {
              node.classList.remove('active');
            }
          });
        }
      });

      /* ── 4. Performance: Audience → Campaign → Conversion → Attribution Loop ── */
      ScrollTrigger.create({
        trigger: '#hv2-performance',
        start: 'top 75%',
        end: 'bottom 45%',
        scrub: 0.5,
        onUpdate: function (self) {
          var p = self.progress;
          var cards = [
            document.getElementById('perfCard1'),
            document.getElementById('perfCard2'),
            document.getElementById('perfCard3'),
            document.getElementById('perfCard4')
          ];
          cards.forEach(function (card, idx) {
            if (!card) return;
            var threshold = idx * 0.20;
            if (p >= threshold) {
              card.classList.add('active');
            } else {
              card.classList.remove('active');
            }
          });

          // Attribution return loop activates when card 4 reached
          var perfLoop = document.getElementById('hv2PerfLoop');
          if (perfLoop) {
            if (p >= 0.70) {
              perfLoop.classList.add('active');
            } else {
              perfLoop.classList.remove('active');
            }
          }
        }
      });

      /* ── 5. Content Engine: Topic → Draft → Human E-E-A-T Review → Distribution ── */
      ScrollTrigger.create({
        trigger: '#hv2-content-engine',
        start: 'top 75%',
        end: 'bottom 45%',
        scrub: 0.5,
        onUpdate: function (self) {
          var p = self.progress;
          var stages = [
            document.getElementById('contentStage1'),
            document.getElementById('contentStage2'),
            document.getElementById('contentStage3'),
            document.getElementById('contentStage4')
          ];
          stages.forEach(function (st, idx) {
            if (!st) return;
            var threshold = idx * 0.22;
            if (p >= threshold) {
              st.classList.add('active');
            } else {
              st.classList.remove('active');
            }
          });
        }
      });

      /* ── 6. AI Marketing Engine: E0 → E7 (VISUAL CLIMAX) ── */
      if (!isMobile) {
        // Desktop: Pin section for +100vh of clean scrubbing
        ScrollTrigger.create({
          trigger: '#hv2-ai-engine',
          start: 'top 10%',
          end: '+=100%',
          pin: true,
          pinSpacing: true,
          scrub: 0.6,
          onUpdate: function (self) {
            var p = self.progress; // 0.0 to 1.0

            // 1. Update 3D Engine Scene
            HV2.EngineScene.updateProgress(p);

            // 2. Synchronize DOM Steps 1–5
            var steps = [
              document.getElementById('engineStep1'),
              document.getElementById('engineStep2'),
              document.getElementById('engineStep3'),
              document.getElementById('engineStep4'),
              document.getElementById('engineStep5')
            ];
            var thresholds = [0.08, 0.22, 0.36, 0.50, 0.64];

            steps.forEach(function (step, i) {
              if (!step) return;
              if (p >= thresholds[i]) {
                step.classList.add('active');
                step.style.opacity = '1.0';
              } else {
                step.classList.remove('active');
                step.style.opacity = '0.25';
              }
            });

            // 3. Synchronize SVG Return Loop
            var loopEl = document.getElementById('hv2EngineLoop');
            var pulsePath = document.getElementById('hv2LoopPulse');
            var badgeEl = document.getElementById('hv2-engine-badge');

            if (loopEl && pulsePath) {
              if (p >= 0.78) {
                loopEl.classList.add('active');
                var loopSubT = (p - 0.78) / 0.14; // 0.0 to 1.0
                pulsePath.style.strokeDashoffset = String(320 - loopSubT * 320);
              } else {
                loopEl.classList.remove('active');
                pulsePath.style.strokeDashoffset = '320';
              }
            }

            // 4. Headline Badge update at full resonance (Hold state)
            if (badgeEl) {
              badgeEl.textContent = p >= 0.90 ? 'ONE CONNECTED MARKETING SYSTEM' : 'The Core';
              badgeEl.style.color = p >= 0.90 ? '#a5b4fc' : 'var(--brand-400)';
            }
          }
        });
      } else {
        // Mobile: Clean, natural vertical stagger without pinning
        var mobileSteps = document.querySelectorAll('.hv2-engine-step');
        mobileSteps.forEach(function (step) {
          gsap.fromTo(step, 
            { opacity: 0.25, y: 15 },
            {
              opacity: 1, y: 0, duration: 0.5,
              scrollTrigger: { trigger: step, start: 'top 85%' }
            }
          );
        });
        gsap.fromTo('#hv2EngineLoop',
          { opacity: 0, y: 10 },
          {
            opacity: 1, y: 0, duration: 0.6,
            scrollTrigger: { trigger: '#hv2EngineLoop', start: 'top 90%' }
          }
        );
      }

      /* ── 7. Services, Portfolio, Tabs, Card Tilt ── */
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
     4. INTERSECTION OBSERVER — WEBGL SLEEP/WAKE
     Pauses render loop when canvases scroll offscreen (0% CPU/GPU).
     ═══════════════════════════════════════════════════════════ */
  HV2.initCanvasObservers = function () {
    if (isMobile || prefersReducedMotion) return;

    var heroCanvas = document.getElementById('hv2-hero-canvas');
    if (heroCanvas) {
      var heroObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) HV2.HeroScene.start();
          else HV2.HeroScene.pause();
        });
      }, { threshold: 0.05 });
      heroObs.observe(heroCanvas);
    }

    var engineCanvas = document.getElementById('hv2-engine-canvas');
    if (engineCanvas) {
      var engineObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) HV2.EngineScene.start();
          else HV2.EngineScene.pause();
        });
      }, { threshold: 0.05 });
      engineObs.observe(engineCanvas);
    }
  };


  /* ═══════════════════════════════════════════════════════════
     5. TAB SWITCHING — Learning Hub (Accessible)
     ═══════════════════════════════════════════════════════════ */
  HV2.initTabs = function () {
    var tabBtns = document.querySelectorAll('.hv2-tab-btn');
    var tabPanels = document.querySelectorAll('.hv2-tab-panel');
    if (!tabBtns.length) return;

    tabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = btn.getAttribute('data-tab');
        tabBtns.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
        tabPanels.forEach(function (p) { p.classList.remove('active'); });

        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        var panel = document.getElementById(target);
        if (panel) panel.classList.add('active');
      });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     6. SERVICE CARD TILT — Desktop Only
     ═══════════════════════════════════════════════════════════ */
  HV2.initCardTilt = function () {
    if (isMobile || prefersReducedMotion) return;

    document.querySelectorAll('.hv2-service-card').forEach(function (card) {
      card.addEventListener('pointermove', function (e) {
        var rect = card.getBoundingClientRect();
        var x = (e.clientX - rect.left) / rect.width - 0.5;
        var y = (e.clientY - rect.top) / rect.height - 0.5;
        card.style.transform = 'perspective(600px) rotateY(' + (x * 4) + 'deg) rotateX(' + (-y * 4) + 'deg) translateY(-4px)';
      });

      card.addEventListener('pointerleave', function () {
        card.style.transform = '';
      });
    });
  };


  /* ═══════════════════════════════════════════════════════════
     INITIALIZATION BOOTSTRAP
     Waits for window load to guarantee deferred scripts parsed.
     ═══════════════════════════════════════════════════════════ */
  function init() {
    hasThree = typeof THREE !== 'undefined';
    hasGSAP  = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

    HV2.HeroScene.init();
    HV2.EngineScene.init();
    HV2.Motion.init();
    HV2.initCanvasObservers();
    HV2.initTabs();
    HV2.initCardTilt();
  }

  if (document.readyState === 'complete') {
    init();
  } else {
    window.addEventListener('load', init);
  }

})();
