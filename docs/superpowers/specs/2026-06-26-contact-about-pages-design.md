# Contact & About Pages — Design

**Date:** 2026-06-26
**Theme:** `ameer-homepage` (WooCommerce, WordPress-core-only, no ACF)

## Goal

Add two new pages to the Ameer theme — **Contact** and **About Us** — built as
custom page templates that match the homepage's section-based visual style
(cream hero, SVG dividers, `reveal` animations, the `.btn` system). Contact form
submissions are stored in WordPress as a private CPT *and* emailed to the admin,
mirroring the existing newsletter pattern (`inc/newsletter.php`).

## Decisions (confirmed with user)

- **Contact form:** store each submission in WP (new CPT) **and** `wp_mail()` the
  admin. No plugin; core-only, consistent with the no-ACF convention.
- **Build approach:** custom assignable page templates ("Template: About",
  "Template: Contact"), editable via Customizer fields + the page editor.
- **Navigation:** NOT wired by this work — the user will link the pages into the
  nav/footer themselves.
- **Page creation:** templates only. The WP pages are created by the user in
  wp-admin and assigned the templates. (A one-time seeder is out of scope unless
  requested later.)

## Architecture

Follows existing theme conventions:
- `functions.php` is a thin bootstrap that `require`s `inc/*` modules.
- Singleton content lives in the Customizer; lists/bodies use the page editor.
- Forms post to `admin-post.php` and redirect back with a status query arg
  (exactly like `ameer_handle_subscribe`).
- CSS is enqueued conditionally; templates are detected with `is_page_template()`.

### Files

| File | Action | Purpose |
|---|---|---|
| `page-contact.php` | create | Template Name: "Ameer: Contact" |
| `page-about.php` | create | Template Name: "Ameer: About" |
| `inc/contact.php` | create | `ameer_message` CPT + form renderer + `admin-post` handler |
| `functions.php` | edit | `ameer_require( 'contact.php' );` |
| `inc/customizer.php` | edit | New fields in the existing **Contact & Footer** section |
| `inc/enqueue.php` | edit | Enqueue `css/page-templates.css` on the two templates |
| `css/page-templates.css` | create | Styling for both page templates |

## Components

### 1. Contact module — `inc/contact.php`

Mirrors `inc/newsletter.php` structure and security model.

- **CPT `ameer_message`** — `public => false`, `show_ui => true`, menu icon
  `dashicons-email-alt`, `capabilities` deny `create_posts` (read-only in admin,
  like `ameer_subscriber`), `supports => array( 'title', 'editor' )`. Admin
  **Messages** menu lists incoming messages.
- **`ameer_contact_form()`** — renders the form:
  - fields: `ameer_name` (text, required), `ameer_email` (email, required),
    `ameer_phone` (tel, optional), `ameer_message` (textarea, required).
  - a **honeypot** field (e.g. `ameer_website`) visually hidden; if filled,
    silently treat as spam (redirect success without storing).
  - hidden `action=ameer_contact`, `redirect_to`, and `wp_nonce_field`.
  - status messages from `?sent=1` (thanks) / `?sent=invalid` (validation error).
- **`ameer_handle_contact()`** — hooked on `admin_post_ameer_contact` and
  `admin_post_nopriv_ameer_contact`:
  1. verify nonce → on failure redirect `?sent=invalid`.
  2. honeypot check → if filled, redirect `?sent=1` without storing.
  3. sanitize: `sanitize_text_field` (name/phone), `sanitize_email` (email),
     `sanitize_textarea_field` (message). Validate required + `is_email()`.
  4. `wp_insert_post` an `ameer_message` (title = name, content = message),
     store email/phone as post meta (`_ameer_msg_email`, `_ameer_msg_phone`).
  5. `wp_mail()` to `get_option( 'admin_email' )` with the message details and a
     `Reply-To` of the submitter's email.
  6. `wp_safe_redirect( add_query_arg( 'sent', '1', $redirect ) )`.

### 2. Contact template — `page-contact.php`

- `Template Name: Ameer: Contact`.
- Hero block identical in structure to `page.php`'s `.article-hero` (title +
  bottom divider).
- A `.container` two-column layout:
  - **Left — info cards**, each rendered only when its Customizer value is set:
    phone (`tel:` link), email (`mailto:`), WhatsApp (click-to-chat
    `https://wa.me/<digits>`), address, business hours, SSM, social icons
    (reuse the footer's TikTok/IG/FB SVGs).
  - **Right — the form** via `ameer_contact_form()`.
- Optional Google-Maps embed (`ameer_map_embed`) in a responsive wrapper below,
  shown only when set.
- Uses `reveal` / `data-anim` hooks so existing scroll animations apply.

### 3. About template — `page-about.php`

- `Template Name: Ameer: About`.
- Hero block (title + divider).
- Intro section reusing the existing `ameer_about_*` Customizer fields (eyebrow,
  title, lead, the four badges) — same markup family as
  `template-parts/section-about.php`.
- Main story body = `the_content()` (page editor), wrapped in `.article-prose`
  for readable typography, so the user edits the story like a normal page.
- Closing CTA row (shop / contact) reusing the `.btn` system.

### 4. Customizer additions — `inc/customizer.php`

New fields appended to the existing `ameer_contact` section, using the existing
helpers (`ameer_cz_text`, `ameer_cz_url`, etc.):

- `ameer_contact_whatsapp` — WhatsApp number (digits; used for `wa.me`).
- `ameer_contact_address` — physical address (textarea / multiline text).
- `ameer_contact_hours` — business hours text.
- `ameer_map_embed` — Google Maps embed `src` URL (esc_url on output).

All optional; their UI blocks render only when non-empty.

### 5. Enqueue — `inc/enqueue.php`

Add a check for the two templates and enqueue `css/page-templates.css`
(dependency `ameer-sunny`). `article-page.css` already loads on `is_page()`, so
hero styles are covered; the new file adds contact grid / info card / form /
map styling and any about-page extras.

## Data flow

```
Visitor submits Contact form
  → POST admin-post.php (action=ameer_contact)
    → ameer_handle_contact(): nonce + honeypot + sanitize + validate
      → wp_insert_post(ameer_message) + post meta
      → wp_mail(admin, Reply-To: submitter)
      → redirect back ?sent=1
  → page-contact.php shows the thank-you notice
Admin reads messages in wp-admin → Messages (ameer_message list)
```

## Error handling

- Invalid/failed nonce or missing required fields → redirect `?sent=invalid`,
  inline error message; nothing stored.
- Honeypot filled → redirect `?sent=1`, nothing stored, no email (silent spam
  drop).
- `wp_mail()` failure does not block storage — the message is already saved in
  WP, so it is never lost (the email is best-effort).
- All output escaped (`esc_html`, `esc_url`, `esc_attr`); all input sanitized.

## Testing

Manual verification (no automated PHP test harness in this theme):
1. Create a Page, assign **Ameer: Contact**, view it → hero + info + form render.
2. Submit valid form → redirected with thank-you; new **Messages** entry exists;
   admin receives email.
3. Submit with empty required field → validation message, nothing stored.
4. Fill the honeypot (via devtools) → success redirect, nothing stored, no email.
5. Set/clear each Customizer field → corresponding info block appears/hides.
6. Create a Page, assign **Ameer: About**, add body content → intro (Customizer)
   + story (editor) render with correct styling.
7. Confirm fixed-header clearance (title not hidden under the header) on both.

## Out of scope

- Wiring pages into nav/footer menus.
- Auto-creating the WP pages (seeder).
- reCAPTCHA / third-party spam services (honeypot only).
- Email-template HTML styling beyond a clean plaintext/simple body.
