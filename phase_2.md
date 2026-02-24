"I need to expand the database and UI of my DynaBio project to include a comprehensive set of fields for both a formal Biodata and a personal Biography.

Please provide the SQL ALTER TABLE statements and the updated PHP code for user/editor.php and user/action_update_biodata.php to handle the following:

1. Database Expansion:

Add fields to the biodata table for 'Personal Information': position desired, nickname, present address, provincial address, place of birth, gender, civil status, citizenship, and religion.

Add fields for 'Physical Attributes': height (cm/ft) and weight (kg/lbs).

Add a JSON field family_background to store spouse, children, and parents' details.

Add a JSON field government_ids to store SSS, TIN, PhilHealth, Pag-IBIG, and NBI clearance numbers.

2. UI/UX in user/editor.php:

Create a multi-tab or collapsible section interface to organize these into 'Identity', 'Family', 'Professional/Government', and 'Story'.

Use my current glassmorphism design system for these new input groups.

3. Conditional Display in view.php:

Implement a 'Privacy Toggle' logic. The standard public URL (view.php?u=username) should only render the Biography (About Me, Skills, Socials, and Tagline).

Create a 'Secure View' that requires a session check or a specific token to display the sensitive Biodata fields (Addresses, Family details, and Government IDs) for official use.

Technical Requirements:

Continue using PDO for all database queries and ensure all inputs are sanitized.

Ensure the updated_at timestamp is correctly triggered when these new fields are saved.

Use the existing data-theme variable system to ensure new fields match the user's chosen theme."

4. Live Tech Stack Integration (GitHub API):(OPTIONAL)

Modify user/editor.php to include a field for a GitHub username.

In view.php, create a backend function to fetch the user's most recently pushed-to repositories or top languages using the GitHub API.

Dynamically render these as 'Live Activity' cards on the public profile so the tech stack updates automatically based on real-world commits.

5. Interactive Achievement Timeline:

Add a new JSON field to the biodata table called timeline_data (or create a new related table).

In user/editor.php, build a UI that allows users to add milestones (Date, Title, Description, and Icon).

In view.php, render these milestones as an interactive, vertically scrolling timeline that matches the current glassmorphism theme.

6. Conditional 'Role-Based' Views:

Implement a system where a user can generate a 'Recruiter Link' (e.g., view.php?u=username&view=professional).

When the view parameter is set to professional, the biography should prioritize the Skills and Live Activity sections.

The default view should remain more personal, focusing on the 'About Me' story.

7. PDF Export (Biodata Style):

Create a specialized 'Secure View' or an export function that transforms the dynamic story into a formal, 1-page Biodata format.

This view should include the 'hard' facts (Full Name, Location, etc.) while hiding the more casual 'Biography' elements for professional applications.