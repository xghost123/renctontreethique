# 🗄️ DATABASE TROUBLESHOOTING GUIDE - SQLite

**Issue:** Registration returns HTTP 500 on every valid submission  
**Root Cause:** SQLite database file is not persisting on Railway OR not writable  
**Database:** SQLite (not PostgreSQL/MySQL)  
**Evidence:** Validation errors never appear, proving DB is never reached

---

## 🔍 DIAGNOSIS

### Evidence of DB Unreachability:

```
1. Valid payload → 500 (should be 200 success)
2. Duplicate email → 500 (should be 422 "already taken")
3. POST /login → 500 (auth also broken)
4. GET / & /terms → 200 (static pages work fine)
```

**Conclusion:** The app cannot read/write to SQLite database on Railway.

---

## ✅ FIX CHECKLIST FOR SQLite

### Step 1: Verify SQLite Configuration

**In .env:**
```bash
DB_CONNECTION=sqlite          ✅ Correct
DB_DATABASE=database/database.sqlite  ✅ Correct
# Other DB_* vars commented out    ✅ Correct
```

### Step 2: Check Railway SQLite Storage

**The Problem with SQLite on Railway:**
- Railway's file system is ephemeral (resets on redeploy)
- SQLite database file is lost unless stored in persistent volume
- Need to mount a persistent volume for `database/` directory

**In Railway Dashboard:**

```
1. Go to your App service
2. Click "Settings" → "Storage"
3. Check if there's a mount for /app/database (or similar)

If NOT mounted:
❌ Database file is lost on redeploy
❌ Each deployment starts fresh
❌ Registration data disappears

If mounted:
✅ Database persists across redeploys
✅ Data is saved
```

### Step 3: Create SQLite Storage Mount on Railway

**Option A: Via Railway Dashboard**
```
1. App Service → Settings → Storage
2. Click "New Mount"
3. Mount Path: /app/database
4. Volume: Create new volume (or select existing)
5. Save
```

**Option B: Via railway.toml (recommended)**

Create/edit `railway.toml` at project root:

```toml
[build]
builder = "dockerfile"

[deploy]
startCommand = "php artisan serve --host 0.0.0.0"

[mounts]
  [mounts.database]
  path = "/app/database"
  volume = "sqlite-data"

[variables]
DB_CONNECTION = "sqlite"
DB_DATABASE = "/app/database/database.sqlite"
APP_URL = "https://web-production-aa6669.up.railway.app"
ASSET_URL = "https://web-production-aa6669.up.railway.app"
```

### Step 4: Push and Redeploy

```bash
git add railway.toml
git commit -m "Add SQLite persistent storage mount"
git push origin master

# Railway auto-deploys
# Database volume is created and mounted
```

### Step 5: Run Migrations After Deploy

**On Railway pod:**
```bash
railway run php artisan migrate
# Creates tables in SQLite
```

### Step 6: Seed Admin Users

```bash
railway run php artisan db:seed --class=AdminSeeder
# Creates test accounts
```

### Step 7: Test SQLite Connection

```bash
railway run php artisan tinker

# Inside tinker:
>>> DB::connection()->getPdo()
# Should return PDO object

>>> DB::table('users')->count()
# Should return number (not error)

>>> exit
```

---

## 🚨 COMMON SQLite ISSUES ON RAILWAY

### Issue A: "Unable to open database file"

```
Error: SQLSTATE[HY000]: General error: unable to open database file
```

**Causes:**
1. `/app/database` directory doesn't exist
2. No write permissions
3. Volume not mounted

**Fix:**
```bash
# On Railway pod, check if directory exists:
railway run ls -la /app/database

# If not exists, create it:
railway run mkdir -p /app/database

# Set permissions:
railway run chmod 777 /app/database
```

### Issue B: "Database file disappears after redeploy"

```
First request works → Redeploy → 500 error
(Database file was lost)
```

**Fix:**
- Mount persistent volume (see Step 3)
- Ensure `railway.toml` has `[mounts.database]` section

### Issue C: "Database locked"

```
Error: database is locked
```

**Cause:** Multiple processes trying to write simultaneously

**Fix:**
```bash
# This is rare in production but check:
1. Only one app instance is running
2. No background jobs running
3. SQLite WAL (write-ahead log) files exist in /app/database/

# Force unlock (dangerous):
railway run rm /app/database/database.sqlite-wal
railway run rm /app/database/database.sqlite-shm
```

### Issue D: "Migrations haven't run"

```
Error: Table 'users' doesn't exist
```

**Fix:**
```bash
# After deploying, migrations must run:
railway run php artisan migrate

# Check status:
railway run php artisan migrate:status

# Should show all migrations as "Ran"
```

---

## 🔧 QUICK FIX WORKFLOW FOR SQLite

```bash
# 1. Add railway.toml with SQLite mount
cat > railway.toml << 'EOF'
[build]
builder = "dockerfile"

[deploy]
startCommand = "php artisan serve --host 0.0.0.0"

[mounts]
  [mounts.database]
  path = "/app/database"
  volume = "sqlite-data"

[variables]
DB_CONNECTION = "sqlite"
DB_DATABASE = "/app/database/database.sqlite"
APP_URL = "https://web-production-aa6669.up.railway.app"
ASSET_URL = "https://web-production-aa6669.up.railway.app"
EOF

# 2. Commit and push
git add railway.toml
git commit -m "Add SQLite persistent storage"
git push origin master

# 3. After redeploy completes, run migrations
railway run php artisan migrate

# 4. Seed admin users
railway run php artisan db:seed --class=AdminSeeder

# 5. Test registration
curl -X POST https://web-production-aa6669.up.railway.app/register \
  -H "Content-Type: application/json" \
  -d '{
    "gender":"male",
    "name":"Test User",
    "email":"test@example.com",
    "mobile":"0612345678",
    "password":"Test1234",
    "password_confirmation":"Test1234",
    "agree_terms":true,
    "agree_privacy":true
  }'

# Should return 302 (success) not 500
```

---

## 📋 SQLite vs PostgreSQL/MySQL

| Aspect | SQLite | PostgreSQL/MySQL |
|--------|--------|------------------|
| Setup | ✅ Simple (file) | ❌ Complex (server) |
| Storage | 📁 Local file | 🖥️ Remote server |
| Scaling | ❌ Limited | ✅ Scalable |
| Concurrency | ⚠️ Limited | ✅ Good |
| Railway | ⚠️ Needs mount | ✅ Service included |
| Cost | ✅ Free | ⚠️ Paid |

**For Rencontre Éthique:** SQLite is fine for current scale, just need persistent storage mount.

---

## ✅ AFTER FIXING SQLite

Once persistent volume is mounted and migrations run:

```bash
✅ Registration works
✅ Admin login works
✅ All user features work
✅ Data persists across redeploys
✅ No database service charges
```

---

## 📞 IF YOU'RE STILL STUCK

**Check:**
1. Is `/app/database` mounted in Railway?
2. Have migrations been run? (`railway run php artisan migrate:status`)
3. Does `database.sqlite` file exist? (`railway run ls -la /app/database/`)
4. Can the app write to the directory? (`railway run touch /app/database/test.txt`)

**Contact Railway support with:**
- Project ID
- Storage mounts configuration
- Console logs from failed migration
- Output of `railway run ls -la /app/database/`