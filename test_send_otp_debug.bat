@echo off
echo Testing BMV Send OTP API
echo ========================
echo.

echo Sending OTP request...
curl -X POST "http://localhost:8000/api/v1/auth/send-otp" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"phone\":\"1234567890\",\"country_code\":\"+91\"}" ^
  -o response.json -w "\nHTTP Status: %%{http_code}\n"

echo.
echo Response saved to response.json
echo.
echo Response content:
type response.json
echo.
echo.
pause
