# 🚨 Railway Deployment Troubleshooting

**Status:** Build succeeded ✅ but app won't start ❌

**Error:** "Application failed to respond" (HTTP 500)

**Request ID:** YK5L9nJUSb2wZOJMpHNmDw

---

## 🔍 Diagnostics

Build completed successfully:
- ✅ Docker image built (347.6 MB)
- ✅ Image pushed to Railway registry
- ✅ Container started

But app is returning 500 errors.

**Most likely causes:**
1. Database file doesn't exist (migrations not run)
2. Laravel cache/config issues
3. Storage permissions
4. Database connection error

---

## 🛠️ IMMEDIATE FIX

Run these commands in Railway:

### Step 1: Check app logs
```bash
railway logs
# Look for actual error messages
```

### Step 2: Clear Laravel cache
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
```

### Step 3: Create database file (if missing)
```bash
railway run touch /app/database/database.sqlite
```

### Step 4: Run migrations
```bash
railway run php artisan migrate --force
```

### Step 5: Seed admin user
```bash
railway run php artisan db:seed --class=AdminSeeder --force
```

### Step 6: Check permissions
```bash
railway run chmod -R 777 /app/storage /app/bootstrap/cache /app/database
```

### Step 7: Restart container
Go to Railway Dashboard → App → Redeploy

---

## 📋 What the Build Did

✅ Dockerfile executed successfully:
- Installed system packages
- Compiled PHP extensions (pdo, pdo_sqlite, curl, mbstring, etc.)
- Installed Composer
- Installed Node.js
- Ran `composer install`
- Ran `npm install && npm run build`
- Created storage directories
- Set permissions

**But:** Laravel still needs:
- Database file initialized
- Migrations run
- Storage directories writable
- Config cache cleared

---

## 🔧 Alternative: Update Dockerfile to Run Migrations

If you want migrations to run automatically on deploy:

Add these lines to Dockerfile before `CMD`:

```dockerfile
# Run migrations on startup
RUN php artisan migrate --force || true
RUN php artisan db:seed --class=AdminSeeder --force || true
```

Or update the start command to include it:

```dockerfile
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force 2>/dev/null; php artisan serve --host 0.0.0.0 --port 8000"]
```

---

## 📌 QUICK CHECKLIST

After build completes, run:

```bash
# 1. Clear caches
railway run php artisan config:clear
railway run php artisan cache:clear

# 2. Create database
railway run touch /app/database/database.sqlite

# 3. Run migrations
railway run php artisan migrate --force

# 4. Seed users
railway run php artisan db:seed --class=AdminSeeder --force

# 5. Set permissions
railway run chmod -R 777 /app/storage /app/bootstrap/cache /app/database

# 6. Restart
# Go to Railway Dashboard → Redeploy
```

Then test: https://web-production-aa6669.up.railway.app

---

## 🔑 Why This Happens

Docker image is like a "frozen snapshot" - it doesn't have:
- Live database files
- First-time migrations
- Writable storage directories

These need to be set up AFTER the container starts.

**Solution:** Run setup commands once deployed.

---

## 📞 Still Having Issues?

Check these in Railway logs:

**Look for:**
- "SQLSTATE" errors → Database issue
- "No such file" → Missing database.sqlite
- "Permission denied" → Permissions issue
- "Undefined variable" → Config cache issue

If you see specific errors, share them and I'll fix the code.

---

## ✅ Success Indicators

When it works:
- Homepage loads (/)
- Registration page loads (/register)
- Can fill and submit form
- User created in database
- Redirects to profile wizard

When it doesn't:
- Any URL → 500 error
- Check logs for actual error