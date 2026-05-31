# Yemdat Design System

A token-driven, bilingual (EN/AR, RTL-aware) component layer for the public site.
Built on **Tailwind CSS v4** (configured in `resources/css/app.css`, no `tailwind.config.js`)
and **Alpine.js**. The goal: one source of truth for brand, color, spacing, and components
so screens stay consistent and are quick to build.

> Status: foundation (tokens + core components + preview). Views are migrated to these
> components incrementally — see "Adoption" below.

## Tokens

Defined in the `@theme` block of [resources/css/app.css](resources/css/app.css). Tailwind
generates utilities from them (e.g. `--color-primary` → `bg-primary`/`text-primary`/`border-primary`).

### Colors
| Token (utility)        | Value     | Use |
|------------------------|-----------|-----|
| `primary`              | `#593E2D` | Primary actions, headings (brand brown) |
| `primary-hover`        | `#3E2B20` | Primary hover |
| `accent`               | `#F2CB57` | Highlights, focus rings (gold) |
| `accent-hover`         | `#C88D16` | Accent hover (orange) |
| `surface`              | `#FAF8F3` | Page background (beige) |
| `surface-raised`       | `#FFFFFF` | Cards, panels |
| `surface-sunken`       | `#EFE9DE` | Subtle fills, table stripes |
| `border`               | `#E6DFD2` | Hairline borders |
| `ink` / `ink-soft`     | `#3E2B20` / `#6B5847` | Primary / muted text |
| `success/danger/warning/info` (+ `-soft`) | — | Status colors and their tinted backgrounds |

The raw brand palette (`yemdat-beige|brown|gold|orange|dark` + `cream|sand|gold-soft`) is kept
for backward compatibility with existing views. **Prefer the semantic tokens in new work.**

### Radii / shadows / fonts
- `rounded-btn` (`--radius-btn`, 0.625rem), `rounded-card` (`--radius-card`, 1rem)
- `shadow-card`, `shadow-pop`
- `font-sans` (Instrument Sans, LTR) and `font-arabic` (Tajawal, RTL). Both layouts switch the
  body font automatically based on `app()->getLocale()`.

## Components

Anonymous Blade components in [resources/views/components/ui/](resources/views/components/ui/),
used as `<x-ui.NAME>`:

| Component | Key props | Notes |
|-----------|-----------|-------|
| `<x-ui.button>`   | `variant` (primary\|accent\|outline\|ghost\|danger), `size` (sm\|md\|lg), `href`, `type` | Renders `<a>` when `href` is set, else `<button>` |
| `<x-ui.card>`     | `padding` | Raised surface (`shadow-card`, `rounded-card`) |
| `<x-ui.badge>`    | `variant` (neutral\|accent\|success\|danger\|warning\|info) | Pills for statuses |
| `<x-ui.alert>`    | `variant` (success\|danger\|warning\|info), `title` | Flash messages / notices |
| `<x-ui.label>`    | `for`, `required` | Form label (adds `*` when required) |
| `<x-ui.input>`    | `name`, `type`, `label`, `value`, `required`, `id` | Repopulates via `old()`, shows `@error` and red state |
| `<x-ui.textarea>` | `name`, `label`, `value`, `required`, `rows`, `id` | As above |
| `<x-ui.page-header>` | `title`, `subtitle` | Section heading |

Extra HTML attributes (`placeholder`, `class`, `autocomplete`, …) are forwarded via attribute merging.

### Example
```blade
<x-ui.card>
    <x-ui.page-header title="Contact us" subtitle="We'll reply within 2 business days." class="mb-4" />
    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
        @csrf
        <x-ui.input name="name" label="{{ __('contact.name') }}" :required="true" />
        <x-ui.input name="email" type="email" label="Email" :required="true" />
        <x-ui.textarea name="message" label="Message" :required="true" />
        <x-ui.button type="submit">Send</x-ui.button>
    </form>
</x-ui.card>
```

## Preview

Run the app locally and visit **`/ui-preview`** (registered only when `APP_ENV=local`). It renders
every component in both LTR/English and RTL/Arabic so you can check direction and fonts.

## RTL

Layouts set `dir="rtl"` and `font-arabic` for Arabic. In components, prefer **logical** utilities
(`ps-*`/`pe-*`, `ms-*`/`me-*`, `text-start`/`text-end`) or `rtl:`/`ltr:` variants over hard
left/right, so a single component works in both directions.

## Adoption (incremental)

Existing views use inline utility classes. We migrate them to `<x-ui.*>` **page by page** rather
than in one big sweep (smaller diffs, easier to verify, friendlier to the file-upload deploy
workflow). When you touch a public view, replace ad-hoc buttons/cards/inputs/alerts with the
components above.
