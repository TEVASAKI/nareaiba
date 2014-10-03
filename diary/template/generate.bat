@echo off

rem %1 : INfile and OUTfile NAME @ Markdown (*.md) to HTML (*.html)
rem %2 : PageTitle

rem USAGE : PROMPT$  generate.bat %1 %2
rem example : PROMPT$  generate.bat 20141213 12月13日の徒然

rem 要するに、1ページで一日単位。

pandoc.exe -f markdown --template=./html5_template %1.md -o %1.html -V pagetitle="%2"
