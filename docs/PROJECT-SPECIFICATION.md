# Ikki Project Specification

Brand Ikki; domain ikki.pk; Pakistan market; own-brand perfume collection of approximately six launch fragrances; PKR; PHP/MySQL; InfinityFree; WhatsApp +923094733789; COD at launch.

## Design
Compact dark luxury: near-black, charcoal and restrained gold. Editorial typography, generous whitespace, cinematic product imagery, subtle animation and mobile-first layouts. Avoid crowded grids, excessive sliders, popups and unnecessary page sections.

## Storefront
Homepage, collection, product details, cart, checkout, contact, about, shipping, returns, privacy and terms. Product fields include fragrance family, concentration, gender, top/heart/base notes, image gallery and multiple size variants.

## CMS
Dashboard, products/variants, categories, nested navigation menus, page manager, homepage section manager, banner/media manager, store settings, orders, inventory, admin roles and audit logs. Initial page builder uses safe predefined content blocks, not arbitrary PHP execution.

## WhatsApp order flow
Server validates cart and stock, fetches prices from MySQL, calculates totals, creates order/items in a DB transaction, sets awaiting_whatsapp_confirmation, then redirects to wa.me with URL-encoded details. Customer must press Send. Opening WhatsApp is not proof of delivery. Admin manually verifies and confirms.

## Security
PDO prepared statements, password hashing, secure sessions, CSRF, server-side price and stock checks, output escaping, strict upload validation, admin authorization and audit logs. No secrets or customer data in Git.
