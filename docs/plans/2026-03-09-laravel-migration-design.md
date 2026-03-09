# 4est.info — Laravel Migration Design

**Date**: 2026-03-09
**Approach**: Big Bang rewrite — new Laravel project, old site stays live until ready

## Stack

- Laravel 12 + PHP 8.3
- MySQL 8
- Tailwind CSS
- Blade templates (no starter kit, custom auth)
- Docker Compose (local dev)
- Vite (CSS/JS build)

## Database Models & Relations

### User
- id, name, email, password (bcrypt), role (admin/customer), timestamps
- Future: hasMany Orders

### Category
- id, name, slug, timestamps
- hasMany Creations
- Translated via lang files (only 4 categories)

### Material
- id, name, timestamps
- hasMany Creations
- Translated via lang files

### Creation
- id, size, price, download, category_id, material_id, timestamps
- belongsTo Category, Material
- hasMany GalleryImages
- hasMany CreationTranslations

### CreationTranslation
- id, creation_id, locale (ro/en), name, slug, description
- unique constraint: [creation_id, locale]
- belongsTo Creation

### GalleryImage
- id, creation_id, filename, ranking, timestamps
- belongsTo Creation

### Statistic
- id, ip, city, state, country, continent, page, created_at
- Populated by TrackVisit middleware

## Routing

### Public (prefixed with /{locale})
```
GET  /{locale}                          → Home
GET  /{locale}/creations                → Creations grid
GET  /{locale}/creations/{slug}         → Creation detail
GET  /{locale}/about                    → About page
GET  /{locale}/contact                  → Contact form
POST /{locale}/contact                  → Send email
```
- `/` redirects to `/ro` (or detect from Accept-Language)

### Auth
```
GET  /login                     → Login form
POST /login                     → Authenticate
POST /logout                    → Logout
GET  /register                  → Register form
POST /register                  → Create account
GET  /forgot-password           → Password reset
```

### Admin (middleware: auth + admin)
```
GET    /admin                        → Dashboard (visitor stats)
GET    /admin/creations              → List creations
GET    /admin/creations/create       → Add form
POST   /admin/creations              → Store creation
GET    /admin/creations/{id}/edit    → Edit form
PUT    /admin/creations/{id}         → Update creation
DELETE /admin/creations/{id}         → Delete creation
GET    /admin/users                  → List users
       ... (similar CRUD)
```

## Middleware
- `SetLocale` — reads /{locale} prefix, sets app locale
- `AdminOnly` — checks user role === admin
- `TrackVisit` — logs visitor IP + geolocation to statistics table
- `BlockIp` — blocks IPs from DB list (replaces hardcoded list)

## Services

### ImageService
- Upload images per creation
- Resize to 1920x1080 preserving aspect ratio
- Watermark "4est" with awery.smallcaps.ttf font
- Generate thumbnails for grid
- Storage: storage/app/public/creations/{id}/

### Mail
- Laravel Mail for contact form
- reCAPTCHA v2 validation

### StatisticsService
- GeoPlugin API for IP geolocation
- Admin dashboard with recent visits

## Internationalization (i18n)
- Route prefix: /{locale} (ro, en)
- UI strings: lang/ro.json, lang/en.json
- Content: CreationTranslation table (name, slug, description per locale)
- Categories/Materials: translated via lang files

## Docker Compose (local dev)
```
services:
  app:      PHP 8.3-FPM + Composer + extensions (GD, PDO, mbstring, intl)
  nginx:    Reverse proxy → app:9000, port 8080
  mysql:    MySQL 8, port 3306
  mailpit:  Email testing, UI port 8025
  node:     Vite dev server (Tailwind build)
```
- Makefile: make up, make down, make migrate, make seed

## Data Migration
- Seed from existing SQL dumps (46 creations, 363 images, 4 categories, 5 materials)
- Copy images/ and downloads/ directories to Laravel storage
- Re-hash admin password with bcrypt

## Out of Scope (future)
- E-commerce (cart, orders, payments)
- Customer profile/dashboard
- Weather monitor (removed)

## Deploy (later)
- VPS (Hetzner or DigitalOcean)
- Configure after local dev is complete
