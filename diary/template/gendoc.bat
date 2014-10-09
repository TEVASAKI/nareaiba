rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/

rem %~d : ドライブレター
rem %~p : パス名
rem %~n : ファイル名
rem %~x : 拡張子
rem %~t : ファイルの日付と時刻
rem SendTo "%APPDATA%\Microsoft\Windows\SendTo\gendoc.lnk"

rem pandoc option : --template  テンプレート指定
rem               : -f          入力形式指定
rem               : -o          出力形式指定
rem               : -V          テンプレート内の引数指定

rem 実行場所が各ディレクトリからなので、テンプレート内のcssは相対パス指定で。

@echo off

if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
) else (
  echo 処理できるファイルがありません - 「%~d1%~p1%~n1%~x1」
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0\html5_template.html" -f markdown -o "%F%.html" "%F%%~x1"  -V pagetitle="%~n1" -V date-meta="%~t1"
if errorlevel 1 (
  echo pandocの処理に失敗しました
  pause
  goto :EOF
)

