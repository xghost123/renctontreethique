#!/bin/bash
# Development wrapper script for Laravel + Vue project
# Uses SQLite instead of MySQL for lightweight local development

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_INI="$PROJECT_ROOT/php.ini.dev"

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

case "$1" in
  serve)
    echo -e "${BLUE}Starting Laravel development server...${NC}"
    php -c "$PHP_INI" artisan serve
    ;;
  queue)
    echo -e "${BLUE}Starting queue worker...${NC}"
    php -c "$PHP_INI" artisan queue:listen --tries=1
    ;;
  tinker)
    echo -e "${BLUE}Starting Laravel Tinker...${NC}"
    php -c "$PHP_INI" artisan tinker
    ;;
  migrate)
    echo -e "${BLUE}Running migrations...${NC}"
    php -c "$PHP_INI" artisan migrate
    ;;
  seed)
    echo -e "${BLUE}Seeding database...${NC}"
    php -c "$PHP_INI" artisan db:seed
    ;;
  fresh)
    echo -e "${BLUE}Refreshing database...${NC}"
    php -c "$PHP_INI" artisan migrate:fresh --seed
    ;;
  *)
    echo -e "${GREEN}=== Matrimony Laravel + Vue Development Setup ===${NC}"
    echo ""
    echo "Database: SQLite (database/database.sqlite)"
    echo "Node: $(node --version)"
    echo "PHP: $(php -v | head -1)"
    echo ""
    echo -e "${BLUE}Available commands:${NC}"
    echo "  ./dev.sh serve       - Start Laravel dev server (http://localhost:8000)"
    echo "  ./dev.sh queue       - Start queue worker"
    echo "  ./dev.sh tinker      - Start Laravel Tinker REPL"
    echo "  ./dev.sh migrate     - Run migrations"
    echo "  ./dev.sh seed        - Seed database"
    echo "  ./dev.sh fresh       - Migrate fresh + seed"
    echo ""
    echo -e "${BLUE}Frontend development:${NC}"
    echo "  npm run dev          - Start Vite dev server"
    echo "  npm run build        - Build for production"
    echo ""
    echo -e "${GREEN}Start all services:${NC}"
    echo "  Terminal 1: ./dev.sh serve"
    echo "  Terminal 2: npm run dev"
    echo ""
esac
