@echo off

rem echo %1 %2 %3
rem pandoc.exe -f markdown --template=../template/html5_template.html %1.md -o %2.html -V pagetitle="%3" -V date-meta="$(date)"

pandoc.exe -V date-meta=$(date) -f markdown --template=../template/html5_template %1.md -o %2.html -V pagetitle="%3"
