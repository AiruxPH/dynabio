# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- `includes/config.php` to store database credentials securely (added to `.gitignore`).
- `includes/config.php.example` as a template for database configuration.
- `.gitignore` to prevent sensitive files from being committed.

### Changed
- Strengthened security in `includes/db.php` by:
    - Switching from `mysqli` to `PDO` for database interactions.
    - Moving credentials to a separate configuration file.
    - Implementing `try-catch` blocks for secure error handling.
    - Setting strict PDO attributes for better security and error reporting.
    - Ensuring global charset is set to `utf8mb4`.
