/**
 * Global Form Guards
 * Protects forms against offline state and accidental unsaved navigation.
 */
class FormGuard {
    constructor(formId, submitBtnId, options = {}) {
        this.form = document.getElementById(formId);
        this.submitBtn = document.getElementById(submitBtnId);
        this.options = Object.assign({
            trackDirty: true
        }, options);
        this.isDirty = false;
        
        if (!this.form || !this.submitBtn) return;

        this.init();
    }

    init() {
        if (this.options.trackDirty) {
            // 1. Dirty State Tracker (Detect any inputs changing)
            const inputs = this.form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const markDirty = () => { this.isDirty = true; };
                input.addEventListener('input', markDirty);
                input.addEventListener('change', markDirty);
            });

            // 2. BeforeUnload Warning
            window.addEventListener('beforeunload', (e) => {
                if (this.isDirty) {
                    const msg = "Changes you made may not be saved.";
                    e.preventDefault();
                    e.returnValue = msg;
                    return msg;
                }
            });

            // 3. Clear Dirty State on successful explicit submit intention
            this.form.addEventListener('submit', () => {
                this.isDirty = false;
            });
        }

        // 4. Online/Offline Handling
        window.addEventListener('offline', () => this.handleNetworkChange(false));
        window.addEventListener('online', () => this.handleNetworkChange(true));
        
        // Initial check
        if (!navigator.onLine) {
            this.handleNetworkChange(false);
        }
    }

    handleNetworkChange(isOnline) {
        if (!isOnline) {
            this.submitBtn.disabled = true;
            
            // Create or show an offline banner
            if (!document.getElementById('offlineBanner')) {
                const banner = document.createElement('div');
                banner.id = 'offlineBanner';
                banner.style.cssText = 'background: #ef4444; color: white; text-align: center; padding: 0.5rem; position: fixed; top: 0; left: 0; width: 100%; z-index: 9999; font-weight: 500; font-family: Inter, sans-serif;';
                banner.textContent = 'You are currently offline. Actions are disabled until connection restores.';
                document.body.appendChild(banner);
            }
        } else {
            this.submitBtn.disabled = false;
            const banner = document.getElementById('offlineBanner');
            if (banner) {
                banner.remove();
            }
        }
    }

    setDirty(state) {
        if (this.options.trackDirty) {
            this.isDirty = !!state;
        }
    }
}
