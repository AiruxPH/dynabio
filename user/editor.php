<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data for the navbar
$stmt = $pdo->prepare("SELECT email, username, photo FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch existing biodata if any
$stmt = $pdo->prepare("SELECT * FROM biodata WHERE user_id = ?");
$stmt->execute([$user_id]);
$biodata = $stmt->fetch();

// Helper defaults
$fullName = $biodata ? htmlspecialchars((string) $biodata['full_name']) : '';
$tagline = $biodata ? htmlspecialchars((string) $biodata['tagline']) : '';
$aboutMe = $biodata ? htmlspecialchars((string) $biodata['about_me']) : '';
$location = $biodata ? htmlspecialchars((string) $biodata['location']) : '';

// Parse JSON arrays back into Javascript-friendly arrays
$skills = ($biodata && $biodata['skills']) ? $biodata['skills'] : '[]';
$socialLinks = ($biodata && $biodata['social_links']) ? $biodata['social_links'] : '{}';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit DynaBio - DynaBio Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <style>
        .editor-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .editor-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: relative;
            overflow: hidden;
        }

        .editor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.1));
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Skills Tag System */
        .skills-input-wrapper {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            min-height: 40px;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .skill-tag {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .skill-tag button {
            background: none;
            border: none;
            color: currentColor;
            cursor: pointer;
            padding: 0;
            font-size: 0.85rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .skill-tag button:hover {
            opacity: 1;
            color: #fca5a5;
        }

        /* Social Links List */
        .social-link-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .social-link-item .form-control {
            margin-bottom: 0;
        }

        .remove-social-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .remove-social-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #fecaca;
        }

        .add-social-btn {
            background: rgba(255, 255, 255, 0.05);
            color: #e4e4e7;
            border: 1px dashed rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }

        .add-social-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-style: solid;
        }

        .editor-footer {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .form-row {
                flex-direction: column;
                gap: 1.5rem;
            }
        }
    </style>
</head>

<body class="layout-dashboard">

    <nav class="navbar">
        <a href="../index.php" style="text-decoration: none;">
            <div class="navbar-brand">Dynabio</div>
        </a>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <?php if (!empty($user['username'])): ?>
                <a href="../view.php?u=<?php echo htmlspecialchars($user['username']); ?>" target="_blank" class="btn"
                    style="padding: 0.5rem 1rem; font-size: 0.875rem;">View Public Profile</a>
            <?php endif; ?>
            <a href="profile.php">
                <img src="<?php echo !empty($user['photo']) ? htmlspecialchars("../" . $user['photo']) : '../user-placeholder.png'; ?>"
                alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid
                rgba(255,255,255,0.2);">
            </a>
        </div>
    </nav>

    <div class="editor-container">

        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <a href="../index.php" style="color: #a1a1aa; text-decoration: none;"><i class="fas fa-arrow-left"></i>
                Back</a>
            <h1 style="margin: 0; font-size: 1.5rem;">Edit Biography Data</h1>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <div class="editor-card">
            <form id="biodataForm">
                <h2 class="section-title">Identity & Story</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Display Name</label>
                        <input type="text" id="full_name" class="form-control" value="<?php echo $fullName; ?>"
                            placeholder="e.g. Jane Doe">
                    </div>

                    <div class="form-group">
                        <label for="location">Location (Optional)</label>
                        <input type="text" id="location" class="form-control" value="<?php echo $location; ?>"
                            placeholder="e.g. Tokyo, Japan">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tagline">Professional Tagline</label>
                    <input type="text" id="tagline" class="form-control" value="<?php echo $tagline; ?>"
                        placeholder="e.g. Visionary Architect building the future">
                </div>

                <div class="form-group">
                    <label for="about_me">About Me Story</label>
                    <textarea id="about_me" class="form-control form-textarea"
                        placeholder="Tell the world your story..."><?php echo $aboutMe; ?></textarea>
                </div>

                <h2 class="section-title">My Skills</h2>
                <div class="skills-input-wrapper">
                    <input type="text" id="skillInput" class="form-control"
                        placeholder="Type a skill and press Enter...">
                    <button type="button" class="btn btn-primary" id="addSkillBtn" style="width: auto;"><i
                            class="fas fa-plus"></i></button>
                </div>
                <div class="skills-container" id="skillsContainer">
                    <!-- Javascript generates tags here -->
                </div>

                <h2 class="section-title" style="margin-top: 2rem;">Social Presences</h2>
                <div id="socialLinksContainer">
                    <!-- Javascript generates rows here -->
                </div>
                <button type="button" class="add-social-btn" id="addSocialBtn">
                    <i class="fas fa-plus"></i> Add Link
                </button>

                <div class="editor-footer">
                    <button type="submit" class="btn btn-primary" id="saveDraftBtn" style="width: auto;">
                        <span id="saveBtnText"><i class="fas fa-save"></i> Publish Biography Updates</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data State
        let skillsState = <?php echo $skills; ?>;
        // Parse from JSON string to Object
        let socialLinksState = <?php echo $socialLinks; ?>;
        if (typeof socialLinksState === 'string') {
            try { socialLinksState = JSON.parse(socialLinksState); } catch (e) { socialLinksState = {}; }
        }

        // --- SKILLS LOGIC ---
        const skillInput = document.getElementById('skillInput');
        const addSkillBtn = document.getElementById('addSkillBtn');
        const skillsContainer = document.getElementById('skillsContainer');

        function renderSkills() {
            skillsContainer.innerHTML = '';
            skillsState.forEach((skill, index) => {
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `
                    ${skill}
                    <button type="button" onclick="removeSkill(${index})"><i class="fas fa-times"></i></button>
                `;
                skillsContainer.appendChild(tag);
            });
        }

        function addSkill() {
            const val = skillInput.value.trim();
            if (val && !skillsState.includes(val)) {
                if (skillsState.length >= 15) {
                    alert('Maximum 15 skills allowed.');
                    return;
                }
                skillsState.push(val);
                renderSkills();
                skillInput.value = '';
            }
        }

        function removeSkill(index) {
            skillsState.splice(index, 1);
            renderSkills();
        }

        skillInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submit
                addSkill();
            }
        });
        addSkillBtn.addEventListener('click', addSkill);


        // --- SOCIAL LOGIC ---
        const socialLinksContainer = document.getElementById('socialLinksContainer');
        const addSocialBtn = document.getElementById('addSocialBtn');

        // Define common platforms for nice icons
        const platforms = ['twitter', 'github', 'linkedin', 'instagram', 'youtube', 'facebook', 'website'];

        function createSocialRow(platformKey = '', urlValue = '') {
            const row = document.createElement('div');
            row.className = 'social-link-item';

            const selectHTML = platforms.map(p =>
                `<option value="${p}" ${p === platformKey ? 'selected' : ''}>${p.charAt(0).toUpperCase() + p.slice(1)}</option>`
            ).join('');

            row.innerHTML = `
                <select class="form-control" style="width: 150px; flex-shrink: 0;" aria-label="Platform">
                    <option value="" disabled selected>Platform</option>
                    ${selectHTML}
                </select>
                <input type="url" class="form-control" placeholder="https://..." value="${urlValue}">
                <button type="button" class="remove-social-btn" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            return row;
        }

        function renderSocials() {
            socialLinksContainer.innerHTML = '';
            for (const [key, url] of Object.entries(socialLinksState)) {
                socialLinksContainer.appendChild(createSocialRow(key, url));
            }
        }

        addSocialBtn.addEventListener('click', () => {
            // Limit to 7 links max
            if (socialLinksContainer.children.length >= 7) {
                alert("Maximum 7 social links allowed.");
                return;
            }
            socialLinksContainer.appendChild(createSocialRow());
        });

        // Initialize Native Renders
        renderSkills();
        renderSocials();


        // --- SUBMISSION VIA AJAX ---
        document.getElementById('biodataForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('saveDraftBtn');
            const btnText = document.getElementById('saveBtnText');
            const alertBox = document.getElementById('alertBox');

            // Gather social links directly from DOM to catch un-saved states
            let currentSocials = {};
            Array.from(socialLinksContainer.children).forEach(row => {
                const selectInfo = row.querySelector('select').value;
                const urlInfo = row.querySelector('input').value.trim();
                // Ensure they picked a platform and wrote a URL
                if (selectInfo && urlInfo) {
                    currentSocials[selectInfo] = urlInfo;
                }
            });

            // Payload assembly
            const payload = {
                full_name: document.getElementById('full_name').value.trim(),
                tagline: document.getElementById('tagline').value.trim(),
                about_me: document.getElementById('about_me').value.trim(),
                location: document.getElementById('location').value.trim(),
                skills: skillsState,
                social_links: currentSocials
            };

            // Loading state
            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Saving...';
            alertBox.style.display = 'none';
            alertBox.className = 'alert';

            try {
                const response = await fetch('action_update_biodata.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    alertBox.textContent = "Biography updated successfully! It's now live.";
                    alertBox.classList.add('alert-success');
                    alertBox.style.display = 'block';
                } else {
                    alertBox.textContent = data.message || "Failed to update biography.";
                    alertBox.classList.add('alert-danger');
                    alertBox.style.display = 'block';
                }
            } catch (error) {
                alertBox.textContent = "A network error occurred. Please try again.";
                alertBox.classList.add('alert-danger');
                alertBox.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                btnText.innerHTML = '<i class="fas fa-save"></i> Publish Biography Updates';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
    <script src="../js/form_guards.js"></script>
    <script>
        // Track unsaved changes logic
        const editorGuard = new FormGuard('biodataForm', 'saveDraftBtn');
    </script>

    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
    <script src="../js/background_animation.js"></script>
</body>

</html>