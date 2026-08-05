#!/bin/bash
# Set up environment for Laravel development with SQLite support
export PHPRC="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/php.ini.dev"
exec php artisan serve --port=8000 "$@"
