<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bio - DynaBio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../css/views/editor.css?v=2.0">
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

    <div style="max-width: 1100px; margin: 0 auto; padding: 0 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 2rem;">
            <a href="../index.php" style="color: #a1a1aa; text-decoration: none;"><i class="fas fa-arrow-left"></i>
                Hub</a>
            <h1 style="margin: 0; font-size: 1.5rem;">Edit Master Profile</h1>
        </div>
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
                                        <span class="milestone-date">
                                            <?php echo date("F j, Y", strtotime($ms['milestone_date'])); ?>
                                        </span>
                                        <h4 class="milestone-title">
                                            <?php echo htmlspecialchars($ms['title']); ?>
                                        </h4>
                                        <?php if (!empty($ms['description'])): ?>
                                            <p class="milestone-desc">
                                                <?php echo nl2br(htmlspecialchars($ms['description'])); ?>
                                            </p>
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

    <script>
        window.EditorData = {
            skills: <?php echo json_encode($skills); ?>,
            socialLinks: <?php echo json_encode($socialLinks); ?>,
            familyBackground: <?php echo json_encode($familyBackground); ?>
        };
    </script>
    <script src="../js/toast.js"></script>
    <script src="../js/form_guards.js"></script>
    <script src="../js/views/editor.js"></script>

    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
    <script src="../js/background_animation.js"></script>
</body>

</html>