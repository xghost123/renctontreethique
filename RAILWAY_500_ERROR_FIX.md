# Railway 500 Error - Debug & Fix Guide

## Immediate Steps

### Step 1: Check Railway Logs
1. Go to: https://railway.app/project/your-project
2. Click "Logs" tab
3. Look for red error messages
4. Copy the exact error and check below

### Step 2: Most Common Fix - APP_URL

Your Railway domain is: `https://web-production-aa6669.up.railway.app/`

**Update this variable in Railway:**
```
APP_URL=https://web-production-aa6669.up.railway.app
```

(Don't include the trailing `/`)

### Step 3: Verify All Variables Are Set

Go to Project Settings → Variables and check ALL are present:

```
✓ APP_NAME=RencontreEthique
✓ APP_ENV=production
✓ APP_DEBUG=false
✓ APP_KEY=base64:L4VGFSUG+axRhDrJqW252jeOv/sOsoEomoYigcRPSW0=
✓ APP_URL=https://web-production-aa6669.up.railway.app
✓ DB_CONNECTION=sqlite
✓ DB_DATABASE=database/database.sqlite
✓ CACHE_DRIVER=file
✓ CACHE_TTL=3600
✓ SESSION_DRIVER=file
✓ SESSION_LIFETIME=120
✓ QUEUE_CONNECTION=sync
✓ MAIL_MAILER=log
✓ MAIL_HOST=smtp.mailtrap.io
✓ MAIL_PORT=465
✓ MAIL_ENCRYPTION=tls
✓ MAIL_FROM_ADDRESS=noreply@rencontre-ethique.app
✓ MAIL_FROM_NAME=Rencontre Éthique
✓ FILESYSTEM_DISK=public
✓ FILESYSTEM_VISIBILITY=public
✓ LOG_CHANNEL=single
✓ LOG_LEVEL=debug
✓ BROADCAST_DRIVER=log
✓ BCRYPT_ROUNDS=10
✓ HASH_DRIVER=bcrypt
```

### Step 4: After Updating Variables

1. Go to "Deploy" tab
2. Trigger a redeploy
3. Wait 2-3 minutes
4. Test the URL again

## Common 500 Errors

### Error: "No application encryption key has been specified"
**Cause:** APP_KEY not set or wrong format
**Fix:** Copy exact value:
```
APP_KEY=base64:L4VGFSUG+axRhDrJqW252jeOv/sOsoEomoYigcRPSW0=
```

### Error: "could not find driver"
**Cause:** SQLite not configured properly
**Fix:** Ensure these match exactly:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Error: "file_put_contents(): failed to open stream"
**Cause:** Storage directory not writable
**Fix:** Wait - Railway sets this up on first deploy

### Error: "SQLSTATE[HY000]"
**Cause:** Database file missing or permissions
**Fix:** This should not happen - SQLite is in repo

## What to Check in Logs

Look for any of these patterns:

```
[ErrorException] - Missing variables
[RuntimeException] - Database issues
[PDOException] - SQLite problems
[FileException] - Storage/file issues
[InvalidArgumentException] - Config problems
```

## If Still Getting 500

Try these commands in Railway SSH:

```bash
php artisan cache:clear
php artisan config:clear  
php artisan view:clear
php artisan storage:link
php artisan migrate:fresh --force
```

## Test Page

After fixing, try:
- Homepage: https://your-url/
- Login: https://your-url/login
- Register: https://your-url/register

## Contact Railway Support

If still not working:
1. Save your error log (copy from Logs tab)
2. Go to Railway support
3. Share: error message + all env variables (except APP_KEY)
4. They'll help diagnose

## Next Steps

1. ✅ Update APP_URL to your actual Railway domain
2. ✅ Check all variables are set (no empty fields)
3. ✅ Trigger redeploy
4. ✅ Wait 2-3 minutes
5. ✅ Test again

Your platform should work now! 🚀
