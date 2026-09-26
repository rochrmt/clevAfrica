/* =========================================================
   CLEV Africa Consulting — Interactions
   ========================================================= */
(() => {
  "use strict";

  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const lerp = (a, b, t) => a + (b - a) * t;
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const finePointer = window.matchMedia("(pointer: fine)").matches;

  /* ---------- Intro ---------- */
  const intro = $("#intro");
  document.body.classList.add("is-locked");
  const endIntro = () => {
    intro.classList.add("is-done");
    document.body.classList.remove("is-locked");
    $(".hero")?.classList.add("is-inview");
    $$(".hero [data-reveal]").forEach((el) => el.classList.add("is-inview"));
  };
  window.addEventListener("load", () => setTimeout(endIntro, reduceMotion ? 0 : 500), { once: true });
  setTimeout(endIntro, 3500); // safety

  /* ---------- Split text ---------- */
  $$("[data-split]").forEach((el) => {
    const walk = (node) => {
      Array.from(node.childNodes).forEach((child) => {
        if (child.nodeType === Node.TEXT_NODE) {
          const frag = document.createDocumentFragment();
          child.textContent.split(/(\s+)/).forEach((part) => {
            if (!part) return;
            if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(" ")); return; }
            const w = document.createElement("span");
            w.className = "word";
            const inner = document.createElement("span");
            inner.textContent = part;
            w.appendChild(inner);
            frag.appendChild(w);
          });
          node.replaceChild(frag, child);
        } else if (child.nodeType === Node.ELEMENT_NODE) {
          walk(child);
        }
      });
    };
    walk(el);
    $$(".word > span", el).forEach((s, i) => { s.style.transitionDelay = `${i * 45}ms`; });
    if (!el.hasAttribute("data-reveal")) el.dataset.revealSplit = "";
  });

  /* ---------- Reveal on scroll ---------- */
  $$("[data-reveal]").forEach((el) => {
    if (el.dataset.delay) el.style.setProperty("--d", `${el.dataset.delay}s`);
  });
  $$(".logo").forEach((el, i) => el.style.setProperty("--i", i));

  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (e.isIntersecting) {
        e.target.classList.add("is-inview");
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.15, rootMargin: "0px 0px -8% 0px" });

  $$("[data-reveal], [data-reveal-split], .steps, .logos").forEach((el) => {
    if (el.closest(".hero")) return; // handled by intro
    io.observe(el);
  });

  /* ---------- Counters ---------- */
  const counters = $$("[data-count]");
  const cio = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = +el.dataset.count;
      const dur = 1600;
      const start = performance.now();
      const tick = (now) => {
        const p = Math.min(1, (now - start) / dur);
        const eased = 1 - Math.pow(1 - p, 4);
        el.textContent = Math.round(target * eased).toLocaleString("fr-FR").replace(/\s/g, "");
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
      cio.unobserve(el);
    });
  }, { threshold: 0.5 });
  counters.forEach((c) => cio.observe(c));

  /* ---------- Header / progress / active link / totop ---------- */
  const header = $("#header");
  const progress = $("#progress span");
  const totop = $("#totop");
  const sections = $$("main section[id]");
  const navLinks = $$(".nav__link");
  let lastY = window.scrollY;

  const onScroll = () => {
    const y = window.scrollY;
    const max = document.documentElement.scrollHeight - innerHeight;
    progress.style.setProperty("--p", max > 0 ? (y / max).toFixed(4) : 0);
    header.classList.toggle("is-scrolled", y > 40);
    header.classList.toggle("is-hidden", y > lastY && y > 400 && !header.classList.contains("is-menu"));
    lastY = y;
    totop.classList.toggle("is-visible", y > innerHeight * 0.8);

    let current = null;
    sections.forEach((s) => { if (y >= s.offsetTop - innerHeight * 0.4) current = s.id; });
    navLinks.forEach((l) => l.classList.toggle("is-active", l.getAttribute("href") === `#${current}`));
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  totop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }));

  /* ---------- Mobile menu ---------- */
  const burger = $("#burger");
  const nav = $("#nav");
  const closeMenu = () => {
    nav.classList.remove("is-open");
    header.classList.remove("is-menu");
    burger.setAttribute("aria-expanded", "false");
    burger.setAttribute("aria-label", "Ouvrir le menu");
    document.body.classList.remove("is-locked");
  };
  burger.addEventListener("click", () => {
    const open = !nav.classList.contains("is-open");
    nav.classList.toggle("is-open", open);
    header.classList.toggle("is-menu", open);
    header.classList.remove("is-hidden");
    burger.setAttribute("aria-expanded", String(open));
    burger.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
    document.body.classList.toggle("is-locked", open);
  });
  $$("a", nav).forEach((a) => a.addEventListener("click", closeMenu));
  window.addEventListener("keydown", (e) => { if (e.key === "Escape") closeMenu(); });

  /* ---------- Parallax ---------- */
  const parallaxEls = $$("[data-parallax]");
  if (!reduceMotion && parallaxEls.length) {
    let ticking = false;
    const update = () => {
      parallaxEls.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > innerHeight) return;
        const speed = parseFloat(el.dataset.parallax);
        const center = rect.top + rect.height / 2 - innerHeight / 2;
        el.style.transform = `translate3d(0, ${(-center * speed).toFixed(1)}px, 0)`;
      });
      ticking = false;
    };
    window.addEventListener("scroll", () => { if (!ticking) { requestAnimationFrame(update); ticking = true; } }, { passive: true });
    update();
  }

  /* ---------- Spotlight (cursor-following glow) ---------- */
  $$("[data-spot]").forEach((el) => {
    const move = (x, y) => {
      const r = el.getBoundingClientRect();
      el.style.setProperty("--mx", `${x - r.left}px`);
      el.style.setProperty("--my", `${y - r.top}px`);
    };
    el.addEventListener("pointermove", (e) => move(e.clientX, e.clientY), { passive: true });
  });

  /* ---------- Tilt (mouse + touch) ---------- */
  if (!reduceMotion) {
    $$("[data-tilt]").forEach((el) => {
      let raf = null;
      let tx = 0, ty = 0, cx = 0, cy = 0;
      const render = () => {
        cx = lerp(cx, tx, 0.12); cy = lerp(cy, ty, 0.12);
        el.style.transform = `perspective(900px) rotateX(${cy.toFixed(2)}deg) rotateY(${cx.toFixed(2)}deg)`;
        if (Math.abs(cx - tx) > 0.01 || Math.abs(cy - ty) > 0.01) raf = requestAnimationFrame(render); else raf = null;
      };
      const set = (x, y) => {
        const r = el.getBoundingClientRect();
        tx = ((x - r.left) / r.width - 0.5) * 10;
        ty = -((y - r.top) / r.height - 0.5) * 10;
        if (!raf) raf = requestAnimationFrame(render);
      };
      el.addEventListener("pointermove", (e) => set(e.clientX, e.clientY), { passive: true });
      el.addEventListener("pointerleave", () => { tx = 0; ty = 0; if (!raf) raf = requestAnimationFrame(render); });
    });
  }

  /* ---------- Magnetic buttons ---------- */
  if (finePointer && !reduceMotion) {
    $$("[data-magnetic]").forEach((el) => {
      el.addEventListener("pointermove", (e) => {
        const r = el.getBoundingClientRect();
        const x = (e.clientX - r.left - r.width / 2) * 0.25;
        const y = (e.clientY - r.top - r.height / 2) * 0.35;
        el.style.transform = `translate(${x.toFixed(1)}px, ${y.toFixed(1)}px)`;
        el.style.transition = "transform 0.15s ease-out";
      });
      el.addEventListener("pointerleave", () => {
        el.style.transition = "transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)";
        el.style.transform = "";
      });
    });
  }

  /* ---------- Ripple (touch feedback) ---------- */
  $$(".btn, .value, .card, .logo, .office, .step__num").forEach((el) => {
    el.addEventListener("pointerdown", (e) => {
      if (reduceMotion) return;
      const r = el.getBoundingClientRect();
      const size = Math.max(r.width, r.height) * 2;
      const dot = document.createElement("span");
      dot.className = "ripple";
      dot.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX - r.left - size / 2}px;top:${e.clientY - r.top - size / 2}px;`;
      if (!el.classList.contains("btn")) dot.style.background = "rgba(31,139,75,0.12)";
      el.appendChild(dot);
      dot.addEventListener("animationend", () => dot.remove(), { once: true });
    }, { passive: true });
  });

  /* ---------- Custom cursor ---------- */
  const cursor = $("#cursor");
  if (finePointer && !reduceMotion) {
    document.body.classList.add("has-cursor");
    cursor.classList.add("is-hidden");
    const dot = $(".cursor__dot");
    const ring = $(".cursor__ring");
    const label = $(".cursor__label");
    let mx = innerWidth / 2, my = innerHeight / 2, rx = mx, ry = my;
    let visible = false;

    window.addEventListener("pointermove", (e) => {
      mx = e.clientX; my = e.clientY;
      if (!visible) { visible = true; cursor.classList.remove("is-hidden"); }
    }, { passive: true });
    document.addEventListener("pointerleave", () => cursor.classList.add("is-hidden"));
    document.addEventListener("pointerenter", () => cursor.classList.remove("is-hidden"));
    window.addEventListener("pointerdown", () => cursor.classList.add("is-down"));
    window.addEventListener("pointerup", () => cursor.classList.remove("is-down"));

    const loop = () => {
      rx = lerp(rx, mx, 0.18); ry = lerp(ry, my, 0.18);
      dot.style.transform = `translate(${mx}px, ${my}px) translate(-50%, -50%)`;
      ring.style.transform = `translate(${rx}px, ${ry}px) translate(-50%, -50%)`;
      label.style.transform = `translate(${rx}px, ${ry}px) translate(-50%, -50%)`;
      requestAnimationFrame(loop);
    };
    loop();

    const hoverables = "a, button, [data-tilt], .logo, .shot, input, textarea, select, label";
    document.addEventListener("pointerover", (e) => {
      const t = e.target.closest(hoverables);
      cursor.classList.toggle("is-hover", !!t && !e.target.closest("[data-drag]"));
      const drag = e.target.closest("[data-drag]");
      cursor.classList.toggle("is-drag", !!drag);
      label.textContent = drag ? "Glisser" : "";
    });
  }

  /* ---------- Draggable gallery ---------- */
  $$("[data-drag]").forEach((track) => {
    let isDown = false, startX = 0, startScroll = 0, velocity = 0, lastX = 0, lastT = 0, raf = null;
    const stopMomentum = () => { if (raf) { cancelAnimationFrame(raf); raf = null; } };
    const momentum = () => {
      if (Math.abs(velocity) < 0.2) { raf = null; return; }
      track.scrollLeft -= velocity;
      velocity *= 0.94;
      raf = requestAnimationFrame(momentum);
    };
    track.addEventListener("pointerdown", (e) => {
      if (e.pointerType === "touch") return; // native touch scrolling
      isDown = true; stopMomentum();
      startX = e.clientX; startScroll = track.scrollLeft; lastX = e.clientX; lastT = performance.now(); velocity = 0;
      track.classList.add("is-dragging");
      track.setPointerCapture(e.pointerId);
    });
    track.addEventListener("pointermove", (e) => {
      if (!isDown) return;
      const now = performance.now();
      track.scrollLeft = startScroll - (e.clientX - startX);
      velocity = (e.clientX - lastX) * (16 / Math.max(1, now - lastT));
      lastX = e.clientX; lastT = now;
    });
    const end = () => {
      if (!isDown) return;
      isDown = false;
      track.classList.remove("is-dragging");
      raf = requestAnimationFrame(momentum);
    };
    track.addEventListener("pointerup", end);
    track.addEventListener("pointercancel", end);
    track.addEventListener("pointerleave", end);
    // Convert vertical wheel to horizontal scroll inside the track
    track.addEventListener("wheel", (e) => {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        const atStart = track.scrollLeft <= 0 && e.deltaY < 0;
        const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1 && e.deltaY > 0;
        if (atStart || atEnd) return;
        e.preventDefault();
        track.scrollLeft += e.deltaY;
      }
    }, { passive: false });
  });

  /* ---------- Marquee: duplicate content + pause on touch ---------- */
  $$(".marquee").forEach((m) => {
    const track = $(".marquee__track", m);
    track.innerHTML += track.innerHTML;
    m.style.setProperty("--dur", `${m.dataset.speed || 45}s`);
    m.addEventListener("touchstart", () => m.classList.add("is-paused"), { passive: true });
    m.addEventListener("touchend", () => m.classList.remove("is-paused"), { passive: true });
  });

  /* ---------- Hero mouse parallax ---------- */
  const heroBg = $(".hero__bg img");
  if (heroBg && finePointer && !reduceMotion) {
    let tx = 0, ty = 0, cx = 0, cy = 0, raf = null;
    const render = () => {
      cx = lerp(cx, tx, 0.06); cy = lerp(cy, ty, 0.06);
      heroBg.style.translate = `${cx.toFixed(1)}px ${cy.toFixed(1)}px`;
      raf = (Math.abs(cx - tx) > 0.05 || Math.abs(cy - ty) > 0.05) ? requestAnimationFrame(render) : null;
    };
    $(".hero").addEventListener("pointermove", (e) => {
      tx = (e.clientX / innerWidth - 0.5) * -24;
      ty = (e.clientY / innerHeight - 0.5) * -16;
      if (!raf) raf = requestAnimationFrame(render);
    }, { passive: true });
  }

  /* ---------- Contact form ---------- */
  const form = $("#form");
  const note = $("#formNote");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    let valid = true;
    $$("[required]", form).forEach((input) => {
      const field = input.closest(".field");
      const ok = input.type === "email" ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value) : input.value.trim().length > 1;
      field.classList.toggle("is-invalid", !ok);
      if (!ok) valid = false;
    });
    if (!valid) {
      note.textContent = "Merci de renseigner les champs obligatoires.";
      note.classList.add("is-error");
      return;
    }
    const data = new FormData(form);
    const subject = encodeURIComponent(`[Site] Demande — ${data.get("domaine")} — ${data.get("nom")}`);
    const body = encodeURIComponent(
      `Nom : ${data.get("nom")}\nEntreprise : ${data.get("entreprise") || "-"}\nE-mail : ${data.get("email")}\nTéléphone : ${data.get("tel") || "-"}\nDomaine : ${data.get("domaine")}\n\n${data.get("message")}`
    );
    window.location.href = `mailto:contact@clevafricaconsulting.com?subject=${subject}&body=${body}`;
    note.classList.remove("is-error");
    note.textContent = "Merci ! Votre messagerie s'ouvre pour finaliser l'envoi. Nous vous répondons sous 48 h.";
    form.reset();
  });
  $$("input, textarea", form).forEach((i) => i.addEventListener("input", () => i.closest(".field").classList.remove("is-invalid")));

  /* ---------- Misc ---------- */
  $("#year").textContent = new Date().getFullYear();

  // Smooth anchor offset for fixed header
  $$('a[href^="#"]').forEach((a) => {
    a.addEventListener("click", (e) => {
      const id = a.getAttribute("href");
      if (id.length < 2) return;
      const target = $(id);
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.scrollY - (id === "#accueil" ? 0 : 72);
      window.scrollTo({ top, behavior: reduceMotion ? "auto" : "smooth" });
      history.replaceState(null, "", id);
    });
  });
})();
