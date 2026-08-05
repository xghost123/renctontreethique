# Matrimony Laravel + Vue - Development Setup

## ✅ Setup Complete!

Your project is now configured for **lightweight SQLite development** without needing MySQL or Redis server installation.

### What's Been Done

- ✅ **Database**: Configured to use SQLite (`database/database.sqlite`)
- ✅ **Sessions**: File-based (no database required)
- ✅ **Queue**: Synchronous (no Redis required)
- ✅ **Cache**: File-based (no Redis required)
- ✅ **Migrations**: All ran successfully
- ✅ **PHP**: SQLite extensions enabled via custom `php.ini.dev`

## 🚀 Quick Start

### Option 1: Windows Command Prompt

```bash
# In one terminal
dev.cmd serve          # Starts Laravel on http://localhost:8000

# In another terminal
npm run dev            # Starts Vite frontend dev server
```

### Option 2: Git Bash / WSL

```bash
# In one terminal
./dev.sh serve         # Starts Laravel

# In another terminal
npm run dev            # Starts Vite
```

### Option 3: Manual (Full Control)

```bash
# Terminal 1: Laravel API Server
php -c php.ini.dev artisan serve

# Terminal 2: Frontend Build (Vite)
npm run dev

# Terminal 3 (Optional): Queue Worker
php -c php.ini.dev artisan queue:listen --tries=1
```

## 📁 Project Structure

```
matrimony-laravel-vue/
├── app/                    # Laravel controllers, models, etc.
├── database/
│   ├── database.sqlite     # SQLite database (auto-created)
│   ├── migrations/         # Database schemas
│   └── seeders/           # Database seed files
├── resources/
│   └── js/                # Vue components
├── routes/                # API routes
├── public/                # Static assets
├── storage/               # Logs, cache, sessions
├── node_modules/          # npm dependencies
├── vendor/                # Composer dependencies
├── php.ini.dev            # Custom PHP config (SQLite enabled)
├── .env                   # Configuration (SQLite + file-based)
├── vite.config.js         # Frontend build config
├── package.json           # npm packages
└── composer.json          # PHP packages
```

## 📝 Useful Commands

### Database Management

```bash
# Show database info
./dev.sh                   # Shows all commands

# Run migrations
./dev.sh migrate

# Fresh start (drop + migrate + seed)
./dev.sh fresh

# Interact with database
./dev.sh tinker
```

### Frontend Development

```bash
# Development server with hot reload
npm run dev

# Build for production
npm run build

# Check dependencies
npm list
```

### Laravel Artisan

```bash
# With custom PHP config
php -c php.ini.dev artisan [command]

# Examples:
php -c php.ini.dev artisan make:controller MyController
php -c php.ini.dev artisan make:model MyModel -m
php -c php.ini.dev artisan tinker
```

## 🔍 Verify Setup

Check that everything is working:

```bash
# Check database
ls -lh database/database.sqlite

# Check PHP SQLite support
php -c php.ini.dev -m | grep sqlite

# Check Node setup
node --version
npm --version

# Check Laravel
php -c php.ini.dev artisan --version
```

## 🐛 Troubleshooting

### "SQLite driver not found"
Make sure you're using `php -c php.ini.dev` or `./dev.sh` commands.

### Port 8000 already in use
```bash
# Use a different port
php -c php.ini.dev artisan serve --port=8001
```

### Vite not detecting changes
```bash
# Kill the Vite process and restart
npm run dev
```

### Database locked
```bash
# SQLite can be finicky with file locks. Use fresh start:
./dev.sh fresh
```

### Need to reset everything
```bash
# Remove database and restart
rm database/database.sqlite
./dev.sh migrate
```

## 📦 Stack

- **Backend**: Laravel 12 (PHP 8.5)
- **Frontend**: Vue 3 + Vite 6
- **Database**: SQLite 3
- **Styling**: Tailwind CSS
- **UI Components**: Inertia.js, DataTables
- **Dev Tools**: PHPUnit, Pint, Laravel Tinker

## 🌐 Access Points

When running:

- **API/Web**: http://localhost:8000
- **Vite Dev**: http://localhost:5173 (auto-proxied)
- **Laravel Docs**: https://laravel.com/docs/12
- **Vue Docs**: https://vuejs.org/

## ⚙️ Environment Variables

See `.env` file. Key settings:

- `DB_CONNECTION=sqlite` - Use SQLite
- `SESSION_DRIVER=file` - File-based sessions
- `QUEUE_CONNECTION=sync` - Synchronous queue (dev only)
- `CACHE_STORE=file` - File-based cache
- `APP_DEBUG=true` - Debug mode enabled

## 📌 Notes

- SQLite is **perfect for local development** but not recommended for production
- For production: migrate to MySQL + Redis (see docker-compose.yml)
- The custom `php.ini.dev` is git-ignored and local-only
- Database backups: Just copy `database/database.sqlite`
- All file-based storage goes to `storage/` directory

## ❓ Need Help?

Check the original README.md or contact the developer at rajon.kobir@gmail.com

---

**Ready to develop!** 🚀
