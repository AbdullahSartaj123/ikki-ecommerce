# Ikki — Premium Perfume Store

Private PHP/MySQL e-commerce project for Ikki, a curated own-brand fragrance store serving Pakistan.

- Domain: https://ikki.pk
- WhatsApp: +923094733789 (wa.me format: 923094733789)
- Currency: PKR
- Stack: PHP, MySQL, HTML, CSS, JavaScript
- Hosting target: InfinityFree
- Checkout: Save order in MySQL, then open WhatsApp with a prefilled order summary. Customer must press Send; this is not automatic WhatsApp delivery.

## Initial build
This repository contains the first application foundation: storefront, product catalogue, cart, server-side checkout/order creation, WhatsApp handoff, initial admin login/dashboard, schema and documentation. CMS modules will be built iteratively.

## Setup
1. Create a MySQL database and import `database/schema.sql`.
2. Copy `config/database.example.php` to `config/database.php` and add hosting credentials.
3. Set a strong `IKKI_ADMIN_SETUP_KEY` in your environment/config before using the one-time admin setup endpoint.
4. Upload to PHP hosting with HTTPS enabled.
5. Ensure `storage/uploads` is writable and cannot execute scripts.
6. Open `/admin/setup.php?key=YOUR_SETUP_KEY` once to create the first admin, then remove/disable setup.php.
7. Add products and variants from admin. The starter storefront will show published products.

## Security
Never commit real credentials, admin passwords, customer data or database dumps. Use HTTPS. Use PDO prepared statements, CSRF tokens, server-side price/stock validation and strong admin passwords.

## Hosting note
Check the current InfinityFree PHP/MySQL versions, storage/upload limits, SSL and deployment options before production. The application intentionally avoids mandatory Composer packages and background workers.
