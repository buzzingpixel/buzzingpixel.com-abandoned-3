@echo off

set cmd=%1
set allArgs=%*
for /f "tokens=1,* delims= " %%a in ("%*") do set allArgsExceptFirst=%%b
set secondArg=%2
set valid=false

if "%cmd%" == "" (
    set valid=true
    echo The following commands are available:
    echo   .\dev up
    echo   .\dev run
    echo   .\dev down
    echo   .\dev test
    echo   .\dev phpstan [args]
    echo   .\dev phpunit [args]
    echo   .\dev psalm [args]
    echo   .\dev yarn [args]
    echo   .\dev cli [args]
    echo   .\dev composer [args]
    echo   .\dev login [args]
)

if "%cmd%" == "up" (
    set valid=true
    call :up
)

if "%cmd%" == "run" (
    set valid=true
    call :up
    docker exec -it --user root --workdir /app node-buzzingpixel bash -c "yarn run fab"
)

if "%cmd%" == "down" (
    set valid=true
    docker-compose -f docker-compose.yml -p buzzingpixel down
)

if "%cmd%" == "test" (
    set valid=true
    echo Running psalm...
    call :psalm
    echo Running phpstan...
    call :phpstan
    echo Running phpunit...
    call :phpunit
)

if "%cmd%" == "psalm" (
    set valid=true
    call :psalm
)

if "%cmd%" == "phpstan" (
    set valid=true
    call :phpstan
)

if "%cmd%" == "phpunit" (
    set valid=true
    call :phpunit
)

if "%cmd%" == "yarn" (
    set valid=true
    docker kill node-buzzingpixel
    docker-compose -f docker-compose.yml -p buzzingpixel up -d
    docker exec -it --user root --workdir /app node-buzzingpixel bash -c "%allArgs%"
)

if "%cmd%" == "cli" (
    set valid=true
    docker exec -it --user root --workdir /app-www php-buzzingpixel bash -c "php %allArgs%"
)

if "%cmd%" == "composer" (
    set valid=true
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "%allArgs%"
)

if "%cmd%" == "login" (
    set valid=true
    docker exec -it --user root %secondArg%-buzzingpixel bash
)

if not "%valid%" == "true" (
    echo Specified command not found
    exit /b 1
)

exit /b 0

:: Up function
:up
    docker-compose -f docker-compose.yml -p buzzingpixel up -d
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "cd /app && composer install"
    docker exec -it --user root --workdir /app node-buzzingpixel bash -c "yarn"

    if not "%cmd%" == "run" (
        docker exec -it --user root --workdir /app node-buzzingpixel bash -c "yarn run fab --build-only"
    )

    cd platform
    call yarn
    cd ..
exit /b 0

:: psalm function
:psalm
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "chmod +x /app/vendor/bin/psalm && /app/vendor/bin/psalm %allArgsExceptFirst%"
exit /b 0

:: phpstan function
:phpstan
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "chmod +x /app/vendor/bin/phpstan && /app/vendor/bin/phpstan analyse src %allArgsExceptFirst%"
exit /b 0

:: phpunit function
:phpunit
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "chmod +x /app/vendor/bin/phpunit && /app/vendor/bin/phpunit --configuration /app/phpunit.xml %allArgsExceptFirst%"
exit /b 0
