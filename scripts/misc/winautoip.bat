@echo off
rem Set primary and alternate DNS for IPv4.
setlocal
set "SITEID=020"

rem The next line removes leading zeros to define the third octet of the
rem IPv4 address always correct. There are implementations on IP address
rem parsing routines on Windows (for example command ping) and Linux which
rem interpret integer numbers with a leading 0 as octal number and not as
rem decimal number according to C standard. The number 020 would be set
rem unexpected as decimal number 16 instead of 20.
for /F "tokens=* delims=0" %%A in ("%SITEID%") do set DROPZERO=%%A

rem Get first ethernet adapter name output by command IPCONFIG filtered
rem by command FINDSTR with a case-insensitive regular expression search.
rem "Ethernet adapter " is output left to the name of the adapter on English
rem Windows. But on German Windows "Ethernetadapter" is the right word. This
rem is the reason for the regular expression with an optional space between
rem "ethernet" and "adpater".
set "AdapterName="
for /F "tokens=* delims=:" %%A in ('%SystemRoot%\System32\ipconfig.exe ^| %SystemRoot%\System32\findstr.exe /I /R "ethernet.*adapter"') do (
    set "AdapterName=%%A"
    goto SetAddress
)
rem No ethernet adapter found, exit batch job.
endlocal
goto :EOF

:SetAddress
rem Remove everything from beginning of string to first occurrence of
rem the string "adapter " to remove "Ethernet adapter " respectively
rem on German Windows "Ethernetadapter".
set "AdapterName=%AdapterName:*adapter =%"

rem Remove the colon from the end of the adapter name.
set "AdapterName=%AdapterName:~0,-1%"

echo Ethernet adapter found: %AdapterName%
echo Setting static IP information ...
set IpAddress=%1
set DefaultGate=%2
set SubNetMask=%3
set Dns=%4
%SystemRoot%\System32\netsh.exe interface ip set address "%adapterName%" static %IpAddress% %SubNetMask% %DefaultGate% ^1>output_net.txt
%SystemRoot%\System32\netsh.exe interface ip set dns name="%adapterName%" static %Dns% primary>>output_net.txt
endlocal
