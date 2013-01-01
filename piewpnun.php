<?php
if ( function_exists('wp_new_user_notification') )
	add_action('admin_notices', array($pie_register, 'override_warning'));
	
# Override set user password and send email to User #
if ( !function_exists('wp_new_user_notification') ) :
function wp_new_user_notification($user_id, $plaintext_pass = '') {
	$user = new WP_User($user_id);	
	
	#-- PIE REGESTER --#
	global $wpdb, $pie_register;
	$piereg = get_option( 'pie_register' );
	$piereg_custom = get_option( 'pie_register_custom' );
	$ref = explode( '?', $_SERVER['HTTP_REFERER']);
	$ref = $ref[0];
	$admin = trailingslashit( get_option('siteurl') ) . 'wp-admin/user-new.php';
	if( !is_array( $piereg_custom ) ) $piereg_custom = array();
	//Julian Fixes
	if (!empty($plaintext_pass)){
		if( $piereg['password'] && !empty($_POST['user_pw'])){
            $plaintext_pass = $_POST['user_pw'];
		}
           // otherwise use the supplied password
	}else{
		$plaintext_pass = $pie_register->RanPass(6);
	}
	
	if( $piereg['firstname'] && $_POST['firstname'] )	
		update_usermeta( $user_id, 'first_name', $_POST['firstname']);
	if( $piereg['lastname'] && $_POST['lastname'] )	
		update_usermeta( $user_id, 'last_name', $_POST['lastname']);
	if( $piereg['website'] && $_POST['website'] )	
		update_usermeta( $user_id, 'user_url', $_POST['website']);
	if( $piereg['aim'] && $_POST['aim'] )	
		update_usermeta( $user_id, 'aim', $_POST['aim']);
	if( $piereg['yahoo'] && $_POST['yahoo'] )	
		update_usermeta( $user_id, 'yim', $_POST['yahoo']);
	if( $piereg['jabber'] && $_POST['jabber'] )	
		update_usermeta( $user_id, 'jabber', $_POST['jabber']);
	if( $piereg['phone'] && $_POST['phone'] )	
		update_usermeta( $user_id, 'phone', $_POST['phone']);
	if( $piereg['about'] && $_POST['about'] )	
		update_usermeta( $user_id, 'description',$_POST['about']);
	if( $piereg['code'] && $_POST['regcode'] )	
		update_usermeta( $user_id, 'invite_code', $_POST['regcode']);
	if( $ref != $admin && $piereg['admin_verify'] ){
		update_usermeta( $user_id, 'admin_verify_user', $user->user_login );
		update_usermeta( $user_id, 'email_verify_user_pwd', $user->user_pass );
		update_usermeta( $user_id, 'email_verify_email', $user->user_email );
		$temp_id = 'unverified__' . $pie_register->RanPass(7);
		$notice = __('Your account requires activation by an administrator before you will be able to login.', 'piereg') . "\r\n";
	}else if( $ref != $admin && $piereg['email_verify'] ){
		$code = $pie_register->RanPass(25);
		update_usermeta( $user_id, 'email_verify', $code );
		update_usermeta( $user_id, 'email_verify_date', date('Ymd') );
		update_usermeta( $user_id, 'email_verify_user', $user->user_login );
		update_usermeta( $user_id, 'email_verify_user_pwd', $user->user_pass );
		update_usermeta( $user_id, 'email_verify_email', $user->user_email );
		$email_code = '?piereg_verification=' . $code.'&';
		$prelink = __('Verification URL: ', 'piereg');
		$notice = __('Please use the link above to verify and activate your account', 'piereg') . "\r\n";
		$temp_id = 'unverified__' . $pie_register->RanPass(7);
		delete_user_setting('default_password_nag', $user_id);
		update_user_option($user_id, 'default_password_nag', false, true);
	}else if( $ref != $admin && $piereg['paypal_option']){
		$code = $pie_register->RanPass(25);
		update_usermeta( $user_id, 'email_verify', $code );
		update_usermeta( $user_id, 'email_verify_user', $user->user_login );
		update_usermeta( $user_id, 'email_verify_user_pwd', $user->user_pass );
		update_usermeta( $user_id, 'email_verify_email', $user->user_email );
		$temp_id = 'unverified__' . $pie_register->RanPass(7);
		$email_code = '?piereg_verification=' . $code.'&';

		$prelink = __('Verification URL: ', 'piereg');
		$notice = __('Please click on the above link to verify your email', 'piereg') . "\r\n";
	}
	if (!empty($piereg_custom)) {
		foreach( $piereg_custom as $k=>$v ){
			$id = $pie_register->Label_ID($v['label']);
			if( $v['reg'] && $_POST[$id] ){
				if( is_array( $_POST[$id] ) ) $_POST[$id] = implode(', ', $_POST[$id]);
				update_usermeta( $user_id, $id, $_POST[$id]);
			}
		}
	}
	#-- END Pie Rgister --#
	
	wp_set_password($plaintext_pass, $user_id);
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	#-- Pie-Register --#
	if( !$piereg['custom_adminmsg'] && !$piereg['disable_admin'] ){
	#-- END Pie-Register --#
	
	$message  = sprintf(__('New user Register on your blog %s:', 'piereg'), get_option('blogname')) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n\r\n";
	$message .= sprintf(__('E-mail: %s', 'piereg'), $user_email) . "\r\n";

	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Register', 'piereg'), get_option('blogname')), $message);
	
	#-- Pie-Register --#
	}else if( !$piereg['disable_admin'] ){		
		if( $piereg['adminhtml'] ){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		}
		//$headers .= 'From: ' . $piereg['adminfrom'] . "\r\n" . 'Reply-To: ' . $piereg['adminfrom'] . "\r\n";
		add_filter('wp_mail_from', array($pie_register, 'adminfrom'));
		add_filter('wp_mail_from_name', array($pie_register, 'adminfromname'));
		$subject = $piereg['adminsubject'];
		$message = str_replace('%user_login%', $user_login, $piereg['adminmsg']);
		$message = str_replace('%user_email%', $user_email, $message);
		$message = str_replace('%blogname%', get_option('blogname'), $message);
		$message = str_replace('%user_ip%', $_SERVER['REMOTE_ADDR'], $message);
		$message = str_replace('%user_host%', gethostbyaddr($_SERVER['REMOTE_ADDR']), $message);
		$message = str_replace('%user_ref%', $_SERVER['HTTP_REFERER'], $message);
		$message = str_replace('%user_agent%', $_SERVER['HTTP_USER_AGENT'], $message);
		if( $piereg['firstname'] ) $message = str_replace('%firstname%', $_POST['firstname'], $message);
		if( $piereg['lastname'] ) $message = str_replace('%lastname%', $_POST['lastname'], $message);
		if( $piereg['website'] ) $message = str_replace('%website%', $_POST['website'], $message);
		if( $piereg['aim'] ) $message = str_replace('%aim%', $_POST['aim'], $message);
		if( $piereg['yahoo'] ) $message = str_replace('%yahoo%', $_POST['yahoo'], $message);
		if( $piereg['jabber'] ) $message = str_replace('%jabber%', $_POST['jabber'], $message);
		if( $piereg['phone'] ) $message = str_replace('%phone%', $_POST['phone'], $message);
		if( $piereg['about'] ) $message = str_replace('%about%', $_POST['about'], $message);
		if( $piereg['code'] ) $message = str_replace('%'.$piereg['codename'].'code%', $_POST['regcode'], $message);
		
		if( !is_array( $piereg_custom ) ) $piereg_custom = array();
		if (!empty($piereg_custom)) {
			foreach( $piereg_custom as $k=>$v ){
				$meta = $pie_register->Label_ID($v['label']);
				$value = get_user_meta( $user_id, $meta ,true);
				$message = str_replace('%'.$meta.'%', $value, $message);
			}
		}
		$siteurl = get_option('siteurl');
		$message = str_replace('%siteurl%', $siteurl, $message);
		
		if( $piereg['adminhtml'] && $piereg['admin_nl2br'] )
			$message = nl2br($message);
		
		wp_mail(get_option('admin_email'), $subject, $message, $headers); 
	}
	#-- END Pie-Register --#
	
	if ( empty($plaintext_pass) )
		return;
		
	#-- Pie-Register --#
	if( !$piereg['custom_msg'] ){
	#-- END Pie-Register --#
	
		$message  = sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n";
		$message .= sprintf(__('Password: %s', 'piereg'), $plaintext_pass) . "\r\n";
		//$message .= get_option('siteurl') . "/wp-login.php";
	
	#-- Pie-Register --#
		$message .= $email_code?$prelink . get_option('siteurl') . "/wp-login.php" . $email_code . "\r\n":"-xxx-"; 
		$message .= $notice; 
	#-- END Pie-Register --#
	
		wp_mail($user_email, sprintf(__('[%s] Your username and password', 'piereg'), get_option('blogname')), $message);
	
	#-- Pie-Register --#
	}
	else{
		$unvemailcheck=get_user_meta($user_id,'email_verify_email',true);
		
		if( ($unvemailcheck && $piereg['emailvmsghtml']) || ($unvemailcheck && $piereg['adminvmsghtml']) || (!$unvemailcheck && $piereg['html'])){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		}
		//$headers .= 'From: ' . $piereg['from'] . "\r\n" . 'Reply-To: ' . $piereg['from'] . "\r\n";
		add_filter('wp_mail_from', array($pie_register, 'userfrom'));
		add_filter('wp_mail_from_name', array($pie_register, 'userfromname'));
		$subject = $piereg['subject'];
//Here we need to put the PENDING VERIFICATION EMAIL
		//Email Verification
		if( ($unvemailcheck) && ($piereg['email_verify']) ){
		$message = str_replace('%user_pass%', $plaintext_pass, $piereg['emailvmsg']);
		}else if( ($unvemailcheck) && ($piereg['admin_verify']) ){
		//Admin Verification
		$message = str_replace('%user_pass%', $plaintext_pass, $piereg['adminvmsg']);
		}else{
		//Confirmed User Message
		$message = str_replace('%user_pass%', $plaintext_pass, $piereg['msg']);
		}
		$message = str_replace('%user_login%', $user_login, $message);
		$message = str_replace('%user_email%', $user_email, $message);
		$message = str_replace('%blogname%', get_option('blogname'), $message);
		$message = str_replace('%user_ip%', $_SERVER['REMOTE_ADDR'], $message);
		$message = str_replace('%user_host%', gethostbyaddr($_SERVER['REMOTE_ADDR']), $message);
		$message = str_replace('%user_ref%', $_SERVER['HTTP_REFERER'], $message);
		$message = str_replace('%user_agent%', $_SERVER['HTTP_USER_AGENT'], $message);
		if( $piereg['firstname'] ) $message = str_replace('%firstname%', $_POST['firstname'], $message);
		if( $piereg['lastname'] ) $message = str_replace('%lastname%', $_POST['lastname'], $message);
		if( $piereg['website'] ) $message = str_replace('%website%', $_POST['website'], $message);
		if( $piereg['aim'] ) $message = str_replace('%aim%', $_POST['aim'], $message);
		if( $piereg['yahoo'] ) $message = str_replace('%yahoo%', $_POST['yahoo'], $message);
		if( $piereg['jabber'] ) $message = str_replace('%jabber%', $_POST['jabber'], $message);
		if( $piereg['phone'] ) $message = str_replace('%phone%', $_POST['phone'], $message);
		if( $piereg['about'] ) $message = str_replace('%about%', $_POST['about'], $message);
		if( $piereg['code'] ) $message = str_replace('%'.$piereg['codename'].'code%', $_POST['regcode'], $message);
		
		if( !is_array( $piereg_custom ) ) $piereg_custom = array();
		if (!empty($piereg_custom)) {
			foreach( $piereg_custom as $k=>$v ){
				$meta = $pie_register->Label_ID($v['label']);
				$value = get_user_meta( $user_id, $meta ,true);
				$message = str_replace('%'.$meta.'%', $value, $message);
			}
		}
		
		$redirect = 'redirect_to=' . $piereg['login_redirect'];
		if( $piereg['email_verify'] &&  !$piereg['paypal_option'])
			$siteurl = get_option('siteurl') . "/wp-login.php" . $email_code . $redirect;
			
		else if($piereg['paypal_option'])
			$siteurl = get_option('siteurl') . "/wp-login.php" . $email_code;
				
		else
			$siteurl = get_option('siteurl') . "/wp-login.php?" . $redirect;
			
		$message = str_replace('%siteurl%', $siteurl, $message);
		
		//Email Verification
		if( ($unvemailcheck) && ($piereg['email_verify']) &&  $piereg['emailvhtml'] && $piereg['emailvuser_nl2br'] ){
			$message = nl2br($message);
		}else if( ($unvemailcheck) && ($piereg['admin_verify']) &&  $piereg['adminvhtml'] && $piereg['adminvuser_nl2br'] ){
			$message = nl2br($message);
		}else if( $piereg['html'] && $piereg['user_nl2br'] ){
			$message = nl2br($message);
		}
		
		wp_mail($user_email, $subject, $message, $headers); 
	}
	if( $ref != $admin && ( $piereg['email_verify'] || $piereg['admin_verify'] ) ) {
			$temp_user = $wpdb->query( "UPDATE $wpdb->users SET user_login = '$temp_id' WHERE ID = '$user_id'" );
	}else if( $ref != $admin && ($piereg['paypal_option']) ) {
	
			$temp_user = $wpdb->query( "UPDATE $wpdb->users SET user_login = '$temp_id' WHERE ID = '$user_id'" );
			$temp_email = $wpdb->query( "UPDATE $wpdb->users SET user_email = '$temp_id_".$user_email."' WHERE ID = '$user_id'" );
			//$wpdb->query( "UPDATE $wpdb->users SET user_email = '$user_email_$temp_id' WHERE ID = '$user_id'" );
			}
			
	#-- END Pie-Register --#
}
endif;
?>