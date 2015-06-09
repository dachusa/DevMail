<?php
	$rootpath = $_SERVER["DOCUMENT_ROOT"];
	if(!isset($_GET['queueID'])){
		if(isset($_POST['queueID'])){
			require_once($rootpath."/app/Bootstrap.php");
			$mail = new Mail();
			foreach($_POST['queueID'] as $queueID){
				if($mail->DeleteByQueueID($queueID)){
					$siteAlert = "<div class=\"alert alert-success\">Message Deleted</div>";
				}else{
					$siteAlert = "<div class=\"alert alert-danger\">Message Not Deleted</div>";
				}
			}		
		}else{
			$siteAlert = "<div class=\"alert alert-danger\">Invalid Request</div>";
		}
	}else{
		$queueID = $_GET['queueID'];
		require_once($rootpath."/app/Bootstrap.php");
		
		$mail = new Mail();
		if($mail->DeleteByQueueID($queueID)){
			$siteAlert = "<div class=\"alert alert-success\">Message Deleted</div>";
		}else{
			$siteAlert = "<div class=\"alert alert-danger\">Message Not Deleted</div>";
		}
	}
	
	include($rootpath."/index.php");
?>