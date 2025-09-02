# reCAPTCHA Setup Guide

## 1. Get reCAPTCHA Keys

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Click "Create" to create a new site
3. Choose "reCAPTCHA v2" and "I'm not a robot" Checkbox
4. Add your domain (e.g., `localhost` for development, your production domain)
5. Accept the terms and submit
6. Copy the **Site Key** and **Secret Key**

## 2. Environment Variables

Add these to your `.env` file:

```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

## 3. Test Keys (Development Only)

For development/testing, you can use these test keys:
- **Site Key**: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
- **Secret Key**: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

These test keys will always pass validation and show a warning message.

## 4. How It Works

### Frontend (JavaScript)
- The reCAPTCHA widget is loaded on the login page
- When the form is submitted, JavaScript gets the reCAPTCHA response
- The response is included in the API request to `/api/login`

### Backend (PHP)
- The `AuthController` validates the reCAPTCHA response
- It sends a request to Google's verification API
- Only if reCAPTCHA is valid, the login process continues

## 5. Error Handling

- If reCAPTCHA is not completed, user gets an error message
- If reCAPTCHA verification fails, user gets an error and reCAPTCHA resets
- On successful login, user is redirected to OTP verification

## 6. Production Setup

For production:
1. Create a new reCAPTCHA site with your actual domain
2. Update the environment variables with your production keys
3. Remove the test keys from `config/services.php` (they're already using env variables)

## 7. Troubleshooting

- **reCAPTCHA not showing**: Check if the site key is correct and domain is registered
- **Verification always fails**: Check if the secret key is correct
- **JavaScript errors**: Make sure the reCAPTCHA script is loaded before the form submission
