<div align="center">
  <img src="https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/svgs/solid/dna.svg" width="100" height="100" alt="Dynabio Logo">
  
  # DynaBio Engine
  
  **The Next Generation of Dynamic Personal Portfolios**

  [![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)]()
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)]()
  [![Vanilla JS](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)]()

  *Empowering developers and creatives to build, manage, and share interactive career journeys without touching a single line of code.*
</div>

---

## 🌟 Executive Summary
DynaBio is a full-stack, Model-View-Controller (MVC) engineered PHP application. It functions as a centralized hub allowing users to register, securely construct a detailed professional biography, and immediately generate a sleek, public-facing URL. 

Born from an academic requirement, the platform transcends basic CRUD boundaries by integrating real-time GitHub repository fetching, modular UI theming, and an extensive array of explicitly captured programmatic DOM interactions.

---

## 🚀 Core Features

### 🛡️ Secure, State-of-the-Art Authentication
- **Dual-Factor Security:** Implements a strict `set_username` flow and an active 6-digit OTP email verification relay utilizing the PHPMailer SMTP architecture before granting dashboard access.
- **Form Guards & Toast Mechanics:** Features over a dozen interactive structural defenses that sanitize pasting, prevent multiple rapid form submissions (`onsubmit`), restrict character injection (`onkeydown`), and emit responsive, non-obstructive toast notifications directly into the UI.

### 🎨 The Dynamic Theming Matrix
Why stick to one look? Users can mutate their public-facing portfolio in real-time across **8 distinct aesthetic paradigms**:
1. Default Glass (Core Glassmorphism)
2. Neon Cyberpunk (Aggressive High-Contrast)
3. Midnight Blue (Professional Deep Corporate)
4. Minimal Light (Standard Accessibility)
5. Solarized Amber (Retro Nostalgia)
6. Emerald Matrix (Hacker Console)
7. Rose Quartz (Soft Pastels)
8. Deep Space (True AMOLED Black)

### 📈 Structural Journey Timeline
Users can drag, drop, and construct historical milestones to represent their education or career steps visually via the `views/editor.php` interface.

### ⚡ 36 Native Javascript Event Listeners
Programmed explicitly for academic evaluation, the application contains exactly 36 handcrafted inline (`on[event]`) Javascript triggers scattered across the application DOM. These range from basic form validation overrides (`oninvalid`, `oncut`) to advanced DOM parallax (`onscroll`), 3D spatial tilts (`onmousemove`), and defensive drag blocking (`ondragstart`). Full technical documentation logic is found inside `inline_events.md`.

---

## 🛠️ Technology Stack Breakdown

| Layer | Technologies Utilized | Purpose |
| :--- | :--- | :--- |
| **Frontend Layout** | HTML5, Native CSS3 | Strict structural formatting and CSS variable injection for live theming. |
| **Frontend Logic** | Vanilla Javascript | Client-side validation, `showToast` API components, and DOM interaction listeners. |
| **Backend Engine** | PHP 8.0+ | Server-side routing, logic separation (MVC architecture), and API compilation. |
| **Database** | MySQL (PDO) | Secure, parameterized remote SQL execution against a distributed Hostinger server. |
| **External APIs** | GitHub REST API | Secure cURL operations to pull user-defined repository intelligence based on metadata hooks. |
| **Dependencies** | PHPMailer | Facilitates the 6-digit OTP email dispatch mechanics. |

---

## 📦 Directory Architecture

```text
├── auth/                   # Publicly facing authentication router logic (signup.php, login.php)
├── controllers/            # PHP Request Handlers (action_login.php, action_verify.php)
├── css/                    # Modular Style Engine (themes.css, views/*.css)
├── database/               # SQL dump routines, Trigger logic, Schema backups
├── includes/               # Core engine connections (db.php, config.php, auth_check.php)
├── js/                     # Client-side javascript components (toast.js, form_guards.js)
├── markdowns/              # Dev prompts and generation records
├── vendor/                 # Composer dependencies (PHPMailer)
├── views/                  # UI Layouts & DOM Structures (dashboard.php, public.php, about.php)
├── index.php               # Core engine bootstrapper
└── inline_events.md        # Academic Event Listener Catalog
```

---

## 🔒 Security Posture
- **Password Hashes:** All passwords strictly rely on modern PHP `password_hash()` methods.
- **SQL Sanitization:** The entire application communicates exclusively via PDO prepared constraints to nullify SQL injections.
- **Cross-Site Scripting (XSS):** Data printed into the DOM utilizes `htmlspecialchars()` natively across the board.
- **Bot Defense:** Registration scripts enforce email verification and natively buffer repeat `onsubmit` requests.

---

<div align="center">
  <p>Built as an extensive academic thesis exploring modern web architecture parameters.<br/>
  <b>© 2026 DynaBio Engine</b></p>
</div>
