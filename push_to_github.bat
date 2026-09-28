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
:: Auto commit message pakai tanggal & waktu sekarang
for /f "tokens=1-3 delims=/" %%a in ("%DATE%") do set TODAY=%%c-%%b-%%a
for /f "tokens=1-2 delims=:" %%a in ("%TIME: =0%") do set TIMENOW=%%a%%b
set AUTO_MSG=Update %TODAY% %TIMENOW%

set /p COMMIT_MSG=Commit message (Enter untuk pakai: "%AUTO_MSG%"): 
if "%COMMIT_MSG%"=="" set COMMIT_MSG=%AUTO_MSG%

echo.
echo [4/5] Creating commit: "%COMMIT_MSG%"
git commit -m "%COMMIT_MSG%"

echo.
echo [5/5] Pushing to GitHub (main branch)...
git push origin main

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Push gagal! Mencoba pull terlebih dahulu...
    git pull origin main --rebase
    echo.
    echo Retry push...
    git push origin main
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo [ERROR] Push tetap gagal. Cek koneksi atau resolve conflict secara manual.
        pause
        exit /b 1
    )
)

echo.
echo ========================================================
echo   Push Berhasil!
echo   Cek repository Anda di:
echo   https://github.com/Darrellrifqi/klinik-komputer
echo ========================================================
echo.
pause
