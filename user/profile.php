<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/db.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND is_archived = 0");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="../auth/style.css">
    <style>
        .profile-container {
            max-width: 600px;
            width: 100%;
        }

        .avatar-section {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: calc(50% - 60px);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-upload-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        #photoInput {
            display: none;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .header-back {
            position: absolute;
            top: 2.5rem;
            left: 2.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .header-back:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="auth-container profile-container">
        <a href="../index.php" class="header-back"><i class="fas fa-arrow-left"></i></a>

        <div class="auth-header" style="margin-bottom: 1rem;">
            <h1>My Profile</h1>
            <p>Manage your account settings</p>
        </div>

        <div id="alertBox" class="alert" style="display: none;"></div>

        <div class="avatar-section">
            <img src="../<?php echo htmlspecialchars($user['photo'] ?? 'images/default.png'); ?>" alt="Avatar"
                class="avatar-img" id="avatarPreview">
            <label for="photoInput" class="avatar-upload-btn" title="Change Photo">
                <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="photoInput" accept="image/*">
        </div>

        <form id="profileForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <!-- Email is usually readonly or requires a complex flow to change -->
                <input type="email" id="email" class="form-control"
                    value="<?php echo htmlspecialchars($user['email']); ?>" readonly
                    style="background: rgba(255,255,255,0.02); color: #94a3b8; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="role">Account Role</label>
                <input type="text" id="role" class="form-control"
                    value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" readonly
                    style="background: rgba(255,255,255,0.02); color: #94a3b8; cursor: not-allowed; text-transform: capitalize;">
            </div>

            <!-- Profile Actions -->
            <div class="action-buttons" style="flex-direction: column;">
                <button type="submit" id="saveBtn" class="btn btn-primary">
                    <i class="fas fa-save" style="margin-right: 8px;"></i> Save Changes
                </button>
                <a href="../auth/forgot_password.php" class="btn"
                    style="text-align: center; text-decoration: none; display:block;">
                    <i class="fas fa-key" style="margin-right: 8px;"></i> Change Password
                </a>
                <button type="button" id="deleteBtn" class="btn btn-danger" style="margin-top: 1rem;">
                    <i class="fas fa-trash-alt" style="margin-right: 8px;"></i> Delete Account
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

    <script>
        const alertBox = document.getElementById('alertBox');

        function showAlert(msg, isSuccess) {
            alertBox.textContent = msg;
            alertBox.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-danger');
            alertBox.style.display = 'block';
            setTimeout(() => { alertBox.style.display = 'none'; }, 5000);
        }

        // Photo Upload Preview
        const photoInput = document.getElementById('photoInput');
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Save Profile API Call
        document.getElementById('profileForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('saveBtn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="spinner"></span> Saving...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('username', document.getElementById('username').value);
            if (photoInput.files.length > 0) {
                formData.append('photo', photoInput.files[0]);
            }

            try {
                const response = await fetch('action_profile.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                showAlert(data.message, data.success);
            } catch (err) {
                showAlert("Network error occurred.", false);
                profileGuard.setDirty(true); // reset dirty flag because save failed
            }

            btn.innerHTML = originalText;
            btn.disabled = false;
        });

        // Delete Account Logic
        const deleteModal = document.getElementById('deleteModal');
        document.getElementById('deleteBtn').addEventListener('click', () => {
            deleteModal.style.display = 'flex';
        });
        document.getElementById('cancelDelete').addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });

        document.getElementById('confirmDelete').addEventListener('click', async () => {
            const btn = document.getElementById('confirmDelete');
            btn.innerHTML = '<span class="spinner"></span> Deleting...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'delete_account');

            try {
                const response = await fetch('action_profile.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = '../auth/login.php';
                } else {
                    showAlert(data.message, false);
                    deleteModal.style.display = 'none';
                    btn.innerHTML = 'Yes, Delete';
                    btn.disabled = false;
                }
            } catch (err) {
                showAlert("Network error.", false);
                deleteModal.style.display = 'none';
                btn.innerHTML = 'Yes, Delete';
                btn.disabled = false;
            }
        });
    </script>
    <script src="../js/form_guards.js"></script>
    <script>
        const profileGuard = new FormGuard('profileForm', 'saveBtn');
    </script>
    <?php include __DIR__ . '/../includes/username_modal.php'; ?>
</body>

</html>