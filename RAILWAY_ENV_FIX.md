# 🔧 RAILWAY .ENV FIX - Critical Issues Found

**Your Current .env on Railway:**  
26 variables set, but 2 critical issues found!

---

## 🚨 CRITICAL ISSUES

### Issue #1: HASH_DRIVER Typo (LINE 15)
**Current:** `HASH_DRIVER="bcryp"`  
**Should be:** `HASH_DRIVER="bcrypt"`  
**Impact:** Password hashing will FAIL → registration broken → HTTP 500

### Issue #2: DB_DATABASE Path (LINE 12)
**Current:** `DB_DATABASE="database/database.sqlite"`  
**Should be:** `DB_DATABASE="/app/database/database.sqlite"`  
**Impact:** Laravel might not find database file → DB connection fails

---

## ✅ CORRECTED .env for Railway

Copy this ENTIRE text and paste into Railway Variables:

```
APP_DEBUG=false
APP_ENV=production
APP_KEY=base64:L4VGFSUG+axRhDrJqW252jeOv/sOsoEomoYigcRPSW0=
APP_NAME=RencontreEthique
APP_URL=https://web-production-aa6669.up.railway.app
ASSET_URL=https://web-production-aa6669.up.railway.app
BCRYPT_ROUNDS=10
BROADCAST_DRIVER=log
CACHE_DRIVER=file
CACHE_TTL=3600
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite
FILESYSTEM_DISK=public
FILESYSTEM_VISIBILITY=public
HASH_DRIVER=bcrypt
LOG_CHANNEL=single
LOG_LEVEL=warning
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rencontre-ethique.app
MAIL_FROM_NAME=Rencontre Éthique
MAIL_HOST=smtp.mailtrap.io
MAIL_MAILER=log
MAIL_PORT=465
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## 🚀 How to Update Railway .env

### Step 1: Go to Railway Dashboard
- Click your app service
- Go to "Variables" tab

### Step 2: Update Each Variable

**Edit these ONLY:**

1. **HASH_DRIVER**
   - Old: `bcryp`
   - New: `bcrypt`
   - Click edit, fix it

2. **DB_DATABASE**
   - Old: `database/database.sqlite`
   - New: `/app/database/database.sqlite`
   - Click edit, fix it

3. **LOG_LEVEL** (optional but recommended for production)
   - Old: `debug`
   - New: `warning`
   - Click edit, change it

### Step 3: Save & Redeploy
- After saving each variable, Railway auto-saves
- Go to "Deploy" and click "Redeploy"
- Wait for build (8-12 minutes)

---

## 📋 What Each Variable Does

| Variable | Value | Purpose |
|----------|-------|---------|
| `APP_DEBUG` | false | Hide errors (production) |
| `APP_ENV` | production | Production mode |
| `APP_KEY` | base64:... | Encryption key (set ✓) |
| `APP_NAME` | RencontreEthique | App name |
| `APP_URL` | https://web-production-... | Public URL |
| `DB_CONNECTION` | sqlite | ✓ Correct database type |
| `DB_DATABASE` | /app/database/database.sqlite | ✓ **FIXED** path |
| `HASH_DRIVER` | bcrypt | ✓ **FIXED** password hashing |
| `LOG_LEVEL` | warning | Production logging |
| Others | ... | Various settings |

---

## ⚠️ Why These Errors Cause 500s

### HASH_DRIVER="bcryp" → 500 Error
```
User tries to register
Form submitted with password
Laravel calls Hash::make($password)
Uses HASH_DRIVER="bcryp"
"bcryp" driver doesn't exist
Laravel throws exception
HTTP 500
```

### DB_DATABASE="database/database.sqlite" → 500 Error
```
start.sh runs migrations
Laravel tries to connect to DB
Path "database/database.sqlite" (relative)
Not found in container
Connection fails
HTTP 500
```

---

## ✅ After Fix, What Happens

1. ✓ Redeploy with corrected .env
2. ✓ Dockerfile builds
3. ✓ Container starts
4. ✓ start.sh runs:
   - Clears config/cache
   - Runs migrations (uses correct /app/database/database.sqlite path)
   - Seeds admin user
   - Starts Laravel
5. ✓ **Registration works!**
6. ✓ **Passwords hash correctly (bcrypt)!**
7. ✓ **Platform LIVE!**

---

## 📞 Summary

| Issue | Current | Fix | Impact |
|-------|---------|-----|--------|
| HASH_DRIVER | bcryp | bcrypt | Password hashing |
| DB_DATABASE | database/... | /app/database/... | DB connection |
| LOG_LEVEL | debug | warning | Cleaner logs |

---

**File:** RAILWAY_ENV_CORRECTED.txt (copy the content from above)

**Next:** Update these 2-3 variables in Railway → Redeploy → Platform works! ✅