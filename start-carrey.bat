@echo off
cd /d "%~dp0"
pm2 start server.js --name "carrey-ai"
pause 