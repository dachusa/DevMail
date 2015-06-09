<?php
	if(!isset($_GET['queueID'])){
		print "<h1>Invalid Request</h1>";
	}else{
		$queueID = $_GET['queueID'];
		$rootpath = $_SERVER["DOCUMENT_ROOT"];
		require($rootpath."/app/Bootstrap.php");
		
		$mail = new Mail();
		$email = $mail->GetMessageByQueueID($queueID);
		if($email->raw == ""){
			echo "<h1>Message Not Found</h1>";
		}else{
			echo "<pre>" . $email->raw . "</pre>";
		}
	}
?>