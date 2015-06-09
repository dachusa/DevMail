<?php
	if(!isset($_GET['queueID'])){
		$siteAlert = "<div class=\"alert alert-danger\">Invalid Request</div>";
		$rootpath = $_SERVER["DOCUMENT_ROOT"];
		include($rootpath."/index.php");
	}else{
		$queueID = $_GET['queueID'];
		$rootpath = $_SERVER["DOCUMENT_ROOT"];
		require_once($rootpath."/app/Bootstrap.php");
		
		$mail = new Mail();
		$email = $mail->GetMessageByQueueID($queueID);
		if($email->message == ""){
			$siteAlert = "<div class=\"alert alert-danger\">Message Not Sent</div>";
		}else{
			$response = $email->Send();
			if($response=="1"){
				$siteAlert = "<div class=\"alert alert-success\">Message Sent</div>";
			}else{
				$siteAlert = "<div class=\"alert alert-danger\">Message Not Sent: $response</div>";
			}
		}
		include($rootpath."/index.php");
	}
?>