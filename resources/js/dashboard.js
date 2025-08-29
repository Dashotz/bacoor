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
        
        // Disabled heartbeat to prevent CSRF token issues
        // sendHeartbeat();
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
        // Send logout request to server
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found, redirecting to login');
            window.location.href = '/login';
            return;
        }
        
        fetch('/session/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).finally(() => {
            // Redirect to login page
            window.location.href = '/login';
        });
    }

    // Heartbeat function disabled to prevent CSRF token issues
    /*
    function sendHeartbeat() {
        console.log('Sending heartbeat to server...');
        
        // Get fresh CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found, stopping heartbeat');
            return;
        }
        
        fetch('/session/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            console.log('Heartbeat response:', response.status);
            if (response.status === 419) {
                // CSRF token mismatch, try to refresh the page to get a new token
                console.log('CSRF token mismatch, refreshing page...');
                window.location.reload();
                return;
            }
            if (!response.ok) {
                // Session expired on server, redirect to login
                console.log('Session expired on server, redirecting to login');
                window.location.href = '/login';
            }
        }).catch((error) => {
            console.error('Heartbeat error:', error);
            // Ignore errors, just keep session alive
        });
    }
    */

    // Reset timer on user activity (simplified to prevent heartbeat issues)
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    activityEvents.forEach(event => {
        document.addEventListener(event, (e) => {
            // Don't reset timer for logout button clicks
            if (e.target && e.target.id === 'logout-btn') {
                return;
            }
            // Simplified timer reset without heartbeat
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

    // Temporarily disabled heartbeat to prevent CSRF token issues
    // Send heartbeat to server every 5 minutes to keep session alive (much reduced frequency)
    /*
    setInterval(() => {
        sendHeartbeat();
    }, 300000); // 5 minutes
    */
});

// Secure user data fetching function
async function fetchUserData() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }

        const response = await fetch('/api/user/profile', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            if (response.status === 401) {
                // Unauthorized, redirect to login
                window.location.href = '/login';
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success && data.data) {
            // Update user display name in header
            const userNameElement = document.getElementById('user-name');
            if (userNameElement) {
                userNameElement.textContent = data.data.display_name;
            }

            // Update welcome message
            const welcomeNameElement = document.getElementById('welcome-name');
            if (welcomeNameElement) {
                welcomeNameElement.textContent = data.data.display_name;
            }

            // Store minimal user data for app functionality (no sensitive info)
            window.dashboardApp.userData = {
                displayName: data.data.display_name,
                firstName: data.data.first_name,
                memberSince: data.data.member_since
            };

            console.log('User data loaded securely');
        }
    } catch (error) {
        console.error('Error fetching user data:', error);
        
        // Fallback: show generic welcome message
        const userNameElement = document.getElementById('user-name');
        if (userNameElement) {
            userNameElement.textContent = 'Citizen';
        }
        
        const welcomeNameElement = document.getElementById('welcome-name');
        if (welcomeNameElement) {
            welcomeNameElement.textContent = 'Citizen';
        }
    }
}
