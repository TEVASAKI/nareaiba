@echo off

rem %1 : INfile and OUTfile NAME @ Markdown (*.md) to HTML (*.html)
rem %2 : PageTitle

rem USAGE : PROMPT$  generate.bat %1 %2
rem example : PROMPT$  generate.bat 20141213 12��13���̓k�R

rem �v����ɁA1�y�[�W�ň���P�ʁB

pandoc.exe -f markdown --template=./html5_template %1.md -o %1.html -V pagetitle="%2"
