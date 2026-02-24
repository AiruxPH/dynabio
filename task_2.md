Feature Spec: Persistent Account Chooser (Recent Logins)
1. Overview
The Account Chooser (or Account Switcher) is a UX enhancement designed to streamline the authentication process for returning users. Instead of requiring a full manual login (Email/Username entry) every session, the application will display a list of profiles that have successfully authenticated on the current browser.

2. Core Objectives
Reduce Friction: Allow users to initiate login with a single click on their profile.

Multi-Account Support: Enable easy switching between different user roles or accounts on the same device.

Privacy Control: Provide a clear "Remove/Forget Account" option to clear local footprints.

3. Technical Implementation (Browser-Side)
To maintain performance and minimize server requests, the "Recent Logins" list will be managed via Client-Side Storage (localStorage).

Data Schema
The stored object should be a minimalist representation of the user profile:
| Field | Type | Description |
| :--- | :--- | :--- |
| uid | String | A unique identifier (UUID or hashed ID). |
| display_name | String | The name shown on the login card. |
| email_hint | String | Partially masked email (e.g., r****@email.com). |
| avatar_url | String | URL to the user's profile picture. |
| last_login | Timestamp | Used to sort the list by most recent. |

Security Guardrails
No Sensitive Data: Under no circumstances should passwords, PII (Personally Identifiable Information), or active Session Tokens be stored in localStorage.

Explicit Consent: Only add a user to the "Chooser" list after a successful manual authentication.

Manual Removal: Provide a "Forget this account" button that clears the specific entry from the local array.

4. User Flow
Initial State: User arrives at [auth/login.php]. If localStorage is empty, show the standard login form.

Post-Login: On success, the app triggers saveToRecentLogins(userData).

Returning State: User arrives at [auth/login.php]. The app detects existing entries and renders the Account Chooser UI (cards with avatars).

Selection: User clicks a card. The "Email/Username" field is auto-filled, and the user is prompted only for their password/biometrics.

Quick Tips for your Project
Limit the List: I recommend capping the list at 3 to 5 users. If it gets longer, it becomes harder to navigate than just typing a name!

Empty State: Always keep a "Use another account" button at the bottom of the list so new users aren't locked out.