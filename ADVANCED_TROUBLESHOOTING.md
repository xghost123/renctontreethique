# 🚨 Railway 500 Error - Advanced Troubleshooting

**Build:** ✅ Succeeds (347.6 MB image)  
**App:** ❌ Still returning 500 errors  
**Latest Fix:** Improved start.sh with logging, database permissions, APP_KEY generation

---

## 🔍 Root Cause Analysis

The Dockerfile build succeeds, but app crashes on startup. Most likely causes (in order):

1. **start.sh script exits before Laravel starts**
2. **Laravel crashes due to missing .env variables**
3. **Database connection fails**
4. **Storage directory permissions**

---

## 📋 DEBUGGING STEPS

### Step 1: View Railway Logs

**Run in Railway:**
```bash
railway logs
# Scroll up to see what happened during startup
# Look for error messages in the output
```

**What to look for:**
```
ERROR: ...
Exception: ...
SQLSTATE[HY000]: General error: ...
Fatal error:
Warning:
```

Share the actual error message you see.

### Step 2: Check if Container Started

```bash
railway status
# Should show: Service Status: Running
```

### Step 3: Check .env Variables

```bash
railway run php -r "echo getenv('APP_KEY');"
# Should output: base64:L4VGFSUG+axRhDrJqW252jeOv/sOsoEomoYigcRPSW0=

railway run php -r "echo getenv('DB_CONNECTION');"
# Should output: sqlite
```

### Step 4: Check Database File

```bash
railway run ls -la /app/database/database.sqlite
# Should show file exists with -rw-rw-rw- permissions (666)
```

### Step 5: Check Migrations Table

```bash
railway run php artisan migrate:status
# Should show all migrations as "Ran"
```

### Step 6: Test Database Connection

```bash
railway run php artisan tinker
>>> DB::connection()->getPdo()
# Should return PDO object (not error)
```

---

## 🛠️ QUICK FIX COMMANDS

If logs show specific errors, try these:

### If migrations not ran:
```bash
railway run php artisan migrate --force
```

### If cache is corrupted:
```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan route:clear
railway run php artisan view:clear
```

### If storage permissions wrong:
```bash
railway run chmod -R 777 /app/storage /app/bootstrap/cache /app/database
```

### If database locked:
```bash
railway run rm -f /app/database/database.sqlite
railway run touch /app/database/database.sqlite
railway run php artisan migrate --force
```

### Force restart:
```bash
# Go to Railway Dashboard → App → Redeploy
```

---

## ✅ What the Latest Dockerfile Does

The updated Dockerfile (commit 3558ad1):

1. ✅ Installs all system dependencies
2. ✅ Installs all PHP extensions
3. ✅ Installs Composer
4. ✅ Installs Node.js
5. ✅ Copies app code
6. ✅ Runs `composer install`
7. ✅ Generates APP_KEY if needed
8. ✅ Builds npm assets
9. ✅ Creates storage/database directories
10. ✅ Creates database.sqlite file with proper permissions (666)
11. ✅ Creates start.sh script with:
    - Config/cache clearing
    - Migration running
    - User seeding
    - Logging with timestamps
12. ✅ Uses `exec` for proper signal handling
13. ✅ Starts Laravel server

---

## 📌 Key Information

**Build Image:** 347.6 MB  
**Container Status:** Unknown (need logs)  
**Database:** SQLite at `/app/database/database.sqlite`  
**Logs:** Available in Railway Dashboard → App → Logs  
**Request ID:** zjkaI4ciTbOcyB73xtoGcA

---

## 🚀 NEXT ACTION

**Check Railway logs:**

1. Go to Railway Dashboard
2. Click your app service
3. Go to "Logs" tab
4. Scroll to the bottom (most recent)
5. Copy any error messages
6. Share them so I can fix the actual issue

---

## ⚡ If You Can't Access Logs

Try these workarounds:

### Option A: Check start.sh output
```bash
railway run cat /app/start.sh
# See if the script was created properly
```

### Option B: Run startup manually
```bash
railway run bash -c "php artisan config:clear && php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force"
# Run each step manually to see where it fails
```

### Option C: Test Laravel directly
```bash
railway run php artisan tinker
>>> User::count()
# If this works, DB is fine
```

---

## 💡 Possible Issues & Solutions

| Error | Cause | Fix |
|-------|-------|-----|
| `SQLSTATE[HY000]` | DB not writable | `chmod 777 /app/database` |
| `No such file or dir` | Missing .env | `cp .env.example .env` |
| `Undefined variable: APP_KEY` | Missing APP_KEY | `php artisan key:generate` |
| `Permission denied` | Bad perms | `chmod 777 storage bootstrap/cache database` |
| `Failed to open stream` | Cache issue | `php artisan cache:clear` |
| `Target [X] is not instantiable` | Config cache | `php artisan config:clear` |

---

## 📞 WHAT I NEED FROM YOU

To help fix this, share:

1. **Railway logs output** (the actual error message)
2. **Request ID:** zjkaI4ciTbOcyB73xtoGcA
3. **Have you redeployed** with the latest Dockerfile? (commit 3558ad1)

Once I see the actual error, I can fix it immediately.