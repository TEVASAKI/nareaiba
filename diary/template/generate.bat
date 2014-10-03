@echo off

rem %1 : INfile and OUTfile NAME @ Markdown (*.md) to HTML (*.html)
rem %2 : PageTitle

rem USAGE : PROMPT$  generate.bat %1 %2
rem example : PROMPT$  ../template/generate.bat 20141213 12��13���̓k�R
rem ���s�ꏊ���e�f�B���N�g������Ȃ̂ŁA���΃p�X�w��ŁB

rem �v����ɁA1�y�[�W�ň���P�ʁB

pandoc.exe -f markdown --template=..\template\html5_template.html %1.md -o %1.html -V pagetitle="%2"
