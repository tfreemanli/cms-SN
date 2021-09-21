<?php require_once('./Connections/localhost.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "./login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$where_clu = " where ct.id is not null ";
$hint_text = "所有联系人";
$Keyword = "";
if(isset($_GET['keyword'])){
	$Keyword=$_GET['keyword'];
	$where_clu .= " and (mobilephone like binary '%". $Keyword."%'";
	$where_clu .= " or homephone like binary '%". $Keyword."%'";
	$where_clu .= " or otherphone like binary '%". $Keyword."%'";
	$where_clu .= " or cat like binary '%". $Keyword."%'";
	$where_clu .= " or UPPER(address) LIKE BINARY CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(suburb) LIKE BINARY CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(city) LIKE BINARY CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(firstname) like binary  CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(lastname) like binary  CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(memo) like binary  CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= " or UPPER(email) like binary CONCAT('%',UPPER('". $Keyword."'),'%')";
	$where_clu .= ")";
	$hint_text = "搜索：".$Keyword;
}
if(isset($_GET['browse'])){
	if(isset($_GET['source'])) { $where_clu .= " and source='". $_GET['source'] ."'"; }
	if(isset($_GET['cat'])) { $where_clu .= " and cat='". $_GET['cat'] ."'"; }
	if(isset($_GET['status'])) { 
		if($_GET['status']=='normal'){
			$where_clu .= " and status<>'unCat'";
		}else{
			$where_clu .= " and status='". $_GET['status'] ."'"; 
		}
	}
	if(isset($_GET['isclient'])){
		if($_GET['isclient']=="on") { $where_clu .= " and isclient='on'"; } else { $where_clu .= " and isclient is null";}
	}
	
	$hint_text = "浏览：";
	
	if(isset($_GET['method']) && isset($_GET['p'])){
		
		$method = $_GET['method'];
		$p = $_GET['p'];
		
		
		if($method=="should"){
			$where_clu .= " and actived = 'on' ";
			//改进了搜索条件，添加了Before和After选项
			//$where_clu .= " and to_days(now())-to_days(cvs.followupdate)<=0 and to_days(cvs.followupdate)-to_days(now())<=" . $p;
			$where_clu .= " and (((cvs.type = 'On' or cvs.type = 'Before' or cvs.type = 'Loop') and to_days(now())-to_days(cvs.followupdate)<=0 and to_days(cvs.followupdate)-to_days(now())=" . $p .") or (cvs.type = 'After' and to_days(date_add(now(), interval ".$p." day)) - to_days(cvs.followupdate)>=2 ))";
			$hint_text .= $p.'日应联系';
		}else if($method == "not"){
			$where_clu .= " and actived = 'on' ";
			$where_clu .= " and to_days(now())-to_days(cvs.followupdate) between 0 and " . $p;
			$hint_text .= $p.'日内未联系';
		}else if($method == "must"){//这个规则出现矛盾，需要修改。今天临时加了actived的条件。应该是最后联系超过3个月并且没有提醒联系的。
			$where_clu .= " and actived = 'on' ";
			$where_clu .= " and to_days(now())-to_days(cvs.followupdate)>=" . $p;
			$hint_text .= $p.'日之前未联系';
		}else if($method == "never"){
			$where_clu .= " and logdate is null and cvs is null and followupdate is null ";
			$hint_text .= '从未联系';
		}else if($method == "already"){
			$where_clu .= " and to_days(now())-to_days(cvs.logdate) >=0 and to_days(now())-to_days(cvs.logdate)<=" . $p;
			$hint_text .= $p.'日已联系';
		}else if($method == "new"){
			$where_clu .= " and to_days(now())-to_days(ct.insertdate) >=0 and to_days(now())-to_days(ct.insertdate)<=" . $p;
			$hint_text .= $p.'日新增的';
		}
	}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs = 6;
$pageNum_rs = 0;
if (isset($_GET['pageNum_rs'])) {
  $pageNum_rs = $_GET['pageNum_rs'];
}
$startRow_rs = $pageNum_rs * $maxRows_rs;

mysql_select_db($database_localhost, $localhost);
mysql_query("SET NAMES UTF8");
//$query_rs = "SELECT * FROM contact ".$where_clu." ORDER BY id DESC";
$query_rs = "SELECT *, ct.id as c_id, ct.insertdate as ct_insertdate from contact as ct left join conversation as cvs on cvs.ct_id=ct.id ".$where_clu." group by ct.id order by followupdate desc, cat desc";
$query_limit_rs = sprintf("%s LIMIT %d, %d", $query_rs, $startRow_rs, $maxRows_rs);
$rs = mysql_query($query_limit_rs, $localhost) or die(mysql_error());
$row_rs = mysql_fetch_assoc($rs);
//echo $query_limit_rs;

if (isset($_GET['totalRows_rs'])) {
  $totalRows_rs = $_GET['totalRows_rs'];
} else {
  $all_rs = mysql_query($query_rs);
  $totalRows_rs = mysql_num_rows($all_rs);
}
$totalPages_rs = ceil($totalRows_rs/$maxRows_rs)-1;

$queryString_rs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs") == false && 
        stristr($param, "totalRows_rs") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs = sprintf("&totalRows_rs=%d%s", $totalRows_rs, $queryString_rs);

function getLanguage($lang){
	if($lang == "Chinese"){
		return "国";
	}else if($lang == "Cantonese"){
		return "粤";
	}else if($lang == "English"){
		return "英";
	}
}
?>
<?php //echo $query_rs;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Contact List</title>
<link href="./css.css" rel="stylesheet" type="text/css">
<script language="javascript" src="./js/common.js"></script>
<style type="text/css">
<!--
.STYLE2 {color: #FFFFFF}
-->
</style>
<script language="javascript">
<!--
var rows = 6;
function getCVS(i,o){
	for(var j=0;j<rows;j++){
		document.getElementById(j).style.backgroundColor=(j%2==0)?"#ffffff":"#f3f3f3";
	}
	o.style.backgroundColor="#CADB2A";
	//alert(window.bottomFrame);
	self.parent.frames["bottomFrame"].location.href= 'fr_btm.php?ct_id='+i;
	self.parent.frames["bottomFrameRight"].location.href= 'fr_btm_hse.php?ct_id='+i;
	return;
}
//-->
</script>
<style type="text/css">
<!--
#mainList {
	
	left:0px;
	top:18px;
	width:100%;
	height:265px;
	z-index:1;
	overflow:auto;
}
-->
</style>
</head>

<body><table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
          <td width="33%" align="left" valign="top">Records <?php echo ($startRow_rs + 1) ?> to <?php echo min($startRow_rs + $maxRows_rs, $totalRows_rs) ?> of <?php echo $totalRows_rs ?> <b><?php echo $hint_text;?></b></td>
          <td width="45%" align="center" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="23%" align="center"><?php if ($pageNum_rs > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rs=%d%s", $currentPage, 0, $queryString_rs); ?>"><img src="First.gif" alt="" border=0></a>
              <?php } // Show if not first page ?> </td>
              <td width="31%" align="center"><?php if ($pageNum_rs > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rs=%d%s", $currentPage, max(0, $pageNum_rs - 1), $queryString_rs); ?>"><img src="Previous.gif" alt="" border=0></a>
              <?php } // Show if not first page ?></td>
              <td width="23%" align="center"><?php if ($pageNum_rs < $totalPages_rs) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rs=%d%s", $currentPage, min($totalPages_rs, $pageNum_rs + 1), $queryString_rs); ?>"><img src="Next.gif" alt="" border=0></a>
              <?php } // Show if not last page ?></td>
              <td width="23%" align="center"><?php if ($pageNum_rs < $totalPages_rs) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rs=%d%s", $currentPage, $totalPages_rs, $queryString_rs); ?>"><img src="Last.gif" alt="" border=0></a>
              <?php } // Show if not last page ?></td>
            </tr>
          </table></td>
          <td width="22%" valign="top"><span class="Mgr_Heading">&nbsp;&nbsp;&nbsp;<a href="ct_add.php">New Contact &gt;&gt; </a></span><input name="textfield" type="text" id="textfield" value="<?php echo $query_limit_rs;?>" size="2">
         </td>
  </tr>
      </table>
      <div id="mainList">
<table width="98%" border="0" cellspacing="1" cellpadding="1" bgcolor="#999999" id="maintable">
        <tr>
          <td width="1%" bgcolor="#4E550F"><span class="STYLE2">&nbsp;</span></td>
          <td width="6%" bgcolor="#4E550F"><span class="STYLE2">分类</span></td>
          <td width="10%" bgcolor="#4E550F"><span class="STYLE2">姓名</span></td>
          <td width="11%" bgcolor="#4E550F"><span class="STYLE2">联系电话</span></td>
          <td width="31%" bgcolor="#4E550F"><span class="STYLE2"> MDT|备注|(订阅)电邮</span></td>
          <td width="3%" bgcolor="#4E550F"><span class="STYLE2">正式</span></td>
          <td width="21%" bgcolor="#4E550F"><span class="STYLE2">地址</span></td>
          <td width="13%" bgcolor="#4E550F"><span class="STYLE2">日期</span></td>
        </tr>
        <?php
		$col = "#FFFFFF";
		$i=0;
		 do { ?>
          <tr id="<?php echo $i;?>" bgcolor="<?php echo $col;?>" onClick="javascript:getCVS(<?php echo $row_rs['c_id']; ?>, this);">
          	<?php 
			$frd = "#FFFFFF";
			if($row_rs['friendship']=="1"){$frd="#BFFFFF";}
			if($row_rs['friendship']=="2"){$frd="#9CFF8E";}
			if($row_rs['friendship']=="3"){$frd="#FFFF99";}
			if($row_rs['friendship']=="4"){$frd="#FFB0FF";}
			if($row_rs['friendship']=="5"){$frd="#FF9933";}
			?>
            <td bgcolor="<?php echo $frd;?>"><?php echo $row_rs['friendship']; ?></td>
            <td align="center"><?php echo $row_rs['cat']; ?><br>
            <b><?php echo $row_rs['status']; ?></b></td>
            <td>(<?php echo getLanguage($row_rs['language']); ?>) <a href="ct_edit.php?<?php echo $_SERVER['QUERY_STRING'];?>&id=<?php echo $row_rs['c_id']; ?>"><?php echo $row_rs['lastname']; ?> <?php echo $row_rs['firstname']; ?> <?php echo $row_rs['gender']; ?></a></td>
            <td><a href="wtai://wp/mc;<?php echo str_replace(" ","",str_replace("-","",$row_rs['homephone']));?>"><?php if ($row_rs['homephone']!="") {echo "H:".$row_rs['homephone']."<br>";} ?></a>
              <a href="tel:<?php echo str_replace(" ","",str_replace("-","",$row_rs['mobilephone']));?>"><?php if($row_rs['mobilephone']!="") {echo "M:".$row_rs['mobilephone']."<br>";} ?></a>
              <a href="wtai://wp/mc;<?php echo str_replace(" ","",str_replace("-","",$row_rs['otherphone']));?>"><?php if($row_rs['otherphone']!="") {echo "O:".$row_rs['otherphone'];} ?></a></td>
            <td>[<?php echo $row_rs['source']; ?>]<strong>
            <a href="http://raywhite.mydesktop.com.au/cgi-bin/clients/agents/version6/addressbook/newaddeditcontactform.cgi?contactid=<?php echo $row_rs['MDT']; ?>" target="_blank"><?php echo $row_rs['MDT']; ?></a>
            <?php
            if($row_rs['Newsletter']=='on'){ echo " 订阅"; }
            if($row_rs['unsub']=='on'){ echo " 退订"; }
			?></strong>
            <a href="mailto:<?php echo $row_rs['email']; ?>" target="_blank"><?php echo $row_rs['email']; ?></a><br>
            <?php echo $row_rs['memo']; ?></td>
            <td><?php echo $row_rs['isclient']; ?></td>
            <?php 
			$tmpadd = $row_rs['address']. " " .$row_rs['suburb']. " ". $row_rs['city'];
			?>
            <td><a href="http://maps.google.co.nz/maps?q=<?php echo str_replace(" ","+",$tmpadd);?>" target="_blank">
			<?php echo $row_rs['address']."&nbsp;"; ?> <?php echo $row_rs['suburb']."&nbsp;"; ?> <?php echo ($row_rs['city']=="Auckland")?"":$row_rs['city']; ?></a><br><span style="font-size:10px;"><?php echo $row_rs['propertydesc']; ?></span>
            </td>
            <td>
			 录: <?php echo $row_rs['ct_insertdate']; ?><br><?php if($row_rs['followupdate']!=null){ echo $row_rs['type']." ".$row_rs['followupdate']; }?></td>
          </tr>
          <?php
  		if($col == "#FFFFFF"){
			$col = "#F3F3F3";
		}else{
			$col = "#FFFFFF";
		}
		$i++;
		
		 } while ($row_rs = mysql_fetch_assoc($rs)); ?>
      </table>
      <script language="javascript">
      <!--
	  rows = <?php echo $i;?>;
	  //-->
      </script>
</div>
</body>
</html>
<?php
mysql_free_result($rs);
mysql_free_result($all_rs);
?>
