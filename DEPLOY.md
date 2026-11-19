# Auto-deploy (office.areducation.net)

Push to `main` → GitHub Actions runs `composer install --no-dev` → FTP uploads Laravel to the subdomain folder.

## cPanel document root

Subdomain `office.areducation.net` document root must point to Laravel **`public`**:

- Example: `/home/aredkrko/office.areducation.net/public`

If cPanel created `office.areducation.net` as the app root, keep Laravel files there and set docroot to its `public` folder.

## GitHub Secrets (aredu-admin)

| Secret | Example value |
|--------|----------------|
| `FTP_SERVER` | `ftp.areducation.net` or `63.250.38.167` |
| `FTP_USERNAME` | cPanel/FTP user |
| `FTP_PASSWORD` | FTP password |
| `FTP_SERVER_DIR` | `/office.areducation.net/` |

Confirm the exact remote folder in cPanel → **Domains / Subdomains** (sometimes `/public_html/office/`).

## Server `.env` (manual, once)

FTP deploy **never overwrites** `.env`. On the server create/edit:

```
APP_URL=https://office.areducation.net
```

CORS / frontend origin should allow `https://areducation.net`.

After first deploy (SSH/Terminal if available):

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

Uploads stay in `storage/` / `public/uploads` — workflow does not wipe them (`dangerous-clean-slate: false`).
