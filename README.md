# IKKI — Luxury Perfume Store

Private PHP/MySQL e-commerce project for IKKI, a curated own-brand fragrance store serving Pakistan.

- Domain: https://ikki.pk
- WhatsApp: +923094733789 (wa.me format: 923094733789)
- Currency: PKR
- Stack: PHP, MySQL, HTML, CSS, JavaScript
- Hosting target: InfinityFree
- Checkout: Save order in MySQL, then open WhatsApp with a prefilled order summary. Customer must press Send; this is not automatic WhatsApp delivery.

## Storefront and launch collection
The storefront now includes an editorial luxury homepage, collection grid, product detail pages, cart, checkout, order handoff, contact, delivery, returns and privacy pages. The storefront visual system uses a white and cream palette, champagne-gold accents, Playfair Display/Montserrat typography, responsive layouts, custom SVG bottle concept artwork and reduced-motion-aware reveal effects.

Four proposed launch concepts are included: Noor, Sahra, Velvet Dusk and Azure 04. Their fragrance notes, descriptions and PKR prices are illustrative concept content, not verified formula or retail information. Confirm final product names, compositions, pricing, photography and stock before taking live orders.

## Seed the four sample products
After importing `database/schema.sql`, import `database/seed-products.sql` into the same database. This creates the four proposed products, one 50 ml variant per product and local SVG concept artwork references. Review and change sample prices and stock in the admin dashboard before launch. The seed script is designed to be re-runnable without duplicating the same slugs/SKUs.

## Setup
1. Create a MySQL database and import `database/schema.sql`.
2. Copy `config/database.example.php` to `config/database.php` and add hosting credentials.
3. In `config/database.php`, define a long random `IKKI_ADMIN_SETUP_KEY` constant (or set it as an environment variable) before using the one-time admin setup endpoint.
4. Upload to PHP hosting with HTTPS enabled.
5. Ensure `storage/uploads` is writable and cannot execute scripts.
6. Open `/admin/setup.php?key=YOUR_SETUP_KEY` once to create the first admin, then remove/disable setup.php.
7. Add products and variants from admin. The starter storefront will show published products.

## Security
Never commit real credentials, admin passwords, customer data or database dumps. Use HTTPS. Use PDO prepared statements, CSRF tokens, server-side price/stock validation and strong admin passwords.

## Hosting note
Check the current InfinityFree PHP/MySQL versions, storage/upload limits, SSL and deployment options before production. The application intentionally avoids mandatory Composer packages and background workers.
