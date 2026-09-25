@echo off
title Push Klinik Komputer to GitHub
color 0A
echo ========================================================
echo   Pushing Klinik Komputer Project to GitHub
echo   Repository: https://github.com/Darrellrifqi/klinik-komputer.git
echo ========================================================
echo.

cd /d "%~dp0"

echo [1/5] Setting remote origin URL...
git remote set-url origin https://github.com/Darrellrifqi/klinik-komputer.git 2>NUL || git remote add origin https://github.com/Darrellrifqi/klinik-komputer.git
git branch -M main

echo.
echo [2/5] Staging all files...
git add .

echo.
echo [3/5] Current status:
git status --short

echo.
set /p COMMIT_MSG=Masukkan commit message (kosongkan untuk default): 
if "%COMMIT_MSG%"=="" set COMMIT_MSG=Update program Klinik Komputer

echo.
echo [4/5] Creating commit: "%COMMIT_MSG%"
git commit -m "%COMMIT_MSG%"

echo.
echo [5/5] Pushing to GitHub (main branch)...
git push origin main

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Push gagal! Coba pull dulu dengan: git pull origin main
    pause
    exit /b 1
)

echo.
echo ========================================================
echo   Proses Push Berhasil Selesai!
echo   Cek repository Anda di GitHub:
echo   https://github.com/Darrellrifqi/klinik-komputer
echo ========================================================
echo.
pause
