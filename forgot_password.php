<?php 


$warning 	= __("Please enter your username or email address. You will receive a link to create a new password via email.",'piereg');
$success	= "";
if (isset($_POST['reset_pass']))
{
  global $wpdb;
$error 		= array();
$username = trim($_POST['user_login']);
$user_exists = false;
// First check by username
if ( username_exists( $username ) ){
    $user_exists = true;
    $user = get_user_by('login', $username);
}
// Then, by e-mail address
elseif( email_exists($username) ){
        $user_exists = true;
        $user = get_user_by_email($username);
}else{
    $error[] = __('Username or Email was not found, try again!','piereg');
}
if ($user_exists){
    $user_login = $user->user_login;
    $user_email = $user->user_email;
		
		
		$key = wp_generate_password(20, false);
		 do_action('retrieve_password_key', $user_login, $key);
   // $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
  //  if ( empty($key) ) {
        // Generate something random for a key...
      	 require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	    
		$hashed = $wp_hasher->HashPassword( $key );
       
        // Now insert the new md5 key into the db
        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
   // }

    //create email message
    $message = __('Someone has asked to reset the password for the following site and username.','piereg') . "\r\n\r\n";
    $message .= get_option('siteurl') . "\r\n\r\n";
    $message .= sprintf(__('Username:','piereg')." %s ", $user_login) . "\r\n\r\n";
    $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.','piereg') . "\r\n\r\n";
   $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "&redirect_to=".urlencode(get_option('siteurl'))."\r\n";
 
	//send email meassage
    if (FALSE == wp_mail($user_email, sprintf('[%s] ' . __('Password Reset','piereg'), get_option('blogname')), $message))
    $error[] =  __('The e-mail could not be sent.','piereg') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...','piereg') ;
}
	if (count($error) == 0 )
	{
		$success =  __('A message will be sent to your email address.','piereg'); 
	}	
}


?>

<div id="piereg_login">
  <?php if (is_array($error) && count($error) == 0 ) {
	 	?>
  <p class="piereg_message">
    <?php echo $success?>
  </p>
  <?php
	} else if (is_array($error) && count($error) > 0 ) {  
		?>
  <p class="piereg_login_error">
    <?php echo $error[0]?>
  </p>
  <?php
	} else {
?>
 <p class="piereg_warning">
    <?php echo $warning?>
  </p>
<?php 
	
	}
  ?>
  <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="piereg_lostpasswordform">
    <p>
      <label for="user_login">Username or E-mail:</label>
      <input type="text" size="20" value="" class="input validate[required]" id="user_login" name="user_login">
    </p>
    <input type="hidden" value="" name="redirect_to">
    <p class="submit">
      <?php do_action('login_form', 'resetpass'); ?>
      <input type="submit" value="<?php _e('Reset my password'); ?>" class="button button-primary button-large" id="wp-submit" name="user-submit">
    </p>
    <?php if(!is_page()) { ?>
    <p id="nav"> <a href="<?php echo wp_login_url(); ?>">Log in</a> | <a href="<?php  echo site_url('/wp-login.php?action=register');?>">Register</a> </p>
    <p id="backtoblog"><a title="Are you lost?" href="<?php bloginfo("url"); ?>">← Back to Pie Register</a></p>
    <?php } ?>
    
    <input type="hidden" name="reset_pass" value="1" />
    <input type="hidden" name="user-cookie" value="1" />
  </form>
</div>