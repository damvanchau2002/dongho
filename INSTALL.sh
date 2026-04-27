#!/usr/bin/env bash

# ============================================================================
# LEOPARD STORE - SECURITY UPGRADE QUICK START
# ============================================================================
# Hướng dẫn cài đặt nhanh cho nâng cấp bảo mật Phase 1
# ============================================================================

echo "🚀 Leopard Store - Security Upgrade Installation"
echo "================================================"
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if running from correct directory
if [ ! -f "index.php" ]; then
    echo -e "${RED}Error: Run this script from Website root directory${NC}"
    exit 1
fi

echo -e "${BLUE}Step 1: Checking project structure...${NC}"
echo ""

# Function to check directory
check_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} Directory exists: $1"
    else
        echo -e "${YELLOW}⚠${NC} Creating directory: $1"
        mkdir -p "$1"
        chmod 755 "$1"
    fi
}

# Check required directories
check_dir "helpers"
check_dir "config"
check_dir "logs"

echo ""
echo -e "${BLUE}Step 2: Checking files...${NC}"
echo ""

# Function to check file
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} File exists: $1"
        return 0
    else
        echo -e "${RED}✗${NC} File missing: $1"
        return 1
    fi
}

# Check helper files
required_files=(
    "helpers/SecurityHelper.php"
    "helpers/Logger.php"
    "helpers/ProtectionHelper.php"
    "config/database.php"
    "migrate_passwords.php"
    "health_check.php"
    "SECURITY_UPGRADE.md"
)

failed=0
for file in "${required_files[@]}"; do
    if ! check_file "$file"; then
        ((failed++))
    fi
done

if [ $failed -gt 0 ]; then
    echo ""
    echo -e "${RED}Error: $failed required files are missing${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 3: Setting permissions...${NC}"
echo ""

# Set proper permissions
chmod 755 helpers/*.php
chmod 755 config/*.php
chmod 755 migrate_passwords.php
chmod 755 health_check.php
chmod 755 logs

echo -e "${GREEN}✓${NC} Permissions set correctly"

echo ""
echo -e "${BLUE}Step 4: Checking PHP version...${NC}"
echo ""

php_version=$(php -v | grep -oP 'PHP \K[0-9.]+' | head -1)
required_version="7.0.0"

if [ "$(printf '%s\n' "$required_version" "$php_version" | sort -V | head -n1)" = "$required_version" ]; then
    echo -e "${GREEN}✓${NC} PHP version: $php_version (>= $required_version)"
else
    echo -e "${RED}✗${NC} PHP version: $php_version (< $required_version required)"
    exit 1
fi

echo ""
echo -e "${BLUE}Step 5: Summary${NC}"
echo ""

echo -e "${GREEN}✓ Installation completed successfully!${NC}"
echo ""
echo "📋 Next Steps:"
echo "1. Open http://localhost/Website/health_check.php"
echo "2. Verify all checks pass"
echo "3. Run migration: http://localhost/Website/migrate_passwords.php"
echo "4. Test login with existing account"
echo "5. Test signup with new account"
echo ""
echo "📚 Documentation:"
echo "- README_SECURITY_UPGRADE.md - Overview"
echo "- SECURITY_UPGRADE.md - Detailed guide"
echo "- EXAMPLES_USAGE.md - Code examples"
echo "- CHANGELOG.md - Change log"
echo ""
echo "✨ Security upgrade is ready to use!"
