@echo off
REM Category API Testing Script for Windows
REM This script tests all Category API endpoints

setlocal enabledelayedexpansion

REM Configuration
set BASE_URL=http://localhost/api/v1
set JWT_TOKEN=
REM Add your JWT token above

REM Check if JWT token is set
if "%JWT_TOKEN%"=="" (
    echo Error: JWT_TOKEN is not set. Please add your token to the script.
    pause
    exit /b 1
)

REM Check if curl is available
where curl >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: curl is not installed or not in PATH.
    echo Please install curl to use this script.
    pause
    exit /b 1
)

echo Starting Category API Tests...
echo.

REM ========================================
REM CATEGORY ENDPOINTS
REM ========================================

echo ========================================
echo CATEGORY ENDPOINTS
echo ========================================
echo.

echo Testing: Get all categories (paginated)
curl -X GET "%BASE_URL%/categories" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get active categories only
curl -X GET "%BASE_URL%/categories?status=active" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get product categories only
curl -X GET "%BASE_URL%/categories?category_type=product" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Search categories by name
curl -X GET "%BASE_URL%/categories?search=electronics" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get categories with sub-categories
curl -X GET "%BASE_URL%/categories?include_subcategories=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get categories with all nested relationships
curl -X GET "%BASE_URL%/categories?include_subcategories=true&include_child_categories=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get all categories without pagination
curl -X GET "%BASE_URL%/categories?paginate=false" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get category by ID (1)
curl -X GET "%BASE_URL%/categories/1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get category by ID with relationships
curl -X GET "%BASE_URL%/categories/1?include_subcategories=true&include_child_categories=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get category by slug
curl -X GET "%BASE_URL%/categories/slug/electronics" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get full category hierarchy
curl -X GET "%BASE_URL%/categories/hierarchy" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get active category hierarchy
curl -X GET "%BASE_URL%/categories/hierarchy?status=active" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

REM ========================================
REM SUB-CATEGORY ENDPOINTS
REM ========================================

echo ========================================
echo SUB-CATEGORY ENDPOINTS
echo ========================================
echo.

echo Testing: Get all sub-categories (paginated)
curl -X GET "%BASE_URL%/subcategories" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-categories for category ID 1
curl -X GET "%BASE_URL%/subcategories?category_id=1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get active sub-categories only
curl -X GET "%BASE_URL%/subcategories?status=active" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Search sub-categories by name
curl -X GET "%BASE_URL%/subcategories?search=mobile" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-categories with parent category
curl -X GET "%BASE_URL%/subcategories?include_category=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-categories with all relationships
curl -X GET "%BASE_URL%/subcategories?include_category=true&include_child_categories=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-category by ID (1)
curl -X GET "%BASE_URL%/subcategories/1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-category by ID with relationships
curl -X GET "%BASE_URL%/subcategories/1?include_category=true&include_child_categories=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get sub-category by slug
curl -X GET "%BASE_URL%/subcategories/slug/mobile-phones" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

REM ========================================
REM CHILD CATEGORY ENDPOINTS
REM ========================================

echo ========================================
echo CHILD CATEGORY ENDPOINTS
echo ========================================
echo.

echo Testing: Get all child categories (paginated)
curl -X GET "%BASE_URL%/child-categories" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child categories for category ID 1
curl -X GET "%BASE_URL%/child-categories?category_id=1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child categories for sub-category ID 1
curl -X GET "%BASE_URL%/child-categories?sub_category_id=1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get active child categories only
curl -X GET "%BASE_URL%/child-categories?status=active" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Search child categories by name
curl -X GET "%BASE_URL%/child-categories?search=smartphone" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child categories with parent relationships
curl -X GET "%BASE_URL%/child-categories?include_category=true&include_subcategory=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child category by ID (1)
curl -X GET "%BASE_URL%/child-categories/1" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child category by ID with relationships
curl -X GET "%BASE_URL%/child-categories/1?include_category=true&include_subcategory=true" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

echo Testing: Get child category by slug
curl -X GET "%BASE_URL%/child-categories/slug/smartphones" -H "Authorization: Bearer %JWT_TOKEN%" -H "Content-Type: application/json"
echo.
echo.
timeout /t 1 /nobreak >nul

REM ========================================
REM SUMMARY
REM ========================================

echo ========================================
echo TESTING COMPLETE
echo ========================================
echo.
echo All API endpoints have been tested.
echo Review the output above for any failures.
echo.

pause
