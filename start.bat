@echo off
echo Starting Lead Management System...
echo.

cd /d C:\laragon\www\lead-management-system

echo 1. Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo 2. Starting development server...
php artisan serve --host=127.0.0.1 --port=8001

echo.
echo If server doesn't start, press Ctrl+C and try:
echo php artisan serve --port=8002
pause