<?php



function pieResetFormOutput($pie_register = false){

	$forgot_pass_form = '';

	$forgot_pass_form .= '

	<div class="piereg_entry-content pieregForgotPassword">

	<div id="piereg_forgotpassword">';

	

	$warning 	= '<strong>'.ucwords(__("warning","piereg")).'</strong>: '.__("Please enter your username or email address. You will receive a link to create a new password via email.",'piereg');

	$success	= "";

	if(isset($_POST['user_login']) and trim($_POST['user_login']) == ""){

		$error[] = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.__('Invalid Username or Email, try again!','piereg');

	}elseif (isset($_POST['reset_pass']))

	{

	  global $wpdb,$wp_hasher;

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

		$error[] = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.__('Username or Email was not found, try again!','piereg');

	}

	if ($user_exists){

		

		$user_login = $user->user_login;

		$user_email = $user->user_email;



		$allow = apply_filters( 'allow_password_reset', true, $user->ID );

		

		if($allow){

			//$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

			

			do_action('retrieve_password_key', $user_login);

		   // $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

		 // Generate something random for a key...

			if ( empty( $wp_hasher ) ) {

				require_once ABSPATH . 'wp-includes/class-phpass.php';

				$wp_hasher = new PasswordHash( 8, true );

			}

			$wp_hasher = new PasswordHash( 8, true );

			$key = wp_generate_password( 20, false );

			$hashed = $wp_hasher->HashPassword( $key );

		   	do_action( 'retrieve_password_key', $user_login, $key );

			// Now insert the new md5 key into the db

			$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

			

			$option = get_option('pie_register_2');

			$pie_register_base = new PieReg_Base();

							

			$message_temp = "";
			if($option['user_formate_email_forgot_password_notification'] == "0"){
				$message_temp	= nl2br(strip_tags($option['user_message_email_forgot_password_notification']));
			}else{
				$message_temp	= $option['user_message_email_forgot_password_notification'];
			}
			
			$message		= $pie_register_base->filterEmail($message_temp,$user->user_login, '',$key );

			$from_name		= $option['user_from_name_forgot_password_notification'];

			$from_email		= $option['user_from_email_forgot_password_notification'];					

			$reply_email 	= $option['user_to_email_forgot_password_notification'];

			$subject 		= $option['user_subject_email_forgot_password_notification'];

			

			//Headers

			$headers  = 'MIME-Version: 1.0' . "\r\n";

			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		

			if(!empty($from_email) && filter_var($from_email,FILTER_VALIDATE_EMAIL))//Validating From

			$headers .= "From: ".$from_name." <".$from_email."> \r\n";

			if($reply_email){

				$headers .= "Reply-To: {$reply_email}\r\n";

				$headers .= "Return-Path: {$from_name}\r\n";

			}else{

				$headers .= "Reply-To: {$from_email}\r\n";

				$headers .= "Return-Path: {$from_email}\r\n";

			}

	

						

			//send email meassage

			if (FALSE == wp_mail($user_email, $subject, $message,$headers)){
				$error[] =  '<strong>'.ucwords(__("error","piereg")).'</strong>: '.__('The e-mail could not be sent.','piereg') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...','piereg') ;
			}

			

			unset($key);

			unset($hashed);

			unset($_POST['user_login']);

		}else{

			$error[] = apply_filters('piereg_password_reset_not_allowed_text',__("Password reset is not allowed for this user","piereg"));

		}

		

		

		

		

		/*$message = __('Someone has asked to reset the password for the following site and username.','piereg') . "\r\n\r\n";

		$message .= get_option('siteurl') . "\r\n\r\n";

		$message .= sprintf(__('Username:','piereg')." %s ", $user_login) . "\r\n\r\n";

		$message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.','piereg') . "\r\n\r\n";

	   $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "&redirect_to=".urlencode(get_option('siteurl'))."\r\n";*/

	 

		if (count($error) == 0 )

		{

			$success =  '<strong>'.ucwords(__("success","piereg")).'</strong>: '.apply_filters("piereg_message_will_be_sent_to_your_email",__('A message will be sent to your email address.','piereg'));

		}	

	}

		

	}

	

	

	$forgot_pass_form .='<div id="piereg_login">';

		if (isset($error) && is_array($error) && count($error) == 0 ) {	  

				$forgot_pass_form .= '<div class="alert alert-successs"><p class="piereg_message">';

				$forgot_pass_form .= $success;

				$forgot_pass_form .= '</p></div>';

		} else if (isset($error) && is_array($error) && count($error) > 0 ) {

				$forgot_pass_form .= '<div class="alert alert-danger"><p class="piereg_login_error">';

				$forgot_pass_form .= $error[0];

				$forgot_pass_form .= '</p></div>';

		} elseif($warning) {

				$forgot_pass_form .= '<div class="alert alert-warning"><p class="piereg_warning">'.$warning.'</p></div>';

		}

	$forgot_pass_form .= '

	  <form method="post" action="'.$_SERVER['REQUEST_URI'].'" id="piereg_lostpasswordform">

		<p>

		  <label for="user_login">'.__("Username or E-mail:","piereg").'</label>

		  <input type="text" size="20" value="" class="input validate[required]" id="user_login" name="user_login">

		</p>

		<input type="hidden" value="" name="redirect_to">

		<p class="submit">';

		  

		  do_action('pieresetpass');

		  

		  $forgot_pass_form .= '

		  <input type="submit" value="'.__('Reset my password',"piereg").'" class="button button-primary button-large" id="wp-submit" name="user-submit">

		</p>';

		

		if(!is_page()) {

			$forgot_pass_form .= '<p class="forgot_pass_links"> <a href="'.wp_login_url().'">'.__('Log in',"piereg").'</a> | <a href="'.wp_registration_url().'">'.__('Register',"piereg").'</a> </p>

			<p class="forgot_pass_links"><a title="'.__('Are you lost?',"piereg").'" href="'.get_bloginfo("url").'">&larr; '.__('Back to',"piereg").' '.get_bloginfo("name").'</a></p>';

		}

		$forgot_pass_form .= '

		<input type="hidden" name="reset_pass" value="1" />

		<input type="hidden" name="user-cookie" value="1" />

	  </form>

	</div>

	</div>

	</div>';

	

	return $forgot_pass_form;

}

?>

