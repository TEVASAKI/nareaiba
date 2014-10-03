@echo off

rem %1 : INfile and OUTfile NAME @ Markdown (*.md) to HTML (*.html)
rem %2 : PageTitle

rem USAGE : PROMPT$  generate.bat %1 %2
rem example : PROMPT$  ../template/generate.bat 20141213 12月13日の徒然
rem 実行場所が各ディレクトリからなので、相対パス指定で。

rem 要するに、1ページで一日単位。

pandoc.exe -f markdown --template=..\template\html5_template.html %1.md -o %1.html -V pagetitle="%2"
