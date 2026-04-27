@echo off
REM ============================================================================
REM LEOPARD STORE - SECURITY UPGRADE QUICK START (Windows)
REM ============================================================================

setlocal enabledelayedexpansion

echo ==========================================
echo Leopard Store - Security Upgrade Install
echo ==========================================
echo.

REM Check if in correct directory
if not exist "index.php" (
    echo Error: Run this script from Website root directory
    exit /b 1
)

echo Step 1: Checking project structure...
echo.

REM Function to create directory if not exists
if not exist "helpers" (
    echo Creating directory: helpers
    mkdir helpers
) else (
    echo. ✓ Directory exists: helpers
)

if not exist "config" (
    echo Creating directory: config
    mkdir config
) else (
    echo. ✓ Directory exists: config
)

if not exist "logs" (
    echo Creating directory: logs
    mkdir logs
) else (
    echo. ✓ Directory exists: logs
)

echo.
echo Step 2: Checking files...
echo.

set "failed=0"

REM Check helper files
for %%f in (
    "helpers\SecurityHelper.php"
    "helpers\Logger.php"
    "helpers\ProtectionHelper.php"
    "config\database.php"
    "migrate_passwords.php"
    "health_check.php"
    "SECURITY_UPGRADE.md"
) do (
    if exist %%f (
        echo. ✓ File exists: %%f
    ) else (
        echo. ✗ File missing: %%f
        set "failed=!failed! 1"
    )
)

if not "!failed!"=="" (
    echo.
    echo Error: Some required files are missing
    exit /b 1
)

echo.
echo Step 3: All checks passed!
echo.
echo ==========================================
echo Installation completed successfully!
echo ==========================================
echo.
echo Next Steps:
echo 1. Open browser: http://localhost/Website/health_check.php
echo 2. Verify all checks pass
echo 3. Run migration: http://localhost/Website/migrate_passwords.php
echo 4. Test login with existing account
echo 5. Test signup with new account
echo.
echo Documentation:
echo - README_SECURITY_UPGRADE.md - Overview
echo - SECURITY_UPGRADE.md - Detailed guide
echo - EXAMPLES_USAGE.md - Code examples
echo - CHANGELOG.md - Change log
echo.
echo Security upgrade is ready to use!
echo ==========================================
echo.

pause
