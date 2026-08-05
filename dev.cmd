@echo off
REM Development wrapper script for Laravel + Vue project
REM Uses SQLite instead of MySQL for lightweight local development

setlocal enabledelayedexpansion

set PROJECT_ROOT=%~dp0
set PHP_INI=%PROJECT_ROOT%php.ini.dev

if "%1"=="" goto info
if /i "%1"=="serve" goto serve
if /i "%1"=="queue" goto queue
if /i "%1"=="tinker" goto tinker
if /i "%1"=="migrate" goto migrate
if /i "%1"=="seed" goto seed
if /i "%1"=="fresh" goto fresh
goto info

:serve
echo Starting Laravel development server...
php -c "%PHP_INI%" artisan serve
goto end

:queue
echo Starting queue worker...
php -c "%PHP_INI%" artisan queue:listen --tries=1
goto end

:tinker
echo Starting Laravel Tinker...
php -c "%PHP_INI%" artisan tinker
goto end

:migrate
echo Running migrations...
php -c "%PHP_INI%" artisan migrate
goto end

:seed
echo Seeding database...
php -c "%PHP_INI%" artisan db:seed
goto end

:fresh
echo Refreshing database...
php -c "%PHP_INI%" artisan migrate:fresh --seed
goto end

:info
echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║  Matrimony Laravel + Vue Development Setup                   ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.
echo Database: SQLite (database/database.sqlite)
for /f "tokens=*" %%A in ('node --version') do (
    echo Node: %%A
)
for /f "tokens=1-2" %%A in ('php -v') do (
    echo PHP: %%A %%B
    goto php_ok
)
:php_ok
echo.
echo Available commands:
echo   dev.cmd serve       - Start Laravel dev server
echo   dev.cmd queue       - Start queue worker
echo   dev.cmd tinker      - Start Laravel Tinker REPL
echo   dev.cmd migrate     - Run migrations
echo   dev.cmd seed        - Seed database
echo   dev.cmd fresh       - Migrate fresh + seed
echo.
echo Frontend development:
echo   npm run dev         - Start Vite dev server
echo   npm run build       - Build for production
echo.
echo To start all services:
echo   1. Open Command Prompt here
echo   2. Run: dev.cmd serve
echo   3. Open another Command Prompt
echo   4. Run: npm run dev
echo.

:end
endlocal
