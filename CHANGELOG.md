# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
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
