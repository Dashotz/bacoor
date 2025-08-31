// Global dashboard app object
window.dashboardApp = {
    extendSession: null,
    resetSessionTimer: null,
    userData: null,
    refreshUserData: fetchUserData
};

document.addEventListener('DOMContentLoaded', () => {
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    // Check if user is authenticated with JWT
    if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
        console.log('User not authenticated, redirecting to login');
        window.location.href = '/';
        return;
    }

    // Secure user data fetching
    fetchUserData();

    // Session timeout functionality
    let sessionTimeout;
    let warningTimeout;
    const SESSION_TIMEOUT_MINUTES = 3; // 3 minutes
    const WARNING_BEFORE_TIMEOUT = 1; // Show warning 1 minute before timeout
    let timeRemaining = SESSION_TIMEOUT_MINUTES * 60; // seconds
    let isWarningShown = false;

    function updateSessionTimer() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timerEl = document.getElementById('session-timer');
        if (timerEl) {
            timerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        // Show warning when 1 minute remaining
        if (timeRemaining <= WARNING_BEFORE_TIMEOUT * 60 && !isWarningShown) {
            showSessionWarning();
            isWarningShown = true;
        }

        if (timeRemaining <= 0) {
            // Session expired, force logout
            forceLogout();
            return;
        }

        timeRemaining--;
        sessionTimeout = setTimeout(updateSessionTimer, 1000);
    }

    function resetSessionTimer() {
        timeRemaining = SESSION_TIMEOUT_MINUTES * 60;
        isWarningShown = false;
        
        if (sessionTimeout) {
            clearTimeout(sessionTimeout);
        }
        if (warningTimeout) {
            clearTimeout(warningTimeout);
        }
        
        // Hide warning if it was shown
        hideSessionWarning();
        
        updateSessionTimer();
    }

    function showSessionWarning() {
        // Create warning modal if it doesn't exist
        let warningModal = document.getElementById('session-warning-modal');
        if (!warningModal) {
            warningModal = document.createElement('div');
            warningModal.id = 'session-warning-modal';
            warningModal.innerHTML = `
                <div class="session-warning-overlay">
                    <div class="session-warning-content">
                        <h3>Session Timeout Warning</h3>
                        <p>Your session will expire in <span id="warning-timer">1:00</span> minute due to inactivity.</p>
                        <p>Click anywhere or press any key to extend your session.</p>
                        <button onclick="extendSession()" class="btn btn-primary">Extend Session</button>
                    </div>
                </div>
            `;
            document.body.appendChild(warningModal);
        }
        
        warningModal.style.display = 'block';
        
        // Start warning countdown
        let warningTime = WARNING_BEFORE_TIMEOUT * 60;
        const warningTimerEl = document.getElementById('warning-timer');
        
        const warningCountdown = setInterval(() => {
            const minutes = Math.floor(warningTime / 60);
            const seconds = warningTime % 60;
            if (warningTimerEl) {
                warningTimerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            if (warningTime <= 0) {
                clearInterval(warningCountdown);
                forceLogout();
            }
            warningTime--;
        }, 1000);
        
        warningTimeout = setTimeout(() => {
            clearInterval(warningCountdown);
        }, WARNING_BEFORE_TIMEOUT * 60000);
    }

    function hideSessionWarning() {
        const warningModal = document.getElementById('session-warning-modal');
        if (warningModal) {
            warningModal.style.display = 'none';
        }
    }

    function extendSession() {
        resetSessionTimer();
        hideSessionWarning();
    }
    
    // Expose functions globally
    window.dashboardApp.extendSession = extendSession;
    window.dashboardApp.resetSessionTimer = resetSessionTimer;

    function forceLogout() {
        // Use JWT logout instead of CSRF-based logout
        if (window.jwtAuth) {
            window.jwtAuth.handleLogout();
        } else {
            // Fallback: redirect to login
            window.location.href = '/';
        }
    }

    // Reset timer on user activity
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    activityEvents.forEach(event => {
        document.addEventListener(event, (e) => {
            // Don't reset timer for logout button clicks
            if (e.target && e.target.id === 'logout-btn') {
                return;
            }
            // Reset timer on user activity
            timeRemaining = SESSION_TIMEOUT_MINUTES * 60;
            isWarningShown = false;
            
            if (sessionTimeout) {
                clearTimeout(sessionTimeout);
            }
            if (warningTimeout) {
                clearTimeout(warningTimeout);
            }
            
            hideSessionWarning();
            updateSessionTimer();
        }, true);
    });
    
    // Detect visibility changes (alt-tabbing, switching windows, etc.)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            console.log('Page became hidden (alt-tab, window switch, etc.)');
            // Don't reset timer when page becomes hidden - let it continue counting
        } else {
            console.log('Page became visible again');
            // Don't reset timer when returning - only reset on actual interaction
        }
    });
    
    // Detect window focus/blur (additional alt-tab detection)
    window.addEventListener('blur', () => {
        console.log('Window lost focus');
        // Don't reset timer when window loses focus
    });
    
    window.addEventListener('focus', () => {
        console.log('Window gained focus');
        // Don't reset timer when window gains focus - only reset on actual interaction
    });

    // Start the session timer
    updateSessionTimer();
    
    console.log('Session timer started with', SESSION_TIMEOUT_MINUTES, 'minutes timeout (continues counting when away, resets only on interaction)');
});

// Secure user data fetching function using JWT
async function fetchUserData() {
    try {
        // Check if JWT auth is available
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
            console.error('JWT authentication not available or user not authenticated');
            window.location.href = '/';
            return;
        }

        // Use JWT auth to get user profile
        const userData = await window.jwtAuth.getUserProfile();
        
        if (userData) {
            // Update user display name in header
            const userNameElement = document.getElementById('user-name');
            if (userNameElement) {
                userNameElement.textContent = userData.display_name;
            }

            // Update welcome message
            const welcomeNameElement = document.getElementById('welcome-name');
            if (welcomeNameElement) {
                welcomeNameElement.textContent = userData.display_name;
            }

            // Store minimal user data for app functionality (no sensitive info)
            window.dashboardApp.userData = {
                displayName: userData.display_name,
                firstName: userData.first_name,
                memberSince: userData.member_since
            };

            console.log('User data loaded securely via JWT');
        } else {
            throw new Error('Failed to fetch user profile');
        }
    } catch (error) {
        console.error('Error fetching user data:', error);
        
        // If JWT token is invalid, redirect to login
        if (window.jwtAuth) {
            window.jwtAuth.clearAuth();
        }
        window.location.href = '/';
    }
}
