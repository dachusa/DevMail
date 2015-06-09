<?php

class PostfixToDB{

	public function GetQueueIDs(){
		$queueIDs = Array();
		$output = shell_exec("postqueue -p");
		//print "<pre>$output</pre><hr/>";
		
		if(strpos($output, "Mail queue is empty")===false){
			$outputArray = explode("\n", $output);
			if(count($outputArray)>0){
				for($i=1;$i<count($outputArray);$i++){
					$queueID = substr($outputArray[$i],0,8);
					if (ctype_alnum($queueID)) {
						$queueIDs[] = $queueID;
					}
				}
			}
		}
		return $queueIDs;
	}	
	
	public function StoreEmail($queueID){
		$output = shell_exec("sudo postcat -q ".$queueID . "  2>&1");
		
		$sender = self::RowExtract("From: ", $output);
		$to = self::RowExtract("To: ", $output);
		$message = self::ExtractMessage($output);
		$sentTime = self::RowExtract("message_arrival_time: ", $output);
		$originIP = self::RowExtract("named_attribute: client_address=", $output);
		$originName = self::RowExtract("named_attribute: helo_name=", $output);
		$subject = self::RowExtract("Subject: ", $output);
		$raw = $output;
		$account = "";
		
		$db = new DB();
		$sqlStatement = "INSERT INTO email (
									`queueID`,
									`sender`, 
									`to`, 
									`message`, 
									`sentTime`, 
									`originIP`, 
									`originName`, 
									`subject`, 
									`raw`, 
									`account`
									) 
								VALUES (
									:queueID, 
									:sender, 
									:to, 
									:message, 
									:sentTime, 
									:originIP, 
									:originName, 
									:subject, 
									:raw, 
									:account)";
		$sqlParameters = Array(
			new SQLParameter(":queueID", $queueID, "string"),
			new SQLParameter(":sender", $sender, "string"),
			new SQLParameter(":to", $to, "string"),
			new SQLParameter(":message", $message, "string"),
			new SQLParameter(":sentTime", $sentTime, "datetime"),
			new SQLParameter(":originIP", $originIP, "string"),
			new SQLParameter(":originName", $originName, "string"),
			new SQLParameter(":subject", $subject, "string"),
			new SQLParameter(":raw", $raw, "string"),
			new SQLParameter(":account", $account, "string")
		);
		//Insert queueID, sender, to, message, sentTime, originIP, originName, subject, raw, account
		$db->Execute($sqlStatement, $sqlParameters);
		self::RemoveEmailFromQueue($queueID);
	}
	
	public function RemoveEmailFromQueue($queueID){
		$output = shell_exec("sudo postsuper -d ".$queueID . "  2>&1");
		//print "<pre>" . $output . "</pre><hr/>";
	}
	
	private function RowExtract($needle, $haystack){
		$startIndex = strpos($haystack, $needle);
		$startIndex += strlen($needle);
		$truncatedHaystack = substr($haystack, $startIndex);
		$endIndex = strpos($truncatedHaystack, "\n");
		$data = substr($truncatedHaystack, 0, $endIndex);
		return $data;
	}
	
	private function ExtractMessage($raw){
		$rawLines = explode("\n", $raw);
		$messageLines = Array();
		$messageStarted = false;
		$messageEnded = false;
		$count=0;
		foreach($rawLines as $rawLine){
			if(!$messageStarted && !$messageEnded){
				if(!(strpos($rawLine, "Content-Transfer-Encoding: ")===false)){
					$messageStarted=true;
				}
			}else{
				if(!$messageEnded && $count>=2){
					if(!(strpos($rawLine, "*** HEADER EXTRACTED")===false)){
						$messageEnded=true;
					}else{
						$messageLines[] = $rawLine;
					}
				}
				$count++;
			}
		}
		return implode("\n", $messageLines);
	}
}
//postqueue -p
//postcat -q MESSAGE_ID
//postsuper  -d MESSAGE_ID
?>
