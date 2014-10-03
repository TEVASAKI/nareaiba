import System.Cmd
main = system pandoc.exe -V date-meta=$(date) -f markdown --template=../template/html5_template 201407.md -o 20140702.html -V pagetitle="10月の徒然"

