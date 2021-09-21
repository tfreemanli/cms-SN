<?php require_once('../Connections/localhost.php'); 

$errorMsg = '';

try{
  /**
   * 添加記錄
   */
  if ((isset($_POST['edit_mode'])) && ($_POST['edit_mode'] == 'form_addNote')) {
    
    if ($stmt = $mysqli->prepare("INSERT INTO mysutra (ssid,msNamespaceCode, msNamespace, msDisplayCode,msDisplayName,msGroup,msKeyword, msPlace, msPeople,msText,msDatetime) VALUES (?,?,?,?,?,?,?,?,?,?, NOW())")) {
          /* bind parameters for markers */
          $stmt->bind_param("isssssssss", $_POST['ssid'],$_POST['msNamespaceCode'],$_POST['msNamespace'],$_POST['msDisplayCode'],$_POST['msDisplayName'],$_POST['msGroup'],$_POST['msKeyword'],$_POST['msPlace'],$_POST['msPeople'],$_POST['msText']);
          /* execute query */
          $stmt->execute();
          $errorMsg = $stmt->error;
          $stmt->close();
          /* bind result variables */
          //$stmt->bind_result($ssid, $ssCode, $ssName);
      }

    $errorMsg .= 'Add Note Finished. ';
  }
  
  //echo '2';
  /**
   * 編輯記錄
   */

  if ((isset($_POST['edit_mode'])) && ($_POST['edit_mode'] == 'form_editNote')) {
    
    
    if ($stmt2 = $mysqli->prepare("UPDATE mysutra SET msNamespaceCode=?, msNamespace=?, msDisplayCode=?,msDisplayName=?,msGroup=?,msKeyword=?, msPlace=?, msPeople=?,msText=?, msDatetime=CURRENT_TIMESTAMP WHERE msid=?")) {
          /* bind parameters for markers */
          $stmt2->bind_param("sssssssssi", $_POST['msNamespaceCode'],$_POST['msNamespace'],$_POST['msDisplayCode'],$_POST['msDisplayName'],$_POST['msGroup'],$_POST['msKeyword'],$_POST['msPlace'],$_POST['msPeople'],$_POST['msText'],$_POST['msid']);
          /* execute query */
          $stmt2->execute();
          //error_log($stmt2->affected_rows());
          $errorMsg = $stmt2->error;
          //$errorMsg .= $stmt2->affected_rows();
          //echo '3';
          $stmt2->close();
          /* bind result variables */
          //$stmt->bind_result($ssid, $ssCode, $ssName);
      }

    //$errorMsg .= 'Edit Note Finished. ';
  }
}catch(Exeption $e){
  error_log($e->getMessage());
}
//console.log($errorMsg);
printf($errorMsg);
?>