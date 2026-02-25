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
        const globalWidget = document.getElementById('globalSaveWidget');
        if (globalWidget) {
            globalWidget.style.display = (this.getAttribute('data-target') === 'tab-timeline') ? 'none' : 'flex';
        }
    });
});

// Parse JSON States from injected Global Window Object
let skillsState = [];
let socialLinksState = {};
let fbState = { spouse: "", children: "", parents: "" };

if (window.EditorData) {
    // Array parsing with fallback
    try {
        const parsedSkills = typeof window.EditorData.skills === 'string' ? JSON.parse(window.EditorData.skills) : window.EditorData.skills;
        skillsState = Array.isArray(parsedSkills) ? parsedSkills : [];
    } catch (e) { skillsState = []; }

    // Object parsing with fallback
    try {
        const parsedSocials = typeof window.EditorData.socialLinks === 'string' ? JSON.parse(window.EditorData.socialLinks) : window.EditorData.socialLinks;
        socialLinksState = parsedSocials || {};
    } catch (e) { socialLinksState = {}; }

    // Deep object parsing for nested JSON strings which may also just immediately resolve
    try {
        const parsedFbString = typeof window.EditorData.familyBackground === 'string' ? window.EditorData.familyBackground : JSON.stringify(window.EditorData.familyBackground);
        // Clean out any quotes that were double casted by JSON.encode and raw htmlspecialchars then parse
        if (parsedFbString && parsedFbString !== "null") {
            // In PHP null family_background becomes the string "null"
            const cleanString = parsedFbString.replace(/&quot;/g, '"');
            const validJSON = JSON.parse(cleanString);
            if (validJSON && typeof validJSON === 'object') {
                fbState = {
                    spouse: validJSON.spouse || "",
                    children: validJSON.children || "",
                    parents: validJSON.parents || ""
                };
            }
        }
    } catch (e) {
        fbState = { spouse: "", children: "", parents: "" };
    }
}

// Populate Family back to inputs safely if elements exist
const fbSpouse = document.getElementById('fb_spouse');
const fbChildren = document.getElementById('fb_children');
const fbParents = document.getElementById('fb_parents');

if (fbSpouse) fbSpouse.value = fbState.spouse || '';
if (fbChildren) fbChildren.value = fbState.children || '';
if (fbParents) fbParents.value = fbState.parents || '';

// --- SKILLS LOGIC ---
const skillInput = document.getElementById('skillInput');
function renderSkills() {
    const container = document.getElementById('skillsContainer');
    if (!container) return;
    container.innerHTML = '';
    skillsState.forEach((skill, index) => {
        const tag = document.createElement('div');
        tag.className = 'skill-tag';
        // Need to escape user input since we are injecting innerHTML
        const escapedSkill = escapeHtml(skill);
        tag.innerHTML = `${escapedSkill} <button type="button" onclick="removeSkill(${index})"><i class="fas fa-times"></i></button>`;
        container.appendChild(tag);
    });
}

function escapeHtml(unsafe) {
    return (unsafe || "").toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function addSkill() {
    if (!skillInput) return;
    const val = skillInput.value.trim();
    if (val && !skillsState.includes(val)) {
        if (skillsState.length >= 15) return alert('Maximum 15 skills allowed.');
        skillsState.push(val);
        renderSkills();
        skillInput.value = '';
    }
}

function removeSkill(index) {
    skillsState.splice(index, 1);
    renderSkills();
}

if (skillInput) {
    skillInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') { e.preventDefault(); addSkill(); }
    });
}
const addSkillBtn = document.getElementById('addSkillBtn');
if (addSkillBtn) {
    addSkillBtn.addEventListener('click', addSkill);
}

// --- SOCIAL LOGIC ---
const socialLinksContainer = document.getElementById('socialLinksContainer');
const platforms = ['twitter', 'github', 'linkedin', 'instagram', 'youtube', 'facebook', 'website'];

function createSocialRow(platformKey = '', urlValue = '') {
    const row = document.createElement('div');
    row.className = 'social-link-item';
    const selectHTML = platforms.map(p => `<option value="${p}" ${p === platformKey ? 'selected' : ''}>${p.charAt(0).toUpperCase() + p.slice(1)}</option>`).join('');

    // Safely inject the value
    const escapedUrl = escapeHtml(urlValue);

    row.innerHTML = `<select class="form-control" style="width: 150px; flex-shrink: 0;"><option value="" disabled selected>Platform</option>${selectHTML}</select>
        <input type="url" class="form-control" placeholder="https://..." value="${escapedUrl}">
        <button type="button" class="remove-social-btn" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>`;
    return row;
}

function renderSocials() {
    if (!socialLinksContainer) return;
    socialLinksContainer.innerHTML = '';
    // Always render standard objects as entries
    if (socialLinksState && typeof socialLinksState === 'object' && !Array.isArray(socialLinksState)) {
        for (const [key, url] of Object.entries(socialLinksState)) {
            socialLinksContainer.appendChild(createSocialRow(key, url));
        }
    }
}

const addSocialBtn = document.getElementById('addSocialBtn');
if (addSocialBtn && socialLinksContainer) {
    addSocialBtn.addEventListener('click', () => {
        if (socialLinksContainer.children.length >= 7) return alert("Max 7 links.");
        socialLinksContainer.appendChild(createSocialRow());
    });
}

// Initialize Native Renders safely
renderSkills();
renderSocials();

// Helper to safely get element value
function getVal(id) {
    const el = document.getElementById(id);
    return el ? el.value.trim() : '';
}

// --- GLOBAL SAVE (TABS 1,2,3) ---
const biodataForm = document.getElementById('biodataForm');
if (biodataForm) {
    biodataForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const submitBtn = document.getElementById('saveDraftBtn');

        // Gather social links
        let currentSocials = {};
        if (socialLinksContainer) {
            Array.from(socialLinksContainer.children).forEach(row => {
                const selectElement = row.querySelector('select');
                const inputElement = row.querySelector('input');

                if (selectElement && inputElement) {
                    const selectInfo = selectElement.value;
                    const urlInfo = inputElement.value.trim();
                    if (selectInfo && urlInfo) currentSocials[selectInfo] = urlInfo;
                }
            });
        }

        // Gather Family Background
        let fb = {
            spouse: getVal('fb_spouse'),
            children: getVal('fb_children'),
            parents: getVal('fb_parents')
        };

        const payload = {
            // Identity
            full_name: getVal('full_name'),
            nickname: getVal('nickname'),
            tagline: getVal('tagline'),
            location: getVal('location'),
            about_me: getVal('about_me'),

            // Personal
            position_desired: getVal('position_desired'),
            present_address: getVal('present_address'),
            provincial_address: getVal('provincial_address'),
            place_of_birth: getVal('place_of_birth'),
            citizenship: getVal('citizenship'),
            gender: getVal('gender'),
            civil_status: getVal('civil_status'),
            religion: getVal('religion'),
            height: getVal('height'),
            weight: getVal('weight'),
            family_background: fb,

            // Professional
            github_username: getVal('github_username'),
            skills: skillsState,
            social_links: currentSocials
        };

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
        }

        try {
            const response = await fetch('action_update_biodata.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (data.success) {
                showToast("Blueprint updated successfully!", "success");
                // Reset form dirty state since save was successful
                if (typeof editorGuard !== 'undefined') editorGuard.setDirty(false);
            } else {
                showToast(data.message || "Failed to update.", "danger");
            }
        } catch (error) {
            showToast("Network error.", "danger");
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Global Blueprint';
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}

// --- TIMELINE ICON SELECTOR ---
document.querySelectorAll('.icon-option').forEach(opt => {
    opt.addEventListener('click', function () {
        document.querySelectorAll('.icon-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');

        const msIconInput = document.getElementById('ms_icon');
        if (msIconInput) {
            msIconInput.value = this.getAttribute('data-icon');
        }
    });
});

// --- TIMELINE SUBMIT VIA AJAX ---
const addMilestoneForm = document.getElementById('addMilestoneForm');
if (addMilestoneForm) {
    addMilestoneForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('ms_submitBtn');

        const payload = {
            action: 'add',
            date: getVal('ms_date'),
            title: getVal('ms_title'),
            desc: getVal('ms_desc'),
            icon: getVal('ms_icon') || 'fa-solid fa-star'
        };

        if (btn) btn.disabled = true;
        try {
            const req = await fetch('action_manage_milestones.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const res = await req.json();
            if (res.success) {
                window.location.reload(); // Quick reload to render new PHP list perfectly
            } else {
                showToast(res.message, "danger");
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } catch (e) {
            showToast("Network Error saving milestone.", "danger");
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            if (btn) btn.disabled = false;
        }
    });
}

// --- TIMELINE DELETE VIA AJAX ---
window.deleteMilestone = async function (id) {
    if (!confirm("Are you sure you want to delete this historical milestone?")) return;

    try {
        const req = await fetch('action_manage_milestones.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'delete', id: id })
        });
        const res = await req.json();
        if (res.success) {
            const milestoneEl = document.getElementById('ms-' + id);
            if (milestoneEl) milestoneEl.remove();
            showToast("Milestone record erased.", "success");
        } else {
            showToast(res.message, "danger");
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    } catch (e) {
        showToast("Network Error deleting milestone.", "danger");
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Track unsaved changes logic on main form
let editorGuard;
if (typeof FormGuard !== 'undefined' && document.getElementById('biodataForm')) {
    editorGuard = new FormGuard('biodataForm', 'saveDraftBtn');
}
