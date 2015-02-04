#!/usr/bin/perl

#↑Perlのパス　プロバイダーのマニュアルをみて確認


#*************** 設定項目 ***************************

$passwd='qJA5UfQMmzPkTtvXDEyh';				#管理パスワード

$max=30;						#生成するRSSフィードの数
$url='http://www5.pf-x.net/~tevasaki/'; 			#サイトのURL 最後に/をつける
$sitename='Maple馴れ合い場';			#サイトの名前
$sitedesp='MapleStoryでの出会いとかを大事にするのがモットーの馴れ合い場。たまにPC技術提供とか。';    	#サイトの説明

#--------最低限の変更はここまで------------

$script='admin_conf.cgi';	#このスクリプト
$logfile='data.log';	#ログ
$rdf='index.rdf';	#rdf

$kousin=1;			#htmlの更新ページを作る(0:作らない 1:つくる)
$kousinskin='kousinskin.htm';	#htmlで更新を表示する場合のスキン
$kousinhtml='kousin.htm';	#更新を生成するページ
$target="_top";			#リンクのターゲット

$hizuke=1;			#rssに日付を表示するか(0:しない 1:する)

$max_title=50;		#titleの最大文字数
$max_des=200;		#descriptionの最大文字数


#**************** ここまで。*************************

$maxsize=10000;

my(
%FORM,$buff,$c_name,%DUMMY,%COOKIE,$host,$lockflag,$passflag,@delnum,$oya
);


$FORM{'page'}=0;
if($ENV{'REQUEST_METHOD'} eq "POST"){
	if($ENV{'CONTENT_LENGTH'}>$maxsize){
		&error("投稿量が多すぎます。");
	}
	read(STDIN,$buff,$ENV{'CONTENT_LENGTH'});
}
else{
	$buff=$ENV{'QUERY_STRING'};
}
if($buff){
	&decode;
}

sub decode{
	my(@pairs,$pair,$name,$value);
	@pairs=split(/&/,$buff);
	foreach $pair (@pairs){
		($name,$value)=split(/=/,$pair);
		$value=~tr/+/ /;
		$value=~s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
		$value=~s/&/&amp;/g;
		$value=~s/"/&quot;/g;
		$value=~s/'/&apos;/g;
		if($name ne "title" && $name ne "comment"){
			$value=~s/</&lt;/g;
			$value=~s/>/&gt;/g;
		}
		if($name eq "comment"){
			$value=~s/\x0D\x0A/<br \/>/g;
			$value=~s/\x0D/<br \/>/g;
			$value=~s/\x0A/<br \/>/g;

		}
		else{
			$value=~s/\x0D\x0A//g;
			$value=~s/\x0D//g;
			$value=~s/\x0A//g;
		}
		$FORM{$name}=$value;
	}
}


$passflag=0;

if($FORM{'mode'} eq "form"){
	&Form;
}
elsif($FORM{'mode'} eq "regist"){
	&Regist;
}
elsif($FORM{'mode'} eq "del"){
	&Del;
}
elsif($FORM{'mode'} eq "edit"){
	&Form("edit");
}
elsif($FORM{'mode'} eq "editregist"){
	&EditRegist;
}
elsif($FORM{'mode'} eq "position"){
	&position;
}
else{
	&Login;
}

sub checkpass{
if($_[0] ne $passwd){
	&error("パスワードが違います。");
}

}

sub position{
my(
$pos,$idx,$head,@all,$flag,$no,$l,$target,
@newnew,$i,$z,$t_num,@g_new,$q,$u,$lastid,$hd,
);
&checkpass($FORM{'pass'});

$pos=$FORM{'pos'};

if($FORM{'ya'} eq '▲'){
	$pos--;
	if($pos <= 0){
		$pos=1;
	}
}
else{
	$pos++;
	if($pos > $FORM{'all'}){
		$pos=$FORM{'all'};
	}
}


open FILE,$logfile || &error("$logfileが開けません。ファイルを確認してください。");
$hd=<FILE>;
@all=<FILE>;
close FILE;
$idx=0;
foreach $l(@all){
	$flag=0;
	($no)=split(/<>/,$l);
	if($FORM{'no'} eq $no){
		$flag=1;
		$target=$l;
	}
	if(!$flag){
		$newnew[$idx]=$l;
		$idx++;
	}
}

$i=1;
$flag=0;
$z=0;
$idx=0;
foreach $l(@newnew){
	if($pos == $i){
		$flag=1;
		$g_new[$idx]=$target;
		$idx++;
	}
	$g_new[$idx]=$l;
	$idx++;
	$i++;
	$z++;
}
if(!$flag){
	$g_new[$idx]=$target;
}
open FILE,">$logfile" || &error("$logfileに書き込みできません。パーミッションを確認してください。");
print FILE $hd;
print FILE @g_new;
close FILE;

&Form;

}


sub Login{

&header;

print <<EOM;
<body>
<br>
<br>
<br>
<div align=center>
<form method="POST" action="$script">
<input type="hidden" name="mode" value="form">
パスワード<input type="password" name="pass" size=8 value=""><br><br>
<input type="submit" value="ログイン">

</form>
</div>
</body>
</html>
EOM
exit;

}

sub Form{
my(
$result,$mode,$r,$no,$title,$link,$comment,
$midasi,$mon,$year,$day,$hd,$i,$s,$j,$overflag,$flag,$date,
$time,$hour,$sec,$min,$mday,$act,$num

);

$mode=$_[0];
$result=$_[1];

&checkpass($FORM{'pass'});

if($_[0] eq "edit"){
	open FILE,$logfile || &error("$logfileが開けません。ファイルを確認してください。");
	$hd=<FILE>;
	while(<FILE>){
		$flag=0;
		($no,$date,$title,$link,$comment)=split(/<>/,$_);
		if($no == $FORM{'editnum'}){
			$flag=1;
			last;
		}
	}
	close FILE;

	$mode="editregist";
	$comment=~s/<br \/>/\n/g;
	$midasi="編集";
	($year,$mon,$mday,$time)=split(/\./,$date);
	($hour,$min)=split(/:/,$time);

}
else{
	$mode="regist";
	$midasi="新規登録";
	($sec,$min,$hour,$mday,$mon,$year)=localtime(time);
	$mon++;
	$year=$year+1900;
	$mon=sprintf("%02d",$mon);
	$day=sprintf("%02d",$mday);
	$hour=sprintf("%02d",$hour);
	$min=sprintf("%02d",$min);
}

&header;

print <<EOM;
<body>
EOM

if($_[0] eq "edit"){
print <<EOM;
<form action="$script" method="POST">
<input type=hidden name="mode" value="form">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="submit" value="戻る">
</form>
EOM
}
print <<EOM;
<br>
<div align=center>
<br>
RSSフィードの登録
<br>
<form action="$script" method="POST" name="fo" onsubmit="return check()">
<input type=hidden name="mode" value="$mode">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="editnum" value="$FORM{'editnum'}">
<table border="1">
<tr>
<td valign="middle" align="left" width=100>日付</td>
<td valign="middle" align="left">
<input type="text" name="year" size="8" value="$year">年
<input type="text" name="mon" size="4" value="$mon">月
<input type="text" name="day" size="4" value="$mday">日
&nbsp;
<input type="text" name="hour" size="4" value="$hour">時
<input type="text" name="min" size="4" value="$min">分
</td>
</tr>
<tr>
<td valign="middle" align="left" width=100>タイトル</td>
<td valign="middle" align="left"><input type="text" name="title" size="50" value="$title"><br>(10〜30文字くらい)
</td>
</tr>
<tr>
<td valign="middle" align="left">ページ</td>
<td valign="middle" align="left">$url<input type="text" name="link" size="20" value="$link"> (例)sample.html<br>
トップページのときはそのままでOK<br>
同じページがすでに登録されている場合は上書きします。
</td>
</tr>
<tr>
<td valign="middle" align="left">コメント</td>
<td valign="middle" align="left">
<textarea wrap="soft" name="comment" rows="3" cols="50">$comment</textarea><br>(10〜200文字くらい)
</td>
</tr>
</table>
<br>
<input type="submit" value="登録">
</form>
EOM

if($_[0] ne "edit"){

print <<EOM;
現在登録されているRSSフィード
<br>
<table border=1 cellpadding="2" cellspacing="1">
<tr>
<td valign="middle" align="center">順番</td>
<td valign="middle" align="center" width=250><b>タイトル</b></td>
<td valign="middle" align="center" width=160><b>ページ</b></td>
<td valign="middle" align="center">　</td>
<td valign="middle" align="center">　</td>
</tr>
EOM
	unless(open(FILE,"$logfile")){
		&error("$logfileが開けません。");
	}
	$hd=<FILE>;
	$num=0;
	while(<FILE>){
		$num++;
	}
	close FILE;


	unless(open(FILE,"$logfile")){
		&error("$logfileが開けません。");
	}
	$hd=<FILE>;
	
	$i=0;
	while(<FILE>){
		$i++;
		($no,$date,$title,$link)=split(/<>/,$_);
		if($link eq ""){
			$link="&nbsp;";
		}
		if($title){
			$title=~s/<br \/>//g;
			$s="";
			$j=0;
			$overflag=0;
			while($title=~/([\x81-\x9F][\x40-\xFC]|[\xE0-\xEF][\x40-\xFC]|[\x21-\x7E]|[\xA1-\xDF])/go){
				$s.=$1;
				$j++;
				if($j>=10){
					$overflag=1;
					$s.="...";
					last;
				}
			}
			if($overflag){
				$title=$s;
			}
		}

print <<EOM;
<tr>
<td align=center>
<form method="POST" action="$script" style="margin:0px">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="mode" value="position">
<input type="hidden" name="act" value="$act">
<input type="hidden" name="no" value="$no">
<input type="hidden" name="all" value="$num">
<input type="hidden" name="pos" value="$i">
<input type="submit" name="ya" value="▲">
<input type="submit" name="ya" value="▼">
</form>
</td>
<td>$title</td>
<td>$link</td>
<td align=center>
<form method="POST" action="$script" style="margin:0px">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="delnum" value="$no">
<input type="submit" value="削除">
</form>
</td>
<td align=center>
<form method="POST" action="$script" style="margin:0px">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="editnum" value="$no">
<input type="submit" value="編集">
</form>
</td>
</tr>
EOM
}
	close FILE;
print <<EOM;
</table>
<br>
<input type="button" value="確認する" onclick="openwin('$rdf')">
EOM
}

print <<EOM;

</div>
</body>
</html>
</table>
</div>
</body>
</html>
EOM
exit;

}

sub Regist{
my(
$i,$act,@all,
$hd,$num,$title,$link,
$flag,@new,$line,$rest,$d,$date,$no,$idx
);
&checkpass($FORM{'pass'});

if($FORM{'title'} eq ""){
	&error("タイトルが入力されていません。");
}
if($FORM{'comment'} eq ""){
	&error("コメントが入力されていません。");
}

$date="$FORM{'year'}.$FORM{'mon'}.$FORM{'day'}.$FORM{'hour'}:$FORM{'min'}:00";

unless(open(FILE,"$logfile")){
	&error("$logfileが開けません。");
}
$hd=<FILE>;
@new=<FILE>;
close FILE;

($no,$d)=split(/<>/,$hd);

if($no eq ""){
	$no=0;
}
$no++;

$hd="$no<>$date<>\n";

$line="$no<>$date<>$FORM{'title'}<>$FORM{'link'}<>$FORM{'comment'}<>\n";

unshift @new,$line;
if(@new > $max){
	pop @new;
}

unless(open(FILE,">$logfile")){
	&error("$logfileが開けません。");
}
print FILE $hd;
print FILE @new;
close FILE;

&rss(\@new,$hd);
if($kousin){
	&makehtml(\@new,$hd);
}
&Form;

}

sub Del{
my($i,$act,@new,$hd,$flag,$no,$idx);
&checkpass($FORM{'pass'});

unless(open(FILE,"$logfile")){
	&error("$logfileが開けません。");
}
$hd=<FILE>;
while(<FILE>){
	$flag=0;
	($no)=split(/<>/,$_);
	if($no == $FORM{'delnum'}){
		$flag=1;
	}
	if(!$flag){
		$new[$idx]=$_;
		$idx++;
	}
}
close FILE;

unless(open(FILE,">$logfile")){
	&error("$logfileが開けません。");
}
print FILE $hd;
print FILE @new;
close FILE;

&rss(\@new,$hd);
if($kousin){
	&makehtml(\@new,$hd);
}
&Form;

}

sub EditRegist{
my($i,$act,$len,$hd,$flag,$no,@new,$date,$n,$d,$idx);
&checkpass($FORM{'pass'});

if($FORM{'title'} eq ""){
	&error("タイトルが入力されていません。");
}
if($FORM{'comment'} eq ""){
	&error("コメントが入力されていません。");
}
$len=length($FORM{'title'});
if($len > $max_title){
	&error("タイトルが長すぎます。現在$lenバイト");
}

$len=length($FORM{'comment'});
if($len > $max_des){
	&error("コメントが長すぎます。現在$lenバイト");
}

$FORM{'link'}=~s/^$url//;


$date="$FORM{'year'}.$FORM{'mon'}.$FORM{'day'}.$FORM{'hour'}:$FORM{'min'}:00";



unless(open(FILE,"$logfile")){
	&error("$logfileが開けません。");
}

$hd=<FILE>;
$idx=0;
while(<FILE>){
	$flag=0;
	($no)=split(/<>/,$_);
	if($no == $FORM{'editnum'}){
		$_="$no<>$date<>$FORM{'title'}<>$FORM{'link'}<>$FORM{'comment'}<>\n";
	}
	$new[$idx]=$_;
	$idx++;

}
close FILE;

($n,$d)=split(/<>/,$hd);
$hd="$n<>$date<>\n";

unless(open(FILE,">$logfile")){
	&error("$logfileが開けません。");
}
print FILE $hd;
print FILE @new;
close FILE;

&rss(\@new,$hd);
if($kousin){
	&makehtml(\@new);
}
&Form;

}


sub rss{
my($ref,$en,$id,$title,$link,$comment,$no,$kousin_date,$date,$year,$mon,$day,
$hd,$time,
);
$ref=$_[0];
$hd=$_[1];

open FILE,">$rdf" || &error("$rdfが開けません。");

print FILE qq(<?xml version="1.0" encoding="Shift_JIS" ?>\n);
print FILE qq(<rdf:RDF\n);
print FILE qq(  xmlns="http://purl.org/rss/1.0/"\n);
print FILE qq(  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#");
print FILE qq(  xmlns:dc="http://purl.org/dc/elements/1.1/"\n);
print FILE qq(  xmlns:sy="http://purl.org/rss/modules/syndication/"\n);
print FILE qq(  xml:lang="ja">\n);
print FILE qq(<channel rdf:about="${url}">\n);
print FILE qq( <title>$sitename</title>\n);
print FILE qq( <link>$url</link>\n);
print FILE qq( <description>$sitedesp</description>\n);

if($hizuke){
	($no,$kousin_date)=split(/<>/,$hd);
	($year,$mon,$day,$time)=split(/\./,$kousin_date);
	$date="${year}-${mon}-${day}T${time}+09:00";
	print FILE qq(  <dc:date>$date</dc:date>\n);
}

print FILE qq( <items>\n);
print FILE qq(  <rdf:Seq>\n);

foreach(@{$ref}){
	($id,$date,$title,$link)=split(/<>/,$_);
	print FILE qq(   <rdf:li rdf:resource="$url$link"/>\n);
}

print FILE qq(  </rdf:Seq>\n);
print FILE qq( </items>\n);
print FILE qq(</channel>\n);


foreach(@{$ref}){
	($id,$date,$title,$link,$comment)=split(/<>/,$_);
	print FILE qq(<item rdf:about="$url$link">\n);
	print FILE qq( <title>$title</title>\n);
	print FILE qq( <link>$url$link</link>\n);
	print FILE qq( <description>$comment</description>\n);
	if($hizuke){
		($year,$mon,$day,$time)=split(/\./,$date);
		$date="${year}-${mon}-${day}T${time}+09:00";
		print FILE qq(  <dc:date>$date</dc:date>\n);
	}
	print FILE qq(</item>\n);
}
print FILE qq(</rdf:RDF>\n);
close FILE;

}

sub makehtml{
my($ref,$skinline,$head,$dummy,$tr,$footer,
$id,$title,$link,$comment,$tr_d,$date,$year,$mon,$day,$time
);

$ref=$_[0];


unless(open(HTML, $kousinskin)){
	&error("$kousinskinが開けません。ファイルを確認してください。");
}
$skinline=join('',<HTML>);
close(HTML);
($head,$dummy)=split(/<!--start-->/, $skinline);
if($head eq ""){
	&error("スキンに&lt;!--start--&gt;が書かれていません。");
}
($tr,$footer)=split(/<!--end-->/, $dummy);
if($footer eq ""){
	&error("スキンに&lt;!--msgend--&gt;が書かれていません。");
}


if(!open(FILE, ">$kousinhtml")){
	&error("$logfileに書き込みできません。パーミッションを確認してください。");
}
print FILE $head;
foreach(@{$ref}){
	($id,$date,$title,$link,$comment)=split(/<>/,$_);
	$tr_d=$tr;
	if($link && $link ne $url){
		$title="<a href=\"$link\" target=\"$target\">$title</a>";
	}
	$tr_d=~s/<!--title-->/$title/;
	($year,$mon,$day,$time)=split(/\./,$date);
	$date="$year.$mon.$day";
	$tr_d=~s/<!--date-->/$date/;
	print FILE $tr_d;
}
print FILE $footer;

close FILE;

}

sub header{
print "Content-type: text/html\n\n";
print <<EOM;
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title></title>
<style type="text/css">

a{
color:#0000ff
}

</style>
<script type="text/javascript">

function openwin(u){
	window.open(u,"swin","location=no,status=yes,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,top=0,left=0,width=800,height=600");
}

EOM

print 'var max_title='.$max_title.';'."\n";
print 'var max_des='.$max_des.';'."\n";

print <<EOM;

function check(){

var errormsg="";
var len_c=document.fo.comment.value.length;
var len_t=document.fo.title.value.length;
if(len_t > max_title){
	errormsg+="※タイトルは"+max_title+"字以内にしてください。現在"+len_t+"文字";
}
if(len_c > max_des){
	errormsg+="※コメントは"+max_des+"字以内にしてください。現在"+len_c+"文字";
}
if(errormsg){
	alert(errormsg);
	return false;
}

}

</script>
</head>
EOM
}


sub error{

&header;
print <<EOM;
<body>
<br>
<br>
<p align="center"><font color="#ff0000"><b>ERROR</b></font><br><br>
EOM
print"$_[0]\n";
print <<EOM;
</p>

<p align="center"><a href="${script}?">戻る</a></p>
</body>
</html>
EOM
exit;
}
exit;