rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/

rem %~d : �h���C�u���^�[
rem %~p : �p�X��
rem %~n : �t�@�C����
rem %~x : �g���q
rem %~t : �t�@�C���̓��t�Ǝ���
rem SendTo "%APPDATA%\Microsoft\Windows\SendTo\gendoc.lnk"

rem pandoc option : --template  �e���v���[�g�w��
rem               : -f          ���͌`���w��
rem               : -o          �o�͌`���w��
rem               : -V          �e���v���[�g���̈����w��

rem ���s�ꏊ���e�f�B���N�g������Ȃ̂ŁA�e���v���[�g����css�͑��΃p�X�w��ŁB

@echo off

if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
) else (
  echo �����ł���t�@�C��������܂��� - �u%~d1%~p1%~n1%~x1�v
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0\html5_template.html" -f markdown -o "%F%.html" "%F%%~x1"  -V pagetitle="%~n1" -V date-meta="%~t1"
if errorlevel 1 (
  echo pandoc�̏����Ɏ��s���܂���
  pause
  goto :EOF
)

