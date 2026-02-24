<?php
// Only show the modal if the user is logged in and doesn't have a username set.
// This assumes $user['username'] is available in the including file.
if (isset($user) && empty($user['username'])):
    ?>
    <style>
        .username-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .username-modal-box {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .username-modal-box h2 {
            margin-top: 0;
            color: #f8fafc;
            font-size: 1.5rem;
        }

        .username-modal-box p {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .username-modal-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            box-sizing: border-box;
        }

        .username-modal-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .username-modal-actions {
            display: flex;
            gap: 1rem;
        }

        .username-modal-btn {
            flex: 1;
            padding: 0.75rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .username-modal-btn-primary {
            background: #3b82f6;
            color: #fff;
        }

        .username-modal-btn-primary:hover {
            background: #2563eb;
        }

        .username-modal-btn-secondary {
            background: transparent;
            color: #94a3b8;
            border: 1px solid #334155;
        }

        .username-modal-btn-secondary:hover {
            background: #334155;
            color: #fff;
        }

        .username-modal-alert {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            display: none;
        }

        .username-modal-alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .username-modal-alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
    </style>

    <div class="username-modal-overlay" id="usernameModalOverlay">
        <div class="username-modal-box">
            <h2>Set Your Username</h2>
            <p>Choose a unique username for your account, or skip to get a randomly generated one.</p>

            <div id="usernameModalAlert" class="username-modal-alert"></div>

            <input type="text" id="usernameModalInput" class="username-modal-input" placeholder="e.g. CyberNinja99"
                autocomplete="off">

            <div class="username-modal-actions">
                <button class="username-modal-btn username-modal-btn-secondary" id="usernameModalSkipBtn">Skip</button>
                <button class="username-modal-btn username-modal-btn-primary" id="usernameModalSaveBtn">Save</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('usernameModalOverlay');
            const input = document.getElementById('usernameModalInput');
            const skipBtn = document.getElementById('usernameModalSkipBtn');
            const saveBtn = document.getElementById('usernameModalSaveBtn');
            const alertBox = document.getElementById('usernameModalAlert');

            function showModalAlert(msg, isSuccess) {
                alertBox.textContent = msg;
                alertBox.className = 'username-modal-alert ' + (isSuccess ? 'username-modal-alert-success' : 'username-modal-alert-error');
                alertBox.style.display = 'block';
            }

            async function submitUsernameData(data) {
                const originalSaveText = saveBtn.innerText;
                const originalSkipText = skipBtn.innerText;

                skipBtn.disabled = true;
                saveBtn.disabled = true;

                if (data.skip) {
                    skipBtn.innerText = 'Creating...';
                } else {
                    saveBtn.innerText = 'Saving...';
                }

                try {
                    // Adjust path depending on where the modal is included from.
                    // Since this runs in the context of the parent page, we use absolute or relative paths carefully.
                    // Assuming it's running from root (index.php) or user/ (profile.php), we might need an absolute path to the endpoint.
                    // Since action_set_username.php is in auth folder, we can use an absolute path from webroot or find relative.
                    // A safe bet is calculating based on window location or using a strict path if we know the domain.
                    // Let's use a path relative to the domain root (adjust if running in a subdirectory)
                    // We'll use a trick: figure out how deep we are by counting slashes, or just provide a static relative path if we know the structure.
                    // Better approach: use a base URL if available, or just fallback to generic path.
                    const basePath = window.location.pathname.includes('/user/') ? '../' : '';
                    const fetchUrl = basePath + 'auth/action_set_username.php';

                    const response = await fetch(fetchUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();

                    if (result.success) {
                        showModalAlert(result.message, true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showModalAlert(result.message, false);
                        skipBtn.disabled = false;
                        saveBtn.disabled = false;
                        skipBtn.innerText = originalSkipText;
                        saveBtn.innerText = originalSaveText;
                    }
                } catch (err) {
                    showModalAlert("A network error occurred.", false);
                    skipBtn.disabled = false;
                    saveBtn.disabled = false;
                    skipBtn.innerText = originalSkipText;
                    saveBtn.innerText = originalSaveText;
                }
            }

            saveBtn.addEventListener('click', () => {
                const val = input.value.trim();
                if (!val) {
                    showModalAlert('Please enter a username or click skip.', false);
                    return;
                }
                submitUsernameData({ skip: false, username: val });
            });

            skipBtn.addEventListener('click', () => {
                if (confirm("Are you sure you want to skip? An auto-generated username will be assigned to you.")) {
                    submitUsernameData({ skip: true });
                }
            });

            // Allow Enter key to save
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    saveBtn.click();
                }
            });
        });
    </script>
<?php endif; ?>