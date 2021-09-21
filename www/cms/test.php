<?php
include_once("../Connections/localhost.php");

//创建ssid=1的经集的目录结构
//$id = $_GET['msid'];
$lv = 3;
$ssid = "1";
try{
      //get 2nd Lv of Chapter
      //$childchapter = array();
      if($stmtchild = $mysqli->prepare("select chpid, ssid, prtid, chpLevel, chpCode, chpName, chpDesc from chapter where ssid = ? and chplevel = 2 order by prtid, chpCode")){
        $stmtchild->bind_param("s",$_SESSION["SS_ID"]);
        $stmtchild->execute();
        $rs = $stmtchild->get_result();

        //創建一個3維數組，prtid:
        $cur_prtid = 0;
        while ($row = $rs->fetch_assoc()){
          //into the array
          if($cur_prtid !== $row['prtid']){
            //creat a now group
            if(!empty($grouparr)){ $childchapter[$cur_prtid] = $grouparr; } //旧的中包放进大包,以prtid为键
            $grouparr = array();//新的中包
            $cur_prtid = $row['prtid']; //更新当前的父id

          }

            $arr["chpid"] = $row['chpid'];
            $arr["ssid"] = $row['ssid'];
            $arr["prtid"] = $row['prtid'];
            $arr["chpLevel"] = $row['chpLevel'];
            $arr["chpCode"] = $row['chpCode'];
            $arr["chpName"] = $row['chpName'];
            $arr["chpDesc"] = $row['chpDesc'];

            $grouparr[] = $arr; //小包放进中包

        }
        
        if(!empty($grouparr)){ $childchapter[$cur_prtid] = $grouparr; } //别忘了把最后一个中包也放进大包,以prtid为键

      }



      
      //get first lv of Chapters
      if ($stmt = $mysqli->prepare("select chpid,ssid, chpLevel, chpCode, chpName, chpDesc from chapter where ssid = ? and chplevel = 1 order by chpCode")) {
        /* bind parameters for markers */
        $stmt->bind_param("s", $_SESSION["SS_ID"]);
        
        /* execute query */
        $stmt->execute();
        /* bind result variables */
        $stmt->bind_result($chpid, $ssid, $chpLevel, $chpCode, $chpName, $chpDesc);
      }



      while ($stmt->fetch()) {
    ?>
      <h3 class="ms_lv1_<?php echo $chpid;?>" sn=""><?php echo $chpCode;?> <?php echo $chpName;?></h3>
      <div>
        <!--
        <ul>
        <?php 
          $garr = array();
          if(!empty($childchapter[$chpid])){ 
            $garr = $childchapter[$chpid];    //从大包直接取出以自己chpid作为prtid的中包
          }
          foreach($garr as $arr =>$val){
        ?>
          <li>
            (<?php echo $_SESSION["SS_CODE"];?> <?php echo $val["chpCode"];?>) 
            <?php echo $val["chpName"];?>
          </li>
          <?php };?>
        </ul>
        -->
      </div>
    <?php
    
      } 
        /* close statement */
        $stmt->close();

}catch(Exception $e){
  echo $e->getMessage();
}
?>
