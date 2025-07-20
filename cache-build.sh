#!/bin/bash

  # Laravel Cache & Build Script für Docker Container
  # Verwendung: ./cache-build.sh [clear|build|all]

  set -e

  CONTAINER_NAME="www.ki-coding.de-php"
  CONTAINER_USER="web"
  APP_PATH="/var/www/current"

  # Farben für Output
  RED='\033[0;31m'
  GREEN='\033[0;32m'
  YELLOW='\033[1;33m'
  BLUE='\033[0;34m'
  NC='\033[0m' # No Color

  # Funktionen
  log_info() {
      echo -e "${BLUE}[INFO]${NC} $1"
  }

  log_success() {
      echo -e "${GREEN}[SUCCESS]${NC} $1"
  }

  log_warning() {
      echo -e "${YELLOW}[WARNING]${NC} $1"
  }

  log_error() {
      echo -e "${RED}[ERROR]${NC} $1"
  }

  # Laravel Caches leeren
  clear_caches() {
      log_info "Clearing Laravel caches..."

      # Config Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan config:clear"
      log_success "Config cache cleared"

      # Route Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan route:clear"
      log_success "Route cache cleared"

      # View Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan view:clear"
      log_success "View cache cleared"

      # Event Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan event:clear"
      log_success "Event cache cleared"

      # Application Cache (Redis/File)
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan cache:clear" || log_warning "Application cache clear failed (Redis might be unavailable)"

      log_success "All Laravel caches cleared!"
  }

  # Frontend Assets bauen
  build_assets() {
      log_info "Building frontend assets..."

      # NPM build
      # npm run build
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && npm run build"
      log_success "Frontend assets built!"
  }

  # Database Migrationen ausführen
  run_migrations() {
      log_info "Running database migrations..."

      # Migrate database
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan migrate --force"
      log_success "Migrations completed"
  }

  # Laravel optimieren
  optimize_laravel() {
      log_info "Optimizing Laravel for production..."

      # Config Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan config:cache"
      log_success "Config cached"

      # Route Cache  
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan route:cache"
      log_success "Routes cached"

      # View Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan view:cache"
      log_success "Views cached"

      # Event Cache
      docker exec -u $CONTAINER_USER $CONTAINER_NAME bash -c "cd $APP_PATH && php artisan event:cache"
      log_success "Events cached"

      log_success "Laravel optimized for production!"
  }

  # Script-Optionen
  case "${1:-all}" in
      "clear")
          clear_caches
          ;;
      "build")
          build_assets
          ;;
      "migrate")
          run_migrations
          ;;
      "optimize")
          optimize_laravel
          ;;
      "all")
          clear_caches
          echo
          run_migrations
          echo
          build_assets
          echo
          optimize_laravel
          ;;
      *)
          echo "Usage: $0 [clear|build|migrate|optimize|all]"
          echo
          echo "Options:"
          echo "  clear     - Clear all Laravel caches"
          echo "  build     - Build frontend assets with Vite"
          echo "  migrate   - Run database migrations"
          echo "  optimize  - Cache Laravel configs/routes/views for production"
          echo "  all       - Run all operations (default)"
          exit 1
          ;;
  esac

  log_success "Script completed successfully!"