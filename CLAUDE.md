# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Full setup
composer run setup
# Equivalent: composer install && cp .env.example .env && php artisan key:generate && php artisan migrate --force && npm install && npm run build

# Development (runs server, queue, log tailing, and Vite concurrently)
composer run dev

# Run tests
composer run test
# Equivalent: php artisan config:clear && php artisan test

# Build assets
npm run build       # production
npm run dev         # watch mode
```

## Architecture

**Yemdat** is a bilingual (English/Arabic) community platform and training portal built on Laravel 12.

### Dual Authentication System

There are two completely separate user models with separate guards:

- **`User` model** — admin staff, uses the `web` guard
- **`Member` model** — community members, uses the `member` guard

These guards are defined in `config/auth.php`. Controllers and middleware must reference the correct guard. Never mix them.

### Route Structure

Routes in `routes/web.php` are organized into three sections:

1. **Public routes** — home, about, events listing, news, contact form, trainer application, membership registration, language switcher
2. **Member routes** (prefixed `/member`, guarded by `auth:member`) — profile, event registration, account deletion via OTP
3. **Admin routes** (prefixed `/admin`, guarded by `auth`) — full CRUD for members, events, posts, tiers, email templates, settings; analytics dashboard

### Bilingual Content Pattern

The app supports English and Arabic via session-based locale switching (`/lang/{locale}`). Models with multilingual content use dual database columns:

```
title_en / title_ar
description_en / description_ar
```

Models define a dynamic accessor (e.g., `getTitleAttribute()`) that returns the correct column based on `app()->getLocale()`. When adding new bilingual fields, follow this same pattern and update migrations with the `_bilingual` naming convention.

### OTP Authentication

Security-critical flows use OTP (not magic links):
- Email verification on signup (`VerificationController`)
- Password reset (`ForgotPasswordController`)
- Account deletion confirmation (`AccountDeletionController`)

### Email Template System

Email content is stored in the `email_templates` database table and editable by admins. Mail classes use `DynamicEmailTrait` to load templates at send time. When adding new email types, add a seeder entry and a corresponding Mail class using that trait.

### Events

- Events use UUID primary keys via the `HasUuids` trait
- Member ↔ Event relationship is many-to-many through the `event_member` pivot table

### Frontend

- **Tailwind CSS v4** via `@tailwindcss/vite` — no separate `tailwind.config.js`; config lives in `resources/css/app.css`
- **Alpine.js v3** for interactive UI components (modals, dropdowns, form state)
- Asset entry points: `resources/css/app.css` and `resources/js/app.js`

### Testing

Tests use SQLite in-memory (configured in `phpunit.xml`). Production uses MySQL (`yemdatdb`). The `composer run test` script clears config cache first to avoid stale environment issues.
