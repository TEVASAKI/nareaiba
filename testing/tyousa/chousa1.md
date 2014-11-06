## 2014/10/21
## PandocでMarkdown からHTML5へ。テンプレート指定で思った通りの書式にするラクをする(?

### 出会いと動機

Qiitaを始めとして、Markdown書式絶好調な世の中ですが  
私の様にいまだにHTMLでページ作ってる時代遅れな人間もいるものでして。  
過去のデータをutf-8化したから次はHTML5化だー  
、、、と、目的を持ちながらも体系的に学ぶ気にもあまりなれず、出来る限りラクして作りたいなーて思っていた日々な訳です。

んで冒頭のMarkdown形式について調べていたら、Haskellで書かれたパーサー **Pandoc** の存在を[sky_y](http://qiita.com/sky_y/items/80bcd0f353ef5b8980ee)さんの投稿で知った訳ですね。  

ええ、勿論飛びつきましたよ私だって健全な男の子ですもの。

### 準備
環境はWindows7 pro 64bit  
環境変数とか色々面倒くさいので、[Chocolatey](https://chocolatey.org/) でHaskellプラットフォームとpandocをば。

#### あると便利なもの
+ Rapid Environment Editor
    + Windows の環境変数いじりに  
      パスがおかしい所とかをすぐに見つけられます  
      `cinst Devbox-RapidEE`

#### Chocolatey のインストール
以下のコードを管理者権限のコマンドプロンプトにペーストして、ベースをインストール。

```bat
@powershell -NoProfile -ExecutionPolicy unrestricted -Command "iex ((new-object net.webclient).DownloadString('https://chocolatey.org/install.ps1'))" && SET PATH=%PATH%;%ALLUSERSPROFILE%\chocolatey\bin
```


#### Haskell Platform(Gkasgow Haskell Compiler(GHC)) のインストール
`choco install xxx` の略である `cinst` を使います。

```bat
cinst HaskellPlatform
```
これで Haskell パッケージ管理コマンド cabal がインストール出来ます。


#### Haskell Platform のアップデートとPandoc のインストール

```haskell
cabal update
cabal install cabal-install
cabal install -fhighlighting pandoc
```

ここは滅茶苦茶時間かかりますが、辛抱強くお待ち下さい。  
(`-fhighlighting` オプションは要らないかもしれないけど)  
コマンドプロンプトで、`pandoc -v` と入力して、バージョン表示されればok.

`cinst pandoc` でもインストール出来ますが、これだとバージョンが1.11.1 になってしまいますので、、。

### テンプレートの作成

以下のコマンドで、html5テンプレートを作成しておきます。

```haskell
pandoc -D html5 > html5_template.html
```

んでそれを元に不要な部分を削って、自分のページで使いたいヘッダとかCSS とか色々指定しつつ  
読みやすいように体裁を整えたのが以下。

```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, user-scalable=yes" name="viewport">
    <link href="../template/markdown-style.css" rel="stylesheet" type="text/css">
    <link href="../../favicon.ico" rel="shortcut icon">
    <meta content="TEVA" name="author">
    $if(date-meta)$
      <meta name="dcterms.date" content="$date-meta$">
    $endif$
    <title>
      $if(title-prefix)$$title-prefix$ - $endif$$pagetitle$
    </title>
  </head>
  <body>
    <style rel="stylesheet" type="text/css">
    $if(quotes)$
    $endif$
    $if(highlighting-css)$
      $highlighting-css$
    $endif$
    </style>
    <!--[if lt IE 9]>
    <script src="http://www.xxx.yyy/js"></script>
    <![endif]-->
    $for(css)$
    <link href="$css$" rel="stylesheet">
    $endfor$
    $if(math)$ 
      $math$
    $endif$
    $for(header-includes)$
      $header-includes$ 
    $endfor$ 
    <!-- LapisAnalyze1 START -->
    <script charset="UTF-8" src="http://www.xxx.yyy/js" type="text/javascript"></script>
    <!-- LapisAnalyze1 END -->
    <!-- LapisAnalyze2 start -->
    <div id="tracker" style="position:absolute;visibility:hidden;">
      <script type="text/javascript">
        sendData();
      </script>
      <noscript>
        <img alt="tracker" height="0" src="http://www.xxx.yyy/php/img" width="0" height="0">
      </noscript>
    </div>
    <!-- LapisAnalyze2 end -->
    $for(include-before)$ 
      $include-before$
    $endfor$
    $if(title)$
      <header>
        <h1 class="title">
          $title$
        </h1>
        $if(subtitle)$
          <h1 class="subtitle">
            $subtitle$
          </h1>
        $endif$
        $for(author)$
          <h2 class="author">
            $author$
          </h2>
        $endfor$
        $if(date)$
          <h3 class="date">
            $date$
          </h3>
        $endif$
      </header>
    $endif$
    $if(toc)$
      <nav id="$idprefix$TOC">
        $toc$
      </nav>
    $endif$
    $body$
    $for(include-after)$
      $include-after$
    $endfor$
  </body>
</html>
```

ページのFavicon とかCSS は直接埋め込んでしまえばちゃんと読みに行ってくれます(・∀・)  
gitHubっぽいCSS をちょいと変更した物を使わせていただいております。  
[ここに置いてあります。](https://gist.github.com/TEVASAKI/9c9da230a6a9f2cda9ce)

### バッチでラクをする。

んで、Vimなりなんなりでmd 形式のマークダウン文章を作った後、
右クリックの送るメニューから一発変換出来るシェルってかバッチを作ります。  

[YGGDRASILL SOFT!!](http://www5d.biglobe.ne.jp/~yggsoft/) で公開されていた、gendoc というバッチを参考にさせていただきました！ありがとうございます。

```bat
@echo off

rem - - - - - - - - - - - - - - - - - - -
rem This script is forked by 'gendoc'.
rem Thanks by YGGDRASILL SOFT!!
rem http://www5d.biglobe.ne.jp/~yggsoft/
rem - - - - - - - - - - - - - - - - - - -

rem BAT option      : ~d          ドライブレター
rem                 : ~p          パス名
rem                 : ~n          ファイル名
rem                 : ~x          拡張子
rem                 : ~t          ファイルの日付と時刻

rem pandoc option   : --template  テンプレート指定。このシェルと同じ場所に置いておく必要がある
rem                 : -f          入力形式指定
rem                 : -o          出力名指定 , 第二引数に入力ファイル名
rem                 : -V          テンプレート内のHaskell 引数指定

rem SendTo "%APPDATA%¥Microsoft¥Windows¥SendTo¥gendoc.lnk"

rem 生成されるHTMLは同じ場所に作られるので、テンプレート内のcssは相対パス指定で。


if exist "%~d1%~p1%~n1%~x1" (
  set F=%~d1%~p1%~n1
) else (
  echo 処理できるファイルがありません - 「%~d1%~p1%~n1%~x1」
  pause
  goto :EOF
)

pandoc --template "%~d0%~p0¥html5_template.html" -f markdown -o "%F%.html" "%F%%~x1"  -V pagetitle="%~n1" -V date-meta="%~t1"
if errorlevel 1 (
  echo pandocの処理に失敗しました
  pause
  goto :EOF
)

rem てゆーか、コメント内に引数の"%~d" とか書いていたら解釈されるんですが、、、
rem これバグじゃねーの
```

んで、これをSendTo "%APPDATA%¥Microsoft¥Windows¥SendTo¥gendoc.lnk" に作成して  
対象のマークダウンファイル上で送ってやれば、同じファイル名のHTMLが作成されます！  
**素晴らしい！！**

### 課題
openSuse13.2 とCentOS6.5 も使っているので、同じような仕組みを作りたいなーと  
シェルが使えるからもう少し簡単だと思いますが、さーどうしましょうかねー。

---