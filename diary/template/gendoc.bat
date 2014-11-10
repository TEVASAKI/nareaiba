@echo off

rem - - - - - - - - - - - - - - - - - - -
rem This script is forked by 'gendoc'.
rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/
rem - - - - - - - - - - - - - - - - - - -

rem BAT option
rem               : ~d          �h���C�u���^�[
rem               : ~p          �p�X��
rem               : ~n          �t�@�C����
rem               : ~x          �g���q
rem               : ~t          �t�@�C���̓��t�Ǝ���

rem pandoc option
rem               : --template  �e���v���[�g�w��B���̃V�F���Ɠ����ꏊ�ɒu���Ă����K�v������
rem               : -c          CSS �̎w��(Pandoc�̍D���ȗl�ɒ@������ł���)
rem               : -f          ���͌`���w��
rem               : -o          �o�͖��w�� , �������ɓ��̓t�@�C����
rem               : -S          Typography�I�ɐ������o�͂���(smartMode)
rem               : -V          �e���v���[�g���̊e��Haskell �����w��

rem SendTo "%APPDATA%\Microsoft\Windows\SendTo\gendoc.lnk"

rem ���������HTML�͓����ꏊ�ɍ����̂ŁAcss��md���猩�����΃p�X�w��ŁB
rem ���ƁA�t�@�C��������H1�^�C�g�������H���Đ�������悤�ɂ���


if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
  set R=%~n1
) else (
  echo �����ł���t�@�C��������܂��� - �u%~d1%~p1%~n1%~x1�v
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0\html5_template.html" -c "../template/markdown-style.css" -f markdown_github+footnotes+definition_lists+pandoc_title_block+header_attributes -o "%F%.html" "%F%%~x1" -S -V pagetitle="%~n1" -V date-meta="%~t1" -V title="%R:~0,4%/%R:~4,2%/%R:~6,2%"
if errorlevel 1 (
  echo pandoc�̏����Ɏ��s���܂���
  pause
  goto :EOF
)

rem �Ă�[���A�R�����g���Ɉ�����"%~d" �Ƃ������Ă�������߂�����ł����A�A�A
rem ����o�O����ˁ[��
