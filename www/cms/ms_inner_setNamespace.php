<?php 
session_start();

$tmpcode = $_SESSION['SS_CODE'].'.'.$_SESSION['SS_VERCODE'];
foreach($_SESSION['NS_CODE'] as $value){
	if($value!=null && $value != '') $tmpcode = $tmpcode.'.'.$value;
}

$tmpname = '';
foreach($_SESSION['NS_NAME'] as $value){
	if($value!=null && $value != '') $tmpname = $tmpname.'.'.$value;
}

//去掉NS中第一个“.”
//echo strpos($tmpname,'.');
if(strpos($tmpname,'.')==0){
	//echo strpos($tmpname,'.');
	$tmpname= substr_replace($tmpname,'',0,1);
}

?>
<p>當前位置： <span style='font-weight:bold;'><?php echo $_SESSION['SS_NAME'];?> . <?php echo $tmpname;?></span> (<span><?php echo $tmpcode;?></span> ) <?php echo round(memory_get_usage()/1024,1) ;?> Kb</p>
<input type="hidden" id="tmpNSCode" value="<?php echo $tmpcode;?>"><input type="hidden" id="tmpNSName" value="<?php echo $tmpname;?>">
<?php
unset($tmpcode);
unset($tmpname);
?>