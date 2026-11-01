# InfinityFree Deployment

1. Verify current PHP/MySQL versions, storage, upload limits, SSL and deployment options.
2. Create a MySQL database and import database/schema.sql in phpMyAdmin.
3. Copy config/database.example.php to config/database.php and enter credentials. Never commit this file.
4. Upload project files using a hosting-supported method.
5. Map ikki.pk and enable HTTPS before collecting customer details.
6. Ensure storage/uploads is writable and PHP execution is blocked there.
7. Create the first admin through a secure one-time setup process; remove/disable that process after use.
8. Test mobile layout, cart, stock validation, order persistence, WhatsApp handoff and admin access.
9. Configure database backups and test restore.

Do not assume automatic GitHub deployment, Composer or background workers are supported by the selected free hosting plan.
