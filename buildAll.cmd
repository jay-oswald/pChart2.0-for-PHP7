ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

CLS
ECHO.
ECHO     浜様様様様様様様様様様様様様様様様様様様様様様様様様様様様様様�
ECHO     �                                                             �
ECHO     �  Processing all examples (this may take a minute or two)    �
ECHO     �                                                             �
ECHO     藩様様様様様様様様様様様様様様様様様様様様様様様様様様様様様様�
ECHO.

php -v 1>NUL 2>NUL
IF NOT %ERRORLEVEL% == 0 GOTO noPHP

FOR /F "tokens=1,2 delims= " %%G IN ('php -v') DO (
 IF %%G==PHP SET PHPVersion=%%H
 )

ECHO     PHP binaries (%PHPVersion%) have been located
ECHO.
ECHO Processing examples : >temp\errors.log

REM SET /P Var="   Progress : "<NUL

FOR %%f IN (examples\example*.*) DO (
   set t=%%f
   if !t:~-3! == php (
     SET /P Var=�<NUL
     ECHO %%f >>temp\errors.log
     php -q "%%f" 2>>&1>>temp\errors.log
    )
)

ECHO.
ECHO.
ECHO     Examples rendered in the "temp\" folder.
ECHO.
GOTO end

:noPHP
ECHO.
ECHO     The PHP binaries can't be found!
ECHO.
ECHO     Examples rendering has been aborded.
:end
PAUSE >NUL