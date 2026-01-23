@echo off
echo Testing BMV Send OTP API
echo ========================
echo.

REM Test 1: Send OTP with valid phone number
echo Test 1: Sending OTP to phone 1234567890
curl -X POST "http://localhost:8000/api/v1/auth/send-otp" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"phone\":\"1234567890\",\"country_code\":\"+91\"}"

echo.
echo.
echo ========================
echo Test completed!
echo Check the response above
echo.
pause
