<?php
include_once('../Connections/localhost.php');

$id = $_GET['msid'];

//$arr = array('\"', '\'','\\');
//$arr2 = array('\\""', "\\''",'\\\\');

$id = $_GET['msid'];
try{
    if ($stmt = $mysqli->prepare("select msid,ssid,msNamespaceCode, msNamespace, msDisplayCode,msDisplayName,msGroup,msKeyword, msPlace, msPeople,msText from mysutra where msid=?")) {
        /* bind parameters for markers */
        $stmt->bind_param("s", $id);
        /* execute query */
        $stmt->execute();
        /* bind result variables */
    //$stmt->bind_result($ssid, $msNamespaceCode, $msNamespace, $msDisplayCode,$msDisplayName,$msGroup,$msKeyword, $msPlace, $msPeople,$msText);
    }

    $search_result = array();
    $rs = $stmt->get_result();

    /* fetch values 
    while ($stmt->fetch()) {
        $search_result[] = $stmt->get_result();
    }*/

    if(!empty($rs)){
        while($row = $rs->fetch_assoc()){
            $search_result[] = $row;
        }
    }else{
        throw new Exception('rs empty.');
    }
    
    echo json_encode($search_result);

        /* close statement */
    $stmt->close();

}catch( Exception $e){
    echo "[{\"result\":\"0\"}]";
}
  ?>
