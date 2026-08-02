# TechAasvik — Deployment Guide (Hostinger Shared Hosting)

## Pre-Deployment Checklist

- [ ] ZIP entire `techaasvik website_new` folder
- [ ] Create MySQL database on Hostinger
- [ ] Edit `app/Config/config.php` with live DB credentials
- [ ] Upload files to `public_html` (or domain folder)
- [ ] Run `setup.php` once, then delete it
- [ ] Verify `.htaccess` is uploaded (enable hidden files in FTP client)

---

## Step 1 — Upload Files

Via Hostinger File Manager or FTP (FileZilla):

```
Upload ALL contents of "techaasvik website_new/" → public_html/
```

> **Important**: Upload the *contents* of the folder, not the folder itself.

### Folder Structure in `public_html/`:
```
public_html/
├── index.php           ← Front controller
├── .htaccess           ← URL rewriting
├── robots.txt
├── site.webmanifest
├── app/
├── assets/
├── views/
├── storage/            ← Create manually, chmod 755
└── database/
```

---

## Step 2 — Create Database

1. Hostinger Control Panel → **MySQL Databases**
2. Create a new database: e.g. `u123456_techaasvik`
3. Create a database user with a strong password
4. Assign user to database with **All Privileges**

---

## Step 3 — Configure app/Config/config.php

Edit these values with your Hostinger credentials:

```php
'database' => [
    'host'    => 'localhost',
    'port'    => 3306,
    'name'    => 'u123456_techaasvik',   // Your DB name
    'user'    => 'u123456_dbuser',       // Your DB user
    'pass'    => 'YourSecurePassword',   // Your DB password
    'charset' => 'utf8mb4',
],
'site' => [
    'url'  => 'https://t1.techaasvik.com',   // Your live domain
    'name' => 'TechAasvik',
],
```

---

## Step 4 — Run Setup Script

1. Navigate to: `https://t1.techaasvik.com/setup.php?token=setup_ta_2026_xK9mP2`
2. This creates all tables and inserts the default admin user:
   - **URL**: `/techaasvik_admin`
   - **Username**: `admin`
   - **Password**: `techaasvik@27`
3. **DELETE `setup.php` immediately** after it runs successfully

---

## Step 5 — Verify .htaccess

Ensure Hostinger has **mod_rewrite enabled** (it is by default on shared hosting).

The `.htaccess` file should be in `public_html/`:
```apache
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

---

## Step 6 — Set File Permissions

Via Hostinger File Manager or SSH:

```bash
chmod 755 storage/
chmod 755 storage/uploads/
chmod 755 storage/cache/
chmod 755 storage/logs/
chmod 644 .htaccess
chmod 644 robots.txt
```

---

## Step 7 — Test Key URLs

| URL | Expected |
|-----|----------|
| `https://t1.techaasvik.com/` | Homepage |
| `https://t1.techaasvik.com/blog` | Blog index |
| `https://t1.techaasvik.com/glossary` | Glossary |
| `https://t1.techaasvik.com/tools` | Tools index |
| `https://t1.techaasvik.com/learn` | Knowledge Center |
| `https://t1.techaasvik.com/techaasvik_admin` | Admin Login |
| `https://t1.techaasvik.com/sitemap.xml` | XML Sitemap |

---

## Step 8 — Admin Panel

**Login URL**: `https://t1.techaasvik.com/techaasvik_admin`  
**Password**: `techaasvik@27`

### First Admin Tasks:
1. **Settings** → Update site name, email, GA4 ID
2. **Categories** → Create your content categories (SEO, Google Ads, etc.)
3. **Tags** → Add your key tags
4. **Content** → Create your first blog post or glossary term
5. **Media** → Upload your logo and key images

---

## Post-Launch SEO Setup

1. **Google Search Console**: Verify domain → Submit `sitemap.xml`
2. **Google Analytics 4**: Add GA4 Measurement ID in Settings
3. **Bing Webmaster Tools**: Submit sitemap
4. **Google Business Profile**: If applicable

---

## PHP Version

Hostinger → **PHP Configuration** → Set to **PHP 8.2** (required)

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| 500 error | Check `storage/logs/php_errors.log` |
| 404 on all pages | `.htaccess` not uploaded or mod_rewrite off |
| DB connection error | Check credentials in `config.php` |
| Admin login fails | Re-run `setup.php` or reset password in DB |
| Images not loading | Check `storage/uploads/` permissions (755) |
