// Login page functionality

// Force hide left panel in mobile view
function hideMobileLeftPanel() {
    if (window.innerWidth <= 768) {
        const leftPanel = document.querySelector('.login-left-panel');
        const placeholderContent = document.querySelector('.placeholder-content');
        
        if (leftPanel) {
            leftPanel.style.display = 'none';
            leftPanel.style.visibility = 'hidden';
            leftPanel.style.opacity = '0';
            leftPanel.style.height = '0';
            leftPanel.style.width = '0';
            leftPanel.style.overflow = 'hidden';
        }
        
        if (placeholderContent) {
            placeholderContent.style.display = 'none';
            placeholderContent.style.visibility = 'hidden';
            placeholderContent.style.opacity = '0';
        }
    }
}

// Auto-refresh login page once when first accessed
function refreshLoginPageOnce() {
    if (window.innerWidth <= 768) {
        // Check if this is the first time accessing login page
        if (!localStorage.getItem('loginPageRefreshed')) {
            localStorage.setItem('loginPageRefreshed', 'true');
            window.location.reload();
            return;
        }
    }
}

// Close notification function
function closeNotification() {
    const notification = document.getElementById('success-notification');
    if (notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-in forwards';
        setTimeout(function() {
            notification.remove();
        }, 300);
    }
}

// Password toggle functionality
function setupPasswordToggle() {
    console.log('DOM loaded, setting up password toggle');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    console.log('Found password toggles:', passwordToggles.length);
    
    passwordToggles.forEach((toggle, index) => {
        console.log('Setting up toggle', index);
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Password toggle clicked');
            
            const targetId = this.getAttribute('data-target');
            console.log('Target ID:', targetId);
            
            const passwordInput = document.getElementById(targetId);
            console.log('Password input found:', !!passwordInput);
            
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeSlashIcon = this.querySelector('.eye-slash-icon');
            console.log('Icons found - eye:', !!eyeIcon, 'eye-slash:', !!eyeSlashIcon);
            
            if (passwordInput && eyeIcon && eyeSlashIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.style.display = 'none';
                    eyeSlashIcon.style.display = 'block';
                    console.log('Password shown');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.style.display = 'block';
                    eyeSlashIcon.style.display = 'none';
                    console.log('Password hidden');
                }
            } else {
                console.error('Missing elements for password toggle');
            }
        });
    });
}

// Initialize login page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Refresh once if needed
    refreshLoginPageOnce();
    
    // Hide left panel immediately
    hideMobileLeftPanel();
    
    // Also run on window resize
    window.addEventListener('resize', hideMobileLeftPanel);
    
    // Setup password toggle
    setupPasswordToggle();
    
    const notification = document.getElementById('success-notification');
    if (notification) {
        setTimeout(function() {
            closeNotification();
        }, 6000);
    }
});
