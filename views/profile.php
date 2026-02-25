<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../css/views/profile.css?v=2.0">
</head>

<body>

    <div class="auth-container profile-container view-mode" id="profileContainer">
        <a href="../index.php" class="header-back"><i class="fas fa-arrow-left"></i></a>

        <div class="auth-header" style="margin-bottom: 1rem;">
            <h1>My Profile</h1>
            <p>Manage your account settings</p>
        </div>

        <div class="avatar-section">
            <img src="../<?php echo htmlspecialchars((string) ($user['photo'] ?? 'images/default.png')); ?>"
                alt="Avatar" class="avatar-img" id="avatarPreview">
            <label for="photoInput" class="avatar-upload-btn" title="Change Photo">
                <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="photoInput" accept="image/*" onchange="previewUpload(this)">
        </div>

        <form id="profileForm">
            <div class="form-group" style="position: relative;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo htmlspecialchars((string) $user['username']); ?>"
                    data-original="<?php echo htmlspecialchars((string) $user['username']); ?>" required>
                <span id="usernameWarning"
                    style="font-size: 0.8rem; color: #94a3b8; display: block; margin-top: 0.25rem; min-height: 1rem;"></span>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <!-- Email is usually readonly or requires a complex flow to change -->
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars((string) $user['email']); ?>"
                    data-original="<?php echo htmlspecialchars((string) $user['email']); ?>" readonly
                    style="background: rgba(255,255,255,0.02); color: #94a3b8; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="role">Account Role</label>
                <input type="text" id="role" name="role" class="form-control"
                    value="<?php echo ucfirst(htmlspecialchars((string) $user['role'])); ?>"
                    data-original="<?php echo ucfirst(htmlspecialchars((string) $user['role'])); ?>" readonly
                    style="background: rgba(255,255,255,0.02); color: #94a3b8; cursor: not-allowed; text-transform: capitalize;">
            </div>

            <!-- View Actions -->
            <div id="viewActions" class="action-buttons" style="flex-direction: column;">
                <button type="button" id="editProfileBtn" class="btn btn-primary"
                    style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);">
                    <i class="fas fa-pen" style="margin-right: 8px;"></i> Edit Profile
                </button>
                <a href="../auth/forgot_password.php" class="btn"
                    style="text-align: center; text-decoration: none; display:block;">
                    <i class="fas fa-key" style="margin-right: 8px;"></i> Change Password
                </a>
                <button type="button" id="deleteBtn" class="btn btn-danger" style="margin-top: 1rem;">
                    <i class="fas fa-trash-alt" style="margin-right: 8px;"></i> Delete Account
                </button>
            </div>

            <!-- Edit Actions -->
            <div id="editActions" class="action-buttons" style="display: none; flex-direction: column;">
                <button type="submit" id="saveBtn" class="btn btn-primary">
                    <i class="fas fa-save" style="margin-right: 8px;"></i> Save Changes
                </button>
                <button type="button" id="cancelEditBtn" class="btn">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal Overlay -->
    <div id="deleteModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter:blur(5px); z-index:999; justify-content:center; align-items:center;">
        <div class="auth-container" style="max-width: 400px; text-align: center;">
            <h2 style="color: #ef4444; margin-top:0;">Delete Account?</h2>
            <p style="color: #a1a1aa; margin-bottom: 2rem;">This action cannot be undone. You will lose access to your
                account immediately.</p>
            <div style="display:flex; gap:1rem;">
                <button id="cancelDelete" class="btn" style="flex:1;">Cancel</button>
                <button id="confirmDelete" class="btn btn-danger" style="flex:1;">Yes, Delete</button>
            </div>
        </div>
    </div>

    <script src="../js/toast.js"></script>
    <script src="../js/form_guards.js"></script>
    <script>
        window.ProfileData = {
            photoUrl: '../<?php echo htmlspecialchars((string) ($user['photo'] ?? 'images/default.png')); ?>'
        };
    </script>
    <script src="../js/views/profile.js"></script>
    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
    <script src="../js/background_animation.js"></script>

    <!-- Phase 8: Academic Inline Event Functions (Extended) -->
    <script>
        function previewUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>