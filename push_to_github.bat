@echo off
title Push Klinik Komputer to GitHub
echo ========================================================
echo   Pushing Klinik Komputer Project to GitHub
echo   Repository: https://github.com/Darrellrifqi/klinik-komputer.git
echo ========================================================
echo.

cd /d "%~dp0"

echo [1/4] Setting remote origin URL...
git remote set-url origin https://github.com/Darrellrifqi/klinik-komputer.git 2>NUL || git remote add origin https://github.com/Darrellrifqi/klinik-komputer.git
git branch -M main

echo.
echo [2/4] Staging all files...
git add .

echo.
echo [3/4] Creating commit...
git commit -m "Update program Klinik Komputer"

echo.
echo [4/4] Pushing to GitHub (main branch)...
git push -u origin main

echo.
echo ========================================================
echo   Process completed! Check your repository on GitHub:
echo   https://github.com/Darrellrifqi/klinik-komputer
echo.
pause

