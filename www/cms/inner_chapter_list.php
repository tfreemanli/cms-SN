<?php
include_once("../Connections/localhost.php");

$selectSsCode = "SN";

if ($stmt = $mysqli->prepare("select * from chapter where chpLevel=0 and ssCode = ?")) {
	/* bind parameters for markers */
	$stmt->bind_param("s", $selectSsCode);
	/* execute query */
	$stmt->execute();
	/* bind result variables */
  $stmt->bind_result($chpid, $chpLevel, $prtid, $ssCode, $chpCode, $chpName, $isNamespace, $chpDesc);
}

/* fetch values */
while ($stmt->fetch()) {
?>
      <option value="<?php echo $chpCode;?>"><?php echo $chpName;?></option>
<?php 
} 


	/* close statement */
  $stmt->close();
  
  ?>