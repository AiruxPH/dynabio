# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
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
