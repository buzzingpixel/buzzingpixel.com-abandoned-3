@echo off

set cmd=%1
set allArgs=%*
for /f "tokens=1,* delims= " %%a in ("%*") do set allArgsExceptFirst=%%b
set secondArg=%2
set valid=false

:: If no command provided, list commands
if "%cmd%" == "" (
    set valid=true
    echo The following commands are available:
    echo   .\dev up
    echo   .\dev run
    echo   .\dev down
    echo   .\dev phpunit [args]
    echo   .\dev yarn [args]
    echo   .\dev cli [args]
    echo   .\dev composer [args]
    echo   .\dev login [args]
)

:: If command is up or run, we need to run the docker containers and install composer and yarn dependencies
if "%cmd%" == "up" (
    set valid=true
    call :up
)

:: If the command is run, then we want to run the build process and watch for changes
if "%cmd%" == "run" (
    set valid=true
    call :up
    docker exec -it --user root --workdir /app node-buzzingpixel bash -c "yarn run fab"
)

:: If the command is down, then we want to stop docker
if "%cmd%" == "down" (
    set valid=true
    docker-compose -f docker-compose.yml -p buzzingpixel down
)

:: Run phpunit if requested
if "%cmd%" == "phpunit" (
    set valid=true
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "chmod +x /app/vendor/bin/phpunit && /app/vendor/bin/phpunit --configuration /app/phpunit.xml %allArgsExceptFirst%"
)

:: Run yarn if requested
if "%cmd%" == "yarn" (
    set valid=true
    docker kill node-buzzingpixel
    docker-compose -f docker-compose.yml -p buzzingpixel up -d
    docker exec -it --user root --workdir /app node-buzzingpixel bash -c "%allArgs%"
)

:: Run cli if requested
if "%cmd%" == "cli" (
    set valid=true
    docker exec -it --user root --workdir /app-www php-buzzingpixel bash -c "php %allArgs%"
)

:: Run composer if requested
if "%cmd%" == "composer" (
    set valid=true
    docker exec -it --user root --workdir /app php-buzzingpixel bash -c "%allArgs%"
)

:: Login to a container if requested
if "%cmd%" == "login" (
    set valid=true
    docker exec -it --user root %secondArg%-buzzingpixel bash
)

:: If there was no valid command found, warn user
if not "%valid%" == "true" (
    echo Specified command not found
    exit /b 1
)

:: Exit with no error
exit /b 0

:: Up function
:up
    type nul >> .env.override
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
