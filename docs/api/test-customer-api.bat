@echo off
setlocal enabledelayedexpansion

REM Customer Info API Test Script for Windows
REM This script helps you test the Customer Info API endpoints

set BASE_URL=http://localhost/bmv/api/v1
set JWT_TOKEN=

echo =========================================
echo Customer Info API Test Script
echo =========================================
echo.

REM Check if curl is available
where curl >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: curl is not installed or not in PATH
    echo Please install curl to use this script
    pause
    exit /b 1
)

REM Ask if user wants to login
echo Do you want to login to get JWT token? (y/n)
set /p login_choice=

if /i "%login_choice%"=="y" (
    echo Enter email:
    set /p email=
    echo Enter password:
    set /p password=
    
    echo.
    echo Logging in...
    
    curl -s -X POST "%BASE_URL%/auth/login" ^
        -H "Content-Type: application/json" ^
        -H "Accept: application/json" ^
        -d "{\"email\":\"%email%\",\"password\":\"%password%\"}" > login_response.json
    
    echo Login Response:
    type login_response.json
    echo.
    echo.
    
    echo Please copy the access_token from above and paste it here:
    set /p JWT_TOKEN=
    
    del login_response.json
    echo.
) else (
    echo Please enter your JWT token:
    set /p JWT_TOKEN=
    echo.
)

:menu
echo =========================================
echo Select an endpoint to test:
echo =========================================
echo 1. Get Profile
echo 2. Update Profile
echo 3. Update Password
echo 4. Update Location
echo 5. Update Social Links
echo 6. Get Customer by ID
echo 7. Get Customer by Username
echo 8. Health Check (no auth)
echo 9. Exit
echo.
echo Enter your choice (1-9):
set /p choice=

if "%choice%"=="1" goto get_profile
if "%choice%"=="2" goto update_profile
if "%choice%"=="3" goto update_password
if "%choice%"=="4" goto update_location
if "%choice%"=="5" goto update_social
if "%choice%"=="6" goto get_by_id
if "%choice%"=="7" goto get_by_username
if "%choice%"=="8" goto health_check
if "%choice%"=="9" goto exit_script
echo Invalid choice. Please try again.
echo.
goto menu

:get_profile
echo.
echo Testing: Get Customer Profile
echo Endpoint: GET /customer/profile
echo.
curl -X GET "%BASE_URL%/customer/profile" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Accept: application/json"
echo.
echo.
goto menu

:update_profile
echo.
echo Enter name:
set /p name=
echo Enter email:
set /p email=
echo Enter phone:
set /p phone=
echo.
echo Testing: Update Customer Profile
echo Endpoint: PUT /customer/profile
echo.
curl -X PUT "%BASE_URL%/customer/profile" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Content-Type: application/json" ^
    -H "Accept: application/json" ^
    -d "{\"name\":\"%name%\",\"email\":\"%email%\",\"phone\":\"%phone%\"}"
echo.
echo.
goto menu

:update_password
echo.
echo Enter current password:
set /p current_password=
echo Enter new password:
set /p new_password=
echo.
echo Testing: Update Password
echo Endpoint: PUT /customer/password
echo.
curl -X PUT "%BASE_URL%/customer/password" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Content-Type: application/json" ^
    -H "Accept: application/json" ^
    -d "{\"current_password\":\"%current_password%\",\"new_password\":\"%new_password%\",\"new_password_confirmation\":\"%new_password%\"}"
echo.
echo.
goto menu

:update_location
echo.
echo Enter latitude:
set /p latitude=
echo Enter longitude:
set /p longitude=
echo Enter address:
set /p address=
echo Enter city:
set /p city=
echo.
echo Testing: Update Location
echo Endpoint: PUT /customer/location
echo.
curl -X PUT "%BASE_URL%/customer/location" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Content-Type: application/json" ^
    -H "Accept: application/json" ^
    -d "{\"latitude\":%latitude%,\"longitude\":%longitude%,\"address\":\"%address%\",\"city\":\"%city%\"}"
echo.
echo.
goto menu

:update_social
echo.
echo Enter Facebook URL (or press Enter to skip):
set /p facebook=
echo Enter Instagram URL (or press Enter to skip):
set /p instagram=
echo.
echo Testing: Update Social Links
echo Endpoint: PUT /customer/social-links
echo.
curl -X PUT "%BASE_URL%/customer/social-links" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Content-Type: application/json" ^
    -H "Accept: application/json" ^
    -d "{\"facebook\":\"%facebook%\",\"instagram\":\"%instagram%\"}"
echo.
echo.
goto menu

:get_by_id
echo.
echo Enter customer ID:
set /p customer_id=
echo.
echo Testing: Get Customer by ID
echo Endpoint: GET /customers/%customer_id%
echo.
curl -X GET "%BASE_URL%/customers/%customer_id%" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Accept: application/json"
echo.
echo.
goto menu

:get_by_username
echo.
echo Enter username:
set /p username=
echo.
echo Testing: Get Customer by Username
echo Endpoint: GET /customers/username/%username%
echo.
curl -X GET "%BASE_URL%/customers/username/%username%" ^
    -H "Authorization: Bearer %JWT_TOKEN%" ^
    -H "Accept: application/json"
echo.
echo.
goto menu

:health_check
echo.
echo Testing: Health Check
echo Endpoint: GET /health
echo.
curl -X GET "http://localhost/bmv/api/health" ^
    -H "Accept: application/json"
echo.
echo.
goto menu

:exit_script
echo Exiting...
exit /b 0
