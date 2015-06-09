<?php
	if($_GET["key"]=="Siu7NjqfbILGw"){
		$rootpath = $_SERVER["DOCUMENT_ROOT"];
		require_once($rootpath."/app/Bootstrap.php");
		
		$postfix = new PostfixToDB();
		$queueIDs = $postfix->GetQueueIDs();
		
		foreach($queueIDs as $queueID){
			$postfix->StoreEmail($queueID);
		}	
	}
?>