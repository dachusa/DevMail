<?php
if($_SERVER['REMOTE_ADDR']=="10.200.51.120"){	
	$to = $_POST['to'];
	if(isset($_POST['cc']))
		$cc = $_POST['cc'];
	if(isset($_POST['bcc']))
		$bcc = $_POST['bcc'];
	$sender = $_POST['sender'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: $sender\r\n";
	if(isset($cc))
		$headers .= "Cc: $cc\r\n";
	if(isset($bcc))	
		$headers .= "Bcc: $bcc\r\n";
	
	echo mail($to,$subject,$message,$headers);
	
}else{
	header('HTTP/1.0 403 Forbidden');
}
?>
