# Railway Deployment Guide - Rencontre Éthique

## Quick Start (5 minutes)

Your code is now on GitHub: https://github.com/xghost123/renctontreethique

### Step 1: Go to Railway.app

1. Visit https://railway.app
2. Sign up/Login with GitHub
3. Click "New Project"
4. Select "Deploy from GitHub"

### Step 2: Connect GitHub Repo

1. Search for `renctontreethique`
2. Select the repository
3. Allow Railway to access your GitHub

### Step 3: Configure Environment Variables

Railway will auto-detect Laravel. Add these variables:

```
APP_NAME=RencontreEthique
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-railway-url.up.railway.app

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

**To get APP_KEY:**
```bash
php artisan key:generate
```
(Copy the output starting with "base64:...")

### Step 4: Deploy

1. Railway auto-deploys when code is pushed
2. Watch the deployment logs
3. Get your production URL from Railway dashboard

## After Deployment

### Run Database Setup (if needed)

In Railway console:
```bash
php artisan migrate
php artisan cache:clear
php artisan config:clear
```

### Your Live URL

Railway gives you: `https://yourapp.up.railway.app`

Share this with everyone!

## Test Accounts (work the same as local)

```
Admin:     admin@halalmarriage.app / password
Member(F): fatima@rencontre-ethique.test / password123
Member(M): mohammed@rencontre-ethique.test / password123
```

## Environment Variables Needed

### Essential (Must Have)
- `APP_NAME` - Application name
- `APP_KEY` - From `php artisan key:generate`
- `APP_ENV` - Set to `production`
- `APP_DEBUG` - Set to `false`
- `APP_URL` - Your Railway domain

### Database
- `DB_CONNECTION` - sqlite
- `DB_DATABASE` - database/database.sqlite

### Cache & Session
- `CACHE_DRIVER` - file (or redis if upgrading)
- `QUEUE_CONNECTION` - sync (or database)
- `SESSION_DRIVER` - file

### Optional Email (for notifications)
- `MAIL_MAILER` - log (test) or smtp (production)
- `MAIL_FROM_ADDRESS` - noreply@rencontre-ethique.app

## Troubleshooting

### Build Fails
- Check: `npm install` and `npm run build` locally
- Ensure `package.json` and `composer.json` are correct
- Railway auto-runs: `composer install`, `npm install`, `npm run build`

### Database Not Found
- Ensure `.gitignore` includes `!database/` and `!database/database.sqlite`
- SQLite file should be in repo (check: file size 228KB+)
- Migrations will run automatically

### Port Issues
- Railway auto-assigns port
- Don't use port 8000 in production
- APP_URL should use `https://`, not `http://`

### 500 Errors
- Check logs in Railway dashboard
- Run: `php artisan config:clear`
- Verify all ENV variables are set
- Check `.env` isn't in .gitignore (should be ignored)

## Cost

Railway Starter Plan: **Free** ($5 credit/month, usually enough)

If you exceed: ~$0.10/GB + $0.50/vCPU per hour

This platform fits easily in free tier!

## Keep It Running

1. **Auto-Deploy**: Push to `master` → Railway auto-deploys
2. **Logs**: View in Railway dashboard
3. **Scale**: Upgrade plan if needed

## Next Steps

1. ✅ Go to https://railway.app
2. ✅ Connect GitHub repo
3. ✅ Add environment variables
4. ✅ Wait for auto-deploy (usually 2-5 minutes)
5. ✅ Test the live URL
6. ✅ Share with world!

---

Your Rencontre Éthique Islamic matrimony platform will be **LIVE** within 5 minutes! 🚀
