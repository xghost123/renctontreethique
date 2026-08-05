# Rencontre Éthique - Vercel Deployment Guide

## Current Status

✅ **Production Ready**
- Laravel 12 + Vue 3 + Inertia.js
- 86 assets (0 errors, 0 warnings)
- SQLite database (26 tables)
- Beautiful responsive homepage with header
- 4 core features: Photos, Messaging, Search, Notifications
- 8-step profile wizard

## Deployment Options

### Option 1: Vercel (Recommended for Frontend)

**Note:** Vercel is optimized for Next.js/Node.js. For Laravel, you'll need a different approach.

### Option 2: Railway.app (Best for Laravel + SQLite)

Railway is excellent for Laravel applications with SQLite.

**Steps:**
1. Push code to GitHub
2. Connect GitHub repo to Railway
3. Add environment variables from .env
4. Railway auto-deploys on push
5. Free tier available

**Cost:** ~$5-10/month

### Option 3: Render.com (Free Tier Available)

**Steps:**
1. Create account on render.com
2. New Web Service → GitHub
3. Connect your repo
4. Build command: `composer install && npm install && npm run build`
5. Start command: `php artisan serve --host=0.0.0.0`
6. Set environment variables
7. Deploy

**Cost:** Free tier ($0/month)

### Option 4: Heroku Alternative - Fly.io

Similar to Heroku but still active and cheaper.

**Cost:** $5/month minimum

### Option 5: DigitalOcean App Platform

Full managed platform, good for Laravel.

**Cost:** $12/month

## Recommended: Railway.app

### Why Railway?

✅ Easy Laravel deployment
✅ Automatic database backups
✅ Environment variables UI
✅ GitHub integration
✅ SQLite support
✅ Affordable pricing
✅ Great documentation

### Deployment Steps for Railway

1. **Prepare your code:**
   ```bash
   git add .
   git commit -m "Ready for Railway deployment"
   git push
   ```

2. **Go to Railway.app**
   - Create account
   - Click "New Project"
   - Select "Deploy from GitHub"
   - Connect your GitHub account
   - Select `matrimony-laravel-vue` repo

3. **Configure:**
   - Railway detects Laravel automatically
   - Copy from .env into Railway's environment variables:
     - `APP_KEY` (from .env)
     - `DB_CONNECTION=sqlite`
     - `DB_DATABASE=database/database.sqlite`
     - `APP_DEBUG=false`
     - `APP_ENV=production`

4. **Deploy:**
   - Railway auto-deploys
   - Visit your provided URL

5. **Post-Deploy:**
   ```bash
   # Run migrations (if needed)
   railway run php artisan migrate
   
   # Clear caches
   railway run php artisan cache:clear
   railway run php artisan config:clear
   ```

## Files Structure for Deployment

```
matrimony-laravel-vue/
├── app/                    # Laravel app code
├── bootstrap/              # Laravel bootstrap
├── database/
│   ├── database.sqlite    # ✅ SQLite DB
│   └── migrations/
├── public/
│   └── build/             # ✅ Built assets (86 files)
├── resources/
│   ├── js/                # Vue components
│   ├── css/
│   └── views/
├── routes/                # API & web routes
├── .env                   # ✅ Updated for localhost
├── .env.example
├── package.json           # Node dependencies
├── composer.json          # PHP dependencies
└── vercel.json            # (Optional for Vercel)
```

## Pre-Deployment Checklist

- ✅ APP_URL=http://localhost:8000 (in .env)
- ✅ DB_DATABASE=database/database.sqlite (in .env)
- ✅ Build passing: `npm run build` (0 errors)
- ✅ Server running locally: `http://127.0.0.1:8000`
- ✅ All 4 features working
- ✅ Homepage loads with header
- ✅ No rate limiting issues
- ✅ Database has 26 tables
- ✅ Assets built (86 files)

## Environment Variables Needed for Production

```
APP_NAME=RencontreEthique
APP_ENV=production
APP_KEY=base64:YourAppKeyHere
APP_DEBUG=false
APP_URL=https://your-deployed-url.com

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=log
```

## Estimated Costs (Per Month)

| Platform | Cost | Pros | Cons |
|----------|------|------|------|
| Railway | $5-20 | Easy, SQLite support | Not free |
| Render | $0 (free tier) | Free tier | Limitations on free tier |
| DigitalOcean | $12+ | Reliable, scalable | Higher cost |
| Fly.io | $5+ | Developer-friendly | Less documentation |
| Vercel + Backend | $20+ | Popular | Need separate backend |

## Next Steps

1. **Choose platform** (Railway recommended)
2. **Push to GitHub**
3. **Connect to platform**
4. **Set environment variables**
5. **Deploy**
6. **Test live URL**

## Local Development

Server is running locally:

```bash
# Access at:
http://127.0.0.1:8000

# Test accounts:
# Admin: admin@halalmarriage.app / password
# Member (F): fatima@rencontre-ethique.test / password123
# Member (M): mohammed@rencontre-ethique.test / password123
```

## Support

For deployment issues:
- Check Railway docs: https://docs.railway.app
- Laravel deployment: https://laravel.com/docs/deployment
- Vue Inertia: https://inertiajs.com
