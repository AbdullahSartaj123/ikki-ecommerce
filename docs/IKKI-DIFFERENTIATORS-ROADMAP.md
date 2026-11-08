# IKKI — Experience Differentiators & Build Roadmap

**Purpose:** Define the distinctive digital experiences that should make IKKI feel like a modern, immersive, multi-brand fragrance house.
**Competitive reference:** Azzaro official fragrance site, reviewed 8 November 2026.
**Status:** Product direction approved; implementation is planned for subsequent build tasks.
**Priority commitment:** The six initiatives marked P1 are the first experience differentiators to build.

## Competitive positioning

Azzaro's official fragrance experience already uses campaign-led product storytelling, fragrance-family language and fragrance-wheel imagery, plus brand philosophy and collection discovery. IKKI should not claim that basic storytelling, product grids or note descriptions are unique inventions. Our opportunity is to combine a four-brand house architecture with more interactive, personalized and responsive digital experiences.

Reference reviewed: https://www.azzaro.com/en/fragrances

## Ten ways IKKI can feel distinctive

1. **Scent Finder — guided fragrance discovery (P1).** A short, elegant quiz asks about mood, preferred notes, occasion, intensity and style, then recommends fragrances from the four IKKI brands with transparent explanations. It should feel like a personal consultation, not a generic survey.
2. **Dynamic Four-Brand Theme Switcher (P1).** Moving between Legendary Profile, Informal, Amane and Sasha changes the page's accent palette, surface treatment, typography details, motion character and campaign art direction. Shared components remain consistent and accessible.
3. **Cinematic, Video-First House Homepage (P1).** An editorial opening built around art-directed campaign video or still imagery, restrained typography, sound-off playback, responsive poster fallbacks and a clear route into the four brand worlds.
4. **Interactive 360° Bottle Experience (P1).** A product viewer supports drag-to-rotate using a real supplied image sequence or 3D asset, with a lightweight static-image fallback for mobile, reduced motion and unsupported devices. No fake 3D effect presented as an actual product view.
5. **Animated Ingredient & Note Atlas (P1).** A visual exploration of top, heart and base notes, linking ingredient imagery to the fragrance story and scent family. It should add context beyond a conventional fragrance wheel.
6. **Scroll-Driven Brand Storytelling (P1).** Editorial sequences connect campaign imagery, brand philosophy, ingredient details and product reveals through carefully paced scroll transitions, with reduced-motion and non-scroll alternatives.
7. **Layered Fragrance Comparison Studio (P2).** Compare two or three fragrances side-by-side by notes, scent family, mood, size and price; include a clear difference summary to support confident decisions.
8. **Discovery Wardrobe / Sample Set Builder (P2).** Let visitors curate a small discovery selection across the four brands before choosing a full bottle. Availability and pricing must come from real catalogue and inventory data.
9. **Personal Fragrance Wardrobe (P2).** An opt-in account feature to save favorites, quiz outcomes, scent preferences and previously explored fragrances, with clear privacy controls.
10. **Live Editorial Product Micro-interactions (P2).** Thoughtful tactile details—ingredient hotspots, bottle detail zoom, product-card pointer lighting, responsive transitions and contextual quick views—used selectively rather than as decorative clutter.

## The six committed priorities

The following six are the first differentiators to implement in future prompts, in this order:

| Order | Initiative | Outcome | Key dependencies |
|---:|---|---|---|
| 1 | Scent Finder | Personal recommendations across all four brands | Approved questionnaire, fragrance metadata, recommendation rules |
| 2 | Dynamic Brand Theme Switcher | Four distinct brand worlds on one coherent platform | Brand token model, accessible contrast variants, brand routes |
| 3 | Cinematic Video-First Homepage | Strong first impression and clear brand discovery | Approved campaign footage/stills, poster images, performance budget |
| 4 | Interactive 360° Bottle Experience | More tactile product inspection | Real bottle photo sequence or optimized 3D assets for each product |
| 5 | Animated Ingredient & Note Atlas | Distinctive fragrance education and product storytelling | Verified ingredient/notes data and ingredient photography |
| 6 | Scroll-Driven Brand Storytelling | Immersive brand narratives without sacrificing usability | Editorial scripts, imagery/video, GSAP/Framer motion integration |

P2 ideas remain in the product backlog and can follow after the six priority experiences are stable.

## Non-negotiable implementation standards

- Follow `docs/RESPONSIVE-ACCESSIBILITY-STANDARDS.md` for every experience.
- Mobile-first; preserve usable layouts at 320px and above.
- Meet WCAG 2.2 AA contrast requirements and provide full keyboard operation.
- Respect `prefers-reduced-motion`; never hide essential content behind animation.
- Optimize video, image sequences and 3D assets; use poster/static fallbacks and lazy loading.
- Do not fabricate product performance, ingredients, stock, reviews, testimonials or user personalization.
- Use real approved product photography and assets when available. Until supplied, use clearly identified placeholders.
- Keep the existing static-export/InfinityFree constraint in mind. Interactive client experiences can run on the storefront, but persistent profiles, live inventory, quiz history and checkout integrations require an appropriate backend/API.
- Build each initiative as reusable, typed components connected to the existing brand and product data layer.
- Validate type-check, production build, responsive behavior and accessibility before considering an initiative complete.
- Do not deploy to production or alter DNS as part of these feature builds unless separately authorized.

## Delivery sequence for future prompts

Each P1 initiative should be delivered as a complete vertical slice: architecture and data requirements, reusable components, responsive UI, accessible interactions, integration with existing routes/data, and validation. Do not create disconnected demo components or mark a feature complete when it is only a visual mockup.

**Commitment:** Treat the six P1 initiatives as the agreed IKKI differentiator roadmap and build them when the user advances to each relevant implementation prompt. This roadmap records the commitment; it does not claim that the six features have already been implemented.
