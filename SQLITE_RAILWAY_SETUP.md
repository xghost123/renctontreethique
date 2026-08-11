# 🚀 SQLite Setup for Railway - Quick Guide

**Status:** Using SQLite (not PostgreSQL/MySQL)  
**Issue:** Database file not persisting on Railway (lost on redeploy)  
**Solution:** Mount persistent volume for `/app/database`

---

## ✅ FIX STEPS

### 1. The railway.toml file is now in the repo ✅

File: `railway.toml`

Contains:
```toml
[mounts]
  [mounts.database]
  path = "/app/database"
  volume = "sqlite-data"

[variables]
DB_CONNECTION = "sqlite"
DB_DATABASE = "/app/database/database.sqlite"
```

### 2. Push to GitHub and redeploy

```bash
git add railway.toml
git commit -m "Add SQLite persistent storage mount for Railway"
git push origin master
```

Railway will automatically:
- Create a persistent volume named "sqlite-data"
- Mount it at `/app/database` in the app container
- Keep database file across redeploys

### 3. After redeploy, run migrations

```bash
railway run php artisan migrate
```

This creates all tables in SQLite.

### 4. Seed admin users

```bash
railway run php artisan db:seed --class=AdminSeeder
```

Creates test accounts for admin/moderator/member.

### 5. Test registration

Visit: https://web-production-aa6669.up.railway.app/register

Fill and submit:
- Gender: Male/Female
- Name: Any name
- Email: Any email
- Mobile: 0612345678 (French format)
- Password: Any 8+ chars
- Accept terms & privacy

Should succeed → Redirect to profile wizard ✅

---

## 🔍 Verify it's working

```bash
# Check if database file exists
railway run ls -la /app/database/

# Should show:
# -rw-r--r-- database.sqlite (200KB+)
```

```bash
# Count users in database
railway run php artisan tinker
>>> DB::table('users')->count()
# Should return a number (not error)
```

---

## ⚠️ Common Issues

**Issue: "Unable to open database file"**
```
Error: SQLSTATE[HY000]: General error: unable to open database file
```

**Fix:**
```bash
railway run mkdir -p /app/database
railway run chmod 777 /app/database
railway run php artisan migrate
```

**Issue: Database disappears after redeploy**

This means the volume mount isn't working. Check:
1. Does `railway.toml` have the `[mounts.database]` section?
2. Did you push and redeploy?
3. Check Railway dashboard: App → Settings → Storage

---

## 📊 What happens now

| Stage | Command | What it does |
|-------|---------|--------------|
| **Deploy** | `git push origin master` | Railway reads railway.toml, creates volume mount |
| **Migrate** | `railway run php artisan migrate` | Creates tables in SQLite |
| **Seed** | `railway run php artisan db:seed --class=AdminSeeder` | Creates test users |
| **Test** | Visit /register | Users can register |
| **Redeploy** | Any code push | Database file persists (not lost!) |

---

## ✅ Final Check

Once migrations are run, test with:

```bash
railway run php artisan migrate:status
# Shows all migrations as "Ran"

railway run php artisan tinker
>>> DB::table('users')->count()
# Returns number (0 if no users, > 0 if seeded)

>>> DB::table('biodata')->count()
# Returns number

>>> exit
```

If all show numbers without errors → Database is working! ✅

---

## 🎉 After Fix

Users can:
- ✅ Register (gender, email, mobile, password sent)
- ✅ Login (with admin credentials)
- ✅ Complete profile wizard
- ✅ Data persists across redeploys
- ✅ No database service charges (SQLite is free!)

---

**Next: Push railway.toml, redeploy, run migrations, and registration will work!**