<?php 

function pieOutputLoginForm(){

$form_data = "";

$form_data .= '<div class="piereg_container">

<div class="piereg_login_container">

<div class="piereg_login_wrapper">';


  //If Registration contanis errors

global $wp_session,$errors;

$newpasspageLock = 0;



			if(isset($_GET['payment']) && $_GET['payment'] == "success")

			{

				$fields = maybe_unserialize(get_option("pie_fields"));

				$login_success = apply_filters("piereg_success_message",__($fields['submit']['message'],"piereg"));

				unset($fields);

			}elseif(isset($_GET['payment']) && $_GET['payment'] == "cancel"){

				/******************************************************/
				/*$user_id 		= intval(base64_decode($_GET['pay_id']));
				$user_data		= get_userdata($user_id);
				if(is_object($user_data)){
					$form 			= new Registration_form();
					$option 		= get_option( 'pie_register_2' );
					$subject 		= $option['user_subject_email_payment_faild'];
					$message_temp = "";
					if($option['user_formate_email_payment_faild'] == "0"){
						$message_temp	= nl2br(strip_tags($option['user_message_email_payment_faild']));
					}else{
						$message_temp	= $option['user_message_email_payment_faild'];
					}
					$message		= $form->filterEmail($message_temp,$user_data, "" );
					$from_name		= $option['user_from_name_payment_faild'];
					$from_email		= $option['user_from_email_payment_faild'];
					$reply_email 	= $option['user_to_email_payment_faild'];
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
	
					wp_mail($user_data->user_email, $subject, $message , $headers);
					unset($user_data);
				}*/
				/******************************************************/
				
				$login_error = apply_filters("piereg_cancled_message",__("You canceled your payment.","piereg"));
			}



			if(isset($errors->errors['login-error'][0]) > 0)

			{

				$login_error = apply_filters("piereg_login_error",__($errors->errors['login-error'][0],"piereg"));

			}

			else if (! empty($_GET['action']) )

        	{

          

            if ( 'loggedout' == $_GET['action'] )

                $login_warning = '<strong>'.ucwords(__("warning","piereg")).'</strong>: '.apply_filters("piereg_now_logout",__("You are now logged out.","piereg"));

            elseif ( 'recovered' == $_GET['action'] )

                $login_success = '<strong>'.ucwords(__("success","piereg")).'</strong>: '.apply_filters("piereg_check_yor_emailconfrm_link",__("Check your e-mail for the confirmation link.","piereg"));

			elseif ( 'payment_cancel' == $_GET['action'] )

                $login_warning = '<strong>'.ucwords(__("warning","piereg")).'</strong>: '.apply_filters("piereg_canelled_your_registration",__("You have canelled your registration.","piereg"));

			elseif ( 'payment_success' == $_GET['action'] )

                $login_success = '<strong>'.ucwords(__("success","piereg")).'</strong>: '.apply_filters("piereg_thank_you_for_registration",__("Thank you for your registration. You will receieve your login credentials soon.","piereg"));		

			elseif ( 'activate' == $_GET['action'] )

			{
				$unverified = get_users(array('meta_key'=> 'hash','meta_value' => $_GET['activation_key']));

				if(sizeof($unverified )==1)

				{

					$user_id	= $unverified[0]->ID;
					$user_login = $unverified[0]->user_login;
					$user_email = $unverified[0]->user_email;

					if($user_login == $_GET['id'])

					{

						update_user_meta( $user_id, 'active', 1);

						$hash = "";

						update_user_meta( $user_id, 'hash', $hash );
						
						/*************************************/
						/////////// THANK YOU E-MAIL //////////
						
						$option			= get_option("pie_register_2");
						$form 			= new Registration_form();
						$subject 		= $option['user_subject_email_email_thankyou'];
						$message_temp = "";
						if($option['user_formate_email_email_thankyou'] == "0"){
							$message_temp	= nl2br(strip_tags($option['user_message_email_email_thankyou']));
						}else{
							$message_temp	= $option['user_message_email_email_thankyou'];
						}
						$message		= $form->filterEmail($message_temp,$user_email);
						$from_name		= $option['user_from_name_email_thankyou'];
						$from_email		= $option['user_from_email_email_thankyou'];
						$reply_email 	= $option['user_to_email_email_thankyou'];
		
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
		
						wp_mail($user_email, $subject, $message , $headers);
						
						/////////// END THANK YOU E-MAIL //////////
						/*************************************/
						
						$login_success = '<strong>'.ucwords(__("success","piereg")).'</strong>: '.apply_filters("piereg_your_account_is_now_active",__("Your account is now active","piereg"));	

					}

					else

					{

						 $login_error = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("Invalid activation key","piereg"));

					}

				}else{
					$user_name = mysql_real_escape_string($_GET['id']);
					$user = get_userdatabylogin($user_name);
					if($user){
						$user_meta = get_user_meta( $user->ID, 'active');
						if(isset($user_meta[0]) && $user_meta[0] == 1){
							$login_warning = '<strong>'.ucwords(__("warning","piereg")).'</strong>: '.apply_filters("piereg_canelled_your_registration",__("You are already activate","piereg"));
							unset($user_meta);
							unset($user_name);
							unset($user);
						}
						else{
							$login_error = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("Invalid activation key","piereg"));
						}
					}
					else{
						$login_error = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_invalid_activation_key",__("You are block","piereg"));
					}
				}

				

				 

			}

			elseif ( 'resetpass' == $_GET['action'] || 'rp' == $_GET['action'] ){

				$user = check_password_reset_key($_GET['key'], $_GET['login']);

				if ( is_wp_error($user) ) {

					if ( $user->get_error_code() === 'expired_key' )

						$login_error = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_you_key_has_been_expired",__("You key has been expired, please reset password again!","piereg").' <a href="'.pie_lostpassword_url().'" title="'.__("Password Lost and Found","piereg").'">'.__("Lost your password?","piereg").'</a>');

					else

						$login_error = '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_this_reset_key_invalid_or_no_longer_exists",__("This Reset key is invalid or no longer exists. Please reset password again!","piereg").' <a href="'.pie_lostpassword_url().'" title="'.__("Password Lost and Found","piereg").'">'.__("Lost your password?","piereg").'</a>');

						$newpasspageLock = 1;

				}else{

					$login_warning = '<strong>'.ucwords(__("warning","piereg")).'</strong>: '.__('Enter your new password below.',"piereg");

				}

				if(isset($_POST['pass1'])){

					$errors = new WP_Error();

					if(isset($_POST['pass1']) && trim($_POST['pass1']) == ""){

						$login_error =  '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_invalid_password",__( 'Invalid Password',"piereg" ));

						$errors->add( 'password_reset_mismatch',$login_error );

					}elseif ( isset($_POST['pass1']) and strlen($_POST['pass1']) < 7  ){

						$login_error =  '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_minimum_8_characters_required_in_password",__( 'Minimum 8 characters required in password',"piereg" ));

						$errors->add( 'password_reset_mismatch',$login_error );

					}elseif ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ){

						$login_error =  '<strong>'.ucwords(__("error","piereg")).'</strong>: '.apply_filters("piereg_the_passwords_do_not_match",__( 'The passwords do not match',"piereg"));

						$errors->add( 'password_reset_mismatch',$login_error );

					}

					

					do_action( 'validate_password_reset', $errors, $user );

				
					if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {

						reset_password($user, $_POST['pass1']);

						$newpasspageLock = 1;

						$login_warning = '';

						$login_error = '';

						$login_success = '<strong>'.ucwords(__("success","piereg")).'</strong>: '.apply_filters("piereg_your_password_has_been_reset",__( 'Your password has been reset.' , "piereg"));

					}

				}
			}

        }

		if(trim($wp_session['message']) != "" )

		{

			$form_data .= '<p class="piereg_login_error"> ' . apply_filters('piereg_messages',__($wp_session['message'],"piereg")) . "</p>";

			$wp_session['message'] = "";

		}

		if ( !empty($login_error) )

			$form_data .= '<p class="piereg_login_error"> ' . apply_filters('piereg_messages', $login_error) . "</p>\n";

		

		if ( !empty($login_success) )

			$form_data .= '<p class="piereg_message">' . apply_filters('piereg_messages',$login_success) . "</p>\n";

		

		if ( !empty($login_warning) )

			$form_data .= '<p class="piereg_warning">' . apply_filters('piereg_messages',$login_warning) . "</p>\n";

		

		if(isset($_POST['success']) && $_POST['success'] != "")

			$form_data .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($_POST['success'],"piereg")).'</p>';

		if(isset($_POST['error']) && $_POST['error'] != "")

			$form_data .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($_POST['error'],"piereg")).'</p>';	

if ( isset($_GET['action']) && ('rp' == $_GET['action'] || 'resetpass' == $_GET['action']) && ($newpasspageLock == 0) ){

	$form_data .= '

	  <form name="resetpassform" class="piereg_resetpassform" action="'.pie_modify_custom_url(pie_login_url(),'action=resetpass&key=' . urlencode( $_GET['key'] ) . '&login=' . urlencode( $_GET['login'] )).'" method="post" autocomplete="off">

	

		<input type="hidden" id="user_login" value="'.esc_attr( $_GET['login'] ).'" autocomplete="off">

		<div class="field">

		  <label for="pass1">'.__("New password","piereg").'</label>

		  <input type="password" name="pass1" id="pass1" class="input validate[required]" size="20" value="" autocomplete="off">

		</div>

		<div class="field">

		  <label for="pass2">'.__("Confirm new password","piereg").'</label>

		  <input type="password" name="pass2" id="pass2" class="input validate[required,equals[pass1]]" size="20" value="" autocomplete="off">

		</div>

		<div class="pie_submit">

		  <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="'.__("Reset Password","piereg").'">

		</div>

		<div class="field">

		 <div class="nav">

		 	<a href="'.pie_login_url().'">'.__("Log in","piereg").'</a> | <a href="'.pie_registration_url().'">'.__("Register","piereg").'</a>

		 </div>

		</div>

		<div class="backtoblog">

			<a title="'.__("Are you lost?","piereg").'" href="'.get_bloginfo("url").'">&larr; '.__("Back to","piereg").' '.get_bloginfo("name").'</a>

		</div>

	  </form>';

}else{

	$form_data .= '

	<form method="post" action="" class="piereg_loginform" name="loginform">

		<p>

			<label for="user_login">'.__("Username","piereg").'</label>

			<input placeholder="'.__("Username","piereg").'" type="text" size="20" value="" class="input validate[required]" id="user_login" name="log">

		</p>

		<p>

			<label for="user_pass">'.__("Password","piereg").'</label>

			<input placeholder="'.__("Password","piereg").'" type="password" size="20" value="" class="input validate[required]" id="user_pass" name="pwd">

		</p>';

		//if(!is_page()) {

			$form_data .= '

			<p class="forgetmenot">

				<label for="rememberme">

					<input type="checkbox" value="forever" id="rememberme" name="rememberme">'.__("Remember Me","piereg").'

				</label>

			</p>';

		//}

		$form_data .= '

		<p class="submit">

			<input type="submit" value="'.__("Log In","piereg").'" class="button button-primary button-large" id="wp-submit" name="wp-submit">

			<input type="hidden" value="'.admin_url().'" name="redirect_to">

			<input type="hidden" value="1" name="testcookie">

		</p>';

		

		//if(!is_page() ) {

			$form_data .= '

			<p id="nav"> <a href="'.pie_registration_url().'">'.__("Register","piereg").'</a> <a style="cursor:default;text-decoration:none;" href="javascript:;"> | </a> <a title="Password Lost and Found" href="'.pie_lostpassword_url().'">'.__("Lost your password?","piereg").'</a> </p>';

		//} ?>

	

		<?php if(isset($pagenow) && $pagenow == 'wp-login.php' ){

			$form_data .= '

			<p id="backtoblog"><a title="'.__("Are you lost?","piereg").'" href="'.bloginfo("url").'">&larr;'.__(" Back to","piereg").' '.get_bloginfo("name").'</a></p>';

		} 

	$form_data .= '

	</form>';

}



 // do_action("check_enable_social_site_method");



$form_data .='</div>

</div></div>';



return $form_data;

}

