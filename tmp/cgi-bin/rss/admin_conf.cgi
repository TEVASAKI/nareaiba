#!/usr/bin/perl

#��Perl�̃p�X�@�v���o�C�_�[�̃}�j���A�����݂Ċm�F


#*************** �ݒ荀�� ***************************

$passwd='qJA5UfQMmzPkTtvXDEyh';				#�Ǘ��p�X���[�h

$max=30;						#��������RSS�t�B�[�h�̐�
$url='http://www5.pf-x.net/~tevasaki/'; 			#�T�C�g��URL �Ō��/������
$sitename='Maple��ꍇ����';			#�T�C�g�̖��O
$sitedesp='MapleStory�ł̏o��Ƃ���厖�ɂ���̂����b�g�[�̓�ꍇ����B���܂�PC�Z�p�񋟂Ƃ��B';    	#�T�C�g�̐���

#--------�Œ���̕ύX�͂����܂�------------

$script='admin_conf.cgi';	#���̃X�N���v�g
$logfile='data.log';	#���O
$rdf='index.rdf';	#rdf

$kousin=1;			#html�̍X�V�y�[�W�����(0:���Ȃ� 1:����)
$kousinskin='kousinskin.htm';	#html�ōX�V��\������ꍇ�̃X�L��
$kousinhtml='kousin.htm';	#�X�V�𐶐�����y�[�W
$target="_top";			#�����N�̃^�[�Q�b�g

$hizuke=1;			#rss�ɓ��t��\�����邩(0:���Ȃ� 1:����)

$max_title=50;		#title�̍ő啶����
$max_des=200;		#description�̍ő啶����


#**************** �����܂ŁB*************************

$maxsize=10000;

my(
%FORM,$buff,$c_name,%DUMMY,%COOKIE,$host,$lockflag,$passflag,@delnum,$oya
);


$FORM{'page'}=0;
if($ENV{'REQUEST_METHOD'} eq "POST"){
	if($ENV{'CONTENT_LENGTH'}>$maxsize){
		&error("���e�ʂ��������܂��B");
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
	&error("�p�X���[�h���Ⴂ�܂��B");
}

}

sub position{
my(
$pos,$idx,$head,@all,$flag,$no,$l,$target,
@newnew,$i,$z,$t_num,@g_new,$q,$u,$lastid,$hd,
);
&checkpass($FORM{'pass'});

$pos=$FORM{'pos'};

if($FORM{'ya'} eq '��'){
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


open FILE,$logfile || &error("$logfile���J���܂���B�t�@�C�����m�F���Ă��������B");
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
open FILE,">$logfile" || &error("$logfile�ɏ������݂ł��܂���B�p�[�~�b�V�������m�F���Ă��������B");
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
�p�X���[�h<input type="password" name="pass" size=8 value=""><br><br>
<input type="submit" value="���O�C��">

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
	open FILE,$logfile || &error("$logfile���J���܂���B�t�@�C�����m�F���Ă��������B");
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
	$midasi="�ҏW";
	($year,$mon,$mday,$time)=split(/\./,$date);
	($hour,$min)=split(/:/,$time);

}
else{
	$mode="regist";
	$midasi="�V�K�o�^";
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
<input type="submit" value="�߂�">
</form>
EOM
}
print <<EOM;
<br>
<div align=center>
<br>
RSS�t�B�[�h�̓o�^
<br>
<form action="$script" method="POST" name="fo" onsubmit="return check()">
<input type=hidden name="mode" value="$mode">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="editnum" value="$FORM{'editnum'}">
<table border="1">
<tr>
<td valign="middle" align="left" width=100>���t</td>
<td valign="middle" align="left">
<input type="text" name="year" size="8" value="$year">�N
<input type="text" name="mon" size="4" value="$mon">��
<input type="text" name="day" size="4" value="$mday">��
&nbsp;
<input type="text" name="hour" size="4" value="$hour">��
<input type="text" name="min" size="4" value="$min">��
</td>
</tr>
<tr>
<td valign="middle" align="left" width=100>�^�C�g��</td>
<td valign="middle" align="left"><input type="text" name="title" size="50" value="$title"><br>(10�`30�������炢)
</td>
</tr>
<tr>
<td valign="middle" align="left">�y�[�W</td>
<td valign="middle" align="left">$url<input type="text" name="link" size="20" value="$link"> (��)sample.html<br>
�g�b�v�y�[�W�̂Ƃ��͂��̂܂܂�OK<br>
�����y�[�W�����łɓo�^����Ă���ꍇ�͏㏑�����܂��B
</td>
</tr>
<tr>
<td valign="middle" align="left">�R�����g</td>
<td valign="middle" align="left">
<textarea wrap="soft" name="comment" rows="3" cols="50">$comment</textarea><br>(10�`200�������炢)
</td>
</tr>
</table>
<br>
<input type="submit" value="�o�^">
</form>
EOM

if($_[0] ne "edit"){

print <<EOM;
���ݓo�^����Ă���RSS�t�B�[�h
<br>
<table border=1 cellpadding="2" cellspacing="1">
<tr>
<td valign="middle" align="center">����</td>
<td valign="middle" align="center" width=250><b>�^�C�g��</b></td>
<td valign="middle" align="center" width=160><b>�y�[�W</b></td>
<td valign="middle" align="center">�@</td>
<td valign="middle" align="center">�@</td>
</tr>
EOM
	unless(open(FILE,"$logfile")){
		&error("$logfile���J���܂���B");
	}
	$hd=<FILE>;
	$num=0;
	while(<FILE>){
		$num++;
	}
	close FILE;


	unless(open(FILE,"$logfile")){
		&error("$logfile���J���܂���B");
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
<input type="submit" name="ya" value="��">
<input type="submit" name="ya" value="��">
</form>
</td>
<td>$title</td>
<td>$link</td>
<td align=center>
<form method="POST" action="$script" style="margin:0px">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="delnum" value="$no">
<input type="submit" value="�폜">
</form>
</td>
<td align=center>
<form method="POST" action="$script" style="margin:0px">
<input type="hidden" name="pass" value="$FORM{'pass'}">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="editnum" value="$no">
<input type="submit" value="�ҏW">
</form>
</td>
</tr>
EOM
}
	close FILE;
print <<EOM;
</table>
<br>
<input type="button" value="�m�F����" onclick="openwin('$rdf')">
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
	&error("�^�C�g�������͂���Ă��܂���B");
}
if($FORM{'comment'} eq ""){
	&error("�R�����g�����͂���Ă��܂���B");
}

$date="$FORM{'year'}.$FORM{'mon'}.$FORM{'day'}.$FORM{'hour'}:$FORM{'min'}:00";

unless(open(FILE,"$logfile")){
	&error("$logfile���J���܂���B");
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
	&error("$logfile���J���܂���B");
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
	&error("$logfile���J���܂���B");
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
	&error("$logfile���J���܂���B");
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
	&error("�^�C�g�������͂���Ă��܂���B");
}
if($FORM{'comment'} eq ""){
	&error("�R�����g�����͂���Ă��܂���B");
}
$len=length($FORM{'title'});
if($len > $max_title){
	&error("�^�C�g�����������܂��B����$len�o�C�g");
}

$len=length($FORM{'comment'});
if($len > $max_des){
	&error("�R�����g���������܂��B����$len�o�C�g");
}

$FORM{'link'}=~s/^$url//;


$date="$FORM{'year'}.$FORM{'mon'}.$FORM{'day'}.$FORM{'hour'}:$FORM{'min'}:00";



unless(open(FILE,"$logfile")){
	&error("$logfile���J���܂���B");
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
	&error("$logfile���J���܂���B");
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

open FILE,">$rdf" || &error("$rdf���J���܂���B");

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
	&error("$kousinskin���J���܂���B�t�@�C�����m�F���Ă��������B");
}
$skinline=join('',<HTML>);
close(HTML);
($head,$dummy)=split(/<!--start-->/, $skinline);
if($head eq ""){
	&error("�X�L����&lt;!--start--&gt;��������Ă��܂���B");
}
($tr,$footer)=split(/<!--end-->/, $dummy);
if($footer eq ""){
	&error("�X�L����&lt;!--msgend--&gt;��������Ă��܂���B");
}


if(!open(FILE, ">$kousinhtml")){
	&error("$logfile�ɏ������݂ł��܂���B�p�[�~�b�V�������m�F���Ă��������B");
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
	errormsg+="���^�C�g����"+max_title+"���ȓ��ɂ��Ă��������B����"+len_t+"����";
}
if(len_c > max_des){
	errormsg+="���R�����g��"+max_des+"���ȓ��ɂ��Ă��������B����"+len_c+"����";
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

<p align="center"><a href="${script}?">�߂�</a></p>
</body>
</html>
EOM
exit;
}
exit;