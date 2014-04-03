<?php
//Printing Success Message
if($success != "")
	echo '<p class="piereg_message">'.$success.'</p>';
if($form->error != "")
	echo '<p class="piereg_login_error">'.$form->error.'</p>';	
?>

<!--<h1 id="piereg_pie_form_heading">Profile</h1>
<a href="<?php //echo wp_logout_url(); ?>">Logout</a>-->

<form enctype="multipart/form-data" id="pie_regiser_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<ul id="pie_register">
<?php
	$form->printFields($current_user);
?>
</ul>	
</form>


