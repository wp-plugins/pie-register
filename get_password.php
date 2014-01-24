<?php 


$warning 	= __("Enter your new password below.",'piereg');
$success	= "";

	
	$errors = new WP_Error();

	if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] )
	{	
		$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );
		
	}
	do_action( 'validate_password_reset', $errors, $user );

	if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
		reset_password($user, $_POST['pass1']);
		
		$success = __( 'Your password has been reset.','piereg' );		
	}

?>

<div id="login">
  <?php if ($success != "") {
	 	?>
  <p class="message">
    <?php echo $success?>
  </p>
  <?php
	} else if (isset($errors->errors['password_reset_mismatch'][0]) && !empty($errors->errors['password_reset_mismatch'][0])  ) {  
		?>
  <p class="login_error">
    <?php  print_r($errors->errors['password_reset_mismatch'][0]); ?>
  </p>
  <?php
	} else {
?>
  <p class="warning">
    <?php echo $warning?>
  </p>
  <?php 
	
	}
  ?>
  <form name="resetpassform" id="resetpassform" action="<?php echo esc_url( site_url( 'wp-login.php?action=resetpass&key=' . urlencode( $_GET['key'] ) . '&login=' . urlencode( $_GET['login'] ), 'login_post' ) ); ?>" method="post" autocomplete="off">
    <input type="hidden" id="user_login" value="<?php echo esc_attr( $_GET['login'] ); ?>" autocomplete="off">
    <p>
      <label for="pass1">New password</label><br />
      <input type="password" name="pass1" id="pass1" class="input validate[required]" size="20" value="" autocomplete="off">
    </p>
    <p>
      <label for="pass2">Confirm new password</label><br />
      <input type="password" name="pass2" id="pass2" class="input validate[required,equals[pass1]]" size="20" value="" autocomplete="off">
    </p>
    <p class="submit">
      <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Reset Password">
    </p>
     <p id="nav"> <a href="<?php echo wp_login_url(); ?>">Log in</a> | <a href="<?php  echo site_url('/wp-login.php?action=register');?>">Register</a> </p>
    <p id="backtoblog"><a title="Are you lost?" href="<?php bloginfo("url"); ?>">← Back to Pie Register</a></p>
  </form>
</div>
