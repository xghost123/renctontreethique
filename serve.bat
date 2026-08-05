@echo off
setlocal enabledelayedexpansion

REM Get the directory where this batch file is located
cd /d "%~dp0"

REM CRITICAL: Set PHP to use the project php.ini which has SQLite enabled
REM The system php.ini doesn't have SQLite enabled, but our custom one does
set PHPRC=%cd%\php.ini

REM Also try PHP_INI_SCAN_DIR in case PHPRC doesn't work
set PHP_INI_SCAN_DIR=%cd%

REM Start Laravel artisan serve with the custom PHP configuration
php artisan serve --port=8000 %*
