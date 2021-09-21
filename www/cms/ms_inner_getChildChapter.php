<?php session_start();?>
<option value="all">全部</option>
<?php 
	include_once('../Connections/localhost.php');
	$debugmode = (isset($_GET['debugmode']) && $_GET['debugmode']=='on')?true:false;
//$_SESSION['NS_CODE'] = array('2','1','2');
//$_SESSION['NS_NAME'] = array('因缘篇','因缘相应','佛陀品');

    try{
          $schword = 'a';

          /**
           * Request from Nav Sch
           */
          if(isset($_GET['prtid']) && $_GET['prtid']!=='all' ){
                
                $schword = $_GET['prtid'];
                $Code = substr($_GET['prtcnn'],0,strpos($_GET['prtcnn'],' ')); //提取从开头至空格
                $Name = substr($_GET['prtcnn'],strpos($_GET['prtcnn'],' ')+1); //空格后开始提取

                updateNsInSession($_GET['prtlv'], $Code, $Name);
          
                if ($stmt = $mysqli->prepare("select chpid, chpCode, chpName from chapter where prtid = ? order by chpCode")) {
                    /* bind parameters for markers */
                    $stmt->bind_param("s", $schword);
                    
                    /* execute query */
                    $stmt->execute();
                    /* bind result variables */
                    $stmt->bind_result($chpid, $chpCode, $chpName);
                }
                
                /* fetch values */
                while ($stmt->fetch()) {
                ?>
                <option value="<?php echo $chpid;?>"><?php echo $chpCode;?> <?php echo $chpName;?></option>
                <?php 
                } 
                /* close statement */
                $stmt->close();
          }
        
        }catch(Exception $e){
          echo $e->getMessage();
        }

  function updateNsInSession($chplv, $chpCode, $chpName){
    $ns_code = $_SESSION['NS_CODE'];
	$ns_name = $_SESSION['NS_NAME'];
	if($debugmode) echo '<br>Code:'.$chpCode.'#<br>Name:'.$chpName;
    if($debugmode) echo '#<br>old code: '.var_dump($_SESSION['NS_CODE']);
    if($debugmode) echo '<br>old name: '.var_dump($_SESSION['NS_NAME']);
    //$chplv -= 1;

    if(!$chplv>0){ 
      //当父级为0,即当初始化NS处理
      foreach($ns_code as &$item){
        $item = '';
      }
      foreach($ns_name as &$item){
        $item = '';
      }
    }else{
        //write NS array
        if(!empty($ns_code) && (count($ns_code)>0)){
          //写入对应的命名空间，并把后面的命名空间清空
          $ns_code[$chplv-1] = $chpCode;
          //var_dump($ns_code);
          //echo $ns_code[0].$ns_code[1];
          //echo ':'.$chpCode.$chpName;
          for($i = $chplv; $i<count($ns_code); ++$i){
            $ns_code[$i] = '';
          }
        }
        if(!empty($ns_name) && (count($ns_name)>0)){
          //写入对应的命名空间，并把后面的命名空间清空
          $ns_name[$chplv-1] = $chpName;
          for($i = $chplv; $i<count($ns_name); ++$i){
            $ns_name[$i] = '';
          }
        }
    }

    $_SESSION['NS_CODE'] = $ns_code;
    $_SESSION['NS_NAME'] = $ns_name;
    if($debugmode) echo '<br>new code: '.var_dump($_SESSION['NS_CODE']);
    if($debugmode) echo '<br>new name: '.var_dump($_SESSION['NS_NAME']);

  }
?>