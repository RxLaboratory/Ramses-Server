@echo off
setlocal

SET sevenZip="C:\Program Files\7-Zip\7z.exe"

:: The paths
SET src_path=%~dp0..\src
SET dckr_mysql_path=%~dp0..\docker-mysql
SET dckr_sqlite_path=%~dp0..\docker-sqlite
SET dest_path=%~dp0build

:: Clean
rd /s /q "%dest_path%"

:: Create folders
md "%dest_path%"
md "%dest_path%\docker-mysql"
md "%dest_path%\docker-mysql\www"
md "%dest_path%\docker-mysql\www\ramses"
md "%dest_path%\docker-mysql\www\ramses\install"
md "%dest_path%\docker-mysql\www\ramses\config"
md "%dest_path%\docker-sqlite"
md "%dest_path%\docker-sqlite\www"
md "%dest_path%\docker-sqlite\www\ramses"
md "%dest_path%\docker-sqlite\www\ramses\install"
md "%dest_path%\docker-sqlite\www\ramses\config"
md "%dest_path%\www"
md "%dest_path%\www\install"
md "%dest_path%\www\config"

:: MAIN
:: www 
xcopy "%src_path%" "%dest_path%\www\" /y
:: install
xcopy "%src_path%\install" "%dest_path%\www\install" /y
:: config
xcopy "%src_path%\config" "%dest_path%\www\config" /y

:: DOCKER MySQL
:: docker
xcopy "%dckr_mysql_path%" "%dest_path%\docker-mysql" /E /y
:: www
xcopy "%src_path%" "%dest_path%\docker-mysql\www\ramses" /y
:: install
xcopy "%src_path%\install" "%dest_path%\docker-mysql\www\ramses\install" /y
:: config
xcopy "%src_path%\config" "%dest_path%\docker-mysql\www\ramses\config" /y
:: Update config files
call :FindReplace "sqlMode = 'sqlite'" "sqlMode = 'mysql'" "%dest_path%\docker-mysql\www\ramses\config\config.php"
call :FindReplace "forceSSL = true" "forceSSL = false" "%dest_path%\docker-mysql\www\ramses\config\config.php"

:: DOCKER SQLite
:: docker
xcopy "%dckr_sqlite_path%" "%dest_path%\docker-sqlite" /E /y
:: www
xcopy "%src_path%" "%dest_path%\docker-sqlite\www\ramses" /y
:: install
xcopy "%src_path%\install" "%dest_path%\docker-sqlite\www\ramses\install" /y
:: config
xcopy "%src_path%\config" "%dest_path%\docker-sqlite\www\ramses\config" /y
:: Update config files
call :FindReplace "sqlMode = 'mysql'" "sqlMode = 'sqlite'" "%dest_path%\docker-sqlite\www\ramses\config\config.php"
call :FindReplace "forceSSL = true" "forceSSL = false" "%dest_path%\docker-sqlite\www\ramses\config\config.php"

:: Zip
cd "%dest_path%\www"
%sevenZip% a "%dest_path%\ramses-server.zip"
cd "..\docker-mysql"
%sevenZip% a "%dest_path%\ramses-server_docker-mysql.zip"
cd "..\docker-sqlite"
%sevenZip% a "%dest_path%\ramses-server_docker-sqlite.zip"

cd ..
rd /s /q "www"
rd /s /q "docker-mysql"
rd /s /q "docker-sqlite"

exit /b 

:FindReplace <findstr> <replstr> <file>
set tmp="%temp%\tmp.txt"
If not exist %temp%\_.vbs call :MakeReplace
for /f "tokens=*" %%a in ('dir "%3" /s /b /a-d /on') do (
  for /f "usebackq" %%b in (`Findstr /mic:"%~1" "%%a"`) do (
    echo(&Echo Replacing "%~1" with "%~2" in file %%~nxa
    <%%a cscript //nologo %temp%\_.vbs "%~1" "%~2">%tmp%
    if exist %tmp% move /Y %tmp% "%%~dpnxa">nul
  )
)
del %temp%\_.vbs
exit /b

:MakeReplace
>%temp%\_.vbs echo with Wscript
>>%temp%\_.vbs echo set args=.arguments
>>%temp%\_.vbs echo .StdOut.Write _
>>%temp%\_.vbs echo Replace(.StdIn.ReadAll,args(0),args(1),1,-1,1)
>>%temp%\_.vbs echo end with