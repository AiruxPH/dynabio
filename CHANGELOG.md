# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
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

### Changed
- Strengthened security in `includes/db.php` by:
    - Switching from `mysqli` to `PDO` for database interactions.
    - Moving credentials to a separate configuration file.
    - Implementing `try-catch` blocks for secure error handling.
    - Setting strict PDO attributes for better security and error reporting.
    - Ensuring global charset is set to `utf8mb4`.
- Integrated manual PHPMailer inclusion to bypass local SSL certificate issues.
