@echo off

rem - - - - - - - - - - - - - - - - - - -
rem This script is forked by 'gendoc'.
rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/
rem - - - - - - - - - - - - - - - - - - -

rem BAT option    : ~d          �h���C�u���^�[
rem               : ~p          �p�X��
rem               : ~n          �t�@�C����
rem               : ~x          �g���q
rem               : ~t          �t�@�C���̓��t�Ǝ���

rem pandoc option : --template  �e���v���[�g�w��B���̃V�F���Ɠ����ꏊ�ɒu���Ă����K�v������
rem               : -f          ���͌`���w��
rem               : -o          �o�͖��w�� , �������ɓ��̓t�@�C����
rem               : -V          �e���v���[�g����Haskell �����w��

rem SendTo "%APPDATA%\Microsoft\Windows\SendTo\gendoc.lnk"

rem ���������HTML�͓����ꏊ�ɍ����̂ŁAcss�͑��΃p�X�w��ŁB


if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
) else (
  echo �����ł���t�@�C��������܂��� - �u%~d1%~p1%~n1%~x1�v
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0\html5_template.html" -c "../template/markdown-style.css" -f markdown_github+footnotes+definition_lists+pandoc_title_block+header_attributes -o "%F%.html" "%F%%~x1"  -V pagetitle="%~n1" -V date-meta="%~t1"
if errorlevel 1 (
  echo pandoc�̏����Ɏ��s���܂���
  pause
  goto :EOF
)

rem �Ă�[���A�R�����g���Ɉ�����"%~d" �Ƃ������Ă�������߂�����ł����A�A�A
rem ����o�O����ˁ[��
