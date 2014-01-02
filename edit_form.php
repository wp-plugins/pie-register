<?
//Printing Success Message
if($success != "")
	echo '<p class="message">'.$success.'</p>';
if($form->error != "")
	echo '<p class="login_error">'.$form->error.'</p>';	
?>


<form enctype="multipart/form-data" id="pie_regiser_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<ul id="pie_register">
<?
	$form->printFields($current_user);
?>
</ul>	
</form>
