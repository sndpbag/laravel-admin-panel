# Important: Add Security Password to .env

You need to add the following line to your `.env` file:

```env
ROLES_SECURITY_PASSWORD=your_secure_password_here
```

## Example:
```env
ROLES_SECURITY_PASSWORD=SuperSecret@123
```

Replace `your_secure_password_here` with your actual security password.

## Note:
- This password will be required to access the roles management pages
- Make sure to use a strong, secure password
- Don't share this password publicly
- After adding to `.env`, restart your Laravel server if it's running
