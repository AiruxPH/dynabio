document.addEventListener('DOMContentLoaded', () => {
    // 1. Create the container
    const bgContainer = document.createElement('div');
    bgContainer.id = 'animated-bg';
    document.body.appendChild(bgContainer);

    // 2. Define Biodata / Tech Icons (FontAwesome)
    const icons = [
        'fa-dna',
        'fa-microchip',
        'fa-database',
        'fa-heartbeat',
        'fa-fingerprint',
        'fa-network-wired',
        'fa-server',
        'fa-user-astronaut',
        'fa-code-branch',
        'fa-laptop-medical'
    ];

    // 3. Configuration
    const numIcons = 20; // How many icons to spawn globally

    // 4. Generator function
    for (let i = 0; i < numIcons; i++) {
        const iconElement = document.createElement('i');

        // Pick a random icon
        const randomIcon = icons[Math.floor(Math.random() * icons.length)];
        iconElement.className = `fas ${randomIcon} floating-icon`;

        // Randomize starting properties
        const startX = Math.random() * 100; // 0% to 100%vw
        const startY = Math.random() * 100; // 0% to 100%vh
        const opacity = Math.random() * 0.15 + 0.05; // 0.05 to 0.20 opacity (very subtle)
        const size = Math.random() * 2 + 1; // 1rem to 3rem

        // Randomize animation duration (speed) and delay
        const duration = Math.random() * 20 + 20; // 20s to 40s
        const delay = Math.random() * -40; // Negative delay so they are already moving when page loads

        // Apply styles
        iconElement.style.left = `${startX}vw`;
        iconElement.style.top = `${startY}vh`;
        iconElement.style.opacity = opacity;
        iconElement.style.fontSize = `${size}rem`;
        iconElement.style.animationDuration = `${duration}s`;
        iconElement.style.animationDelay = `${delay}s`;

        bgContainer.appendChild(iconElement);
    }
});
