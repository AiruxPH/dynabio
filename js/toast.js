/**
 * Global Toast Notification System
 */

// Initialize toast container on DOM load or immediately if ready
document.addEventListener("DOMContentLoaded", initToastContainer);
if (document.readyState === "complete" || document.readyState === "interactive") {
    initToastContainer();
}

function initToastContainer() {
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }
}

/**
 * Displays a floating glassmorphism toast notification.
 * 
 * @param {string} message - The message to display.
 * @param {string} type - 'success' or 'danger'
 */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) {
        // Fallback safety if container doesn't exist yet
        initToastContainer();
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    // Optional: Add icons based on type
    let icon = '';
    if (type === 'success') {
        icon = '<i class="fas fa-check-circle"></i> ';
    } else if (type === 'danger') {
        icon = '<i class="fas fa-exclamation-circle"></i> ';
    }

    toast.innerHTML = icon + message;

    document.getElementById('toast-container').appendChild(toast);

    // Auto dismiss after 3 seconds
    setTimeout(() => {
        toast.classList.add('toast-fadeOut');
        // Wait for animation to finish before removing from DOM
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300); // 300ms matches CSS animation duration
    }, 3000);
}
