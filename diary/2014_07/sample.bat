@echo off

rem pandoc��HTML�o��
rem USAGE : THIS.bat INFILE OUTFILE PAGETITLE

pandoc.exe -f markdown --template=../template/html5_template.html \
                                  %1.md \
                                  -o %2.html \
                                  -V date-meta="$(date)" \
                                  -V pagetitle="%3"

                                  pause

