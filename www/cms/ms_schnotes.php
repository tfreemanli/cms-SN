<?php
session_start();
include_once('../Connections/localhost.php');

try{
		$debugmode = (isset($_GET['debugmode']) && $_GET['debugmode']=='on')?true:false;
		if($debugmode) echo '[Begin] SCH_MODE:'. $_SESSION['SCH_MODE'].' <br> SCH_ORDERBY:'.$_SESSION['SCH_ORDERBY'].'<br> SCH_COND:'.$_SESSION['SCH_COND'].' SS_ID:'.$_SESSION['SS_ID'].$_SESSION['Name'].'<br>';


		$sql = 'select msid,msNamespaceCode, msNamespace, msDisplayCode, msDisplayName, msKeyWord from mysutra ';

		//如果参数有sch_mode则先考虑用参数的sch_mode，对refresh和changeorder两种特殊情况稍后if来判断。
		$sch_mode = 'init';		
		if(isset($_GET['sch_mode'])) $sch_mode = $_GET['sch_mode'];

		$cond_str = '';
		$orderby = ' order by msNamespaceCode desc';
		if(isset($_SESSION['SCH_ORDERBY']) && $_SESSION['SCH_ORDERBY']!= ''){
			$orderby = $_SESSION['SCH_ORDERBY'];
		}
		if(isset($_GET['orderby']) && $_GET['orderby']!= ''){
			$orderby = $_GET['orderby'];
		}

		$stmt = null;
		$numRow = 0;
		$listinfo = '';


		/**
		 * Refresh the search
		 * Just restore the search condition from SESSION;
		 */
		if(isset($_GET['sch_mode']) && $_GET['sch_mode']=='refresh'){
			//get search mode and condition from SESSION, and chk if it's orderby changed.
			$sch_mode = $_SESSION['SCH_MODE'];
			$cond_str = $_SESSION['SCH_COND'];
			$orderby = $_SESSION['SCH_ORDERBY'];

			if($debugmode) echo '[refresh] use session:'.$sch_mode.' and orderby from session:'.$orderby.'<br>';
		}
		
		
		
		if(isset($_GET['sch_mode']) && $_GET['sch_mode']=='changeorder'){
			//GET and update the search condition
			$sch_mode = $_SESSION['SCH_MODE'];
			$cond_str = $_SESSION['SCH_COND'];

			$orderby = $_GET['orderby'];
			$_SESSION['SCH_ORDERBY'] = $orderby;
			
			if($debugmode) echo '[changeorder] old sch_mode:'.$sch_mode.' <br> Session schmode:'. $_SESSION['SCH_MODE'].' <br> orderby set to session:'.$orderby.'<br>';
		}


		/**
		 * Request from Nav Sch
		 */
		if($sch_mode=='schword' ){
			$cond_str = $_GET['cond'];
			$cond = json_decode($cond_str);
			$schword = "%{$cond->sch}%";
			$listinfo .= " 搜索[ {$cond->sch} ] ";
			$sql .= " where msText like ? or msPlace like ? or msPeople like ? or msDisplayCode like ? or msNamespace like ? ".$orderby;
			if($debugmode) echo '[schword] sch:['.$schword.'] in {$cond_str}:<br>'.$sql.'<br>';
		
			if ($stmt = $mysqli->prepare($sql)) {
				/* bind parameters for markers */
				$stmt->bind_param("sssss", $schword,$schword, $schword,$schword,$schword);
				
				/* execute query */
				$stmt->execute();
				/* bind result variables */
				$stmt->bind_result($msid, $msNamespaceCode, $msNamespace, $msDisplayCode, $msDisplayName, $msKeyWord);
				$_SESSION['SCH_ORDERBY'] = $orderby;
				$_SESSION['SCH_COND'] = $cond_str;
				$_SESSION['SCH_MODE'] = $sch_mode;

			}

		}

		/**
		 * Initial search
		 */
		if($sch_mode=='init'){
			$id = '0';
			$sql = "select msid,msNamespaceCode, msNamespace, msDisplayCode, msDisplayName, msKeyWord from mysutra %s ".$orderby;
			$where_clau = ' where msid>0 ';

				if(isset($_SESSION['SS_ID'])){
					$id=$_SESSION['SS_ID'];
				}

			if($debugmode) echo '[SS_ID]:'. $id;
			//Temp set id=1
			//$id = '1';

			$where_clau .= ' and ssid=? ';
			$sql = sprintf($sql, $where_clau);

			if($debugmode) echo '[Init]:'. $sql;

			if ($stmt = $mysqli->prepare($sql)) {
				/* bind parameters for markers */
				$stmt->bind_param("s", $id);
				
				/* execute query */
				$stmt->execute();
				/* bind result variables */
				$stmt->bind_result($msid, $msNamespaceCode, $msNamespace, $msDisplayCode, $msDisplayName, $msKeyWord);

				$_SESSION['SCH_ORDERBY'] = $orderby;
				$_SESSION['SCH_MODE'] = $sch_mode;
			}
		}

		/**
		 * Search for Chapter selected
		 */
		if($sch_mode=='bychapter'){
			$id = '0';
			$sql = 'select msid,msNamespaceCode, msNamespace, msDisplayCode, msDisplayName, msKeyWord from mysutra %s '.$orderby;
			$where_clau = ' where msid>0 ';
			

			if(isset($_SESSION['SS_ID']) && isset($_SESSION['SS_CODE']) && isset($_SESSION['SS_VERCODE']) && isset($_SESSION['SS_CODE'])){
				$where_clau .= ' and ssid='.$_SESSION['SS_ID'];
				$tmp = $_SESSION['SS_CODE'].'.'.$_SESSION['SS_VERCODE'];
				foreach($_SESSION['NS_CODE'] as $value){ 
					if($value!='') $tmp .= '.'.$value; 
				}
				$where_clau .= ' and msNamespaceCode like \''. $tmp .'%\' ';
			}

			$sql = sprintf($sql, $where_clau);
			if($debugmode) echo '[bychapter]: '.$sql.'<br>';

			if ($stmt = $mysqli->prepare($sql)) {
				/* bind parameters for markers */
				//$stmt->bind_param("s", $id);
				
				/* execute query */
				$stmt->execute();
				/* bind result variables */
				$stmt->bind_result($msid, $msNamespaceCode, $msNamespace, $msDisplayCode, $msDisplayName, $msKeyWord);
				$_SESSION['SCH_ORDERBY'] = $orderby;
				$_SESSION['SCH_MODE'] = $sch_mode;

			}
		}

		
		if($stmt != null){
			/* fetch values =============================================*/
			while ($stmt->fetch()) {
				?>
					<div id="note<?php echo $msid;?>" class="under_line note_item light_border">
						<div style="margin-left: 15px; padding:2px">	
							<h3><?php echo $msDisplayCode;?> <?php echo $msDisplayName;?> </h3>	
							<span style="font-size:10px"><?php echo $msNamespace;?></span>
						</div>					
						<div style="margin-top: 2px; padding:5px">
							<p><?php echo $msKeyWord;?></p>
						</div>
					</div>
				<?php 
			} 

			$numRow = $stmt->num_rows;
			$listinfo .= '共 '.$numRow.' 筆';
			$_SESSION['SCH_LISTINFO'] = $listinfo;

			/* close statement */
			$stmt->close();
		}
		//unset($stmt);

}catch(Exception $e){
	echo $e->getMessage();
}
?>