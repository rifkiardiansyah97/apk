# ServisPro Production
PHP 8.2+ / MySQL 8+ application skeleton for computer-service management.

## Deploy
1. Create a MySQL database and dedicated DB user.
2. Import `database/schema.sql`.
3. Copy `app/config.example.php` to `app/config.php` and fill DB credentials and HTTPS app URL.
4. Set the hosting Document Root to `public/`.
5. Open `/install.php` once, create the first admin, then DELETE `public/install.php`.
6. Use HTTPS only. Never expose `app/`, `database/`, or `storage/`.
7. Take encrypted/offsite backups and keep PHP/MySQL updated.

## Security baseline
Prepared statements, CSRF, password hashing (Argon2id), secure session cookies, session regeneration, login rate limiting, security headers/CSP, output escaping, server-side validation, audit logs, role checks, and private application/storage directories.

No web application can honestly be guaranteed 100% immune to hacking. Use HTTPS, strong unique passwords, least-privilege DB credentials, WAF/firewall, updates, backups, monitoring, and remove `install.php` after setup.
