# SEO Comprehensive Audit and Fix Plan - Coffee Globe

## Executive Summary

After a thorough audit of the entire Coffee Globe codebase - frontend, backend, and infrastructure - **15+ critical SEO issues** were identified. The most severe problem is that **English content is completely invisible to search engines** because language switching relies on client-side `localStorage`, which Googlebot does not execute. This plan outlines a phased approach to achieve top search rankings for both Arabic and English content.

---

## Critical Issues Found

### Issue 1: hreflang Tags Are Broken
**File:** `resources/views/app.blade.php` lines 17-19

All three hreflang tags point to the same URL via `url()->current()`. Google requires DIFFERENT URLs for each language version.

### Issue 2: No URL-Based Language Routing
**Files:** `resources/js/Components/Header.vue`, `resources/js/app.js`

Language switching is entirely client-side via localStorage. Googlebot does NOT execute localStorage, so it only sees Arabic content. English content is completely invisible to search engines.

### Issue 3: Sitemap Has No Multilingual Support
**File:** `app/Console/Commands/GenerateSitemap.php`

Only generates single-language URLs. No alternate elements for hreflang. Blog URLs use numeric IDs instead of SEO-friendly slugs.

### Issue 4: Hardcoded Arabic Meta Tags on Most Pages
**Files:** All pages in `resources/js/Pages/Website/`

Index, About, Solution, Blogs, FQs pages all have hardcoded Arabic meta tags. Only Contact, PrivacyPolicy, and TermsOfService use dynamic translations.

### Issue 5: English Locale File Contains Arabic
**File:** `resources/js/locales/en.json`

The pagination and validation sections still contain Arabic text instead of English translations.

---

## Implementation Plan

### Phase 1: URL-Based Language Routing - CRITICAL

This is the most important change. Google needs unique crawlable URLs for each language.

**URL Structure:**
- Arabic default: `coffeeglobe.sa/`, `coffeeglobe.sa/about`, `coffeeglobe.sa/blog/slug`
- English: `coffeeglobe.sa/en/`, `coffeeglobe.sa/en/about`, `coffeeglobe.sa/en/blog/slug`

**Steps:**

1. **Create SetLocale middleware** - `app/Http/Middleware/SetLocale.php`
   - Read locale from URL prefix
   - Set `app()->setLocale()` accordingly
   - Share locale with Inertia

2. **Update routes** - `routes/web.php`
   - Wrap public website routes in locale-prefixed route groups
   - Arabic as default without prefix
   - English with `/en/` prefix
   - Keep admin routes without locale prefix

3. **Fix hreflang tags** - `resources/views/app.blade.php`
   - Generate correct alternate URLs based on current locale
   - Arabic hreflang points to non-prefixed URL
   - English hreflang points to `/en/` prefixed URL

4. **Update Header.vue language switcher** - `resources/js/Components/Header.vue`
   - Replace localStorage switching with URL-based navigation
   - Navigate to locale-prefixed URL instead of setting localStorage

5. **Share locale via Inertia** - `app/Providers/AppServiceProvider.php`
   - Add locale to Inertia shared props

6. **Update app.js** - `resources/js/app.js`
   - Read locale from Inertia props instead of hardcoding ar

### Phase 2: Dynamic SEO Meta Tags

Update all Website pages to use dynamic bilingual meta tags:

7. **Update Index.vue** - Dynamic title, description, keywords, OG tags
8. **Update About.vue** - Dynamic title, description, keywords, OG tags
9. **Update Solution.vue** - Dynamic title, description, keywords, OG tags
10. **Update Blogs.vue** - Dynamic title, description, keywords, OG tags
11. **Update Blog.vue** - Dynamic title, description, keywords, OG tags
12. **Update FQs.vue** - Dynamic title, description, keywords, OG tags
13. **Update Contact.vue** - Minor fixes for consistency
14. **Update PrivacyPolicy.vue and TermsOfService.vue** - Minor fixes
15. **Fix en.json** - Translate all remaining Arabic content to English

### Phase 3: Blog SEO and Sitemap

16. **Add slug column to blogs** - New migration for slug field
17. **Update Blog model and routes** - Use slugs instead of IDs for URLs
18. **Rewrite GenerateSitemap.php** - Multilingual URLs with hreflang alternates
19. **Update sitemap to use blog slugs** - SEO-friendly blog URLs

### Phase 4: Technical SEO Improvements

20. **Add gzip compression to nginx** - `docker/nginx/conf.d/coffeeglobe.sa.conf`
21. **Add WebP/AVIF content negotiation** - nginx config
22. **Fix canonical URL conflicts** - Remove global canonical from app.blade.php
23. **Remove duplicate i18n.js** - Delete `resources/js/utils/i18n.js`
24. **Update useSeo.js composable** - Bilingual support
25. **Fix og:locale** - Reflect actual page locale from URL

### Phase 5: Advanced SEO Optimizations

26. **Audit image alt tags** - Ensure all images have descriptive alt attributes
27. **Verify heading hierarchy** - Proper h1, h2, h3 structure on all pages
28. **Add preconnect for external resources** - Performance optimization

---

## Files to Modify

### New Files
- `app/Http/Middleware/SetLocale.php`
- `database/migrations/xxxx_add_slug_to_blogs_table.php`

### Modified Files
- `routes/web.php`
- `resources/views/app.blade.php`
- `resources/js/app.js`
- `resources/js/Components/Header.vue`
- `resources/js/Pages/Website/Index.vue`
- `resources/js/Pages/Website/About.vue`
- `resources/js/Pages/Website/Solution.vue`
- `resources/js/Pages/Website/Blogs.vue`
- `resources/js/Pages/Website/Blog.vue`
- `resources/js/Pages/Website/FQs.vue`
- `resources/js/Pages/Website/Contact.vue`
- `resources/js/Pages/Website/PrivacyPolicy.vue`
- `resources/js/Pages/Website/TermsOfService.vue`
- `resources/js/locales/en.json`
- `resources/js/locales/ar.json`
- `resources/js/Composables/useSeo.js`
- `app/Models/Blog.php`
- `app/Http/Controllers/Website/BlogsController.php`
- `app/Console/Commands/GenerateSitemap.php`
- `app/Providers/AppServiceProvider.php`
- `docker/nginx/conf.d/coffeeglobe.sa.conf`

### Files to Delete
- `resources/js/utils/i18n.js`

---

## Important Notes

1. **301 Redirects:** After adding slug-based blog URLs, add 301 redirects from old `/blog/{id}` URLs to preserve existing SEO equity.

2. **Google Search Console:** After deployment, submit new sitemap, request indexing for English URLs, and monitor hreflang errors.

3. **Testing:** Each phase should be tested independently before moving to the next.

4. **Database:** The slug migration should include a script to generate slugs from existing blog titles.
