# IKKI Responsive & Accessibility Standards

**Status:** Mandatory project-wide engineering baseline
**Applies to:** Every route, component, brand experience, form, animation and future feature.

These standards are the source of truth for responsive behavior and accessibility acceptance. A feature is not complete merely because it looks correct at one viewport or works with a mouse.

## 1. Responsive breakpoint system

IKKI uses a mobile-first layout. Unprefixed Tailwind styles are the mobile baseline; named aliases enhance larger layouts.

| Device class | Width | Tailwind usage | Design intent |
|---|---:|---|---|
| Mobile | 0–639px | Unprefixed utilities | Single-column layouts, touch-first controls, compact navigation, readable type and reduced visual density. |
| Tablet | 640–1023px | tablet: | Two-column opportunities, wider spacing, tablet navigation patterns and flexible grids. |
| Laptop | 1024–1439px | laptop: | Full navigation, multi-column editorial layouts, desktop product grids and balanced content widths. |
| Large desktop | 1440px+ | desktop: | Expanded editorial composition, larger campaign imagery and controlled maximum content widths. |

### Responsive rules
1. Design mobile first. Add enhancements at tablet, laptop and desktop widths; do not treat mobile as a compressed desktop.
2. Avoid fixed widths for sections, cards, text columns and forms. Prefer fluid widths, grid/flex layouts, minmax(), clamp() and shared container tokens.
3. Prevent horizontal overflow at every viewport, including 320px devices and browser zoom.
4. Navigation must provide a usable mobile menu; hover must never be the only way to reveal navigation.
5. Product grids should adapt progressively: 1–2 columns on narrow mobile, 2 on wider mobile/tablet, 3 on laptop and 3–4 on large desktop as content permits.
6. Use appropriately sized responsive images. Preserve aspect ratios and reserve layout space to prevent CLS.
7. Keep body copy comfortably readable. Do not shrink essential text to preserve art direction.
8. Forms, dialogs, cart and checkout must fit narrow screens without clipped controls or inaccessible horizontal scrolling.
9. Test portrait and landscape orientations, touch and fine pointers, and widths immediately below and above each breakpoint.
10. Large desktop layouts must use a sensible max-width; do not stretch long text lines across the entire screen.

### Tailwind aliases
- tablet: — 640px and up
- laptop: — 1024px and up
- desktop: — 1440px and up
- Unprefixed classes define the mobile style. Existing sm, md, lg, xl and 2xl aliases remain available.

## 2. Semantic HTML baseline
1. Use native semantic elements according to purpose: header, nav, main, section, article, aside, footer, form, button and appropriate lists.
2. Every page must have one clear primary main landmark and a logical heading hierarchy. Do not choose heading levels for visual size.
3. Use real button elements for actions and real anchors/Next.js Link elements for navigation. Never use clickable div or span elements as substitutes.
4. Buttons must have explicit type=button unless they intentionally submit a form.
5. Associate every form control with a visible label. Placeholder text is not a label.
6. Group related controls with fieldset and legend when appropriate.
7. Give icon-only controls accessible names, including search, cart, menu, close, wishlist and quantity controls.
8. Use ARIA only when native HTML cannot express the required semantics. Keep ARIA states synchronized with actual UI state.
9. Dialogs and drawers must manage focus, expose an accessible name, support Escape where appropriate, and return focus to the opener when closed.
10. Preserve logical DOM and keyboard reading order. Do not use CSS ordering to create a misleading sequence.

## 3. Image and media requirements
1. Every meaningful image must have concise, descriptive alternative text describing its purpose or content.
2. Decorative images must include empty alt text so assistive technology can ignore them.
3. Never use filenames, keyword stuffing or repeated brand slogans as alternative text.
4. Product images should identify the actual fragrance and view where useful: bottle, campaign/lifestyle or ingredient.
5. Images that are the only content of a link or button must provide an accessible name through alt text or the parent control.
6. Provide captions or transcripts for meaningful video/audio. Hero video must not be the only source of essential information.
7. Autoplay video must be muted, must not flash, and must provide a pause/stop mechanism when motion persists. Respect reduced motion and use a static poster/fallback.
8. Reserve image dimensions or aspect ratios to prevent layout shifts. Lazy-load below-the-fold images; eager-load only critical above-the-fold imagery.

## 4. Keyboard navigation and interaction
1. Every interactive element must be reachable and operable using a keyboard alone.
2. Use natural tab order. Do not add positive tabindex values.
3. Menus, mega menus, drawers, modals, accordions, carousels, filters and selectors must have clear keyboard behavior.
4. Escape must close dismissible overlays when appropriate. Focus must be contained inside modal dialogs while open.
5. Do not trap keyboard focus in non-modal UI.
6. Never communicate state using color alone; include text, icons with accessible labels, or another perceivable cue.
7. Provide a visible skip link to main content.
8. Hover-only information must also be available on focus and touch.
9. Avoid keyboard traps and ensure all controls have a predictable focus sequence.
10. Aim for touch targets of at least 44 × 44 CSS pixels for primary controls. Never fall below WCAG 2.2 AA minimum target-size requirements without a valid exception.

## 5. Focus appearance and brand palette
1. Every interactive element must have a clearly visible focus-visible style. Removing the browser outline without a stronger replacement is prohibited.
2. Focus indicators should use the active brand focus token: Legendary Profile champagne/antique gold; Informal light terracotta/warm sand; Amane blush/pearl; Sasha violet/chrome; house-wide UI champagne gold.
3. Focus indicators must remain distinguishable against both the component and adjacent background. Use a two-tone or offset ring where a single accent blends into the surface.
4. Focus indicators must not be clipped by cards, containers or overflow-hidden wrappers.
5. Hover styling is never a substitute for focus styling.

Suggested CSS contract: define --brand-focus at the root and override it on brand page roots. Use :focus-visible with a 2px outline and at least 3px outline offset. Validate contrast against actual adjacent surfaces.

## 6. Color contrast and visual clarity

IKKI targets WCAG 2.2 Level AA as the minimum baseline.

| Content type | Minimum contrast |
|---|---:|
| Normal text | 4.5:1 |
| Large text (at least 24px regular or approximately 18.66px bold) | 3:1 |
| Essential UI boundaries, icons and graphical controls | 3:1 |
| Focus indicators | Clearly distinguishable; target at least 3:1 against adjacent colors |

Additional rules:
1. Test actual foreground/background combinations, including text over photography, gradients, overlays and hover states.
2. Gold, bronze, blush and violet accents are not automatically readable text colors. Use sufficiently contrasting foreground/background variants.
3. Do not use low-opacity muted text for essential instructions, product details, prices, form labels or error messages unless contrast still passes.
4. Color must not be the only way to identify errors, success, selected filters, availability or order status.
5. Disabled controls must remain perceivable; do not make important information unreadable.
6. Check all four brand palettes independently and all light/dark surface combinations.
7. If text conveys information, it must meet the relevant contrast requirement even when styled as decorative editorial typography.

## 7. Motion and animation accessibility
1. Every animation system must respect prefers-reduced-motion: reduce.
2. Reduced-motion behavior must cover Framer Motion, GSAP/ScrollTrigger, Lenis, parallax, text reveals, cursor effects, carousels, page transitions and CSS animations.
3. When reduced motion is enabled, remove non-essential movement, parallax, smooth scrolling, cursor-follow and staggered entrances. Present content immediately and preserve functionality.
4. Never hide essential content until an animation runs. Content must remain available if JavaScript or animation initialization fails.
5. Avoid flashing content and rapid repeated movement.
6. Provide pause, stop or hide controls for automatically moving content that lasts more than five seconds or repeats alongside other content, unless essential.
7. Do not make navigation, form submission, cart updates or other essential actions depend on animation completion.
8. Smooth scrolling must not prevent keyboard users from reaching anchors or interfere with browser navigation.
9. Custom cursors are decorative enhancements only. Native pointer behavior must remain usable; disable custom cursors on coarse pointers and reduced-motion preferences.
10. Test reduced motion at OS/browser level, not only through a local component flag.

CSS baseline: inside @media (prefers-reduced-motion: reduce), set animation-duration and transition-duration to 0.01ms, animation-iteration-count to 1 and scroll-behavior to auto. JavaScript libraries must also check the preference and skip or revert their motion setup; CSS alone is not sufficient for GSAP or Lenis.

## 8. Forms, feedback and error handling
1. Every input has a persistent accessible label and clear required/optional status.
2. Validation errors must be specific, associated with the relevant field and announced to assistive technology.
3. On failed submission, identify the error summary and move focus appropriately without losing user-entered data.
4. Success and cart-update messages must be announced using suitable live-region semantics.
5. Loading states must communicate what is happening and prevent accidental duplicate submissions.
6. Never use color alone to communicate invalid, successful or disabled states.
7. Payment and checkout errors must be understandable, actionable and keyboard accessible.

## 9. Responsive and accessibility quality gates
- [ ] Mobile layout works at 320px, 375px and 430px.
- [ ] Tablet layout works around 768px.
- [ ] Laptop layout works around 1024px and 1280px.
- [ ] Large desktop layout works at 1440px and wider.
- [ ] No unintended horizontal overflow at any tested width.
- [ ] Semantic landmarks and logical heading order are present.
- [ ] Every image has meaningful alt text or empty alt text when decorative.
- [ ] All controls work with keyboard only.
- [ ] Focus is visible, branded and not clipped.
- [ ] Dialogs, drawers and menus manage focus correctly.
- [ ] Text and UI contrast meet WCAG 2.2 AA.
- [ ] Reduced-motion mode removes non-essential animation and keeps content visible.
- [ ] Touch targets are comfortably usable.
- [ ] Forms have labels, instructions and accessible error/success feedback.
- [ ] Images reserve layout space and load appropriately.
- [ ] No essential content or action relies solely on hover, color, animation or video.

Required review tools: Lighthouse accessibility audit, axe DevTools or axe-core, browser keyboard-only review, OS/browser reduced-motion setting, contrast checker for all brand combinations, and responsive browser testing at the widths above.

## 10. Enforcement rule for every future implementation
Before implementing any new IKKI page, component or feature:
1. Read this document.
2. Apply responsive and accessibility rules during design and implementation, not as later polish.
3. Reuse shared tokens and components where possible.
4. Test the changed experience at relevant breakpoints and keyboard/reduced-motion states.
5. Report any known exception explicitly; do not silently ship a violation.

**Definition of done:** A feature is not complete until it is visually responsive, keyboard operable, semantically structured, contrast-checked and usable with reduced motion enabled.