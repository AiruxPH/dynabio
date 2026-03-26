# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- **19 Inline Event Listeners on Dashboard:**
  - `onkeydown` — Ctrl+E keyboard shortcut to open editor, Home key scrolls to top.
  - `onscroll` — Scroll spy shows/hides a floating scroll-to-top button.
  - `onclick` (scroll button) — Smooth scroll to top with spin animation on hover.
  - `onload` — Avatar glow pulse when profile image finishes loading.
  - `onmousedown/onmouseup/ontouchstart/ontouchend` — Long-press avatar opens full-size lightbox overlay.
  - `onclick` (location) — Copy location text to clipboard with toast notification.
  - `onmouseenter` (divider) — Divider stretches with a glowing pulse effect.
  - `onclick` (divider) — Mini confetti burst with 12 colored particles.
  - `onselectstart` — Logs text selection from the About section.
  - `onmouseenter` (skill badges) — Ripple scale-up glow effect.
  - `onclick` (skill badges) — Spotlight: dims all other skills, highlights clicked one.
  - `onclick` (social links) — Tracks and toasts which platform was clicked.
  - `onfocus/onblur` (social links) — Glow effect on keyboard tab navigation.
  - `onmousemove/onmouseleave` (identity card) — 3D parallax tilt following cursor.
  - `onclick` (biodata title) — Collapse/expand biodata section with chevron animation.
  - `onmouseenter/onmouseleave` (biodata card) — Border glow on hover.
  - `oncontextmenu` — Custom right-click protection with toast warning.
  - `ondblclick` (timeline title) — Reverses timeline order.
  - Added `date_of_birth` DATE column to the `biodata` table (`database/schema_updates/add_date_of_birth.sql`).
  - Added date picker input to the editor form (`views/editor.php`).
  - Updated `editor.js` payload and `action_update_biodata.php` to save/load the new field.
  - Age is now auto-calculated from DOB and displayed as `"Month Day, Year (Age X)"` on dashboard and public views.

### Removed
- **Applicant's Signature block** removed from both `views/dashboard.php` and `views/public.php`.

### Fixed
- **Corrupted `views/dashboard.php`:** Completely rewrote the file to fix severely corrupted HTML tags (e.g., `</di v>`, `<scr ipt>`, `func tion`) that caused `SyntaxError: Unexpected identifier 'tion'`.

### Changed
- **Biodata display:** "Gender / Age" row split into separate "Gender" and "Date of Birth" rows.


- **Localhost Adaptation & Pathing Fixes:**
  - Standardized authentication entry points to ensure consistent session handling and JavaScript pathing across environments.
  - Implemented absolute redirects using `ACTUAL_WEB_URL` in all backend action scripts to resolve "stuck" login states on subfolder-based local setups (e.g., XAMPP `/dynabio/`).
  - Addressed asset 404s by injecting `ACTUAL_WEB_URL` prefix into all `<link>` and `<script>` tags across all authentication views (`login`, `signup`, `verify`, etc.), preventing directory traversal errors when rendering via the `auth/` controllers.
  - Added automatic redirection from `views/auth/` directly-accessed UI files to their respective controllers in `auth/`.
  - Injected `ACTUAL_WEB_URL` into the frontend window object to enable robust absolute pathing for automated fetch requests in `login.js` and other auth scripts.
  - Configured session cookies to dynamically detect SSL status, allowing secure session persistence on non-HTTPS `http://localhost`.
  - Replaced all external Font Awesome script tags (`kit.fontawesome.com`) and Google Fonts link tags (`fonts.googleapis.com`) with local files (`js/font-awesome/ef9baa832e.js` and `css/font-google-inter.css`) across all 12 views. Downloaded all `.woff2` font variants to `fonts/inter/` to ensure 100% offline localhost compatibility.
- **Single-User Portfolio Architecture (Phase 2):**
  - Added `SITE_OWNER` constant to `config.php` hardcoded to `randythegreat` to establish a global profile anchor.
  - Overhauled `index.php` (dashboard) to exclusively fetch and display the `SITE_OWNER`'s biodata, replacing the generic multi-user hub with the full public portfolio layout.
  - Extracted the "Theme Picker" from the dashboard into a dedicated `themes.php` view, accessible via the navbar exclusively for the `SITE_OWNER`.
  - Implemented conditional permission rendering on the dashboard: Only the `SITE_OWNER` sees the "Edit Biodata" button.
  - Created a robust `download.php` endpoint that compiles the `SITE_OWNER`'s profile, tagline, skills, and chronological milestones into a neatly formatted `.txt` resume file, forced as a browser download.

### Fixed
- **API Network Errors:** Resolved "A network error occurred" during login by eliminating duplicate `session_start()` calls. Added `session_status() === PHP_SESSION_NONE` checks to `auth_utils.php` and all JSON API controllers to prevent PHP Notices from corrupting backend JSON responses.
- **Dashboard Include Error:** Corrected the path for the `username_modal.php` include in `views/dashboard.php` to accurately point to the root `includes/` directory.
- **New User Onboarding Flows (Phase 11):** 
  - Overhauled core databade `LEFT JOIN` queries in `index.php` and `view.php` to actively detect absolute empty states when new users authenticate for the first time.
  - Injected a highly-visible, glowing call-to-action banner into `views/dashboard.php` specifically targeting new users, directing them immediately to the editor to configure their timeline and identity.
  - Built a fallback rendering module into `views/public.php` showcasing a "Ghost" icon layout when visitors attempt to view the portfolio of a user who hasn't initialized any records, effectively shielding the broken UI layout.
- **Global HTTP 500 Catch System (Phase 10):** Engineered a dual-layer crash interception architecture. Instantiated a `register_shutdown_function()` interceptor inside `includes/error_handler.php` to intrinsically clean `ob_start()` buffers and catch fatal PHP execution logic failures (`E_ERROR`, `E_PARSE`) before exposing raw stack traces. Implemented a supplementary `.htaccess` server-level directive (`ErrorDocument 500 /views/500.php`) to catch hard Apache compilation failures. Both layers dynamically route visitors to a secure, glassmorphic `500.php` fallback UI mirroring the core aesthetic.
- **Graceful Localhost Fallbacks:** Upgraded `includes/db.php` with a preemptive `file_exists()` check targeting the database credentials via `config.php`. If a visitor clones the repo locally without building a config environment, the backend gracefully halts execution and paints an HTML UI directing them to the Live Demo and GitHub repository instead of crashing violently.
- **Project Structure & Showcasing (Phase 9):**
  - Built a dedicated, public-facing `about.php` informational page detailing platform features utilizing the global `style.css` glassmorphism properties.
  - Wired navigation anchors inside the `dashboard.php` top-level navbar and the `public.php` footer.
  - Completely restructed the core `README.md` into a professional GitHub Showcase document detailing the technology stack, security posture, array of explicitly coded javascript event listeners, and directory architecture.
- **Login UI Refinements:** 
  - Substantially upgraded the `oninput` Javascript logic on the Login fields to dynamically mutate the submit button text to `"Typing..."` and temporarily disable form dispatch while a user is actively keying in credentials. Also injects a reactive blue `box-shadow` glow into the DOM container while typing is active.
  - Implemented a custom `fadeSwap()` Javascript controller inside `js/views/auth/login.js` that orchestrates smooth CSS scale and opacity transitions when users toggle between the Recent Accounts Chooser and the Standard Login inputs.
  - Added secure `?logout=success` URL parameter parsing logic into `views/auth/login.php` to pop a global `$window.showToast()` success notification and transparently clean the browser's history state upon explicitly logging out.
- **Inline Event Listeners (Phase 8):** Injected 36 native, sensible JavaScript inline event listeners (`onmouseenter`, `oninvalid`, `onmousemove`, `onmouseout`, `onselect`, `onsubmit`, etc.) specifically bound directly to HTML nodes across `views/*` to fulfill strictly academic requirements. Integrated visual form guards directly into the `$window.showToast` component so grading professors can clearly see the interactions visually. Function details are cataloged inside `inline_events.md`.
- **Theme Expansion (Phase 7):** Expanded the global visual core (`css/themes.css`) to support 4 new system-native theme identities: Solarized Amber, Emerald Matrix, Rose Quartz, and Deep Space. Injected their associated `.color-preview` blocks into `css/views/dashboard.css` and `views/dashboard.php`. The system now reliably supports 8 default themes without requiring inline CSS injection from the database.
- **Miscellaneous Enhancements (Phase 6):**
  - **Image Compression:** Integrated PHP GD library inside `user/action_profile.php` to automatically resize and compress user-uploaded profile photos to a maximum of 500px width (maintaining aspect ratio) as JPEG format.
  - **DNS Email Validation:** Fortified `auth/action_signup.php` by injecting `checkdnsrr` to verify MX records on email domains prior to issuing verification codes.
  - **Live Theme Preview:** Created an interactive hover state in `js/views/dashboard.js` allowing users to preview themes seamlessly by hovering over `.theme-option` cards without triggering database saves.
- **Asset Decoupling (Phase 5):** Transitioned away from inline `<style>` and `<script>` blocks across the frontend.
  - Established central `/css/views/` and `/js/views/` directory structures for the MVC architecture.
  - Successfully decoupled all 9 frontend components (`profile.php`, `editor.php`, `dashboard.php`, and `auth/*`).
  - Added secure `window.*Data` objects in the presentation layer to smoothly export backend PHP variables into the loaded Javascript files.
- **Dynamic Biography Editor (Phase 2 Upgrade):** Completely overhauled `user/editor.php` to use a 4-tab Javascript interface (Identity, Personal Info, Professional Stack, The Journey).
  - Added safe handling of standard personal attributes (`address`, `religion`, `gender`, `civil_status`, etc.) without exposing them publicly.
  - Added JSON-based data structure internally for `family_background`.
- **Relational Milestones Engine:** Created a new `milestones` relational table explicitly for tracking users' chronological career timelines.
  - Users can now add, edit, and delete milestones via the AJAX-powered Journey tab.
- **GitHub Live API Engine:** The public `view.php` now features an automatic query matching to a user's GitHub username to pull Live Tech Stack and Repository data dynamically.
  - Implemented a resilient database caching layer (`github_cache`) limiting GitHub API pings to once every 4 hours per profile to prevent Hostinger IP ban limits.
  - Automatically suppresses rendering of sensitive fields (addresses, family, citizenship) from the public URL for strict data privacy.
- **MVC Architecture Refactoring (Phase 3):**
  - **Core Dashboard & Biographies:** Separated logic from presentation in `index.php` and `user/editor.php`, extracting native HTML into dedicated `views/dashboard.php` and `views/editor.php` templates.
  - **User Profile:** Refactored `user/profile.php` to securely inherit `views/profile.php`, unifying session validation via `includes/auth_check.php`.
  - **Authentication Flow:** Completely decoupled HTML from backend logic across all authentication handlers (`login.php`, `signup.php`, `verify.php`, `forgot_password.php`, `reset_password.php`, `set_username.php`), securely porting UI components into `views/auth/`.
  - Applied strict PHP standards by stripping trailing closing tags (`?>`) exclusively across all controller endpoints to prevent dangerous trailing-whitespace injection errors.
- **Global Toast Notification System (Phase 4):**
  - Transitioned the entire platform away from localized HTML alert boxes towards a modern, non-intrusive `showToast(message, type)` javascript utility.
  - Successfully mapped across all views in Authentication, Dashboard, Profile, and Editor.
- **Dynamic Biography Core Engine (Phase 1):**
  - **Database schema** (`database/biodata_schema.txt`) established for the `biodata` table, utilizing JSON arrays for scalable skill tags and social links.
  - **Command Center Dashboard** (`index.php`) overhauled from a generic welcome page into a functional hub featuring a visual Theme Grid (default, neon, midnight, minimal).
  - **Interactive Data Editor** (`user/editor.php`) implemented to manage biography content, seamlessly building JSON arrays in Javascript before leveraging Upsert logic via `user/action_update_biodata.php`.
  - **Public Showcase Engine** (`view.php`) engineered to dynamically query and render a user's `biodata` securely based on their `username` while instantly injecting their personalized CSS Theme overrides.
- Implemented a globally persistent, dynamic background animation system (`js/background_animation.js`).
  - Automatically spawns 20 drifting "Dynamic Biodata" FontAwesome icons (`fa-dna`, `fa-microchip`, `fa-fingerprint`, etc.) behind the glassmorphism UI.
  - Generates organic depth-of-field movement by randomizing icon scaling, opacity, starting positions, and CSS animation durations.
  - Successfully mapped across all Authentication, Profile, and Dashboard pages for a unified visual experience.
- Created a global `style.css` architecture at the project root, porting the entire B/W Glassmorphism design system to a single source of truth.
  - Eliminated inline styles from the `index.php` dashboard and adapted it to match the global glass aesthetics.
  - Deprecated and removed the localized `auth/style.css`.
  - Updated all dependent authentication and user profile pages to link natively to the root stylesheet.
- OTP Verification Quality of Life updates:
  - Upgraded the `auth/verify.php` frontend to utilize a modern 6-box OTP entry interface in place of a generic text input. The inputs natively feature auto-advancing keystrokes, full-string pasting support, backspace navigation, and auto-submission upon entering the final digit.
  - Shortened the required authentication code length from 16 characters to 6 for better usability.
  - Upgraded the `auth_utils.php` email template to render the OTP in a Courier monospace font with wider letter-spacing, resolving visual ambiguity between characters like 'I' and 'l'.
  - `auth/verify.php` now automatically clears the input field when a user successfully requests to resend a code.
- Real-Time Account Chooser Sync. `auth/login.php` now intercepts its `localStorage` on boot and passes it through an AJAX call to a new `auth/action_sync_accounts.php` endpoint. This guarantees that if a user changes their username or deletes their account on one device, their icon and login card on other devices will securely update or vanish instantly, preventing stale data.
- Profile View & Edit Modes.
  - `user/profile.php` now features a protected `view-mode` by default, rendering inputs cleanly as flat text and protecting against accidental keystrokes.
  - Implemented 'Edit Profile' and 'Cancel' workflows in Javascript that toggle read-only states instantly and use HTML5 `dataset` attributes to gracefully reset text back to its original database value if editing is aborted.
- Outgoing System Emails (OTPs, Verification) are now strictly unreplyable. `includes/mail_helper.php` was modified to explicitly inject a `Reply-To: noreply@dynabio.com` header to prevent users from flooding the system inbox.
- Real-time Username Checking on `user/profile.php`.
  - Added visual `<small>` UI to inform users if a name is taken, available, or formatted incorrectly.
  - Generates lightweight, debounced `fetch()` checks against new `user/action_check_username.php` endpoint.
  - Dynamically disables/enables the Profile save button depending on full validity.
- Strict Backend Username Validation enforced on `user/action_profile.php`. Stops manipulation of usernames by ensuring regex match, correct string length, strict lowercase transformation, and rejection of system reserved words.
- Global `js/form_guards.js` utility class constructed to provide offline submission blocking and native browser unsaved-changes warnings (`beforeunload`).
  - Integrated into `user/profile.php` to prevent catastrophic data loss if users navigate away with a dirty form.
  - Intercepts offline events dynamically, disabling submit buttons globally to prevent hanging requests.
  - Successfully mapped across `auth/signup.php` and `auth/login.php` (with configurable trackDirty toggles).
- Persistent "Account Chooser" feature in `auth/login.php` to streamline authentication for returning users on trusted devices.
  - Utilizes `localStorage` to securely save up to 5 recent usernames and avatars, exclusively trigged by the "Remember me" checkbox.
  - Implements a Google-style "Active Account Preview" banner that hides the username input when returning from a quick-login card.
- Username login support alongside Email. Modifed `login.php` to accept both formats visually and fundamentally via `action_login.php`.
- Database trigger SQL script (`database/username_trigger.txt`) explicitly providing safeguards against duplicate usernames across the `users` table via `BEFORE INSERT` and `BEFORE UPDATE` logic.
- Comprehensive local (frontend) and strict remote (backend) validation ensuring usernames:
  - Max 20 characters via precise internal regex.
  - Abide mechanically by `^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$`.
  - Are strictly formatted lowercase.
  - Ban an expansive list of administrative/reserved system keywords.
- Global `includes/username_modal.php` dynamically prompting users without a username to set one or skip (with auto-generation). Applied to protected dashboard and profile pages.

### Fixed
- "Tracking Prevention blocked access" warnings on `user/profile.php` by adding `crossorigin` and `referrerpolicy` to the local FontAwesome CDN link.

### Changed
- Replaced the FontAwesome `cdnjs` stylesheet link with a personalized Font Awesome Kit script globally across all authentication, dashboard, and profile pages. This definitively resolves browser "Tracking Prevention" errors and standardizes asset loading.
- `auth/action_set_username.php` updated to handle username assignments for both setup-flow users and existing-session users without forcing login redirects.

### Added
- `user/profile.php` and `user/action_profile.php` for user profile management (username, role display, avatar uploading, account soft-deletion).
- `auth/set_username.php` and `auth/action_set_username.php` to handle new user handle assignments post-signup.
- Implemented a 60-second countdown timer for resending verification codes in `auth/verify.php`.
- Added "Change Email Address" redirect mechanism during verification in `auth/verify.php`.
- B/W Glassmorphism design system in `auth/style.css` matching specific brand aesthetics, complete with hover-color emissions on primary actions.

### Fixed
- Input autofill styling in `auth/login.php` breaking the new UI (replaced native blue background with a transparent inset shadow).
- Improved missing email error handling in `auth/action_forgot.php` to be more descriptive.

### Changed
- `includes/config.php` to store database and email credentials securely (added to `.gitignore`).
- `includes/config.php.example` as a template for database and email configuration.
- `.gitignore` to prevent sensitive files from being committed.
- `includes/PHPMailer/` library for email sending (manual installation).
- `includes/mail_helper.php` for centralized and easy email sending.
- `test_mail.php` as a utility to verify email functionality.
- Comprehensive `auth/` directory containing the full authentication system:
    - Unified login System featuring cross-checked database expirations, remember-me token generation, and real-time frontend responses.
    - Specialized logic handling >24 hour code regeneration alongside secure >7 days unverified deletion protocols.
    - Dedicated code verification and standard "forgot password" routing.
- `index.php` as a secure dashboard homepage displaying user information and photo handling.
- `auth/logout.php` script for terminating secure sessions.

### Changed
- Strengthened security in `includes/db.php` by:
    - Switching from `mysqli` to `PDO` for database interactions.
    - Moving credentials to a separate configuration file.
    - Implementing `try-catch` blocks for secure error handling.
    - Setting strict PDO attributes for better security and error reporting.
    - Ensuring global charset is set to `utf8mb4`.
- Integrated manual PHPMailer inclusion to bypass local SSL certificate issues.
