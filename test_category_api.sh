#!/bin/bash

# Category API Testing Script
# This script tests all Category API endpoints

# Configuration
BASE_URL="http://localhost/api/v1"
JWT_TOKEN=""  # Add your JWT token here

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print section headers
print_header() {
    echo -e "\n${YELLOW}========================================${NC}"
    echo -e "${YELLOW}$1${NC}"
    echo -e "${YELLOW}========================================${NC}\n"
}

# Function to make API request
make_request() {
    local method=$1
    local endpoint=$2
    local description=$3
    
    echo -e "${GREEN}Testing:${NC} $description"
    echo -e "${GREEN}Endpoint:${NC} $method $endpoint"
    
    response=$(curl -s -w "\n%{http_code}" -X $method \
        -H "Authorization: Bearer $JWT_TOKEN" \
        -H "Content-Type: application/json" \
        "$BASE_URL$endpoint")
    
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | sed '$d')
    
    if [ $http_code -eq 200 ]; then
        echo -e "${GREEN}✓ Success (HTTP $http_code)${NC}"
        echo "$body" | jq '.' 2>/dev/null || echo "$body"
    else
        echo -e "${RED}✗ Failed (HTTP $http_code)${NC}"
        echo "$body" | jq '.' 2>/dev/null || echo "$body"
    fi
    
    echo ""
    sleep 1
}

# Check if JWT token is set
if [ -z "$JWT_TOKEN" ]; then
    echo -e "${RED}Error: JWT_TOKEN is not set. Please add your token to the script.${NC}"
    exit 1
fi

# Check if jq is installed
if ! command -v jq &> /dev/null; then
    echo -e "${YELLOW}Warning: jq is not installed. JSON output will not be formatted.${NC}"
    echo -e "${YELLOW}Install jq for better output: sudo apt-get install jq${NC}\n"
fi

# Start testing
echo -e "${GREEN}Starting Category API Tests...${NC}"

# ========================================
# CATEGORY ENDPOINTS
# ========================================

print_header "CATEGORY ENDPOINTS"

make_request "GET" "/categories" \
    "Get all categories (paginated)"

make_request "GET" "/categories?status=active" \
    "Get active categories only"

make_request "GET" "/categories?category_type=product" \
    "Get product categories only"

make_request "GET" "/categories?search=electronics" \
    "Search categories by name"

make_request "GET" "/categories?include_subcategories=true" \
    "Get categories with sub-categories"

make_request "GET" "/categories?include_subcategories=true&include_child_categories=true" \
    "Get categories with all nested relationships"

make_request "GET" "/categories?paginate=false" \
    "Get all categories without pagination"

make_request "GET" "/categories/1" \
    "Get category by ID (1)"

make_request "GET" "/categories/1?include_subcategories=true&include_child_categories=true" \
    "Get category by ID with relationships"

make_request "GET" "/categories/slug/electronics" \
    "Get category by slug"

make_request "GET" "/categories/hierarchy" \
    "Get full category hierarchy"

make_request "GET" "/categories/hierarchy?status=active" \
    "Get active category hierarchy"

# ========================================
# SUB-CATEGORY ENDPOINTS
# ========================================

print_header "SUB-CATEGORY ENDPOINTS"

make_request "GET" "/subcategories" \
    "Get all sub-categories (paginated)"

make_request "GET" "/subcategories?category_id=1" \
    "Get sub-categories for category ID 1"

make_request "GET" "/subcategories?status=active" \
    "Get active sub-categories only"

make_request "GET" "/subcategories?search=mobile" \
    "Search sub-categories by name"

make_request "GET" "/subcategories?include_category=true" \
    "Get sub-categories with parent category"

make_request "GET" "/subcategories?include_category=true&include_child_categories=true" \
    "Get sub-categories with all relationships"

make_request "GET" "/subcategories/1" \
    "Get sub-category by ID (1)"

make_request "GET" "/subcategories/1?include_category=true&include_child_categories=true" \
    "Get sub-category by ID with relationships"

make_request "GET" "/subcategories/slug/mobile-phones" \
    "Get sub-category by slug"

# ========================================
# CHILD CATEGORY ENDPOINTS
# ========================================

print_header "CHILD CATEGORY ENDPOINTS"

make_request "GET" "/child-categories" \
    "Get all child categories (paginated)"

make_request "GET" "/child-categories?category_id=1" \
    "Get child categories for category ID 1"

make_request "GET" "/child-categories?sub_category_id=1" \
    "Get child categories for sub-category ID 1"

make_request "GET" "/child-categories?status=active" \
    "Get active child categories only"

make_request "GET" "/child-categories?search=smartphone" \
    "Search child categories by name"

make_request "GET" "/child-categories?include_category=true&include_subcategory=true" \
    "Get child categories with parent relationships"

make_request "GET" "/child-categories/1" \
    "Get child category by ID (1)"

make_request "GET" "/child-categories/1?include_category=true&include_subcategory=true" \
    "Get child category by ID with relationships"

make_request "GET" "/child-categories/slug/smartphones" \
    "Get child category by slug"

# ========================================
# SUMMARY
# ========================================

print_header "TESTING COMPLETE"
echo -e "${GREEN}All API endpoints have been tested.${NC}"
echo -e "${YELLOW}Review the output above for any failures.${NC}\n"
