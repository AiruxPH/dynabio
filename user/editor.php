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
$stmt = $conn->prepare("SELECT email, username, photo FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch existing biodata if any
$stmt = $conn->prepare("SELECT * FROM biodata WHERE user_id = ?");
$stmt->execute([$user_id]);
$biodata = $stmt->fetch();

// Phase 1 Helpers
$fullName = $biodata ? htmlspecialchars((string) $biodata['full_name']) : '';
$tagline = $biodata ? htmlspecialchars((string) $biodata['tagline']) : '';
$aboutMe = $biodata ? htmlspecialchars((string) $biodata['about_me']) : '';
$location = $biodata ? htmlspecialchars((string) $biodata['location']) : '';
$skills = ($biodata && $biodata['skills']) ? $biodata['skills'] : '[]';
$socialLinks = ($biodata && $biodata['social_links']) ? $biodata['social_links'] : '{}';

// Phase 2 Extended Helpers
$positionDesired = $biodata ? htmlspecialchars((string) ($biodata['position_desired'] ?? '')) : '';
$nickname = $biodata ? htmlspecialchars((string) ($biodata['nickname'] ?? '')) : '';
$presentAddress = $biodata ? htmlspecialchars((string) ($biodata['present_address'] ?? '')) : '';
$provincialAddress = $biodata ? htmlspecialchars((string) ($biodata['provincial_address'] ?? '')) : '';
$placeOfBirth = $biodata ? htmlspecialchars((string) ($biodata['place_of_birth'] ?? '')) : '';
$gender = $biodata ? htmlspecialchars((string) ($biodata['gender'] ?? '')) : '';
$civilStatus = $biodata ? htmlspecialchars((string) ($biodata['civil_status'] ?? '')) : '';
$citizenship = $biodata ? htmlspecialchars((string) ($biodata['citizenship'] ?? '')) : '';
$religion = $biodata ? htmlspecialchars((string) ($biodata['religion'] ?? '')) : '';
$height = $biodata ? htmlspecialchars((string) ($biodata['height'] ?? '')) : '';
$weight = $biodata ? htmlspecialchars((string) ($biodata['weight'] ?? '')) : '';
$githubUsername = $biodata ? htmlspecialchars((string) ($biodata['github_username'] ?? '')) : '';

$familyBackground = ($biodata && isset($biodata['family_background']) && $biodata['family_background'])
    ? $biodata['family_background']
    : '{"spouse":"","children":"","parents":""}';


// Fetch Milestones mapping
$milestonesStmt = $conn->prepare("SELECT * FROM milestones WHERE user_id = ? ORDER BY milestone_date DESC");
$milestonesStmt->execute([$user_id]);
$milestones = $milestonesStmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bio - DynaBio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <style>
        .layout-builder {
            display: flex;
            gap: 2rem;
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            align-items: flex-start;
        }

        .sidebar {
            width: 250px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 1.5rem 1rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
            position: sticky;
            top: 2rem;
        }

        .tab-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 1rem 1.5rem;
            background: transparent;
            border: none;
            color: #a1a1aa;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }

        .tab-btn i {
            width: 20px;
            margin-right: 0.5rem;
            text-align: center;
        }

        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .tab-btn.active {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.1);
        }

        .content-area {
            flex-grow: 1;
            min-width: 0;
        }

        .tab-pane {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .editor-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
            margin-bottom: 2rem;
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

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 500;
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
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .skill-tag {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
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
            opacity: 0.7;
        }

        .skill-tag button:hover {
            opacity: 1;
            color: #fca5a5;
        }

        .social-link-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .remove-social-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
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
        }

        .editor-footer {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
        }

        /* Timeline Specific */
        .milestone-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .milestone-meta {
            flex-grow: 1;
        }

        .milestone-actions {
            display: flex;
            gap: 0.5rem;
        }

        .milestone-date {
            font-size: 0.8rem;
            color: #a1a1aa;
            margin-bottom: 0.25rem;
            display: block;
        }

        .milestone-title {
            font-weight: 600;
            color: #fff;
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }

        .milestone-desc {
            font-size: 0.9rem;
            color: #cbd5e1;
            margin: 0;
        }

        .icon-selector {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .icon-option {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.2s;
        }

        .icon-option.active {
            background: rgba(59, 130, 246, 0.3);
            border-color: #60a5fa;
            color: #fff;
        }

        @media (max-width: 768px) {
            .layout-builder {
                flex-direction: column;
                top: 0;
            }

            .sidebar {
                width: 100%;
                display: flex;
                overflow-x: auto;
                padding: 0.5rem;
                position: relative;
            }

            .tab-btn {
                min-width: 150px;
                margin-bottom: 0;
                margin-right: 0.5rem;
            }

            .form-row {
                flex-direction: column;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
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
                    alt="Profile"
                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
            </a>
        </div>
    </nav>

    <div style="max-width: 1100px; margin: 0 auto; padding: 0 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 2rem;">
            <a href="../index.php" style="color: #a1a1aa; text-decoration: none;"><i class="fas fa-arrow-left"></i>
                Hub</a>
            <h1 style="margin: 0; font-size: 1.5rem;">Edit Master Profile</h1>
        </div>
        <div id="alertBox" class="alert" style="display: none; margin-top: 1rem;"></div>
    </div>

    <!-- MAIN BUILDER -->
    <div class="layout-builder">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <button class="tab-btn active" data-target="tab-identity"><i class="fas fa-fingerprint"></i>
                Identity</button>
            <button class="tab-btn" data-target="tab-personal"><i class="fas fa-address-card"></i> Personal
                Info</button>
            <button class="tab-btn" data-target="tab-professional"><i class="fas fa-briefcase"></i> Professional
                Stack</button>
            <button class="tab-btn" data-target="tab-timeline"><i class="fas fa-route"></i> The Journey</button>
        </div>

        <!-- CONTENT AREA -->
        <div class="content-area">

            <!-- MASTER FORM (Wraps Tabs 1, 2, and 3) -->
            <form id="biodataForm">

                <!-- TAB 1: IDENTITY -->
                <div class="tab-pane active" id="tab-identity">
                    <div class="editor-card">
                        <h2 class="section-title">Public Identity & Story</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Display Name</label>
                                <input type="text" id="full_name" class="form-control" value="<?php echo $fullName; ?>"
                                    placeholder="e.g. Jane Doe">
                            </div>
                            <div class="form-group">
                                <label>Nickname</label>
                                <input type="text" id="nickname" class="form-control" value="<?php echo $nickname; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>General Location</label>
                            <input type="text" id="location" class="form-control" value="<?php echo $location; ?>"
                                placeholder="e.g. Tokyo, Japan">
                        </div>

                        <div class="form-group">
                            <label>Professional Tagline</label>
                            <input type="text" id="tagline" class="form-control" value="<?php echo $tagline; ?>"
                                placeholder="e.g. Visionary Architect building the future">
                        </div>

                        <div class="form-group">
                            <label>About Me Story</label>
                            <textarea id="about_me" class="form-control form-textarea"
                                placeholder="Tell the world your story..."><?php echo $aboutMe; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PERSONAL INFO (Sensitive) -->
                <div class="tab-pane" id="tab-personal">
                    <div class="editor-card">
                        <h2 class="section-title">Personal Information <span
                                style="font-size: 0.8rem; font-weight: 400; color: #a1a1aa; float: right; margin-top: 5px;"><i
                                    class="fas fa-eye-slash"></i> Hidden publicly by default</span></h2>

                        <div class="form-group">
                            <label>Position Desired</label>
                            <input type="text" id="position_desired" class="form-control"
                                value="<?php echo $positionDesired; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Present Address</label>
                                <input type="text" id="present_address" class="form-control"
                                    value="<?php echo $presentAddress; ?>">
                            </div>
                            <div class="form-group">
                                <label>Provincial Address</label>
                                <input type="text" id="provincial_address" class="form-control"
                                    value="<?php echo $provincialAddress; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Place of Birth</label>
                                <input type="text" id="place_of_birth" class="form-control"
                                    value="<?php echo $placeOfBirth; ?>">
                            </div>
                            <div class="form-group">
                                <label>Citizenship</label>
                                <input type="text" id="citizenship" class="form-control"
                                    value="<?php echo $citizenship; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Gender</label>
                                <input type="text" id="gender" class="form-control" value="<?php echo $gender; ?>">
                            </div>
                            <div class="form-group">
                                <label>Civil Status</label>
                                <input type="text" id="civil_status" class="form-control"
                                    value="<?php echo $civilStatus; ?>">
                            </div>
                            <div class="form-group">
                                <label>Religion</label>
                                <input type="text" id="religion" class="form-control" value="<?php echo $religion; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Height</label>
                                <input type="text" id="height" class="form-control" value="<?php echo $height; ?>"
                                    placeholder="e.g. 175cm">
                            </div>
                            <div class="form-group">
                                <label>Weight</label>
                                <input type="text" id="weight" class="form-control" value="<?php echo $weight; ?>"
                                    placeholder="e.g. 70kg">
                            </div>
                        </div>

                        <h2 class="section-title" style="margin-top: 2rem;">Family Background</h2>
                        <div class="form-group">
                            <label>Parents' Names</label>
                            <input type="text" id="fb_parents" class="form-control" placeholder="Mother & Father">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Spouse Name (Optional)</label>
                                <input type="text" id="fb_spouse" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Children (Optional)</label>
                                <input type="text" id="fb_children" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: PROFESSIONAL STACK -->
                <div class="tab-pane" id="tab-professional">
                    <div class="editor-card">

                        <h2 class="section-title"><i class="fab fa-github"></i> GitHub Live Integration</h2>
                        <p style="font-size: 0.9rem; color: #a1a1aa; margin-bottom: 1.5rem;">Provide your GitHub
                            username to automatically fetch and display a Live Tech Stack and Activity card on your
                            public profile.</p>
                        <div class="form-group">
                            <label>GitHub Username</label>
                            <input type="text" id="github_username" class="form-control"
                                value="<?php echo $githubUsername; ?>" placeholder="e.g. octocat">
                        </div>

                        <h2 class="section-title" style="margin-top: 2.5rem;">My Skills</h2>
                        <div class="skills-input-wrapper">
                            <input type="text" id="skillInput" class="form-control"
                                placeholder="Type a skill and press Enter...">
                            <button type="button" class="btn btn-primary" id="addSkillBtn" style="width: auto;"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                        <div class="skills-container" id="skillsContainer">
                            <!-- Javascript generates tags here -->
                        </div>

                        <h2 class="section-title" style="margin-top: 2.5rem;">Social Presences</h2>
                        <div id="socialLinksContainer">
                            <!-- Javascript generates rows here -->
                        </div>
                        <button type="button" class="add-social-btn" id="addSocialBtn">
                            <i class="fas fa-plus"></i> Add Link
                        </button>

                    </div>
                </div>

                <!-- GLOBAL SAVE BUTTON FOR TABS 1,2,3 -->
                <div class="editor-card" id="globalSaveWidget"
                    style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #a1a1aa; font-size: 0.9rem;">Remember to save changes made across Identity,
                        Personal, and Professional tabs.</span>
                    <button type="submit" class="btn btn-primary" id="saveDraftBtn" style="width: auto;">
                        <span id="saveBtnText"><i class="fas fa-save"></i> Save Global Blueprint</span>
                    </button>
                </div>
            </form>

            <!-- TAB 4: THE JOURNEY (TIMELINE) -->
            <div class="tab-pane" id="tab-timeline">

                <div class="editor-card">
                    <h2 class="section-title">Add New Milestone</h2>
                    <form id="addMilestoneForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" id="ms_date" class="form-control" required>
                            </div>
                            <div class="form-group" style="flex: 2;">
                                <label>Headline / Title</label>
                                <input type="text" id="ms_title" class="form-control"
                                    placeholder="e.g. Graduated University" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="ms_desc" class="form-control" style="min-height: 80px;"
                                placeholder="Optional details..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Icon Graphic</label>
                            <input type="hidden" id="ms_icon" value="fa-solid fa-star">
                            <div class="icon-selector">
                                <div class="icon-option active" data-icon="fa-solid fa-star"><i
                                        class="fa-solid fa-star"></i></div>
                                <div class="icon-option" data-icon="fa-solid fa-graduation-cap"><i
                                        class="fa-solid fa-graduation-cap"></i></div>
                                <div class="icon-option" data-icon="fa-solid fa-briefcase"><i
                                        class="fa-solid fa-briefcase"></i></div>
                                <div class="icon-option" data-icon="fa-solid fa-award"><i class="fa-solid fa-award"></i>
                                </div>
                                <div class="icon-option" data-icon="fa-solid fa-rocket"><i
                                        class="fa-solid fa-rocket"></i></div>
                                <div class="icon-option" data-icon="fa-solid fa-heart"><i class="fa-solid fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-primary" id="ms_submitBtn" style="width: auto;">
                                <i class="fas fa-plus"></i> Append Milestone
                            </button>
                        </div>
                    </form>
                </div>

                <div class="editor-card">
                    <h2 class="section-title">Timeline History</h2>
                    <div id="milestoneList">
                        <?php if (empty($milestones)): ?>
                            <p style="color: #a1a1aa; text-align: center; margin: 2rem 0;">You haven't added any events to
                                your journey yet.</p>
                        <?php else: ?>
                            <?php foreach ($milestones as $ms): ?>
                                <div class="milestone-card" id="ms-<?php echo $ms['milestone_id']; ?>">
                                    <div
                                        style="margin-right: 1rem; color: #3b82f6; font-size: 1.5rem; width: 30px; text-align: center;">
                                        <i class="<?php echo htmlspecialchars($ms['icon']); ?>"></i>
                                    </div>
                                    <div class="milestone-meta">
                                        <span
                                            class="milestone-date"><?php echo date("F j, Y", strtotime($ms['milestone_date'])); ?></span>
                                        <h4 class="milestone-title"><?php echo htmlspecialchars($ms['title']); ?></h4>
                                        <?php if (!empty($ms['description'])): ?>
                                            <p class="milestone-desc"><?php echo nl2br(htmlspecialchars($ms['description'])); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="milestone-actions">
                                        <button class="btn btn-danger" style="padding: 0.5rem; width: auto;"
                                            onclick="deleteMilestone(<?php echo $ms['milestone_id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div> <!-- END TAB 4 -->

        </div> <!-- END CONTENT AREA -->
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // TABS LOGIC
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                // Ignore if active
                if (this.classList.contains('active')) return;

                // Remove all active classes
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

                // Add active to current
                this.classList.add('active');
                document.getElementById(this.getAttribute('data-target')).classList.add('active');

                // Hide global save widget if on Timeline (which runs its own saves)
                document.getElementById('globalSaveWidget').style.display = (this.getAttribute('data-target') === 'tab-timeline') ? 'none' : 'flex';
            });
        });

        // Parse JSON States
        let skillsState = <?php echo $skills; ?>;
        let socialLinksState = <?php echo $socialLinks; ?>;
        if (typeof socialLinksState === 'string') {
            try { socialLinksState = JSON.parse(socialLinksState); } catch (e) { socialLinksState = {}; }
        }

        let fbState = <?php echo $familyBackground; ?>;
        if (typeof fbState === 'string') {
            try { fbState = JSON.parse(fbState); } catch (e) { fbState = { spouse: "", children: "", parents: "" }; }
        }

        // Populate Family back to inputs
        document.getElementById('fb_spouse').value = fbState.spouse || '';
        document.getElementById('fb_children').value = fbState.children || '';
        document.getElementById('fb_parents').value = fbState.parents || '';


        // --- SKILLS LOGIC ---
        const skillInput = document.getElementById('skillInput');
        function renderSkills() {
            const container = document.getElementById('skillsContainer');
            container.innerHTML = '';
            skillsState.forEach((skill, index) => {
                const tag = document.createElement('div');
                tag.className = 'skill-tag';
                tag.innerHTML = `${skill} <button type="button" onclick="removeSkill(${index})"><i class="fas fa-times"></i></button>`;
                container.appendChild(tag);
            });
        }
        function addSkill() {
            const val = skillInput.value.trim();
            if (val && !skillsState.includes(val)) {
                if (skillsState.length >= 15) return alert('Maximum 15 skills allowed.');
                skillsState.push(val); renderSkills(); skillInput.value = '';
            }
        }
        function removeSkill(index) { skillsState.splice(index, 1); renderSkills(); }
        skillInput.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); addSkill(); } });
        document.getElementById('addSkillBtn').addEventListener('click', addSkill);


        // --- SOCIAL LOGIC ---
        const socialLinksContainer = document.getElementById('socialLinksContainer');
        const platforms = ['twitter', 'github', 'linkedin', 'instagram', 'youtube', 'facebook', 'website'];
        function createSocialRow(platformKey = '', urlValue = '') {
            const row = document.createElement('div'); row.className = 'social-link-item';
            const selectHTML = platforms.map(p => `<option value="${p}" ${p === platformKey ? 'selected' : ''}>${p.charAt(0).toUpperCase() + p.slice(1)}</option>`).join('');
            row.innerHTML = `<select class="form-control" style="width: 150px; flex-shrink: 0;"><option value="" disabled selected>Platform</option>${selectHTML}</select>
                <input type="url" class="form-control" placeholder="https://..." value="${urlValue}">
                <button type="button" class="remove-social-btn" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>`;
            return row;
        }
        function renderSocials() {
            socialLinksContainer.innerHTML = '';
            for (const [key, url] of Object.entries(socialLinksState)) socialLinksContainer.appendChild(createSocialRow(key, url));
        }
        document.getElementById('addSocialBtn').addEventListener('click', () => {
            if (socialLinksContainer.children.length >= 7) return alert("Max 7 links.");
            socialLinksContainer.appendChild(createSocialRow());
        });

        // Initialize Native Renders
        renderSkills();
        renderSocials();


        // --- GLOBAL SAVE (TABS 1,2,3) ---
        document.getElementById('biodataForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('saveDraftBtn');
            const alertBox = document.getElementById('alertBox');

            // Gather social links
            let currentSocials = {};
            Array.from(socialLinksContainer.children).forEach(row => {
                const selectInfo = row.querySelector('select').value;
                const urlInfo = row.querySelector('input').value.trim();
                if (selectInfo && urlInfo) currentSocials[selectInfo] = urlInfo;
            });

            // Gather Family Background
            let fb = {
                spouse: document.getElementById('fb_spouse').value.trim(),
                children: document.getElementById('fb_children').value.trim(),
                parents: document.getElementById('fb_parents').value.trim()
            };

            const payload = {
                // Identity
                full_name: document.getElementById('full_name').value.trim(),
                nickname: document.getElementById('nickname').value.trim(),
                tagline: document.getElementById('tagline').value.trim(),
                location: document.getElementById('location').value.trim(),
                about_me: document.getElementById('about_me').value.trim(),

                // Personal
                position_desired: document.getElementById('position_desired').value.trim(),
                present_address: document.getElementById('present_address').value.trim(),
                provincial_address: document.getElementById('provincial_address').value.trim(),
                place_of_birth: document.getElementById('place_of_birth').value.trim(),
                citizenship: document.getElementById('citizenship').value.trim(),
                gender: document.getElementById('gender').value.trim(),
                civil_status: document.getElementById('civil_status').value.trim(),
                religion: document.getElementById('religion').value.trim(),
                height: document.getElementById('height').value.trim(),
                weight: document.getElementById('weight').value.trim(),
                family_background: fb,

                // Professional
                github_username: document.getElementById('github_username').value.trim(),
                skills: skillsState,
                social_links: currentSocials
            };

            submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
            alertBox.style.display = 'none'; alertBox.className = 'alert';

            try {
                const response = await fetch('action_update_biodata.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                const data = await response.json();
                if (data.success) {
                    alertBox.textContent = "Blueprint updated successfully!";
                    alertBox.classList.add('alert-success');
                } else {
                    alertBox.textContent = data.message || "Failed to update.";
                    alertBox.classList.add('alert-danger');
                }
            } catch (error) {
                alertBox.textContent = "Network error.";
                alertBox.classList.add('alert-danger');
            } finally {
                alertBox.style.display = 'block';
                submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Global Blueprint';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });


        // --- TIMELINE ICON SELECTOR ---
        document.querySelectorAll('.icon-option').forEach(opt => {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.icon-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('ms_icon').value = this.getAttribute('data-icon');
            });
        });

        // --- TIMELINE SUBMIT VIA AJAX ---
        document.getElementById('addMilestoneForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('ms_submitBtn');
            const alertBox = document.getElementById('alertBox');

            const payload = {
                action: 'add',
                date: document.getElementById('ms_date').value,
                title: document.getElementById('ms_title').value.trim(),
                desc: document.getElementById('ms_desc').value.trim(),
                icon: document.getElementById('ms_icon').value
            };

            btn.disabled = true;
            try {
                const req = await fetch('action_manage_milestones.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                const res = await req.json();
                if (res.success) {
                    window.location.reload(); // Quick reload to render new PHP list perfectly
                } else {
                    alertBox.textContent = res.message; alertBox.className = 'alert alert-danger'; alertBox.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (e) {
                alertBox.textContent = "Network Error saving milestone."; alertBox.className = 'alert alert-danger'; alertBox.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } finally {
                btn.disabled = false;
            }
        });

        // --- TIMELINE DELETE VIA AJAX ---
        async function deleteMilestone(id) {
            if (!confirm("Are you sure you want to delete this historical milestone?")) return;
            const alertBox = document.getElementById('alertBox');

            try {
                const req = await fetch('action_manage_milestones.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'delete', id: id }) });
                const res = await req.json();
                if (res.success) {
                    document.getElementById('ms-' + id).remove();
                } else {
                    alertBox.textContent = res.message; alertBox.className = 'alert alert-danger'; alertBox.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (e) {
                alertBox.textContent = "Network Error deleting milestone."; alertBox.className = 'alert alert-danger'; alertBox.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    </script>
    <script src="../js/form_guards.js"></script>
    <script>
        // Track unsaved changes logic on main form
        const editorGuard = new FormGuard('biodataForm', 'saveDraftBtn');
    </script>

    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
    <script src="../js/background_animation.js"></script>
</body>

</html>