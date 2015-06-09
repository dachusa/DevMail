<?php
	$rootpath = $_SERVER["DOCUMENT_ROOT"];
	require_once($rootpath."/app/Bootstrap.php");
	
	include_once($rootpath."/layouts/header.php");
	$mail = new Mail();
	
?>
	<style type="text/css">
		.list-group-item-text{
			margin-left:40px;
		}
		.list-group-item-heading span{
			margin-right:10px;
		}
	</style>
	<div class="row" style="margin-top:20px">
		<div class="list-group">
		  <a href="#" class="list-group-item">
			<h4 class="list-group-item-heading"><span class="fui-gear"></span>Configure Dev Mail</h4>
			<p class="list-group-item-text">All you need to do, is point your systems SMTP server at the IP of this site</p>
		  </a>
		  <a href="#" class="list-group-item">
			<h4 class="list-group-item-heading"><span class="fui-time"></span>Wait Time</h4>
			<p class="list-group-item-text">The wait time is the time it takes to get from your staging site to the PostFix queue.  Once it is in the queue, you reloading the page will parse the queue</p>
		  </a>
		  <a href="#" class="list-group-item">
			<h4 class="list-group-item-heading"><span class="fui-chat"></span>How does it work?</h4>
			<p class="list-group-item-text">Dev Mail receives email via PostFix as a standard SMTP server.  
					Mail however is only allowed to come in and is specifically denied going out.  
					This causes PostFix to generate a queue of emails to send.  
					Then there is a service written in PHP that will parse the queue and remove items once they are received.
					This service runs once every minute and once for each time you reload the inbox page</p>
		  </a>
		</div>		
	</div>
<?php
	include_once($rootpath."/layouts/footer.php");
?>