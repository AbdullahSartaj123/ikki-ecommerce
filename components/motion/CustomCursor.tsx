"use client";

import { useEffect } from "react";
import gsap from "gsap";

export default function CustomCursor() {
  useEffect(() => {
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const coarsePointer = window.matchMedia("(pointer: coarse)").matches;
    if (reduceMotion || coarsePointer) return;

    const cursor = document.querySelector<HTMLElement>("[data-ikki-cursor]");
    if (!cursor) return;

    const xTo = gsap.quickTo(cursor, "x", { duration: 0.22, ease: "power3" });
    const yTo = gsap.quickTo(cursor, "y", { duration: 0.22, ease: "power3" });

    const move = (event: PointerEvent) => {
      xTo(event.clientX);
      yTo(event.clientY);
      cursor.dataset.visible = "true";
    };
    const hide = () => { cursor.dataset.visible = "false"; };
    const enterInteractive = () => cursor.dataset.state = "active";
    const leaveInteractive = () => cursor.dataset.state = "default";

    window.addEventListener("pointermove", move);
    document.addEventListener("pointerleave", hide);
    const interactive = document.querySelectorAll("a, button, [data-cursor='hover']");
    interactive.forEach((node) => {
      node.addEventListener("pointerenter", enterInteractive);
      node.addEventListener("pointerleave", leaveInteractive);
    });

    return () => {
      window.removeEventListener("pointermove", move);
      document.removeEventListener("pointerleave", hide);
      interactive.forEach((node) => {
        node.removeEventListener("pointerenter", enterInteractive);
        node.removeEventListener("pointerleave", leaveInteractive);
      });
    };
  }, []);

  return (
    <div className="ikki-cursor" data-ikki-cursor data-visible="false" data-state="default" aria-hidden="true">
      <span />
    </div>
  );
}