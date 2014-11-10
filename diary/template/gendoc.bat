@echo off

rem - - - - - - - - - - - - - - - - - - -
rem This script is forked by 'gendoc'.
rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/
rem - - - - - - - - - - - - - - - - - - -

rem BAT option
rem               : ~d          ドライブレター
rem               : ~p          パス名
rem               : ~n          ファイル名
rem               : ~x          拡張子
rem               : ~t          ファイルの日付と時刻

rem pandoc option
rem               : --template  テンプレート指定。このシェルと同じ場所に置いておく必要がある
rem               : -c          CSS の指定(Pandocの好きな様に叩き込んでくれ)
rem               : -f          入力形式指定
rem               : -o          出力名指定 , 第二引数に入力ファイル名
rem               : -S          Typography的に正しく出力する(smartMode)
rem               : -V          テンプレート内の各種Haskell 引数指定

rem SendTo "%APPDATA%\Microsoft\Windows\SendTo\gendoc.lnk"

rem 生成されるHTMLは同じ場所に作られるので、cssはmdから見た相対パス指定で。
rem あと、ファイル名からH1タイトルを加工して生成するようにした


if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
  set R=%~n1
) else (
  echo 処理できるファイルがありません - 「%~d1%~p1%~n1%~x1」
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0\html5_template.html" -c "../template/markdown-style.css" -f markdown_github+footnotes+definition_lists+pandoc_title_block+header_attributes -o "%F%.html" "%F%%~x1" -S -V pagetitle="%~n1" -V date-meta="%~t1" -V title="%R:~0,4%/%R:~4,2%/%R:~6,2%"
if errorlevel 1 (
  echo pandocの処理に失敗しました
  pause
  goto :EOF
)

rem てゆーか、コメント内に引数の"%~d" とか書いていたら解釈されるんですが、、、
rem これバグじゃねーの
