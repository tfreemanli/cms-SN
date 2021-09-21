<?php
include_once('../Connections/localhost.php');

//$selectSsCode = isset($_SESSION['SS_CODE'])?$_SESSION['SS_CODE']:'SN';

if ($stmt = $mysqli->prepare('select ssid,verCode,ssCode,ssName from sutraset')) {
	/* bind parameters for markers */
	//$stmt->bind_param("s", $selectSsCode);
	/* execute query */
	$stmt->execute();
	/* bind result variables */
  $stmt->bind_result($ssid,$verCode, $ssCode, $ssName);
}

/* fetch values */
while ($stmt->fetch()) {
?>
      <option value="<?php echo $ssid;?>"><?php echo $ssCode;?>.<?php echo $verCode;?>  <?php echo $ssName;?></option>
<?php 
} 

	/* close statement */
  $stmt->close();
  //unset($stmt);
  
  ?>