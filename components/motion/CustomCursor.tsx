"use client";

import { useEffect } from "react";
import gsap from "gsap";

export default function CustomCursor() {
  useEffect(() => {
    const finePointer = window.matchMedia("(pointer: fine)");
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    let cleanup: (() => void) | undefined;
    const setup = () => {
      cleanup?.(); cleanup = undefined;
      if (!finePointer.matches || reduceMotion.matches) return;
      const cursor = document.querySelector<HTMLElement>("[data-ikki-cursor]");
      const dot = cursor?.querySelector<HTMLElement>(".ikki-cursor-dot");
      const label = cursor?.querySelector<HTMLElement>(".ikki-cursor-label");
      if (!cursor || !dot || !label) return;
      const xTo = gsap.quickTo(cursor, "x", { duration: .34, ease: "power3.out" });
      const yTo = gsap.quickTo(cursor, "y", { duration: .34, ease: "power3.out" });
      const dotXTo = gsap.quickTo(dot, "x", { duration: .08, ease: "power2.out" });
      const dotYTo = gsap.quickTo(dot, "y", { duration: .08, ease: "power2.out" });
      let activeMagnet: HTMLElement | null = null;
      let activeTarget: HTMLElement | null = null;
      const move = (event: PointerEvent) => {
        xTo(event.clientX); yTo(event.clientY); dotXTo(event.clientX); dotYTo(event.clientY);
        cursor.dataset.visible = "true";
        const magnet = (event.target as HTMLElement | null)?.closest<HTMLElement>("[data-magnetic]");
        if (activeMagnet && activeMagnet !== magnet) {
          gsap.to(activeMagnet, { x: 0, y: 0, duration: .45, ease: "elastic.out(1, 0.35)", overwrite: true });
          activeMagnet = null;
        }
        if (magnet && !magnet.matches(":disabled")) {
          const rect = magnet.getBoundingClientRect();
          const dx = event.clientX - (rect.left + rect.width / 2);
          const dy = event.clientY - (rect.top + rect.height / 2);
          const distance = Math.hypot(dx, dy);
          const radius = Number(magnet.dataset.magneticRadius || 115);
          if (distance < radius) {
            activeMagnet = magnet;
            const strength = Number(magnet.dataset.magneticStrength || .22) * (1 - distance / radius);
            gsap.to(magnet, { x: dx * strength, y: dy * strength, duration: .28, ease: "power3.out", overwrite: true });
          } else if (activeMagnet === magnet) {
            gsap.to(magnet, { x: 0, y: 0, duration: .45, ease: "elastic.out(1, 0.35)", overwrite: true });
            activeMagnet = null;
          }
        }
      };
      const enter = (event: PointerEvent) => {
        const target = (event.target as HTMLElement | null)?.closest<HTMLElement>("[data-cursor-label], [data-cursor='view'], [data-cursor='drag'], [data-cursor='shop']");
        if (!target || target === activeTarget) return;
        activeTarget = target;
        label.textContent = target.dataset.cursorLabel || (target.dataset.cursor === "drag" ? "Drag" : target.dataset.cursor === "shop" ? "Shop" : "View");
        cursor.dataset.state = "active"; cursor.dataset.label = "true";
      };
      const leave = (event: PointerEvent) => {
        const related = event.relatedTarget as HTMLElement | null;
        if (activeTarget && related && activeTarget.contains(related)) return;
        activeTarget = null; cursor.dataset.state = "default"; cursor.dataset.label = "false";
      };
      const hide = () => { cursor.dataset.visible = "false"; };
      window.addEventListener("pointermove", move, { passive: true });
      document.addEventListener("pointerover", enter);
      document.addEventListener("pointerout", leave);
      document.addEventListener("pointerleave", hide);
      cleanup = () => {
        window.removeEventListener("pointermove", move);
        document.removeEventListener("pointerover", enter);
        document.removeEventListener("pointerout", leave);
        document.removeEventListener("pointerleave", hide);
        if (activeMagnet) gsap.set(activeMagnet, { x: 0, y: 0 });
        gsap.killTweensOf([cursor, dot]);
      };
    };
    setup();
    const onChange = () => setup();
    finePointer.addEventListener("change", onChange);
    reduceMotion.addEventListener("change", onChange);
    return () => { cleanup?.(); finePointer.removeEventListener("change", onChange); reduceMotion.removeEventListener("change", onChange); };
  }, []);
  return <div className="ikki-cursor" data-ikki-cursor data-visible="false" data-state="default" data-label="false" aria-hidden="true"><span className="ikki-cursor-ring" /><span className="ikki-cursor-dot" /><span className="ikki-cursor-label" /></div>;
}
