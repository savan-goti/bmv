#!/bin/bash

# Customer Info API Test Script
# This script helps you test the Customer Info API endpoints

# Configuration
BASE_URL="http://localhost/bmv/api/v1"
JWT_TOKEN=""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "========================================="
echo "Customer Info API Test Script"
echo "========================================="
echo ""

# Function to test endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local description=$3
    local data=$4
    
    echo -e "${YELLOW}Testing: $description${NC}"
    echo "Endpoint: $method $endpoint"
    
    if [ -z "$JWT_TOKEN" ]; then
        echo -e "${RED}Error: JWT_TOKEN not set. Please login first.${NC}"
        echo ""
        return
    fi
    
    if [ -z "$data" ]; then
        response=$(curl -s -X $method "$BASE_URL$endpoint" \
            -H "Authorization: Bearer $JWT_TOKEN" \
            -H "Accept: application/json")
    else
        response=$(curl -s -X $method "$BASE_URL$endpoint" \
            -H "Authorization: Bearer $JWT_TOKEN" \
            -H "Content-Type: application/json" \
            -H "Accept: application/json" \
            -d "$data")
    fi
    
    echo "Response:"
    echo "$response" | python -m json.tool 2>/dev/null || echo "$response"
    echo ""
}

# Check if user wants to login first
echo "Do you want to login to get JWT token? (y/n)"
read -r login_choice

if [ "$login_choice" = "y" ]; then
    echo "Enter email:"
    read -r email
    echo "Enter password:"
    read -rs password
    
    echo ""
    echo -e "${YELLOW}Logging in...${NC}"
    
    login_response=$(curl -s -X POST "$BASE_URL/auth/login" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d "{\"email\":\"$email\",\"password\":\"$password\"}")
    
    echo "Login Response:"
    echo "$login_response" | python -m json.tool 2>/dev/null || echo "$login_response"
    echo ""
    
    # Extract token (requires jq or manual input)
    if command -v jq &> /dev/null; then
        JWT_TOKEN=$(echo "$login_response" | jq -r '.data.access_token')
        echo -e "${GREEN}Token extracted successfully!${NC}"
    else
        echo -e "${YELLOW}Please enter the JWT token manually:${NC}"
        read -r JWT_TOKEN
    fi
    echo ""
else
    echo "Please enter your JWT token:"
    read -r JWT_TOKEN
    echo ""
fi

# Main menu
while true; do
    echo "========================================="
    echo "Select an endpoint to test:"
    echo "========================================="
    echo "1. Get Profile"
    echo "2. Update Profile"
    echo "3. Update Password"
    echo "4. Update Location"
    echo "5. Update Social Links"
    echo "6. Get Customer by ID"
    echo "7. Get Customer by Username"
    echo "8. Health Check (no auth)"
    echo "9. Exit"
    echo ""
    echo "Enter your choice (1-9):"
    read -r choice
    
    case $choice in
        1)
            test_endpoint "GET" "/customer/profile" "Get Customer Profile"
            ;;
        2)
            echo "Enter name:"
            read -r name
            echo "Enter email:"
            read -r email
            echo "Enter phone:"
            read -r phone
            
            data="{\"name\":\"$name\",\"email\":\"$email\",\"phone\":\"$phone\"}"
            test_endpoint "PUT" "/customer/profile" "Update Customer Profile" "$data"
            ;;
        3)
            echo "Enter current password:"
            read -rs current_password
            echo ""
            echo "Enter new password:"
            read -rs new_password
            echo ""
            
            data="{\"current_password\":\"$current_password\",\"new_password\":\"$new_password\",\"new_password_confirmation\":\"$new_password\"}"
            test_endpoint "PUT" "/customer/password" "Update Password" "$data"
            ;;
        4)
            echo "Enter latitude:"
            read -r latitude
            echo "Enter longitude:"
            read -r longitude
            echo "Enter address:"
            read -r address
            echo "Enter city:"
            read -r city
            
            data="{\"latitude\":$latitude,\"longitude\":$longitude,\"address\":\"$address\",\"city\":\"$city\"}"
            test_endpoint "PUT" "/customer/location" "Update Location" "$data"
            ;;
        5)
            echo "Enter Facebook URL (or press Enter to skip):"
            read -r facebook
            echo "Enter Instagram URL (or press Enter to skip):"
            read -r instagram
            
            data="{\"facebook\":\"$facebook\",\"instagram\":\"$instagram\"}"
            test_endpoint "PUT" "/customer/social-links" "Update Social Links" "$data"
            ;;
        6)
            echo "Enter customer ID:"
            read -r customer_id
            test_endpoint "GET" "/customers/$customer_id" "Get Customer by ID"
            ;;
        7)
            echo "Enter username:"
            read -r username
            test_endpoint "GET" "/customers/username/$username" "Get Customer by Username"
            ;;
        8)
            echo -e "${YELLOW}Testing: Health Check${NC}"
            echo "Endpoint: GET /health"
            response=$(curl -s -X GET "$BASE_URL/../health" -H "Accept: application/json")
            echo "Response:"
            echo "$response" | python -m json.tool 2>/dev/null || echo "$response"
            echo ""
            ;;
        9)
            echo "Exiting..."
            exit 0
            ;;
        *)
            echo -e "${RED}Invalid choice. Please try again.${NC}"
            echo ""
            ;;
    esac
done
