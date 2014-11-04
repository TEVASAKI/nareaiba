<?

if(phpversion()>="4.1.0"){

  extract($_GET);

  extract($_POST);

  extract($_COOKIE);

  extract($_SERVER);

}

/*****************************************

  *  p++BBS                by ToR

  *    レス欄無しタイプ

  *  http://php.s3.to/

  ****************************************

  * 2001/10/19 v4.00 一から書き直し、ログ保存を一行一スレ→一行一カキコに

  * 2001/10/22 v4.01 レス削除バグ修正、他諸々バグ修正

  * 2002/02/14 v4.03 ログ書き込み変更

  * 2002/05/19 v4.04 管理削除ページングバグ修正

  * 2002/06/28 v4.05 検索ﾓｰﾄﾞにｸﾛｽｻｲﾄｽﾘﾌﾟﾁﾝｸﾞ(by office)

  * 2002/06/30 v4.06 削除時ﾍﾟｰｼﾞ送りバグ修正

  * 2006/03/02 v4.2  PHP_SELF→SCRIPT_NAME、禁止ワードホスト設定

  ****************************************

  * ■レス掲示板です。こんな機能があります

  * ・スキンファイルにより自由なデザインが可能

  * ・レスは一定数以上表示しない（裏に回る）

  * ・レスをすると記事がTopにくるようにも出来る

  * ・過去ログ作成機能

  * ・ユーザー・管理者による削除が可能

  * ・二重投稿・同一IPの連続投稿の防止

  * ・空白投稿・多重改行・外部投稿・GET投稿の防止

  * ・レス記事も削除可能となりました

  * ■準備

  * ・空のログファイルbbs.logを用意してパーミッションを666にします。

  * ・スキンディレクトリを作り、スキンファイルを置きます。

  * ・過去ログ機能を使う場合は過去ログNoファイルbbspast.logを用意して666にします

  * ■スキン内の変数解説

  * [head.txt] TOPフォーム

  * 必須：<input type="hidden" name="mode" value="regi">

  *  <body>タグの後に<!--body-->

  *  $title1 - <title>タグ内文字、$title2 - TOPタイトル、

  *  $c_name,$c_email,$c_url,$c_pwd - クッキーの値（名前、E-Mail、URL、パス）

  *  $colf - 色選択ボタン

  * [main.txt] 親記事

  *  $no - 記事No、$sub - タイトル、$name - 名前、

  *  $url - URLがある時リンク、$color - コメント色、$com - コメント

  * [res.txt] レス記事

  *  $rc - レスカウント、後は上と同じ

  * [resform.txt] レスフォーム

  * 必須：<input type="hidden" name="mode" value="regi">

  *       <input type="hidden" name="res" value="'.$no.'">

  *  $col - 色選択selectのoption部分のみ <select name=color>が必要

  *  クッキー値は上と同じです

  * [foot.txt]

  *  $next - ページ送りリンク（177行目）

  *■各モードの説明

  * ?mode=del    削除モード

  * ?mode=admin  管理モード

  * ?mode=past   過去ログ表示

  * ?mode=search ログ検索

  ******************************************

  * bbs.logは空じゃなく、同梱のファイルを使ってください

  *****************************************/

/* ログファイル名　*/

define("LOGFILE", "bbs.log");



/* スキンファイルの場所 */

define("HEADFILE", "./skin/head.txt");		//ヘッダ

define("MAINFILE", "./skin/main.txt");		//親記事

define("RESFILE",  "./skin/res.txt");		//レス記事

define("MAINEND",  "./skin/mainend.txt");	//親記事閉じ(追加）

define("FORMFILE", "./skin/resform.txt");	//レスフォーム

define("FOOTFILE", "./skin/foot.txt");		//フッタ



/* 管理者用パスワード。必ず変更して下さい。*/

define("ADMINPASS", "*l3pMfxDWGUuJhFr26iLa49etXgYAH");



/* <title>に入れるタイトル（モードにより変化）*/

$title1 = '足跡BBS';

/* 掲示板のTOP部分に入れるタイトル（HTML可,skin直接書き込みも可）*/

$title2 = '<font size=5 face=Verdana color=gray><b>足跡BBS</b></font>';



/* 戻り先（HOME）*/

define("HOME", 'http://www5.pf-x.net/~tevasaki/');

/* 記事URLリンク（家アイコン<img src･･ or HOME等）*/

define("HP", 'HP');



/* 一ページに表示する親記事数 */

define("PAGEVIEW", 7);

/* 最新レス何件表示（これ以上は裏に) */

define("RESVIEW", 8);

/* 最大記録行数(レス含）これを越えると古い物から順に過去ログに移ります*/

define("LOGMAX", 200);		//200で親記事30-40個 60KB程度



/* 親とレスの区切り */

define("KUGIRI", "<hr size=1>");

/* レスリンク（レス） */

define("RESALL", "レス");



/* 色選択部分の記号 */

define("MARK", "■");

/* 色の設定 */

$COLORS =  array('800f65','e50086','db0015','eb7988','ad8c38','e4a20b','ffb74c','28af78','008677','6d9a4a','ececff','504dcb','3100b2','3a2d6b');

$COLNAME = array('暗紅','赤紫','紅色','紅梅','黄土','山吹','橙色','青緑','青竹','鶯色','透明','浅縹','瑠璃','紺青');



/* 自動リンク（yes=1 no=0）*/

define("LINK", 1);

/* レスがついたとき、記事がTopにくるか（yes=1 no=0）*/

define("AGE", 1);

/* タイトルが空の時の題名（空なら入力強制）*/

define("MUDAI", "無題(･x･)");



/* 表示制限文字数（名前、題名を一定の長さに収めます。半角で）*/

define("NAMEVIEW", 30);

define("SUBVIEW", 30);

/* 投稿文字数制限（名前、題名、本文がこれ以上だとエラー。半角で） */

define("MAXNAME", 40);

define("MAXSUB", 40);

define("MAXVALUE", 800);

/* 本文の改行数制限 */

define("MAXLINE", 100);



/* 同一ホストからの連続投稿を制限（0で無制限）                   *

 *--> 秒数を記述するとその時間以上を経過しないと連続投稿できない */

define("RENZOKU", 15);

/* 外部書き込み禁止にする(6/2) */

define("GAIBU", 0);



/* 過去ログ作成する? */

define("PAST_KEY", 1);

/* 過去ログ番号ファイル */

define("PAST_NO", "bbspast.log");

/* 過去ログ作成ディレクトリ(書き込み権限必要) */

define("PAST_DIR", "./");

/* 過去ログ接頭辞index?.html kako?.html */

define("PAST_NAME", "index");

/* 過去ログ一つに書き込む行数 */

define("PAST_LINE", 50);



/* HTMLを書き出すかどうか（yes=1 No=0）*

 *->1の場合クッキー使用不可です        */

define("HTML", 1);

/* 静的HTMLを書き出す場合のHTMLファイル */

define("HTMLFILE", "bbs.html");



/* 禁止ワード、URL */

$dword[] = "死ね";

$dword[] = "氏ね";

$dword[] = "<a href=";

$dword[] = "URL=";

$dword[] = "Nice sit";

$dword[] = "http:";

$dword[] = "ｈｔｔｐ：";

$dword[] = "Good to meet you";

$dword[] = "isn't it a lovely";

$dword[] = "ttp://";



/* 禁止ホスト */

$dhost[] = "virtua.com.br";

$dhost[] = "219.236.42.115";

$dhost[] = "213-0-89-006.rad.tsai.es";

$dhost[] = "ec2-79-125-23-11.eu-west-1.compute.amazonaws.com";

$dhost[] = "joblet.jp";

$dhost[] = "s15392755.onlinehome-server.info";

$dhost[] = "213.238.47.41";

$dhost[] = "adresslabels.de";

$dhost[] = "124.254.137.84";

$dhost[] = "180.149.49.114";

$dhost[] = "ec2-204-236-232-121.compute-1.amazonaws.com";

$dhost[] = "157.108.3.110.ap.yournet.ne.jp";



if(get_magic_quotes_gpc()) $pppbbs = stripslashes($pppbbs);

/* 色選択HTML生成 */

function ColSet(){

  global $pppbbs,$COLORS,$COLNAME;



  // クッキー

  if(!HTML) list($c_n,$c_e,$c_u,$c_p,$c_col) = explode(",", $pppbbs);

  if($c_col == "") $c_col = $COLORS[0];// クッキー無いなら0番目



  for($cc = 0; $cc < count($COLORS); $cc++){

    if($c_col == $COLORS[$cc]){

      $chk = " checked";

      $sel = " selected";

    }else{

      $chk = "";

      $sel = "";

    }

    $colradio.="<input type=radio name=color value=\"$COLORS[$cc]\"$chk>";

    $colradio.="<font color=$COLORS[$cc]>".MARK."</font>\n";

    $colselect.="<option value=\"$COLORS[$cc]\" style=color:$COLORS[$cc]$sel>$COLNAME[$cc]\n";



    if($cc == (count($COLORS)/2-1)) $colradio.='<br>';//折り返し

  }

  // [0]=ラジオボタン$colf [1]=セレクト$col

  return array($colradio,$colselect);

}

function LogOpen($log){

  for($t = 1; $t < count($log); $t++){

    list($oya_no,$res_no,) = explode(",", $log[$t]);

    $thread[$oya_no][$res_no] = $log[$t];

  }

  return $thread;

}

/* メイン表示 */

function MainView(&$dat,$thread,$page=""){

  global $SCRIPT_NAME,$pppbbs,$title1,$title2;



  // クッキーください

  if(!HTML) list($c_name,$c_email,$c_url,$c_pwd,$c_col) = explode(",", $pppbbs);

  // 色選択部分作成

  $colhtml = ColSet();

  $colf = $colhtml[0];	//親記事フォーム

  $col = $colhtml[1]; 	//レスselect部



  include(HEADFILE);	//ヘッダ変換



  $st = ($page) ? $page : 0;			//開始行設定

  for($n = 0; $n < $st; $n++) next($thread);	//配列飛ばす



  for($i = 0; $i < PAGEVIEW; $i++){

    list(,$val) = @each($thread);		//配列を進める

    if($val[0]=="") continue;			//空なら飛ばす

    list($no,$resno,$now,$name,$email,$sub,$com,$url,

         $host,$pw,$color) = explode(",", $val[0]);

    // フォーマット

    if(strlen($name) > NAMEVIEW) $name = substr($name,0,NAMEVIEW) . ".";

    if(strlen($sub) > SUBVIEW)   $sub = substr($sub,0,SUBVIEW) . ".";

    if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">".HP."</a>";

    if($email) $name = "<a href=\"mailto:$email\">$name</a>";

    if(LINK) $com = auto_link($com);



    // レスリンク（MAINの上に）

    $resall = "<a href=\"$SCRIPT_NAME?mode=all&no=$no\">".RESALL."</a>";//（常に表示）



    include(MAINFILE);	//親記事変換



    // レス開始位置

    $rst = count($val) - RESVIEW;

    if($rst <= 0) $rst = 1;

    // 裏に回ったらリンク

    if($rst > 1) $resall = "<a href=\"$SCRIPT_NAME?mode=all&no=$no\">".RESALL."</a>";

    // レスがある時は区切り

    if(count($val) > 1) $dat.= KUGIRI;



    for($r = $rst; $r < count($val); $r++){

      if($val[$r] == 0) continue;

      list($no,$rc,$now,$name,$email,$sub,$com,$url,

           $host,$pw,$color) = explode(",", $val[$r]);

      if($com == "") continue;

      if(LINK) $com = auto_link($com);

      include(RESFILE);	//レス記事変換

    }



    include(MAINEND);	//親終了変換（返信フォーム→親閉じ）



    $oya++;		//親記事カウント

    unset($resall);	//変数開放

  }

  // ページ処理

  $prev     = $st - PAGEVIEW;

  $nextpage = $st + PAGEVIEW;

  if($prev < 0) $prev = 0;	//マイナスにしない

  if($page > 0){		//次へいったら前表示

    $next.= "<a href=$SCRIPT_NAME?page=$prev><< Prev</a> ";

  }

  if($nextpage < count($thread) && $oya >= PAGEVIEW){//親記事表示オーバーなら次表示

    $next.= " <a href=$SCRIPT_NAME?page=$nextpage>Next >></a>";

  }

  include(FOOTFILE);	//フッタ変換

}

/* レス単独表示 */

function ResView($res){

  global $thread,$pppbbs,$SCRIPT_NAME;



  // クッキーください

  if(!HTML) list($c_name,$c_email,$c_url,$c_pwd,$c_col) = explode(",", $pppbbs);

  // 色選択部分作成

  $colhtml = ColSet();

  $colf = $colhtml[0];	//親記事フォーム

  $col = $colhtml[1]; 	//レスselect部



  // ターゲットが無いなら終了

  if(!is_array($thread[$res])) return false;

  // ヘッダ部

  $dat.= ModeHead("NO. $res についてのレス");

  $dat.= "<div align=center><center><b>$title1</b><br>";

  // メイン

  while(list(,$val) = each($thread[$res])){

    list($no,$rc,$now,$name,$email,$sub,$com,$url,

         $host,$pw,$color) = explode(",", $val);

    if($com == "") continue;

    // フォーマット

    if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">".HP."</a>";

    if($email) $name = "<a href=\"mailto:$email\">$name</a>";

    if(LINK) $com = auto_link($com);

    // レスNo 0なら親記事

    if($rc == "0"){

      include(MAINFILE);

    }else{

      if($rc == "1") $dat.= KUGIRI;//一番目は区切り

      include(RESFILE);

    }

  }

  include(FORMFILE);



  include(FOOTFILE);



  echo $dat;	//表示

}

/* 書き込み前の処理 */

function RegCheck($sub,$name,$email,$url,$color,$pwd,$com,$res){

  global $SCRIPT_NAME,$REQUEST_METHOD,$log,$thread,$dword,$dhost;



  if($REQUEST_METHOD != "POST") error("不正な投稿をしないで下さい");

  if(GAIBU && !eregi($SCRIPT_NAME,getenv("HTTP_REFERER"))) error("外部から書き込みできません");



  if(!$res && ($sub=="" || ereg("^( |　)*$",$sub))){

    (MUDAI) ?  $sub=MUDAI : error("題名が書き込まれていません");

  }

  if($name=="" || ereg("^( |　)*$",$name)) error("名前が書き込まれていません");

  if($com=="" || ereg("^( |　|[\r\n])*$",$com)) error("本文が書き込まれていません");



  if(strlen($name) > MAXNAME) error("名前が長すぎますっ！");

  if(strlen($sub) > MAXSUB)   error("タイトルが長すぎますっ！");

  if(strlen($com) > MAXVALUE) error("本文が長すぎますっ！");



  //現在時刻

  $tim = time();

  // ホスト名を取得

  $host = gethostbyaddr(getenv("REMOTE_ADDR"));

    // 禁止ホスト

  foreach($dhost as $deny) {

    if (strstr($host, $deny)) error("このホストからの投稿はできません");

  }

  // 禁止ワード

  foreach($dword as $deny) {

    if (strstr($com, $deny)) error("禁止ワードが含まれています");

    if (strstr($url, $deny)) error("禁止ワードが含まれています");

  }

  //同一ホストからの連続投稿禁止

  list($lno,$lname,$lcom,$ltime,$lhost,) = explode(",", $log[0]);

  if (RENZOKU && $host==$lhost && $tim - $ltime < RENZOKU)

    error("連続投稿はもうしばらく時間を置いてからお願い致します<br><br>|ω･)大体15秒ぐらい。");

  //No取得

  $newno = (isset($res)) ? $res : $lno + 1;

  $maxno = (isset($res)) ? $lno : $lno + 1;

  $resno = (isset($res)) ? count($thread[$res]) : 0;

  //時間のフォーマット

  $now = gmdate("Y/m/d(D) H:i",$tim+9*60*60);

  //url整形

  $url = trim($url);

  $url = ereg_replace("^http://", "", $url);

  //PW暗号化

  $pass = substr(md5($pwd),2,8);

  //テキスト整形

  $sub  = CleanStr($sub);

  $name = CleanStr($name);

  $email= CleanStr($email);

  $url  = CleanStr($url);

  $com  = CleanStr($com);

  // 改行文字の統一。

  $com = str_replace( "\r\n",  "\n", $com);

  $com = str_replace( "\r",  "\n", $com);

  // 行数数える（substr_countの代わり）

  $temp = str_replace("\n", "\n"."a",$com);

  $str_cnt = strlen($temp)-strlen($com);

  if($str_cnt > MAXLINE) error("行数が長すぎますっ！");

  // 連続する空行を一行

  $com = ereg_replace("\n((　| )*\n){3,}","\n",$com);

  $com = nl2br($com);			//改行文字の前に<br>を代入する

  $com = str_replace("\n",  "", $com);	//\nを文字列から消す。

  // 二重投稿チェック

  if($name==$lname && $com==$lcom) error("二重投稿は禁止です<br><br><a href=$SCRIPT_NAME>リロード</a>");



  // クッキー保存

  $cookvalue = implode(",", array($name,$email,$url,$pwd,$color));

  setcookie ("pppbbs", $cookvalue,time()+14*24*3600);  /* 2週間で期限切れ */

  // データフォーマット

  $first = "$maxno,$name,$com,$tim,$host,\n";

  $newdata = "$newno,$resno,$now,$name,$email,$sub,$com,$url,$host,$pass,$color,$tim,\n";



/* ログ書き込み age,sage */



  //ログ行数オーバー処理

  if(count($log) >= LOGMAX){

    //最後列のNo取得

    list($pastno,) = explode(",", $log[count($log)-1]);

    for($c = 0; $c < count($log); $c++){

      list($target,$re,$now) = explode(",", $log[$c]);

      if($target == $pastno){	//最後列のスレは過去ログへ

        if(PAST_KEY && $now!="") PastWrite($log[$c]);

        $log[$c] = "";		//配列から消す

      }

    }

  }



  //最新記事Noと二重投稿用フォーマット（一列目）

  $new[0] = $first;



  if($resno == 0){//親記事

    $new[] = $newdata;		//先頭にデータ追加

    next($log);			//ログの一行目は送る

    while(list(,$val) = each($log)){//残りのデータ追加

      $new[] = $val;

    }

  }else{//レス記事

    $find = FALSE;		//フラグ

    for($i = 1; $i < count($log); $i++){

      list($key,) = explode(",", $log[$i]);

      if($newno == $key){		//ハッケソ！

        $find = TRUE;		//フラグOn

        $new[] = $log[$i];	//そのスレを追加

        list($next,) = explode(",", $log[$i+1]);

        if($next != $key) $new[] = $newdata;//次の記事Noが違うならレス最後尾

      }else{

        //アゲなら別配列に、サゲはそのまま残りのデータ追加

        (AGE) ? $old[] = $log[$i] : $new[] = $log[$i];

      }

    }

    for($o = 0; $o < count($old); $o++){//アゲの時残りのデータ追加

      $new[] = $old[$o];

    }

    if(!$find) error("該当記事が見つかりません");

  }

  //ログ更新

  renewlog($new);



  return true;

}

/* テキスト整形 */

function CleanStr($str){

  $str = trim($str);//先頭と末尾の空白除去

  if (get_magic_quotes_gpc()) {//￥を削除

    $str = stripslashes($str);

  }

  $str = htmlspecialchars($str);//タグっ禁止

  $str = str_replace("&amp;", "&", $str);//特殊文字

  return str_replace(",", "&#44;", $str);//カンマを変換

}

/* 記事削除モード */

function MsgDel($page, $admin=""){

  global $SCRIPT_NAME,$thread;



  echo ModeHead("コメント削除画面");



  echo "[<a href=\"$SCRIPT_NAME\">▲ 戻る</a>][<a href=\"$SCRIPT_NAME?mode=admin\">管理</a>]

        <table width=100%><tr><th bgcolor=#008080><font color=#FFFFFF>コメント削除画面</font>

        </th></tr></table>\n<p><center>

        <form action=\"$SCRIPT_NAME\" method=POST>

        <table border=0 cellpadding=5><td bgcolor=FFFFFF><font size=2>

        ■投稿時に記入した「削除キー」により、記事を削除します。<br>

        ■削除したい記事のチェックボックスにチェックを入れ、下記フォームに「削除キー」を入力してください。<br>

        ■親記事を削除する場合、そのレスメッセージも同時に消滅してしまうことになりますので、ご注意ください。

        </font></td></table><p>";

  if($admin == ADMINPASS){//パスがあってる時はホスト表示とボタン表示

    $head = "<th>ホスト</th>";

    $butt = "<input type=hidden name=pass value=\"$admin\"><input type=hidden name=mode value=\"admin\">";

    echo $butt."<input type=submit value=\"削除する\"><input type=reset value=\"リセット\">";

  }else{

    $butt = "<input type=hidden name=mode value=\"usr_del\">";

    echo $butt."<b>削除キー</b> <input type=text name=del_key size=10>

          <input type=submit value=\"削除する\"><input type=reset value=\"リセット\">";

  }

  echo "<p><table border=0 cellspacing=1><tr bgcolor=AADDCC><th>削除</th><th>No</th>

        <th>題名</th><th nowrap>投稿者</th><th>投稿日</th><th>コメント</th>$head</tr>";



  $st = ($page) ? $page : 0;//開始行設定

  for($n = 0; $n < $st; $n++) next($thread);//配列飛ばす



  for($i = 0; $i < PAGEVIEW; $i++){

    list(,$val) = @each($thread);



    for($r = 0; $r < count($val); $r++){

      if($val[$r]=="") continue;

      list($no,$resno,$now,$name,$email,$sub,$com,$url,

           $host,$pwd,$col,$tim,) = explode(",", $val[$r]);

      if($com == "") continue;

      // フォーマット

      if(strlen($com) > 60) $com = substr($com,0,60) . "...";

      if(strlen($sub) > 60) $sub = substr($sub,0,60) . "...";

      $com = str_replace("<br />", " ", $com);

      if($email) $name = "<a href=\"mailto:$email\">$name</a>";

      if($resno != "0") $subj = "<td colspan=2 align=center><b>$no</b>へのレス$resno</td>";

      else $subj = "<th>$no</th><td>$sub</td>";

      if($admin == ADMINPASS) $user = "<td>$host</td>";

      $bg = ($resno == "0") ? "CCF5DD" : "F5F5F5";



      echo "<tr bgcolor=$bg><th><input type=radio name=del value=\"$tim\"></th>$subj<td nowrap>$name</td><td>$now</td><td>$com</td>$user</tr>\n";

    }

    $oya++;

  }

  $prev = $st - PAGEVIEW;

  $next = $st + PAGEVIEW;

  if($prev < 0) $prev = 0;

  echo "</table></form>\n<table border=0 align=left>\n<tr>";



  if($page > 0){//次へいったら前表示

    echo "<td><form action=\"$SCRIPT_NAME\" method=POST>$butt

          <input type=hidden name=page value=\"$prev\">

          <input type=submit value=\"前の親記事",PAGEVIEW,"件\"></form></td>\n";

  }

  if($next < count($thread) && $oya >= PAGEVIEW){//親記事表示オーバーなら次表示

    echo "<td><form action=\"$SCRIPT_NAME\" method=POST>$butt

          <input type=hidden name=page value=\"$next\">

          <input type=submit value=\"次の親記事",PAGEVIEW,"件\"></form></td>";

  }

  die("</tr></table>\n</body></html>");

}

/* ユーザー削除実行 */

function UsrDel($pass,$target){

  global $log;



  if($target){//target=日付け

    $find = FALSE;

    for($i = 1; $i < count($log); $i++){

      list($no,$resno,$now,,,,,,$host,$pwd,$col,$tim) = explode(",", $log[$i]);

      if($delno == $no) $log[$i] = "";	//親記事の時、レスも削除

      if($target == $tim && substr(md5($pass),2,8) == $pwd){

        $find = TRUE;			//パス的中！

        if($resno == "0"){

          $log[$i] = "";	//親記事の時、同一親No削除

          $delno = $no;

        }else{

          $log[$i] = "$no,$resno,,,,,,,,,,\n";

          break;		//レス記事の場合ループ抜ける

        }

      }

    }

    if(!$find) error("パスワードが間違っているか該当記事が見つかりません");

    else renewlog($log);//ログ更新

  }

  return true;

}

/* 管理者削除ログインと実行 */

function Admin($pass, $target){

  global $SCRIPT_NAME,$log;



  if($pass && $pass != ADMINPASS)  error("パスワードが違います");



  if(trim($pass)==""){//パス空ならログイン画面

    echo ModeHead("管理モ－ド");

    echo "<a href=\"$SCRIPT_NAME\">▲掲示板に戻る</a>

        <table width=100%><tr><th bgcolor=#008080><font color=#FFFFFF>管理モード</font>

        </th></tr></table>\n<p><center><h4>パスワードを入力して下さい</h4>

        <form action=\"$SCRIPT_NAME\" method=POST>

        <input type=hidden name=mode value=\"admin\">

        <input type=password name=pass size=8>

        <input type=submit value=\" 認証 \"></form>\n";

    die("</body></html>");

  }



  if($target){//パスの比較しないで削除

    for($i = 1; $i < count($log); $i++){

      list($no,$resno,$now,,,,,,$host,$pwd,$col,$tim) = explode(",", $log[$i]);

      if($delno == $no) $log[$i] = "";	//親記事の時、レスも削除

      if($target == $tim){

        if($resno == "0"){

          $log[$i] = "";	//親記事の時、同一親No削除

          $delno = $no;

        }else{

          $log[$i] = "$no,$resno,,,,,,,,,,\n";

          break;		//レス記事の場合ループ抜ける

        }

      }

    }

    renewlog($log);//ログ更新

  }

  return true;

}

/* ログ更新 in:配列 */

function renewlog($newarr){

  $fp = fopen(LOGFILE, "w");

  flock($fp, 2);

  fputs($fp, implode('', $newarr));

  fclose($fp);



  if(HTML) MakeHtml();



  return true;

}

/* HTML生成 */

function MakeHtml(){

  $log = file(LOGFILE);

  $newarr = LogOpen($log);

  MainView($buf,$newarr);



  $hp = fopen (HTMLFILE, "w");

  flock($hp,2);

  fputs($hp, $buf);

  fclose($hp);



  return true;

}

/* 過去ログ書き込み */

function PastWrite($data){

  //現在の過去ログNo取得

  $cntarr = @file(PAST_NO);

  $cnt = $cntarr[0];

  //過去ログファイル名

  $pastfile = PAST_DIR.PAST_NAME.$cnt.".html";

  if(file_exists($pastfile)){

    $old = file($pastfile);     //ファイルがあるなら読み込む

    if(count($old) > PAST_LINE){//行数オーバーならカウントアップ

      $cnt++;

      $fp = fopen(PAST_NO, "w");

      fputs($fp, $cnt);

      fclose($fp);

      //ファイル名新しくする

      $pastfile = PAST_DIR.PAST_NAME.$cnt.".html";

      unset($old);

    }

  }

  //データ分解

  list($pno,$pres,$pdate,$pname,$pemail,$psub,

       $pcom,$purl,$pho,$ppw,$pco,$pk) = explode(",", $data);

  //URLにリンク、メールにリンク

  if($purl)   $purl = "<a href=\"http://$purl\" target=\"_blank\">HP</a>";

  if($pemail) $pname = "<a href=\"mailto:$pemail\">$pname</a>";

  //＞の行を色変更

  $pcom = eregi_replace("(^|/>)(&gt;[^<]*)", "\\1<font color=999999>\\2</font>", $pcom);

  if(LINK) $pcom = auto_link($pcom);//オートリンク

  //レスの場合レスNo追加

  if($pres != "0") $pno = "$pno のレス$pres";

  //表示フォーマット（一行一ライン）

  $line = "<hr>[$pno] <font color=\"#009900\"><b>$psub</b></font> Name：<b>$pname</b> <small>Date：$pdate</small> $purl<br><ul>$pcom</ul><!-- $pho -->\n";

  //先頭から現過去ログに追加

  $ps = fopen($pastfile, "w");

  flock($ps, 2);

  fputs($ps, $line);

  if($old) fputs($ps, implode('', $old));

  fclose($ps);



  return true;

}

/* 過去ログ表示 */

function PastView($no){

  global $SCRIPT_NAME;



  $no = htmlspecialchars($no);

  //現在の過去ログNo取得

  $cntarr = @file(PAST_NO);

  $cnt = $cntarr[0];

  if(!$no) $no = $cnt;



  echo ModeHead("■ 過去ログ $no ■");

  echo "<font size=2><a href=\"$SCRIPT_NAME?\">▲掲示板に戻る</a></font><br>

        <center>■ 過去ログ $no ■<P>new← ";

  //ページ処理

  for($i = $cnt; $i > 0; $i--){

    if($no == $i) echo "[ $i ]";

    else echo "[<a href=\"$SCRIPT_NAME?mode=past&pno=$i\">$i</a>]";

  }

  echo " →old</center>",PAST_LINE,"件ずつ表示";

  //ファイル名指定

  $pastfile = PAST_DIR.PAST_NAME.$no.".html";

  if(!file_exists($pastfile)) error("<br>過去ログがみつかりません");

  else include($pastfile);//読み込み

  die("</body></html>");

}

/* 検索モード */

function Search($w, $andor, $target){

  global $SCRIPT_NAME,$log;



  if(get_magic_quotes_gpc()) $w = stripslashes($w); //￥消去



  echo ModeHead("検索モード");

  echo "<a href=\"$SCRIPT_NAME?\">▲掲示板に戻る</a><table width='100%'>

        <tr><th bgcolor=008080>検索モード</th></tr></table>

        <br><p align=center>検索したい単語をスペースで区切って入力してください。<br>

        <form action=\"$PHPSELF\" method=POST>

        <input type=hidden name=mode value=search>

        <input type=text name=w size=30 value=\"".htmlspecialchars($w)."\">

        <select name=\"andor\"><option value=\"or\" selected>OR\n

        <option value=\"and\">AND</select>

        <input type=submit value=\"検索\"><br><br>";



  if(trim($w)!=""){			//前後のスペース除去

    $keys = preg_split("/(　| )+/", $w);//複数語を配列に

    next($log);				//一行目送る

    while(list(,$line) = each($log)){	//ログを走査

      $find = FALSE;			//フラグ

      for($i = 0; $i < count($keys); $i++){

        if($keys[$i]=="") continue;	//空は都バス

        if(stristr($line,$keys[$i])){	//マッチです

          $find = TRUE;			//やった

          $line = str_replace($keys[$i],"<b style='color:green;background-color:#ffff66'>$keys[$i]</b>",$line);

        }elseif($andor == "and"){	//ANDの場合マッチしないなら次のログへ

          $find = FALSE;

          break;

        }

      }

      if($find) $result[] = $line;	//マッチしたログを配列に

    }

    //結果表示

    echo "<div align=left>検索結果".count($result)."件<br>";

    for($c = 0; $c < count($result); $c++){//結果展開

      list($no,$res,$now,$name,$email,$sub,

           $com,$url,$ho,,$pco,) = explode(",", $result[$c]);

      //フォーマット

      if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HP</a>";

      if($email) $name = "<a href=\"mailto:$email\">$name</a>";

      if(LINK) $com = auto_link($com);//オートリンク

      //レスの場合レスNo追加

      $tit = ($res != "0") ? "$no のレス$res" : $no;

      //結果表示

      echo "<hr>[<a href=\"$SCRIPT_NAME?mode=all&no=$no\">$tit</a>]

            <font color=\"#009900\"><b>$sub</b></font>

            Name：<b>$name</b> <small>Date：$now</small> $url<br>

            <ul>$com</ul><!-- $pho --><br>\n";

    }

  }

  die("</body></html>");

}

/* 共通ヘッダ */

function ModeHead($title1){

  include(HEADFILE);

  ereg("^(.*)<!--body-->", $dat, $regs);//先頭から<!--body-->まで

  return $regs[1];//ヘッダを表示

}

/* 自動リンク */

function auto_link($str){

  return eregi_replace("(https?|ftp)(://[[:alnum:]\S\+\$\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$str);

}

/* エラー画面 */

function error($mes){

  echo ModeHead("エラー！！！");

  echo "<br><br><hr size=1><br><br>

        <center><font color=red size=5><b>$mes</b></font></center>

        <br><br><hr size=1>";

  die("</body></html>");

}

/*----------Main---------*/

$log = file(LOGFILE);

$thread = LogOpen($log);



switch($mode){

  case 'all':

    ResView($no);

    break;

  case 'regi':

    RegCheck($sub,$name,$email,$url,$color,$pwd,$com,$res);

    header("Location: http://$HTTP_HOST$SCRIPT_NAME");

    //echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$SCRIPT_NAME?\">";

    break;

  case 'admin':

    Admin($pass,$del);

    $log = file(LOGFILE);

    $thread = LogOpen($log);

    MsgDel($page,$pass);

    break;

  case 'usr_del':

    UsrDel($del_key, $del);

    $log = file(LOGFILE);

    $thread = LogOpen($log);

  case 'del':

    MsgDel($page);

    break;

  case 'past':

    PastView($pno);

    break;

  case 'search':

    Search($w, $andor, $f);

    break;

  default:

    MainView($buf,$thread,$page);

    echo $buf;

}

?>