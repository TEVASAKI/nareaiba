<?

if(phpversion()>="4.1.0"){

  extract($_GET);

  extract($_POST);

  extract($_COOKIE);

  extract($_SERVER);

}

/*****************************************

  *  p++BBS                by ToR

  *    ���X�������^�C�v

  *  http://php.s3.to/

  ****************************************

  * 2001/10/19 v4.00 �ꂩ�珑�������A���O�ۑ�����s��X������s��J�L�R��

  * 2001/10/22 v4.01 ���X�폜�o�O�C���A�����X�o�O�C��

  * 2002/02/14 v4.03 ���O�������ݕύX

  * 2002/05/19 v4.04 �Ǘ��폜�y�[�W���O�o�O�C��

  * 2002/06/28 v4.05 ����Ӱ�ނɸ۽��Ľ����ݸ�(by office)

  * 2002/06/30 v4.06 �폜���߰�ޑ���o�O�C��

  * 2006/03/02 v4.2  PHP_SELF��SCRIPT_NAME�A�֎~���[�h�z�X�g�ݒ�

  ****************************************

  * �����X�f���ł��B����ȋ@�\������܂�

  * �E�X�L���t�@�C���ɂ�莩�R�ȃf�U�C�����\

  * �E���X�͈�萔�ȏ�\�����Ȃ��i���ɉ��j

  * �E���X������ƋL����Top�ɂ���悤�ɂ��o����

  * �E�ߋ����O�쐬�@�\

  * �E���[�U�[�E�Ǘ��҂ɂ��폜���\

  * �E��d���e�E����IP�̘A�����e�̖h�~

  * �E�󔒓��e�E���d���s�E�O�����e�EGET���e�̖h�~

  * �E���X�L�����폜�\�ƂȂ�܂���

  * ������

  * �E��̃��O�t�@�C��bbs.log��p�ӂ��ăp�[�~�b�V������666�ɂ��܂��B

  * �E�X�L���f�B���N�g�������A�X�L���t�@�C����u���܂��B

  * �E�ߋ����O�@�\���g���ꍇ�͉ߋ����ONo�t�@�C��bbspast.log��p�ӂ���666�ɂ��܂�

  * ���X�L�����̕ϐ����

  * [head.txt] TOP�t�H�[��

  * �K�{�F<input type="hidden" name="mode" value="regi">

  *  <body>�^�O�̌��<!--body-->

  *  $title1 - <title>�^�O�������A$title2 - TOP�^�C�g���A

  *  $c_name,$c_email,$c_url,$c_pwd - �N�b�L�[�̒l�i���O�AE-Mail�AURL�A�p�X�j

  *  $colf - �F�I���{�^��

  * [main.txt] �e�L��

  *  $no - �L��No�A$sub - �^�C�g���A$name - ���O�A

  *  $url - URL�����鎞�����N�A$color - �R�����g�F�A$com - �R�����g

  * [res.txt] ���X�L��

  *  $rc - ���X�J�E���g�A��͏�Ɠ���

  * [resform.txt] ���X�t�H�[��

  * �K�{�F<input type="hidden" name="mode" value="regi">

  *       <input type="hidden" name="res" value="'.$no.'">

  *  $col - �F�I��select��option�����̂� <select name=color>���K�v

  *  �N�b�L�[�l�͏�Ɠ����ł�

  * [foot.txt]

  *  $next - �y�[�W���胊���N�i177�s�ځj

  *���e���[�h�̐���

  * ?mode=del    �폜���[�h

  * ?mode=admin  �Ǘ����[�h

  * ?mode=past   �ߋ����O�\��

  * ?mode=search ���O����

  ******************************************

  * bbs.log�͋󂶂�Ȃ��A�����̃t�@�C�����g���Ă�������

  *****************************************/

/* ���O�t�@�C�����@*/

define("LOGFILE", "bbs.log");



/* �X�L���t�@�C���̏ꏊ */

define("HEADFILE", "./skin/head.txt");		//�w�b�_

define("MAINFILE", "./skin/main.txt");		//�e�L��

define("RESFILE",  "./skin/res.txt");		//���X�L��

define("MAINEND",  "./skin/mainend.txt");	//�e�L����(�ǉ��j

define("FORMFILE", "./skin/resform.txt");	//���X�t�H�[��

define("FOOTFILE", "./skin/foot.txt");		//�t�b�^



/* �Ǘ��җp�p�X���[�h�B�K���ύX���ĉ������B*/

define("ADMINPASS", "*l3pMfxDWGUuJhFr26iLa49etXgYAH");



/* <title>�ɓ����^�C�g���i���[�h�ɂ��ω��j*/

$title1 = '����BBS';

/* �f����TOP�����ɓ����^�C�g���iHTML��,skin���ڏ������݂��j*/

$title2 = '<font size=5 face=Verdana color=gray><b>����BBS</b></font>';



/* �߂��iHOME�j*/

define("HOME", 'http://www5.pf-x.net/~tevasaki/');

/* �L��URL�����N�i�ƃA�C�R��<img src�� or HOME���j*/

define("HP", 'HP');



/* ��y�[�W�ɕ\������e�L���� */

define("PAGEVIEW", 7);

/* �ŐV���X�����\���i����ȏ�͗���) */

define("RESVIEW", 8);

/* �ő�L�^�s��(���X�܁j������z����ƌÂ������珇�ɉߋ����O�Ɉڂ�܂�*/

define("LOGMAX", 200);		//200�Őe�L��30-40�� 60KB���x



/* �e�ƃ��X�̋�؂� */

define("KUGIRI", "<hr size=1>");

/* ���X�����N�i���X�j */

define("RESALL", "���X");



/* �F�I�𕔕��̋L�� */

define("MARK", "��");

/* �F�̐ݒ� */

$COLORS =  array('800f65','e50086','db0015','eb7988','ad8c38','e4a20b','ffb74c','28af78','008677','6d9a4a','ececff','504dcb','3100b2','3a2d6b');

$COLNAME = array('�Íg','�Ԏ�','�g�F','�g�~','���y','�R��','��F','��','�|','��F','����','���|','�ڗ�','����');



/* ���������N�iyes=1 no=0�j*/

define("LINK", 1);

/* ���X�������Ƃ��A�L����Top�ɂ��邩�iyes=1 no=0�j*/

define("AGE", 1);

/* �^�C�g������̎��̑薼�i��Ȃ���͋����j*/

define("MUDAI", "����(�x�)");



/* �\�������������i���O�A�薼�����̒����Ɏ��߂܂��B���p�Łj*/

define("NAMEVIEW", 30);

define("SUBVIEW", 30);

/* ���e�����������i���O�A�薼�A�{��������ȏゾ�ƃG���[�B���p�Łj */

define("MAXNAME", 40);

define("MAXSUB", 40);

define("MAXVALUE", 800);

/* �{���̉��s������ */

define("MAXLINE", 100);



/* ����z�X�g����̘A�����e�𐧌��i0�Ŗ������j                   *

 *--> �b�����L�q����Ƃ��̎��Ԉȏ���o�߂��Ȃ��ƘA�����e�ł��Ȃ� */

define("RENZOKU", 15);

/* �O���������݋֎~�ɂ���(6/2) */

define("GAIBU", 0);



/* �ߋ����O�쐬����? */

define("PAST_KEY", 1);

/* �ߋ����O�ԍ��t�@�C�� */

define("PAST_NO", "bbspast.log");

/* �ߋ����O�쐬�f�B���N�g��(�������݌����K�v) */

define("PAST_DIR", "./");

/* �ߋ����O�ړ���index?.html kako?.html */

define("PAST_NAME", "index");

/* �ߋ����O��ɏ������ލs�� */

define("PAST_LINE", 50);



/* HTML�������o�����ǂ����iyes=1 No=0�j*

 *->1�̏ꍇ�N�b�L�[�g�p�s�ł�        */

define("HTML", 1);

/* �ÓIHTML�������o���ꍇ��HTML�t�@�C�� */

define("HTMLFILE", "bbs.html");



/* �֎~���[�h�AURL */

$dword[] = "����";

$dword[] = "����";

$dword[] = "<a href=";

$dword[] = "URL=";

$dword[] = "Nice sit";

$dword[] = "http:";

$dword[] = "���������F";

$dword[] = "Good to meet you";

$dword[] = "isn't it a lovely";

$dword[] = "ttp://";



/* �֎~�z�X�g */

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

/* �F�I��HTML���� */

function ColSet(){

  global $pppbbs,$COLORS,$COLNAME;



  // �N�b�L�[

  if(!HTML) list($c_n,$c_e,$c_u,$c_p,$c_col) = explode(",", $pppbbs);

  if($c_col == "") $c_col = $COLORS[0];// �N�b�L�[�����Ȃ�0�Ԗ�



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



    if($cc == (count($COLORS)/2-1)) $colradio.='<br>';//�܂�Ԃ�

  }

  // [0]=���W�I�{�^��$colf [1]=�Z���N�g$col

  return array($colradio,$colselect);

}

function LogOpen($log){

  for($t = 1; $t < count($log); $t++){

    list($oya_no,$res_no,) = explode(",", $log[$t]);

    $thread[$oya_no][$res_no] = $log[$t];

  }

  return $thread;

}

/* ���C���\�� */

function MainView(&$dat,$thread,$page=""){

  global $SCRIPT_NAME,$pppbbs,$title1,$title2;



  // �N�b�L�[��������

  if(!HTML) list($c_name,$c_email,$c_url,$c_pwd,$c_col) = explode(",", $pppbbs);

  // �F�I�𕔕��쐬

  $colhtml = ColSet();

  $colf = $colhtml[0];	//�e�L���t�H�[��

  $col = $colhtml[1]; 	//���Xselect��



  include(HEADFILE);	//�w�b�_�ϊ�



  $st = ($page) ? $page : 0;			//�J�n�s�ݒ�

  for($n = 0; $n < $st; $n++) next($thread);	//�z���΂�



  for($i = 0; $i < PAGEVIEW; $i++){

    list(,$val) = @each($thread);		//�z���i�߂�

    if($val[0]=="") continue;			//��Ȃ��΂�

    list($no,$resno,$now,$name,$email,$sub,$com,$url,

         $host,$pw,$color) = explode(",", $val[0]);

    // �t�H�[�}�b�g

    if(strlen($name) > NAMEVIEW) $name = substr($name,0,NAMEVIEW) . ".";

    if(strlen($sub) > SUBVIEW)   $sub = substr($sub,0,SUBVIEW) . ".";

    if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">".HP."</a>";

    if($email) $name = "<a href=\"mailto:$email\">$name</a>";

    if(LINK) $com = auto_link($com);



    // ���X�����N�iMAIN�̏�Ɂj

    $resall = "<a href=\"$SCRIPT_NAME?mode=all&no=$no\">".RESALL."</a>";//�i��ɕ\���j



    include(MAINFILE);	//�e�L���ϊ�



    // ���X�J�n�ʒu

    $rst = count($val) - RESVIEW;

    if($rst <= 0) $rst = 1;

    // ���ɉ�����烊���N

    if($rst > 1) $resall = "<a href=\"$SCRIPT_NAME?mode=all&no=$no\">".RESALL."</a>";

    // ���X�����鎞�͋�؂�

    if(count($val) > 1) $dat.= KUGIRI;



    for($r = $rst; $r < count($val); $r++){

      if($val[$r] == 0) continue;

      list($no,$rc,$now,$name,$email,$sub,$com,$url,

           $host,$pw,$color) = explode(",", $val[$r]);

      if($com == "") continue;

      if(LINK) $com = auto_link($com);

      include(RESFILE);	//���X�L���ϊ�

    }



    include(MAINEND);	//�e�I���ϊ��i�ԐM�t�H�[�����e���j



    $oya++;		//�e�L���J�E���g

    unset($resall);	//�ϐ��J��

  }

  // �y�[�W����

  $prev     = $st - PAGEVIEW;

  $nextpage = $st + PAGEVIEW;

  if($prev < 0) $prev = 0;	//�}�C�i�X�ɂ��Ȃ�

  if($page > 0){		//���ւ�������O�\��

    $next.= "<a href=$SCRIPT_NAME?page=$prev><< Prev</a> ";

  }

  if($nextpage < count($thread) && $oya >= PAGEVIEW){//�e�L���\���I�[�o�[�Ȃ玟�\��

    $next.= " <a href=$SCRIPT_NAME?page=$nextpage>Next >></a>";

  }

  include(FOOTFILE);	//�t�b�^�ϊ�

}

/* ���X�P�ƕ\�� */

function ResView($res){

  global $thread,$pppbbs,$SCRIPT_NAME;



  // �N�b�L�[��������

  if(!HTML) list($c_name,$c_email,$c_url,$c_pwd,$c_col) = explode(",", $pppbbs);

  // �F�I�𕔕��쐬

  $colhtml = ColSet();

  $colf = $colhtml[0];	//�e�L���t�H�[��

  $col = $colhtml[1]; 	//���Xselect��



  // �^�[�Q�b�g�������Ȃ�I��

  if(!is_array($thread[$res])) return false;

  // �w�b�_��

  $dat.= ModeHead("NO. $res �ɂ��Ẵ��X");

  $dat.= "<div align=center><center><b>$title1</b><br>";

  // ���C��

  while(list(,$val) = each($thread[$res])){

    list($no,$rc,$now,$name,$email,$sub,$com,$url,

         $host,$pw,$color) = explode(",", $val);

    if($com == "") continue;

    // �t�H�[�}�b�g

    if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">".HP."</a>";

    if($email) $name = "<a href=\"mailto:$email\">$name</a>";

    if(LINK) $com = auto_link($com);

    // ���XNo 0�Ȃ�e�L��

    if($rc == "0"){

      include(MAINFILE);

    }else{

      if($rc == "1") $dat.= KUGIRI;//��Ԗڂ͋�؂�

      include(RESFILE);

    }

  }

  include(FORMFILE);



  include(FOOTFILE);



  echo $dat;	//�\��

}

/* �������ݑO�̏��� */

function RegCheck($sub,$name,$email,$url,$color,$pwd,$com,$res){

  global $SCRIPT_NAME,$REQUEST_METHOD,$log,$thread,$dword,$dhost;



  if($REQUEST_METHOD != "POST") error("�s���ȓ��e�����Ȃ��ŉ�����");

  if(GAIBU && !eregi($SCRIPT_NAME,getenv("HTTP_REFERER"))) error("�O�����珑�����݂ł��܂���");



  if(!$res && ($sub=="" || ereg("^( |�@)*$",$sub))){

    (MUDAI) ?  $sub=MUDAI : error("�薼���������܂�Ă��܂���");

  }

  if($name=="" || ereg("^( |�@)*$",$name)) error("���O���������܂�Ă��܂���");

  if($com=="" || ereg("^( |�@|[\r\n])*$",$com)) error("�{�����������܂�Ă��܂���");



  if(strlen($name) > MAXNAME) error("���O���������܂����I");

  if(strlen($sub) > MAXSUB)   error("�^�C�g�����������܂����I");

  if(strlen($com) > MAXVALUE) error("�{�����������܂����I");



  //���ݎ���

  $tim = time();

  // �z�X�g�����擾

  $host = gethostbyaddr(getenv("REMOTE_ADDR"));

    // �֎~�z�X�g

  foreach($dhost as $deny) {

    if (strstr($host, $deny)) error("���̃z�X�g����̓��e�͂ł��܂���");

  }

  // �֎~���[�h

  foreach($dword as $deny) {

    if (strstr($com, $deny)) error("�֎~���[�h���܂܂�Ă��܂�");

    if (strstr($url, $deny)) error("�֎~���[�h���܂܂�Ă��܂�");

  }

  //����z�X�g����̘A�����e�֎~

  list($lno,$lname,$lcom,$ltime,$lhost,) = explode(",", $log[0]);

  if (RENZOKU && $host==$lhost && $tim - $ltime < RENZOKU)

    error("�A�����e�͂������΂炭���Ԃ�u���Ă��炨�肢�v���܂�<br><br>|�֥)���15�b���炢�B");

  //No�擾

  $newno = (isset($res)) ? $res : $lno + 1;

  $maxno = (isset($res)) ? $lno : $lno + 1;

  $resno = (isset($res)) ? count($thread[$res]) : 0;

  //���Ԃ̃t�H�[�}�b�g

  $now = gmdate("Y/m/d(D) H:i",$tim+9*60*60);

  //url���`

  $url = trim($url);

  $url = ereg_replace("^http://", "", $url);

  //PW�Í���

  $pass = substr(md5($pwd),2,8);

  //�e�L�X�g���`

  $sub  = CleanStr($sub);

  $name = CleanStr($name);

  $email= CleanStr($email);

  $url  = CleanStr($url);

  $com  = CleanStr($com);

  // ���s�����̓���B

  $com = str_replace( "\r\n",  "\n", $com);

  $com = str_replace( "\r",  "\n", $com);

  // �s��������isubstr_count�̑���j

  $temp = str_replace("\n", "\n"."a",$com);

  $str_cnt = strlen($temp)-strlen($com);

  if($str_cnt > MAXLINE) error("�s�����������܂����I");

  // �A�������s����s

  $com = ereg_replace("\n((�@| )*\n){3,}","\n",$com);

  $com = nl2br($com);			//���s�����̑O��<br>��������

  $com = str_replace("\n",  "", $com);	//\n�𕶎��񂩂�����B

  // ��d���e�`�F�b�N

  if($name==$lname && $com==$lcom) error("��d���e�͋֎~�ł�<br><br><a href=$SCRIPT_NAME>�����[�h</a>");



  // �N�b�L�[�ۑ�

  $cookvalue = implode(",", array($name,$email,$url,$pwd,$color));

  setcookie ("pppbbs", $cookvalue,time()+14*24*3600);  /* 2�T�ԂŊ����؂� */

  // �f�[�^�t�H�[�}�b�g

  $first = "$maxno,$name,$com,$tim,$host,\n";

  $newdata = "$newno,$resno,$now,$name,$email,$sub,$com,$url,$host,$pass,$color,$tim,\n";



/* ���O�������� age,sage */



  //���O�s���I�[�o�[����

  if(count($log) >= LOGMAX){

    //�Ō���No�擾

    list($pastno,) = explode(",", $log[count($log)-1]);

    for($c = 0; $c < count($log); $c++){

      list($target,$re,$now) = explode(",", $log[$c]);

      if($target == $pastno){	//�Ō��̃X���͉ߋ����O��

        if(PAST_KEY && $now!="") PastWrite($log[$c]);

        $log[$c] = "";		//�z�񂩂����

      }

    }

  }



  //�ŐV�L��No�Ɠ�d���e�p�t�H�[�}�b�g�i���ځj

  $new[0] = $first;



  if($resno == 0){//�e�L��

    $new[] = $newdata;		//�擪�Ƀf�[�^�ǉ�

    next($log);			//���O�̈�s�ڂ͑���

    while(list(,$val) = each($log)){//�c��̃f�[�^�ǉ�

      $new[] = $val;

    }

  }else{//���X�L��

    $find = FALSE;		//�t���O

    for($i = 1; $i < count($log); $i++){

      list($key,) = explode(",", $log[$i]);

      if($newno == $key){		//�n�b�P�\�I

        $find = TRUE;		//�t���OOn

        $new[] = $log[$i];	//���̃X����ǉ�

        list($next,) = explode(",", $log[$i+1]);

        if($next != $key) $new[] = $newdata;//���̋L��No���Ⴄ�Ȃ烌�X�Ō��

      }else{

        //�A�Q�Ȃ�ʔz��ɁA�T�Q�͂��̂܂܎c��̃f�[�^�ǉ�

        (AGE) ? $old[] = $log[$i] : $new[] = $log[$i];

      }

    }

    for($o = 0; $o < count($old); $o++){//�A�Q�̎��c��̃f�[�^�ǉ�

      $new[] = $old[$o];

    }

    if(!$find) error("�Y���L����������܂���");

  }

  //���O�X�V

  renewlog($new);



  return true;

}

/* �e�L�X�g���` */

function CleanStr($str){

  $str = trim($str);//�擪�Ɩ����̋󔒏���

  if (get_magic_quotes_gpc()) {//�����폜

    $str = stripslashes($str);

  }

  $str = htmlspecialchars($str);//�^�O���֎~

  $str = str_replace("&amp;", "&", $str);//���ꕶ��

  return str_replace(",", "&#44;", $str);//�J���}��ϊ�

}

/* �L���폜���[�h */

function MsgDel($page, $admin=""){

  global $SCRIPT_NAME,$thread;



  echo ModeHead("�R�����g�폜���");



  echo "[<a href=\"$SCRIPT_NAME\">�� �߂�</a>][<a href=\"$SCRIPT_NAME?mode=admin\">�Ǘ�</a>]

        <table width=100%><tr><th bgcolor=#008080><font color=#FFFFFF>�R�����g�폜���</font>

        </th></tr></table>\n<p><center>

        <form action=\"$SCRIPT_NAME\" method=POST>

        <table border=0 cellpadding=5><td bgcolor=FFFFFF><font size=2>

        �����e���ɋL�������u�폜�L�[�v�ɂ��A�L�����폜���܂��B<br>

        ���폜�������L���̃`�F�b�N�{�b�N�X�Ƀ`�F�b�N�����A���L�t�H�[���Ɂu�폜�L�[�v����͂��Ă��������B<br>

        ���e�L�����폜����ꍇ�A���̃��X���b�Z�[�W�������ɏ��ł��Ă��܂����ƂɂȂ�܂��̂ŁA�����ӂ��������B

        </font></td></table><p>";

  if($admin == ADMINPASS){//�p�X�������Ă鎞�̓z�X�g�\���ƃ{�^���\��

    $head = "<th>�z�X�g</th>";

    $butt = "<input type=hidden name=pass value=\"$admin\"><input type=hidden name=mode value=\"admin\">";

    echo $butt."<input type=submit value=\"�폜����\"><input type=reset value=\"���Z�b�g\">";

  }else{

    $butt = "<input type=hidden name=mode value=\"usr_del\">";

    echo $butt."<b>�폜�L�[</b> <input type=text name=del_key size=10>

          <input type=submit value=\"�폜����\"><input type=reset value=\"���Z�b�g\">";

  }

  echo "<p><table border=0 cellspacing=1><tr bgcolor=AADDCC><th>�폜</th><th>No</th>

        <th>�薼</th><th nowrap>���e��</th><th>���e��</th><th>�R�����g</th>$head</tr>";



  $st = ($page) ? $page : 0;//�J�n�s�ݒ�

  for($n = 0; $n < $st; $n++) next($thread);//�z���΂�



  for($i = 0; $i < PAGEVIEW; $i++){

    list(,$val) = @each($thread);



    for($r = 0; $r < count($val); $r++){

      if($val[$r]=="") continue;

      list($no,$resno,$now,$name,$email,$sub,$com,$url,

           $host,$pwd,$col,$tim,) = explode(",", $val[$r]);

      if($com == "") continue;

      // �t�H�[�}�b�g

      if(strlen($com) > 60) $com = substr($com,0,60) . "...";

      if(strlen($sub) > 60) $sub = substr($sub,0,60) . "...";

      $com = str_replace("<br />", " ", $com);

      if($email) $name = "<a href=\"mailto:$email\">$name</a>";

      if($resno != "0") $subj = "<td colspan=2 align=center><b>$no</b>�ւ̃��X$resno</td>";

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



  if($page > 0){//���ւ�������O�\��

    echo "<td><form action=\"$SCRIPT_NAME\" method=POST>$butt

          <input type=hidden name=page value=\"$prev\">

          <input type=submit value=\"�O�̐e�L��",PAGEVIEW,"��\"></form></td>\n";

  }

  if($next < count($thread) && $oya >= PAGEVIEW){//�e�L���\���I�[�o�[�Ȃ玟�\��

    echo "<td><form action=\"$SCRIPT_NAME\" method=POST>$butt

          <input type=hidden name=page value=\"$next\">

          <input type=submit value=\"���̐e�L��",PAGEVIEW,"��\"></form></td>";

  }

  die("</tr></table>\n</body></html>");

}

/* ���[�U�[�폜���s */

function UsrDel($pass,$target){

  global $log;



  if($target){//target=���t��

    $find = FALSE;

    for($i = 1; $i < count($log); $i++){

      list($no,$resno,$now,,,,,,$host,$pwd,$col,$tim) = explode(",", $log[$i]);

      if($delno == $no) $log[$i] = "";	//�e�L���̎��A���X���폜

      if($target == $tim && substr(md5($pass),2,8) == $pwd){

        $find = TRUE;			//�p�X�I���I

        if($resno == "0"){

          $log[$i] = "";	//�e�L���̎��A����eNo�폜

          $delno = $no;

        }else{

          $log[$i] = "$no,$resno,,,,,,,,,,\n";

          break;		//���X�L���̏ꍇ���[�v������

        }

      }

    }

    if(!$find) error("�p�X���[�h���Ԉ���Ă��邩�Y���L����������܂���");

    else renewlog($log);//���O�X�V

  }

  return true;

}

/* �Ǘ��ҍ폜���O�C���Ǝ��s */

function Admin($pass, $target){

  global $SCRIPT_NAME,$log;



  if($pass && $pass != ADMINPASS)  error("�p�X���[�h���Ⴂ�܂�");



  if(trim($pass)==""){//�p�X��Ȃ烍�O�C�����

    echo ModeHead("�Ǘ����|�h");

    echo "<a href=\"$SCRIPT_NAME\">���f���ɖ߂�</a>

        <table width=100%><tr><th bgcolor=#008080><font color=#FFFFFF>�Ǘ����[�h</font>

        </th></tr></table>\n<p><center><h4>�p�X���[�h����͂��ĉ�����</h4>

        <form action=\"$SCRIPT_NAME\" method=POST>

        <input type=hidden name=mode value=\"admin\">

        <input type=password name=pass size=8>

        <input type=submit value=\" �F�� \"></form>\n";

    die("</body></html>");

  }



  if($target){//�p�X�̔�r���Ȃ��ō폜

    for($i = 1; $i < count($log); $i++){

      list($no,$resno,$now,,,,,,$host,$pwd,$col,$tim) = explode(",", $log[$i]);

      if($delno == $no) $log[$i] = "";	//�e�L���̎��A���X���폜

      if($target == $tim){

        if($resno == "0"){

          $log[$i] = "";	//�e�L���̎��A����eNo�폜

          $delno = $no;

        }else{

          $log[$i] = "$no,$resno,,,,,,,,,,\n";

          break;		//���X�L���̏ꍇ���[�v������

        }

      }

    }

    renewlog($log);//���O�X�V

  }

  return true;

}

/* ���O�X�V in:�z�� */

function renewlog($newarr){

  $fp = fopen(LOGFILE, "w");

  flock($fp, 2);

  fputs($fp, implode('', $newarr));

  fclose($fp);



  if(HTML) MakeHtml();



  return true;

}

/* HTML���� */

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

/* �ߋ����O�������� */

function PastWrite($data){

  //���݂̉ߋ����ONo�擾

  $cntarr = @file(PAST_NO);

  $cnt = $cntarr[0];

  //�ߋ����O�t�@�C����

  $pastfile = PAST_DIR.PAST_NAME.$cnt.".html";

  if(file_exists($pastfile)){

    $old = file($pastfile);     //�t�@�C��������Ȃ�ǂݍ���

    if(count($old) > PAST_LINE){//�s���I�[�o�[�Ȃ�J�E���g�A�b�v

      $cnt++;

      $fp = fopen(PAST_NO, "w");

      fputs($fp, $cnt);

      fclose($fp);

      //�t�@�C�����V��������

      $pastfile = PAST_DIR.PAST_NAME.$cnt.".html";

      unset($old);

    }

  }

  //�f�[�^����

  list($pno,$pres,$pdate,$pname,$pemail,$psub,

       $pcom,$purl,$pho,$ppw,$pco,$pk) = explode(",", $data);

  //URL�Ƀ����N�A���[���Ƀ����N

  if($purl)   $purl = "<a href=\"http://$purl\" target=\"_blank\">HP</a>";

  if($pemail) $pname = "<a href=\"mailto:$pemail\">$pname</a>";

  //���̍s��F�ύX

  $pcom = eregi_replace("(^|/>)(&gt;[^<]*)", "\\1<font color=999999>\\2</font>", $pcom);

  if(LINK) $pcom = auto_link($pcom);//�I�[�g�����N

  //���X�̏ꍇ���XNo�ǉ�

  if($pres != "0") $pno = "$pno �̃��X$pres";

  //�\���t�H�[�}�b�g�i��s�ꃉ�C���j

  $line = "<hr>[$pno] <font color=\"#009900\"><b>$psub</b></font> Name�F<b>$pname</b> <small>Date�F$pdate</small> $purl<br><ul>$pcom</ul><!-- $pho -->\n";

  //�擪���猻�ߋ����O�ɒǉ�

  $ps = fopen($pastfile, "w");

  flock($ps, 2);

  fputs($ps, $line);

  if($old) fputs($ps, implode('', $old));

  fclose($ps);



  return true;

}

/* �ߋ����O�\�� */

function PastView($no){

  global $SCRIPT_NAME;



  $no = htmlspecialchars($no);

  //���݂̉ߋ����ONo�擾

  $cntarr = @file(PAST_NO);

  $cnt = $cntarr[0];

  if(!$no) $no = $cnt;



  echo ModeHead("�� �ߋ����O $no ��");

  echo "<font size=2><a href=\"$SCRIPT_NAME?\">���f���ɖ߂�</a></font><br>

        <center>�� �ߋ����O $no ��<P>new�� ";

  //�y�[�W����

  for($i = $cnt; $i > 0; $i--){

    if($no == $i) echo "[ $i ]";

    else echo "[<a href=\"$SCRIPT_NAME?mode=past&pno=$i\">$i</a>]";

  }

  echo " ��old</center>",PAST_LINE,"�����\��";

  //�t�@�C�����w��

  $pastfile = PAST_DIR.PAST_NAME.$no.".html";

  if(!file_exists($pastfile)) error("<br>�ߋ����O���݂���܂���");

  else include($pastfile);//�ǂݍ���

  die("</body></html>");

}

/* �������[�h */

function Search($w, $andor, $target){

  global $SCRIPT_NAME,$log;



  if(get_magic_quotes_gpc()) $w = stripslashes($w); //������



  echo ModeHead("�������[�h");

  echo "<a href=\"$SCRIPT_NAME?\">���f���ɖ߂�</a><table width='100%'>

        <tr><th bgcolor=008080>�������[�h</th></tr></table>

        <br><p align=center>�����������P����X�y�[�X�ŋ�؂��ē��͂��Ă��������B<br>

        <form action=\"$PHPSELF\" method=POST>

        <input type=hidden name=mode value=search>

        <input type=text name=w size=30 value=\"".htmlspecialchars($w)."\">

        <select name=\"andor\"><option value=\"or\" selected>OR\n

        <option value=\"and\">AND</select>

        <input type=submit value=\"����\"><br><br>";



  if(trim($w)!=""){			//�O��̃X�y�[�X����

    $keys = preg_split("/(�@| )+/", $w);//�������z���

    next($log);				//��s�ڑ���

    while(list(,$line) = each($log)){	//���O�𑖍�

      $find = FALSE;			//�t���O

      for($i = 0; $i < count($keys); $i++){

        if($keys[$i]=="") continue;	//��͓s�o�X

        if(stristr($line,$keys[$i])){	//�}�b�`�ł�

          $find = TRUE;			//�����

          $line = str_replace($keys[$i],"<b style='color:green;background-color:#ffff66'>$keys[$i]</b>",$line);

        }elseif($andor == "and"){	//AND�̏ꍇ�}�b�`���Ȃ��Ȃ玟�̃��O��

          $find = FALSE;

          break;

        }

      }

      if($find) $result[] = $line;	//�}�b�`�������O��z���

    }

    //���ʕ\��

    echo "<div align=left>��������".count($result)."��<br>";

    for($c = 0; $c < count($result); $c++){//���ʓW�J

      list($no,$res,$now,$name,$email,$sub,

           $com,$url,$ho,,$pco,) = explode(",", $result[$c]);

      //�t�H�[�}�b�g

      if($url)   $url = "<a href=\"http://$url\" target=\"_blank\">HP</a>";

      if($email) $name = "<a href=\"mailto:$email\">$name</a>";

      if(LINK) $com = auto_link($com);//�I�[�g�����N

      //���X�̏ꍇ���XNo�ǉ�

      $tit = ($res != "0") ? "$no �̃��X$res" : $no;

      //���ʕ\��

      echo "<hr>[<a href=\"$SCRIPT_NAME?mode=all&no=$no\">$tit</a>]

            <font color=\"#009900\"><b>$sub</b></font>

            Name�F<b>$name</b> <small>Date�F$now</small> $url<br>

            <ul>$com</ul><!-- $pho --><br>\n";

    }

  }

  die("</body></html>");

}

/* ���ʃw�b�_ */

function ModeHead($title1){

  include(HEADFILE);

  ereg("^(.*)<!--body-->", $dat, $regs);//�擪����<!--body-->�܂�

  return $regs[1];//�w�b�_��\��

}

/* ���������N */

function auto_link($str){

  return eregi_replace("(https?|ftp)(://[[:alnum:]\S\+\$\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$str);

}

/* �G���[��� */

function error($mes){

  echo ModeHead("�G���[�I�I�I");

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