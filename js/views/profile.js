// Photo Upload Preview
const photoInput = document.getElementById('photoInput');
if (photoInput) {
    photoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
}

// Save Profile API Call
const profileForm = document.getElementById('profileForm');
if (profileForm) {
    profileForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('saveBtn');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<span class="spinner"></span> Saving...';
        btn.disabled = true;

        const formData = new FormData(this);
        formData.append('action', 'update_profile');

        if (photoInput && photoInput.files.length > 0) {
            formData.append('photo', photoInput.files[0]);
        }

        try {
            const response = await fetch('action_profile.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            showToast(data.message, data.success ? 'success' : 'danger');

            if (data.success) {
                // Update dataset original values silently
                const inputs = this.querySelectorAll('input:not([type="file"])');
                inputs.forEach(input => input.dataset.original = input.value);

                // Revert to view mode
                setTimeout(() => disableEditMode(), 1000);
            } else {
                if (typeof profileGuard !== 'undefined') profileGuard.setDirty(true);
            }
        } catch (err) {
            showToast("Network error occurred.", "danger");
            if (typeof profileGuard !== 'undefined') profileGuard.setDirty(true);
        }

        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Delete Account Logic
const deleteModal = document.getElementById('deleteModal');
const deleteBtn = document.getElementById('deleteBtn');
const cancelDelete = document.getElementById('cancelDelete');
const confirmDeleteBtn = document.getElementById('confirmDelete');

if (deleteBtn) {
    deleteBtn.addEventListener('click', () => {
        deleteModal.style.display = 'flex';
    });
}
if (cancelDelete) {
    cancelDelete.addEventListener('click', () => {
        deleteModal.style.display = 'none';
    });
}

if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', async () => {
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
                showToast(data.message, "danger");
                deleteModal.style.display = 'none';
                btn.innerHTML = 'Yes, Delete';
                btn.disabled = false;
            }
        } catch (err) {
            showToast("Network error.", "danger");
            deleteModal.style.display = 'none';
            btn.innerHTML = 'Yes, Delete';
            btn.disabled = false;
        }
    });
}

let profileGuard;
if (typeof FormGuard !== 'undefined') {
    profileGuard = new FormGuard('profileForm', 'saveBtn');
}

// View Mode vs Edit Mode Logic
const profileContainer = document.getElementById('profileContainer');
const viewActions = document.getElementById('viewActions');
const editActions = document.getElementById('editActions');
const editProfileBtn = document.getElementById('editProfileBtn');
const cancelEditBtn = document.getElementById('cancelEditBtn');

if (editProfileBtn) {
    editProfileBtn.addEventListener('click', () => {
        profileContainer.classList.remove('view-mode');
        viewActions.style.display = 'none';
        editActions.style.display = 'flex';

        const uInput = document.getElementById('username');
        if (uInput) uInput.focus();
    });
}

function disableEditMode() {
    profileContainer.classList.add('view-mode');
    viewActions.style.display = 'flex';
    editActions.style.display = 'none';

    // Reset inputs to database originals
    const inputs = document.getElementById('profileForm').querySelectorAll('input:not([type="file"])');
    inputs.forEach(input => {
        input.value = input.dataset.original || '';
    });

    // Reset photo preview and file input using injected global data
    if (window.ProfileData && window.ProfileData.photoUrl) {
        document.getElementById('avatarPreview').src = window.ProfileData.photoUrl;
    }
    if (photoInput) photoInput.value = '';

    // Reset username warning
    if (usernameWarning) usernameWarning.textContent = '';

    // Re-lock FormGuard
    if (profileGuard) profileGuard.setDirty(false);
}

if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', disableEditMode);
}

const usernameInput = document.getElementById('username');
const usernameWarning = document.getElementById('usernameWarning');
const saveBtn = document.getElementById('saveBtn');
const originalUsername = usernameInput ? usernameInput.value : '';
const reservedWords = ['admin', 'support', 'help', 'root', 'api', 'login', 'signup', 'settings', 'dashboard', 'system', 'staff', 'mod', 'owner', 'blog', 'about', 'contact', 'null', 'undefined'];
const usernameRegex = /^[a-zA-Z0-9](_(?!_)|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$/;
let debounceTimer;

if (usernameInput && usernameWarning) {
    usernameInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);

        let val = e.target.value;
        usernameWarning.style.color = '#f87171'; // Default red

        // Force lowercase
        if (val !== val.toLowerCase()) {
            val = val.toLowerCase();
            e.target.value = val;
        }

        if (val === originalUsername) {
            usernameWarning.textContent = '';
            saveBtn.disabled = false;
            return;
        }

        if (val.length === 0) {
            usernameWarning.textContent = 'Username is required.';
            saveBtn.disabled = true;
            return;
        }

        if (reservedWords.includes(val)) {
            usernameWarning.textContent = 'This username is reserved and cannot be used.';
            saveBtn.disabled = true;
            return;
        }

        if (!usernameRegex.test(val)) {
            usernameWarning.textContent = '4-20 chars, alphanumeric or single underscores, cannot start/end with underscore.';
            saveBtn.disabled = true;
            return;
        }

        // Client checks passed, show loading indicator
        usernameWarning.style.color = '#94a3b8';
        usernameWarning.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Checking availability...';
        saveBtn.disabled = true;

        // Debounced AJAX Request
        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch('action_check_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: val })
                });
                const data = await response.json();

                if (data.success) {
                    usernameWarning.style.color = '#4ade80';
                    usernameWarning.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    saveBtn.disabled = false;
                } else {
                    usernameWarning.style.color = '#f87171';
                    usernameWarning.innerHTML = '<i class="fas fa-times-circle"></i> ' + data.message;
                    saveBtn.disabled = true;
                }
            } catch (error) {
                usernameWarning.style.color = '#f87171';
                usernameWarning.textContent = 'Error checking username.';
            }
        }, 600); // 600ms delay
    });
}
