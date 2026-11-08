# Ikki — Fragrance, considered.

Premium fragrance storefront built with Next.js 14 App Router, TypeScript, Tailwind CSS and Framer Motion.

## Development

```bash
npm install
npm run dev
```

## Build

```bash
npm run build
```

The project uses Next.js static export (`out/`) for InfinityFree shared hosting. Live checkout, accounts, payment callbacks and order management require a separately hosted API/backend because static hosting cannot execute Next.js server routes or server actions.

## Structure
- `app/`: routes, root layout and global styles
- `components/`: shared layout, page sections, product UI and motion
- `lib/data/`: typed catalogue and navigation data
- `public/images/`: local brand and product imagery


## Responsive and accessibility standards

All page and component work must follow the mandatory [IKKI Responsive & Accessibility Standards](./docs/RESPONSIVE-ACCESSIBILITY-STANDARDS.md). This is the project-wide reference for breakpoints, semantic HTML, image alternatives, keyboard access, focus treatment, motion preferences and WCAG 2.2 AA contrast.
