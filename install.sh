#!/bin/bash

# ============================================================
#  TicketIn Laravel 11 — Installer Otomatis
# ============================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color
BOLD='\033[1m'

echo ""
echo -e "${YELLOW}${BOLD}╔══════════════════════════════════════╗${NC}"
echo -e "${YELLOW}${BOLD}║   🎟️  TicketIn — Laravel 11 Setup    ║${NC}"
echo -e "${YELLOW}${BOLD}╚══════════════════════════════════════╝${NC}"
echo ""

# --- 1. Cek PHP ---
echo -e "${BLUE}[1/7]${NC} Memeriksa PHP..."
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP tidak ditemukan. Install PHP 8.2+ terlebih dahulu.${NC}"
    exit 1
fi
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo -e "    ${GREEN}✅ PHP $PHP_VERSION ditemukan${NC}"

# --- 2. Cek Composer ---
echo -e "${BLUE}[2/7]${NC} Memeriksa Composer..."
if ! command -v composer &> /dev/null; then
    echo -e "${RED}❌ Composer tidak ditemukan. Install dari https://getcomposer.org${NC}"
    exit 1
fi
echo -e "    ${GREEN}✅ Composer ditemukan${NC}"

# --- 3. Install dependencies ---
echo -e "${BLUE}[3/7]${NC} Menginstall dependencies..."
composer install --no-interaction --optimize-autoloader
if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Composer install gagal.${NC}"
    exit 1
fi
echo -e "    ${GREEN}✅ Dependencies terinstall${NC}"

# --- 4. Setup .env ---
echo -e "${BLUE}[4/7]${NC} Menyiapkan file .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo -e "    ${GREEN}✅ .env dibuat dari .env.example${NC}"
else
    echo -e "    ${YELLOW}⚠️  .env sudah ada, dilewati${NC}"
fi

# Generate app key
php artisan key:generate --ansi
echo -e "    ${GREEN}✅ APP_KEY di-generate${NC}"

# --- 5. Setup database ---
echo ""
echo -e "${YELLOW}${BOLD}⚙️  Konfigurasi Database${NC}"
echo -e "Edit file ${BOLD}.env${NC} dan sesuaikan:"
echo ""
echo -e "  DB_DATABASE=${YELLOW}ticketin_db${NC}"
echo -e "  DB_USERNAME=${YELLOW}root${NC}"
echo -e "  DB_PASSWORD=${YELLOW}(password mysql kamu)${NC}"
echo ""
read -p "Sudah mengatur .env? Lanjutkan migrasi? (y/n): " confirm
if [ "$confirm" != "y" ]; then
    echo -e "${YELLOW}Setup dihentikan. Edit .env lalu jalankan:${NC}"
    echo "  php artisan migrate --seed"
    echo "  php artisan storage:link"
    echo "  php artisan serve"
    exit 0
fi

echo -e "${BLUE}[5/7]${NC} Menjalankan migrasi database..."
php artisan migrate --seed --force
if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Migrasi gagal. Pastikan database sudah dibuat dan .env sudah benar.${NC}"
    exit 1
fi
echo -e "    ${GREEN}✅ Migrasi & seeder selesai${NC}"

# --- 6. Storage link ---
echo -e "${BLUE}[6/7]${NC} Membuat symlink storage..."
php artisan storage:link
echo -e "    ${GREEN}✅ Storage link dibuat${NC}"

# --- 7. Optimize & serve ---
echo -e "${BLUE}[7/7]${NC} Optimasi..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo -e "    ${GREEN}✅ Cache dibersihkan${NC}"

echo ""
echo -e "${GREEN}${BOLD}╔══════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}${BOLD}║   ✅  TicketIn berhasil diinstall!          ║${NC}"
echo -e "${GREEN}${BOLD}╚══════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  ${BOLD}Akun default:${NC}"
echo -e "  👑 Admin   : ${YELLOW}admin@ticketin.id${NC} / password"
echo -e "  🎭 EO      : ${YELLOW}eo@ticketin.id${NC}    / password"
echo -e "  👤 User    : ${YELLOW}user@ticketin.id${NC}  / password"
echo ""
echo -e "  ${BOLD}Jalankan server:${NC}"
echo -e "  ${BLUE}php artisan serve${NC}"
echo ""
echo -e "  Buka browser: ${YELLOW}http://localhost:8000${NC}"
echo ""
