::	CMD | OTUS HLA | ANSI | ramdisk.cmd
::	Home work
::	eXellenz (eXellenz@inbox.ru)
::	2024-03-05

@echo off

title imdisk RAM disk init

chcp 1251
cls

set _sysdrive=C:
set _ramdrive=R:
set _curdir=%~dp0
set _curdrive=%~d0
set _sqlite=%_sysdrive%\APP\SQLite\backup
set _log=%_sysdrive%\APP\ramdisk.log

%_sysdrive%
cd "%windir%\SYSTEM32"

:imdisk
echo Create RAM disk...
echo Create RAM disk > %_log%
imdisk.exe -a -m "%_ramdrive%" -o awe -s 64M >> %_log%
IF %ERRORLEVEL% NEQ 0 (
echo imdisk.exe error level is %ERRORLEVEL% >> %_log%
echo Create RAM disk FAILED!
goto :imdisk
)

:format
echo Format RAM disk...
echo Format RAM disk >> %_log%
format.com %_ramdrive% /fs:NTFS /v:ramdisk /q /y >> %_log%
IF %ERRORLEVEL% NEQ 0 (
echo format.com error level is %ERRORLEVEL% >> %_log%
echo Format RAM disk FAILED!
goto :format
)

echo Create temporary directories >> %_log%
md %_ramdrive%\sqlite\db >> %_log%

:xcopy
echo Copy temporary data...
echo Copy temporary data >> %_log%
md "%_ramdrive%\sqlite"
xcopy.exe "%_sqlite%\*" "%_ramdrive%\sqlite" /e /c /q /h /k /y /x /b >> %_log%
IF %ERRORLEVEL% NEQ 0 (
echo xcopy.exe error level is %ERRORLEVEL% >> %_log%
echo Copy temporary data FAILED!
goto :xcopy
)

:exit
%_curdrive%
cd "%_curdir%"
exit /b