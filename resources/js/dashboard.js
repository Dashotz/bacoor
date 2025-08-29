// Global dashboard app object
window.dashboardApp = {
    extendSession: null,
    resetSessionTimer: null
};

document.addEventListener('DOMContentLoaded', () => {
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    // Session timeout functionality
    let sessionTimeout;
    let warningTimeout;
    const SESSION_TIMEOUT_MINUTES = 3;
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
        
        // Send heartbeat to server
        sendHeartbeat();
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
        fetch('/session/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).finally(() => {
            // Redirect to login page
            window.location.href = '/login';
        });
    }

    function sendHeartbeat() {
        console.log('Sending heartbeat to server...');
        fetch('/session/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            console.log('Heartbeat response:', response.status);
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

    // Reset timer on user activity
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    activityEvents.forEach(event => {
        document.addEventListener(event, resetSessionTimer, true);
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

    // Send heartbeat to server every 30 seconds to keep session alive
    setInterval(() => {
        sendHeartbeat();
    }, 30000);
});


