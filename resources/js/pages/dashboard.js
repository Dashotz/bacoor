// Global dashboard app object
window.dashboardApp = {
    extendSession: null,
    resetSessionTimer: null,
    userData: null,
    refreshUserData: fetchUserData
};

// Global function for extending session
function extendSession() {
    if (window.dashboardApp && window.dashboardApp.extendSession) {
        window.dashboardApp.extendSession();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    // Check if user is authenticated with JWT
    // Wait a bit for JWT auth to initialize
    setTimeout(() => {
        console.log('Dashboard JWT Check:');
        console.log('Session Data:', window.sessionData);
        console.log('JWT Auth available:', !!window.jwtAuth);
        console.log('JWT Auth authenticated:', window.jwtAuth ? window.jwtAuth.isAuthenticated() : 'N/A');
        console.log('JWT Token:', window.jwtAuth ? window.jwtAuth.token : 'N/A');
        console.log('JWT User:', window.jwtAuth ? window.jwtAuth.user : 'N/A');
        
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
            console.log('User not authenticated in dashboard, redirecting to home');
            window.location.href = '/';
            return;
        }
        
        // Secure user data fetching
        fetchUserData();
        
        // Fetch full user data for table
        fetchFullUserData();
        
        // Start the session timer only if user is authenticated
        if (window.jwtAuth && window.jwtAuth.isAuthenticated()) {
            updateSessionTimer();
        }
    }, 1000); // Wait 1 second for JWT auth to initialize

    // Session timeout functionality
    let sessionTimeout;
    let warningTimeout;
    const SESSION_TIMEOUT_MINUTES = 30; // 30 minutes (increased from 3)
    const WARNING_BEFORE_TIMEOUT = 5; // Show warning 5 minutes before timeout
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
        // Don't reset timer when page becomes hidden - let it continue counting
        // Don't reset timer when returning - only reset on actual interaction
    });
    
    // Detect window focus/blur (additional alt-tab detection)
    window.addEventListener('blur', () => {
        // Don't reset timer when window loses focus
    });
    
    window.addEventListener('focus', () => {
        // Don't reset timer when window gains focus - only reset on actual interaction
    });

    // Session timer is now started by the setTimeout above after authentication check
    
    // Initialize assessor services functionality
    initializeAssessorServices();
});

// Secure user data fetching function using JWT
async function fetchUserData() {
    try {
        // Check if JWT auth is available
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
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
        } else {
            throw new Error('Failed to fetch user profile');
        }
    } catch (error) {
        // If JWT token is invalid, redirect to login
        if (window.jwtAuth) {
            window.jwtAuth.clearAuth();
        }
        window.location.href = '/';
    }
}

// Fetch full user data for the table display
async function fetchFullUserData() {
    try {
        // Check if JWT auth is available
        if (!window.jwtAuth || !window.jwtAuth.isAuthenticated()) {
            window.location.href = '/';
            return;
        }

        const authHeader = window.jwtAuth.getAuthHeader();
        if (!authHeader.Authorization) {
            window.location.href = '/';
            return;
        }

        const response = await fetch('/api/v1/user/data', {
            method: 'GET',
            headers: {
                ...authHeader,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            if (response.status === 401) {
                // Token expired or invalid
                window.jwtAuth.clearAuth();
                window.location.href = '/';
                return;
            }
            throw new Error('Failed to fetch user data');
        }

        const data = await response.json();
        
        if (data.success && data.user) {
            displayUserDataTable(data.user);
        } else {
            throw new Error('Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching full user data:', error);
        // Show error message in table
        const tbody = document.getElementById('userDataBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="2" style="text-align: center; padding: 20px; color: #dc2626;">
                        Error loading user data. Please refresh the page.
                    </td>
                </tr>
            `;
        }
    }
}

// Display user data in the table
function displayUserDataTable(user) {
    const tbody = document.getElementById('userDataBody');
    if (!tbody) return;

    // Calculate full name from components
    const fullName = calculateFullName(user.first_name, user.middle_name, user.surname, user.suffix);
    
    // Format the data for display
    const userData = [
        { label: 'Full Name', value: fullName },
        { label: 'First Name', value: user.first_name || 'N/A' },
        { label: 'Middle Name', value: user.middle_name || 'N/A' },
        { label: 'Last Name', value: user.surname || 'N/A' },
        { label: 'Suffix', value: user.suffix || 'N/A' },
        { label: 'Birth Date', value: user.birth_date ? new Date(user.birth_date).toLocaleDateString() : 'N/A' },
        { label: 'Gender', value: user.gender ? user.gender.charAt(0).toUpperCase() + user.gender.slice(1) : 'N/A' },
        { label: 'Account Type', value: user.account_type ? user.account_type.charAt(0).toUpperCase() + user.account_type.slice(1) : 'N/A' },
        { label: 'Contact Number', value: user.contact_number || 'N/A' },
        { label: 'Email', value: user.email || 'N/A' },
        { label: 'Government ID Type', value: user.government_id_type ? formatGovernmentIdType(user.government_id_type) : 'N/A' },
        { label: 'Government ID Number', value: user.government_id_number || 'N/A' },
        { label: 'Government ID Document', value: user.government_id_file_path ? createImageElement(user.government_id_file_path) : 'No document uploaded' },
        { label: 'Registration Date', value: user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A' }
    ];

    // Clear loading message and populate table
    tbody.innerHTML = '';
    
    userData.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="field-label">${item.label}</td>
            <td class="field-value">${item.value}</td>
        `;
        tbody.appendChild(row);
    });
}

// Format government ID type for display
function formatGovernmentIdType(type) {
    const typeMap = {
        'driver_license': "Driver's License",
        'passport': 'Passport',
        'sss_id': 'SSS ID',
        'philhealth_id': 'PhilHealth ID',
        'postal_id': 'Postal ID',
        'voter_id': "Voter's ID",
        'national_id': 'National ID'
    };
    return typeMap[type] || type;
}

// Create image element for government ID document
function createImageElement(filePath) {
    if (!filePath) return '<span class="no-image">No document uploaded</span>';
    
    const imageUrl = `/storage/${filePath}`;
    return `<img src="${imageUrl}" alt="Government ID Document" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';"><span class="no-image" style="display:none;">Document not found</span>`;
}

// Calculate full name from components
function calculateFullName(firstName, middleName, surname, suffix) {
    const nameParts = [];
    
    if (firstName) nameParts.push(firstName);
    if (middleName) nameParts.push(middleName);
    if (surname) nameParts.push(surname);
    if (suffix) nameParts.push(suffix);
    
    return nameParts.length > 0 ? nameParts.join(' ') : 'N/A';
}

// Initialize Assessor Services functionality
function initializeAssessorServices() {
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach((card, index) => {
        const startBtn = card.querySelector('.start-btn');
        
        if (startBtn) {
            startBtn.addEventListener('click', function(e) {
                e.preventDefault();
                handleServiceSelection(card, index);
            });
            
            // Add hover effects
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });
}

function handleServiceSelection(card, serviceIndex) {
    const serviceTitle = card.querySelector('.service-title').textContent;
    const startBtn = card.querySelector('.start-btn');
    
    // Add loading state
    card.classList.add('loading');
    startBtn.textContent = 'Processing...';
    startBtn.disabled = true;
    
    // Check if this is the Transfer of Ownership service
    if (serviceTitle.includes('Transfer Of Ownership / Updating Of Tax Declaration')) {
        console.log('Transfer of Ownership service clicked');
        
        // Check if JWT is available and user is authenticated
        if (window.jwtAuth && window.jwtAuth.isAuthenticated()) {
            console.log('User authenticated, redirecting to transfer of ownership page');
            // Pass JWT token in URL query parameter
            const token = window.jwtAuth.token;
            window.location.href = `/transfer-of-ownership?token=${encodeURIComponent(token)}`;
            return;
        }
        
        // If JWT is not available yet, wait a bit and try again
        console.log('JWT not ready, waiting...');
        setTimeout(() => {
            if (window.jwtAuth && window.jwtAuth.isAuthenticated()) {
                console.log('User authenticated after wait, redirecting to transfer of ownership page');
                // Pass JWT token in URL query parameter
                const token = window.jwtAuth.token;
                window.location.href = `/transfer-of-ownership?token=${encodeURIComponent(token)}`;
            } else {
                console.log('User not authenticated, redirecting to home');
                window.location.href = '/';
            }
        }, 1500); // Wait 1.5 seconds for JWT to initialize
        return;
    }
    
    
    // Simulate processing time for other services
    setTimeout(() => {
        // Remove loading state
        card.classList.remove('loading');
        startBtn.textContent = 'Start Today';
        startBtn.disabled = false;
        
        // Show success message
        showServiceMessage(serviceTitle);
        
        // Here you would typically redirect to the specific service form
        // For now, we'll just show a message
        
    }, 1500);
}

function showServiceMessage(serviceTitle) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'service-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">✓</div>
            <div class="notification-message">
                <strong>Service Selected:</strong><br>
                ${serviceTitle}
            </div>
            <button class="notification-close" onclick="closeServiceNotification()">&times;</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        hideServiceNotification(notification);
    }, 5000);
}

function hideServiceNotification(notification) {
    if (notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}

function closeServiceNotification() {
    const notification = document.querySelector('.service-notification');
    hideServiceNotification(notification);
}

// Add CSS for service notifications
const serviceStyle = document.createElement('style');
serviceStyle.textContent = `
    .service-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 350px;
        border-left: 4px solid #10b981;
    }
    
    .service-notification.show {
        transform: translateX(0);
    }
    
    .service-notification .notification-content {
        display: flex;
        align-items: flex-start;
        padding: 16px 20px;
        gap: 12px;
    }
    
    .service-notification .notification-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #10b981;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .service-notification .notification-message {
        flex: 1;
        font-size: 14px;
        color: #374151;
        line-height: 1.4;
    }
    
    .service-notification .notification-close {
        background: none;
        border: none;
        font-size: 18px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .service-notification .notification-close:hover {
        color: #6b7280;
    }
    
    /* Responsive notifications */
    @media (max-width: 768px) {
        .service-notification {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
`;
document.head.appendChild(serviceStyle);
