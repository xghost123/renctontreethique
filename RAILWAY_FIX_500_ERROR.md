# Railway 500 Error - Quick Fix

## Your Domain
```
https://web-production-aa6669.up.railway.app
```

## Most Common Cause
**APP_URL is wrong!** It was set to a placeholder during setup.

## Fix in 3 Steps

### 1. Update APP_URL Variable
- Go to: https://railway.app
- Click your project
- Settings → Variables
- Find: `APP_URL`
- Change FROM: `https://rencontre-ethique-production.up.railway.app`
- Change TO: `https://web-production-aa6669.up.railway.app` (no trailing slash!)

### 2. Verify All 25 Variables Are Set
Check none are empty:
```
APP_NAME=RencontreEthique
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:L4VGFSUG+axRhDrJqW252jeOv/sOsoEomoYigcRPSW0=
APP_URL=https://web-production-aa6669.up.railway.app
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
CACHE_DRIVER=file
CACHE_TTL=3600
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rencontre-ethique.app
MAIL_FROM_NAME=Rencontre Éthique
FILESYSTEM_DISK=public
FILESYSTEM_VISIBILITY=public
LOG_CHANNEL=single
LOG_LEVEL=debug
BROADCAST_DRIVER=log
BCRYPT_ROUNDS=10
HASH_DRIVER=bcrypt
```

### 3. Redeploy
- Go to: Deployments
- Click: "Trigger Deploy"
- Wait: 2-3 minutes
- Check: Logs tab (should show ✓ success)
- Test: Visit your URL

## If Still Getting 500 Error

Go to **Logs** tab and look for the actual error message. Common ones:

| Error | Fix |
|-------|-----|
| "No application encryption key" | Check APP_KEY is copied exactly |
| "could not find driver" | Check DB_CONNECTION and DB_DATABASE |
| "permission denied" | New Procfile should fix this - redeploy |
| "SQLSTATE[HY000]" | Database issue - check DB_DATABASE path |

## Test the Fix

After redeploy, try these URLs:
- Homepage: https://web-production-aa6669.up.railway.app/
- Login: https://web-production-aa6669.up.railway.app/login
- Register: https://web-production-aa6669.up.railway.app/register

## Still Stuck?

1. Copy the exact error from Logs
2. Make sure you used YOUR actual domain (web-production-aa6669...)
3. Check no variables are empty
4. Verify APP_KEY has no typos
5. Redeploy after changing variables

You got this! 🚀
