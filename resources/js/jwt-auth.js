// JWT Authentication Handler
class JWTAuth {
    constructor() {
        this.token = localStorage.getItem('jwt_token');
        this.user = JSON.parse(localStorage.getItem('jwt_user') || 'null');
        this.setupEventListeners();
    }

    // Store JWT token and user data
    setAuth(token, user) {
        this.token = token;
        this.user = user;
        localStorage.setItem('jwt_token', token);
        localStorage.setItem('jwt_user', JSON.stringify(user));
    }

    // Clear JWT token and user data
    clearAuth() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('jwt_user');
    }

    // Get authorization header for API requests
    getAuthHeader() {
        return this.token ? { 'Authorization': `Bearer ${this.token}` } : {};
    }

    // Check if user is authenticated
    isAuthenticated() {
        return !!this.token && !!this.user;
    }

    // Handle login form submission
    async handleLogin(formData) {
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                this.setAuth(data.token, data.user);
                // Remove success message popup
                // this.showSuccessMessage('Login successful!');
                
                // Redirect to OTP verification instead of dashboard
                setTimeout(() => {
                    window.location.href = '/otp';
                }, 500);
            } else {
                this.showErrorMessage(data.message || 'Login failed');
            }
        } catch (error) {
            console.error('Login error:', error);
            this.showErrorMessage('An error occurred during login');
        }
    }

    // Handle registration form submission
    async handleRegister(formData) {
        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                this.setAuth(data.token, data.user);
                this.showSuccessMessage('Registration successful!');
                
                // Redirect to OTP verification instead of dashboard
                setTimeout(() => {
                    window.location.href = '/otp';
                }, 1500);
            } else {
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                } else {
                    this.showErrorMessage(data.message || 'Registration failed');
                }
            }
        } catch (error) {
            console.error('Registration error:', error);
            this.showErrorMessage('An error occurred during registration');
        }
    }

    // Handle logout
    async handleLogout() {
        try {
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...this.getAuthHeader()
                }
            });
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            this.clearAuth();
            
            // Clear remembered credentials on logout
            localStorage.removeItem('remembered_email');
            localStorage.removeItem('remembered_password');
            localStorage.setItem('remember_me', 'false');
            
            window.location.href = '/';
        }
    }

    // Refresh JWT token
    async refreshToken() {
        try {
            const response = await fetch('/api/refresh', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...this.getAuthHeader()
                }
            });

            const data = await response.json();

            if (response.ok) {
                this.setAuth(data.token, this.user);
                return true;
            } else {
                this.clearAuth();
                window.location.href = '/';
                return false;
            }
        } catch (error) {
            console.error('Token refresh error:', error);
            this.clearAuth();
            window.location.href = '/';
            return false;
        }
    }

    // Get current user profile
    async getUserProfile() {
        try {
            const response = await fetch('/api/user/profile', {
                headers: {
                    'Accept': 'application/json',
                    ...this.getAuthHeader()
                }
            });

            if (response.status === 401) {
                // Token expired, try to refresh
                const refreshed = await this.refreshToken();
                if (refreshed) {
                    return this.getUserProfile();
                }
                return null;
            }

            const data = await response.json();
            return data.success ? data.data : null;
        } catch (error) {
            console.error('Get profile error:', error);
            return null;
        }
    }

    // Show success message
    showSuccessMessage(message) {
        // You can customize this to show a nice notification
        alert(message);
    }

    // Show error message
    showErrorMessage(message) {
        // You can customize this to show a nice notification
        alert(message);
    }

    // Show validation errors
    showValidationErrors(errors) {
        let errorMessage = 'Please fix the following errors:\n';
        Object.keys(errors).forEach(field => {
            errorMessage += `\n${field}: ${errors[field].join(', ')}`;
        });
        alert(errorMessage);
    }

    // Setup event listeners
    setupEventListeners() {
        // Login form
        const loginForm = document.getElementById('login');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = {
                    email: document.getElementById('login_email').value,
                    password: document.getElementById('login_password').value
                };
                
                // Save credentials if remember me is checked
                const rememberMeCheckbox = document.getElementById('remember_me');
                if (rememberMeCheckbox && rememberMeCheckbox.checked) {
                    localStorage.setItem('remembered_email', formData.email);
                    localStorage.setItem('remembered_password', formData.password);
                    localStorage.setItem('remember_me', 'true');
                } else {
                    // Clear saved credentials if unchecked
                    localStorage.removeItem('remembered_email');
                    localStorage.removeItem('remembered_password');
                    localStorage.setItem('remember_me', 'false');
                }
                
                this.handleLogin(formData);
            });
        }

        // Registration form
        const registerForm = document.getElementById('register');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = {
                    first_name: document.getElementById('reg_first_name').value,
                    middle_name: document.getElementById('reg_middle_name').value,
                    surname: document.getElementById('reg_surname').value,
                    suffix: document.getElementById('reg_suffix').value,
                    email: document.getElementById('reg_email').value,
                    password: document.getElementById('reg_password').value,
                    password_confirmation: document.getElementById('reg_password_confirmation').value
                };
                this.handleRegister(formData);
            });
        }

        // Logout button
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleLogout();
            });
        }
    }
}

// Initialize JWT authentication when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.jwtAuth = new JWTAuth();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = JWTAuth;
}
