<?php
	$rootpath = $_SERVER["DOCUMENT_ROOT"];
	require_once($rootpath."/app/Bootstrap.php");
	
	include_once($rootpath."/layouts/header.php");
	$mail = new Mail();
	
	if(isset($_GET['page']) && is_numeric($_GET['page'])){
		$page = ($_GET['page'] > 0) ? $_GET['page']-1 : 0;
	}else{
		$postfix = new PostfixToDB();
		$queueIDs = $postfix->GetQueueIDs();
			
		foreach($queueIDs as $queueID){
			$postfix->StoreEmail($queueID);
		}	
		$page = 0;
	}
	
	$limit = 30;
	$emails = $mail->GetMail($page, $limit);
	$page++;
	$emailCount = $mail->GetMailCount();
?>
	<?php if($emailCount > $limit){
		buildPagination($page, $limit, $emailCount);
	 } ?>
	 <style type="text/css">
		.actionRow{
			margin:5px 0px;
		}
	</style>
	<form action="/deleteMail.php" method="post">
		<div class="row actionRow">
			<div class="col-md-2 pull-right">
				<input type="submit" value="Delete Checked" class="btn btn-block btn-sm btn-danger"/>
			</div>
			<div class="col-sm-3 pull-right">
				<a href="#" class="checkAll">
					<span class="icons">
						<span class="first-icon fui-checkbox-checked"></span>
					</span>
					Check All / Uncheck All
				</a>
			</div>
		</div>
		<div class="row">
			<table class="table table-striped">
				<tr>
					<th></th>
					<th>Subject</th>
					<th>From</th>
					<th>To</th>
					<th>Date Sent</th>
					<th>Raw</th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach($emails as $email){?>
				<tr>
					<td>
						<label class="checkbox" for="checkbox_<?php echo $email->Get('queueID');?>">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
							<input type="checkbox" name="queueID[]" value="<?php echo $email->Get('queueID');?>" id="checkbox_<?php echo $email->Get('queueID');?>"  data-toggle="checkbox"/>
					  </label>
					
					<td><a href="/viewMail.php?queueID=<?php echo $email->Get('queueID');?>" target="_blank"><?php echo $email->Get('subject');?></a></td>
					<td><?php echo $email->Get('sender');?></td>
					<td><?php echo $email->Get('to');?></td>
					<td><?php echo $email->Get('sentTime');?></td>
					<td><a href="/viewRaw.php?queueID=<?php echo $email->Get('queueID');?>" target="_blank">Raw</a></td>
					<td><a href="/sendMail.php?queueID=<?php echo $email->Get('queueID');?>" class="palette-peter-river btn btn-default btn-md"><span class="glyphicon glyphicon-send"></span></a></td>
					<td><a href="/deleteMail.php?queueID=<?php echo $email->Get('queueID');?>" class="palette-alizarin btn btn-default btn-md"><span class="glyphicon glyphicon-trash"></span></a></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<?php if($emailCount > 12){?>
		<div class="row actionRow">
			<div class="col-md-2 pull-right">
				<input type="submit" value="Delete Checked" class="btn btn-block btn-sm btn-danger"/>
			</div>
			<div class="col-sm-3 pull-right">
				<a href="#" class="checkAll">
					<span class="icons">
						<span class="first-icon fui-checkbox-checked"></span>
					</span>
					Check All / Uncheck All
				</a>
			</div>
		</div>
		<?php } ?>
	</form>
	<?php if($emailCount > $limit && $emailCount > 12){
		buildPagination($page, $limit, $emailCount);
	  } ?>
	</div>
<?php
	include_once($rootpath."/layouts/footer.php");
	
	
	function buildPagination($page, $limit, $count){
		$pages = $count/$limit;
		if($count%$limit > 0){
			$pages++;
		}
	?>
		<div class="pagination">
			<ul>
				<?php if($page>1){?><li class="previous"><a href="/?page=<?php echo $page-1;?>" class="fui-arrow-left"></a></li><?php } ?>
				<?php for($i=1; $i<=$pages; $i++){?>
					<li <?php if($i == $page){?>class="active"<?php  }?>><a href="/?page=<?php echo $i;?>"><?php echo $i;?></a></li>
				<?php } ?>
				<?php if($page<$pages){?><li class="next"><a href="/?page=<?php echo $page+1;?>" class="fui-arrow-right"></a></li><?php } ?>
			</ul>
		  </div>
	<?php
	}
?>
<script type="text/javascript">
	$(".checkAll").click(function(e){
		var checkbox = $(".checkbox");
		$.each(checkbox, function(){
			$(this).click();
		});
		e.preventDefault();
	});
</script>