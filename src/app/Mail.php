<?php
	class Mail extends BaseObject{
		public $queueID;
		public $sender;
		public $to;
		public $message;
		public $sentTime;
		public $originIP;
		public $originName;
		public $subject;
		public $raw;
		public $account;
		
		public function GetSentTime(){
			$date = new DateTime($this->sentTime);
			return $date->format('m/d/Y h:i:s A');
		}
		
		public function GetMail($page, $limit){
			$offset = $page*$limit;
			$db = new DB();
			$sqlStatement = "SELECT 
											`queueID`, 
											`sender`, 
											`to`, 
											`sentTime`, 
											`originIP`, 
											`originName`, 
											`subject`, 
											`account` 
										FROM email 
										WHERE `deleted` = 0
										ORDER BY sentTime DESC
										LIMIT :offset, :count
										";
			$sqlParameters = Array(
				new SQLParameter(":offset", $offset, "int"),
				new SQLParameter(":count", $limit, "int")
			);
			
			$mailRows = $db->Query($sqlStatement, $sqlParameters);
			
			$mailObjects = Array();
			foreach($mailRows as $mailRow){
				$mailObjects[] = new Mail($mailRow);
			}
			
			return $mailObjects;
		}
		
		public function GetMailCount(){
			$db = new DB();
			$sqlStatement = "SELECT 
											`queueID`
										FROM email 
										WHERE `deleted` = 0";
			$sqlParameters = Array();
			
			return $db->QueryCount($sqlStatement, $sqlParameters);
		}
		
		public function GetMessageByQueueID($queueID){
			$db = new DB();
			$sqlStatement = "SELECT 
											`queueID`, 
											`sender`, 
											`to`, 
											`sentTime`, 
											`originIP`, 
											`originName`, 
											`subject`, 
											`account`, 
											`message`,
											`raw`
										FROM email 
										WHERE queueID=:queueID 
											AND `deleted` = 0";
			$sqlParameters = Array(
				new SQLParameter(":queueID", $queueID, "string")
			);
			
			$mailRows = $db->Query($sqlStatement, $sqlParameters);
			
			$mailObjects = Array();
			foreach($mailRows as $mailRow){
				$mailObjects[] = new Mail($mailRow);
			}
			
			if(count($mailObjects)==1){
				return $mailObjects[0];
			}else{
				return new Mail();
			}
		}
		
		public function DeleteByQueueID($queueID){
			$db = new DB();
			$sqlStatement = "UPDATE email 
										SET deleted = 1
										WHERE queueID=:queueID";
			$sqlParameters = Array(
				new SQLParameter(":queueID", $queueID, "string")
			);
			
			return $db->Execute($sqlStatement, $sqlParameters);
		}
		
		public function Send(){
			if($this->Get('queueID') == ""){
				return "Message must be loaded";
			}
			
			$url = "http://devmail.local";
			
			$fields = array(
				'queueID' => $this->Get('queueID'),
				'sender' => $this->Get('sender'),
				'to' => $this->Get('to'),
				'message' => $this->Get('message'),
				'sentTime' => $this->Get('sentTime'),
				'originIP' => $this->Get('originIP'),
				'originName' => $this->Get('originName'),
				'subject' => $this->Get('subject'),
				'raw' => $this->Get('raw'),
				'account' => $this->Get('account')
			);

			$postvars='';
			$sep='';
			foreach($fields as $key=>$value)
			{
					$postvars.= $sep.urlencode($key).'='.urlencode($value);
					$sep='&';
			}

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

			$result = curl_exec($ch);

			curl_close($ch);

			return $result;
			
		}
		
	}
?>