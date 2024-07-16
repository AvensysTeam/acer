@echo off
setlocal

set "PHP_PATH=C:\xampp\php\php.exe"
set "PROJECT_PATH=C:\xampp\htdocs\acer-avensys-app"
set "BACKUP_PATH=%PROJECT_PATH%\DB"
set "DATE=%DATE:~10,4%-%DATE:~4,2%-%DATE:~7,2%-%TIME:~0,2%-%TIME:~3,2%-%TIME:~6,2%"

if not exist "%BACKUP_PATH%" mkdir "%BACKUP_PATH%"

"%PHP_PATH%" "%PROJECT_PATH%\artisan" database:dump

endlocal
