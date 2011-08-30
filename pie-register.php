<?php
/*
Plugin Name: Pie Register
Plugin URI: http://pie-solutions.com/products/pie-register/
Description: <strong>WordPress 2.5+ ONLY.</strong> Enhance your Registration Page.  Add Custom Logo, Password Field, Invitation Codes, Disclaimer, Captcha Validation, Email Validation, User Moderation, Profile Fields, Charge User fees through Paypal and more.
Pie-register is a fork of register-plus, however many things has changed since.

Author: Johnibom
Version: 1.2.3
Author URI: http://www.pie-solutions.com

LOCALIZATION
* Currently This feature is not available. We are working on it to improve.
				
CHANGELOG
See readme.txt
*/

/*Created by Skullbit
 (website: skullbit.com)

 Modified by JOHNIBOM
 (website: pie-solutions.com       email : johnibom@pie-solutions.com)
*/
error_reporting(0);
$rp = get_option( 'pie_register' ); //load options
if( $rp['dash_widget'] ) //if dashboard widget is enabled
	include_once('dash_widget.php'); //add the dashboard widget
	
if( !class_exists('PieMemberRegister') ){  
	class PieMemberRegister{
		function PieMemberRegister() { //constructor
			global $wp_version;
			
			//ACTIONS
				#Add Settings Panel
				
				add_action( 'admin_menu', array($this, 'AddPanel') );
				#Update Settings on Save
				if( $_POST['action'] == 'pie_reg_update' )
					add_action( 'init', array($this,'SaveSettings') );
				#Enable jQuery on Settings panel
				if( $_GET['page'] == 'pie-register' ){
					wp_enqueue_script('jquery');
					add_action( 'admin_head', array($this, 'SettingsHead') );
				}
				#Add Register Form Fields
				add_action( 'register_form', array($this, 'RegForm') );	
				#Add Register Page Javascript & CSS
				if($_GET['action'] == 'register')
					add_action( 'login_head', array($this, 'PassHead') );
				#Add Custom Logo CSS to Login Page
					add_action( 'login_head', array($this, 'LogoHead') );
				#Hide initial login fields when email verification is enabled
					add_action( 'login_head', array($this, 'HideLogin') );
				#Save Default Settings
					add_action( 'init', array($this, 'DefaultSettings') );
				#Profile 
					add_action( 'show_user_profile', array($this, 'Add2Profile') );
					add_action( 'edit_user_profile', array($this, 'Add2Profile') );
					add_action( 'profile_update', array($this, 'SaveProfile') );
				#Validate User
					add_action( 'login_form', array($this, 'ValidateUser') );
					#Validate Payment of a User
					add_action( 'login_form', array($this, 'ValidPUser') );
					
				#Delete Invalid Users
					add_action( 'init', array($this, 'DeleteInvalidUsers') );
				#Unverified Users Head Scripts
					add_action( 'admin_head', array($this, 'UnverifiedHead') );
				#Admin Validate Users
					if( $_POST['verifyit'] )
						add_action( 'init', array($this, 'AdminValidate') );
				#Admin Send Payment Link
					if( $_POST['paymentl'] )
						add_action( 'init', array($this, 'PaymentLink') );
				#Admin Resend VerificatioN Email
					if( $_POST['emailverifyit'] )
						add_action( 'init', array($this, 'AdminEmailValidate') );
				#Admin Delete Unverified User
					if( $_POST['vdeleteit'] )
						add_action( 'init', array($this, 'AdminDeleteUnvalidated') );
						
			//FILTERS
				#Check Register Form for Errors
				add_filter( 'registration_errors', array($this, 'RegErrors') );	
			//LOCALIZATION
				#Place your language file in the plugin folder and name it "piereg-{language}.mo"
				#replace {language} with your language value from wp-config.php
				load_plugin_textdomain( 'piereg', '/wp-content/plugins/pie-register' );
			
			//VERSION CONTROL
				if( $wp_version < 2.5 )
					add_action('admin_notices', array($this, 'version_warning'));
					
					// Load this plugin last to ensure other plugins don't overwrite the settings

		  add_action( 'activated_plugin', array($this, 'load_last') );
			
		}
		
		function version_warning(){ //Show warning if plugin is installed on a WordPress lower than 2.5
			global $wp_version;
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Pie-Register is only compatible with WordPress v2.5 and up.  You are currently using WordPress v.', 'piereg').$wp_version."</strong> </p></div>
		";
		
		}
		
		function load_last(){

		  // Get array of active plugins

		  if( !$active_plugins = get_option('active_plugins') ) return;

		  // Set this plugin as variable

		  $my_plugin = 'pie-register/'.basename(__FILE__);

		  // See if my plugin is in the array

		  $key = array_search( $my_plugin, $active_plugins );

		  // If my plugin was found

		  if( $key !== FALSE ){

			// Remove it from the array

			unset( $active_plugins[$key] );

			// Reset keys in the array

			$active_plugins = array_values( $active_plugins );

			// Add my plugin to the end

			array_push( $active_plugins, $my_plugin );

			// Resave the array of active plugins

			update_option( 'active_plugins', $active_plugins );

		  }

	  }
		
		function AddPanel(){ //Add the Settings and User Panels
			add_options_page( 'Pie Register', 'Pie Register', 10, 'pie-register', array($this, 'RegPlusSettings') );
			$piereg = get_option('pie_register');
			if( $piereg['email_verify'] || $piereg['admin_verify'] || $piereg['paypal_option'] )
				add_users_page( 'Unverified Users', 'Unverified Users', 10, 'unverified-users', array($this, 'Unverified') );
		}
		
		function DefaultSettings () { 
			$default = array( 
								'paypal_option'			=> '0',
								'paypal_butt_id'		=> '',
								'paypal_pdt'			=> '',
								'password' 				=> '0',
								'password_meter'		=> '0',
								'short'					=> 'Too Short',
								'bad'					=> 'Bad Password',
								'good'					=> 'Good Password',
								'strong'				=> 'Strong Password',
								'code' 					=> '0', 
								'codepass' 				=> array('0'),
								'captcha' 				=> '0',
								'disclaimer'			=> '0',
								'disclaimer_title'		=> 'Disclaimer',
								'disclaimer_content'	=> '',
								'disclaimer_agree'		=> 'Accept the Disclaimer',
								'license'				=> '0',
								'license_title'			=> 'License Agreement',
								'license_content'		=> '',
								'license_agree'			=> 'Accept the License Agreement',
								'privacy'				=> '0',
								'privacy_title'			=> 'Privacy Policy',
								'privacy_content'		=> '',
								'privacy_agree'			=> 'Accept the Privacy Policy',
								'email_exists'			=> '0',
								'firstname'				=> '0',
								'lastname'				=> '0',
								'website'				=> '0',
								'aim'					=> '0',
								'yahoo'					=> '0',
								'jabber'				=> '0',
								'about'					=> '0',
								'profile_req'			=> array('0'),
								'require_style'			=> 'border:solid 1px #E6DB55;background-color:#FFFFE0;',
								'dash_widget'			=> '0',
								'email_verify'			=> '0',
								'admin_verify'			=> '0',
								'email_delete_grace'	=> '7',
								'html'					=> '0',
								'adminhtml'				=> '0',
								'from'					=> get_option('admin_email'),
								'fromname'				=> get_option('blogname'),
								'subject'				=> sprintf(__('[%s] Your username and password', 'piereg'), get_option('blogname')),
								'custom_msg'			=> '0',
								'user_nl2br'			=> '0',
								'msg'					=> " %blogname% Registration \r\n --------------------------- \r\n\r\n Here are your credentials: \r\n Username: %user_login% \r\n Password: %user_pass% \r\n Confirm Registration: %siteurl% \r\n\r\n Thank you for registering with %blogname%!  \r\n",
								'disable_admin'			=> '0',
								'adminfrom'				=> get_option('admin_email'),
								'adminfromname'			=> get_option('blogname'),
								'adminsubject'			=> sprintf(__('[%s] New User Register', 'piereg'), get_option('blogname')),
								'custom_adminmsg'		=> '0',
								'admin_nl2br'			=> '0',
								'adminmsg'				=> " New %blogname% Registration \r\n --------------------------- \r\n\r\n Username: %user_login% \r\n E-Mail: %user_email% \r\n",
								'logo'					=> '',
								'login_redirect'		=> get_option('siteurl'),
								'register_css'			=> '',
								'login_css'				=> '',
								'firstday'				=> 6,
								'dateformat'			=> 'mm/dd/yyyy',
								'startdate'				=> '',
								'calyear'				=> '',
								'calmonth'				=> 'cur'
							);
			# Get Previously Saved Items and put into new Settings
			if( get_option("paypal_option") )
			  	$default['paypal_option'] = get_option("paypal_option");
				if( get_option("paypal_butt_id") )
			  	$default['paypal_butt_id'] = get_option("paypal_butt_id");
				if( get_option("paypal_pdt") )
			  	$default['paypal_pdt'] = get_option("paypal_pdt");
				if( get_option("piereg_password") )
			  	$default['password'] = get_option("piereg_password");
			if( get_option("piereg_code") )
			  	$default['code'] = get_option("piereg_code");
			if( get_option("piereg_codepass") )
			  	$default['codepass'] = get_option("piereg_codepass");
			if( get_option("piereg_captcha") )
			  	$default['captcha'] = get_option("piereg_captcha");
			#Delete Previous Saved Items
			delete_option('paypal_option');
			delete_option('paypal_butt_id');
			delete_option('paypal_pdt');
			delete_option('piereg_password');
			delete_option('piereg_code');
			delete_option('piereg_codepass');
			delete_option('piereg_captcha');
			#Set Default Settings
			if( !get_option('pie_register') ){ #Set Defaults if no values exist
				add_option( 'pie_register', $default );
			}else{ #Set Defaults if new value does not exist
				$piereg = get_option( 'pie_register' );
				foreach( $default as $key => $val ){
					if( !$piereg[$key] ){
						$piereg[$key] = $val;
						$new = true;
					}
				}
				if( $new )
					update_option( 'pie_register', $piereg );
			}
		}
		function SaveSettings(){
			check_admin_referer('piereg-update-options');
			$update = get_option( 'pie_register' );
			$update["paypal_option"] = $_POST['piereg_paypal_option'];
			$update["paypal_butt_id"] = $_POST['piereg_paypal_butt_id'];
			$update["paypal_pdt"] = $_POST['piereg_paypal_pdt'];
			$update["password"] = $_POST['piereg_password'];
			$update["password_meter"] = $_POST['piereg_password_meter'];
			$update["short"] = $_POST['piereg_short'];
			$update["bad"] = $_POST['piereg_bad'];
			$update["good"] = $_POST['piereg_good'];
			$update["strong"] = $_POST['piereg_strong'];
			$update["code"] = $_POST['piereg_code'];
			if( $_POST['piereg_code'] ) {
				$update["codepass"] = $_POST['piereg_codepass'];
				foreach( $update["codepass"] as $k=>$v ){
					$update["codepass"][$k] = strtolower($v);
				}
				$update["code_req"] = $_POST['piereg_code_req'];
			}
			$update["captcha"] = $_POST['piereg_captcha'];
			$update["disclaimer"] = $_POST['piereg_disclaimer'];
			$update["disclaimer_title"] = $_POST['piereg_disclaimer_title'];
			$update["disclaimer_content"] = $_POST['piereg_disclaimer_content'];
			$update["disclaimer_agree"] = $_POST['piereg_disclaimer_agree'];
			$update["license"] = $_POST['piereg_license'];
			$update["license_title"] = $_POST['piereg_license_title'];
			$update["license_content"] = $_POST['piereg_license_content'];
			$update["license_agree"] = $_POST['piereg_license_agree'];
			$update["privacy"] = $_POST['piereg_privacy'];
			$update["privacy_title"] = $_POST['piereg_privacy_title'];
			$update["privacy_content"] = $_POST['piereg_privacy_content'];
			$update["privacy_agree"] = $_POST['piereg_privacy_agree'];
			$update["email_exists"] = $_POST['piereg_email_exists'];
			$update["firstname"] = $_POST['piereg_firstname'];
			$update["lastname"] = $_POST['piereg_lastname'];
			$update["website"] = $_POST['piereg_website'];
			$update["aim"] = $_POST['piereg_aim'];
			$update["yahoo"] = $_POST['piereg_yahoo'];
			$update["jabber"] = $_POST['piereg_jabber'];
			$update["phone"] = $_POST['piereg_phone'];
			$update["about"] = $_POST['piereg_about'];
			$update["profile_req"] = $_POST['piereg_profile_req'];
			$update["require_style"] = $_POST['piereg_require_style'];
			$update["dash_widget"] = $_POST['piereg_dash_widget'];
			$update["admin_verify"] = $_POST['piereg_admin_verify'];
			$update["email_verify"] = $_POST['piereg_email_verify'];
			$update["email_verify_date"] = $_POST['piereg_email_verify_date'];
			$update["email_delete_grace"] = $_POST['piereg_email_delete_grace'];
			$update["reCAP_public_key"] = $_POST['piereg_reCAP_public_key'];
			$update["reCAP_private_key"] = $_POST['piereg_reCAP_private_key'];
			$update['html'] = $_POST['piereg_html'];
			$update['from'] = $_POST['piereg_from'];
			$update['fromname'] = $_POST['piereg_fromname'];
			$update['subject'] = $_POST['piereg_subject'];
			$update['custom_msg'] = $_POST['piereg_custom_msg'];
			$update['user_nl2br'] = $_POST['piereg_user_nl2br'];
			$update['msg'] = $_POST['piereg_msg'];
			$update['disable_admin'] = $_POST['piereg_disable_admin'];
			$update['adminhtml'] = $_POST['piereg_adminhtml'];
			$update['adminfrom'] = $_POST['piereg_adminfrom'];
			$update['adminfromname'] = $_POST['piereg_adminfromname'];
			$update['adminsubject'] = $_POST['piereg_adminsubject'];
			$update['custom_adminmsg'] = $_POST['piereg_custom_adminmsg'];
			$update['admin_nl2br'] = $_POST['piereg_admin_nl2br'];
			$update['adminmsg'] = $_POST['piereg_adminmsg'];
			$update['login_redirect'] = $_POST['piereg_login_redirect'];
			$update['register_css'] = $_POST['piereg_register_css'];
			$update['login_css'] = $_POST['piereg_login_css'];
			$update['firstday'] = $_POST['piereg_firstday'];
			$update['dateformat'] = $_POST['piereg_dateformat'];
			$update['startdate'] = $_POST['piereg_startdate'];
			$update['calyear'] = $_POST['piereg_calyear'];
			$update['calmonth'] = $_POST['piereg_calmonth'];
			if( $_FILES['piereg_logo']['name'] ) $update['logo'] = $this->UploadLogo();
			else if( $_POST['remove_logo'] ) $update['logo'] = '';

			if( $_POST['label'] ){
				foreach( $_POST['label'] as $k => $field ){
					if( $field )
					$custom[$k] = array( 'label' => $field, 'profile' => $_POST['profile'][$k], 'reg' => $_POST['reg'][$k], 'required' => $_POST['required'][$k], 'fieldtype' => $_POST['fieldtype'][$k], 'extraoptions' => $_POST['extraoptions'][$k] );
				}
			}			
			
			update_option( 'pie_register_custom', $custom );
			update_option( 'pie_register', $update );
			$_POST['notice'] = __('Settings Saved', 'piereg');
		}
		
		function UploadLogo(){
		 	$upload_dir = ABSPATH . get_option('upload_path');
			if(!empty($upload_dir)) $upload_dir=ABSPATH.'wp-content/uploads';
			$upload_file = trailingslashit($upload_dir) . basename($_FILES['piereg_logo']['name']);
			//echo $upload_file;
			if( !is_dir($upload_dir) )
				wp_upload_dir();
			if( move_uploaded_file($_FILES['piereg_logo']['tmp_name'], $upload_file) ){
				chmod($upload_file, 0777);				
				$logo = $_FILES['piereg_logo']['name'];			
				return trailingslashit( get_option('siteurl') ) . 'wp-content/uploads/' . $logo;
			}else{
				return false;
			}		 
		}
		
		function SettingsHead(){
			$piereg = get_option( 'pie_register' );
			?>
<script type="text/javascript">

function set_add_del_code(){
	jQuery('.remove_code').show();
	jQuery('.add_code').hide();
	jQuery('.add_code:last').show();
	jQuery(".code_block:only-child > .remove_code").hide();
}
function selremcode(clickety){
	jQuery(clickety).parent().remove(); 
	set_add_del_code(); 
	return false;
}
function seladdcode(clickety){
	jQuery('.code_block:last').after(
    	jQuery('.code_block:last').clone());
	jQuery('.code_block:last input').attr('value', '');

	set_add_del_code(); 
	return false;
}
function set_add_del(){
	jQuery('.remove_row').show();
	jQuery('.add_row').hide();
	jQuery('.add_row:last').show();
	jQuery(".row_block:only-child > .remove_row").hide();
}
function selrem(clickety){
	jQuery(clickety).parent().parent().remove(); 
	set_add_del(); 
	return false;
}
function seladd(clickety){
	jQuery('.row_block:last').after(
    	jQuery('.row_block:last').clone());
	jQuery('.row_block:last input.custom').attr('value', '');
	jQuery('.row_block:last input.extraops').attr('value', '');
	var custom = jQuery('.row_block:last input.custom').attr('name');
	var reg = jQuery('.row_block:last input.reg').attr('name');
	var profile = jQuery('.row_block:last input.profile').attr('name');
	var req = jQuery('.row_block:last input.required').attr('name');
	var fieldtype = jQuery('.row_block:last select.fieldtype').attr('name');
	var extraops = jQuery('.row_block:last input.extraops').attr('name');
	var c_split = custom.split("[");
	var r_split = reg.split("[");
	var p_split = profile.split("[");
	var q_split = req.split("[");
	var f_split = fieldtype.split("[");
	var e_split = extraops.split("[");
	var split2 = c_split[1].split("]");
	var index = parseInt(split2[0]) + 1;
	var c_name = c_split[0] + '[' + index + ']';
	var r_name = r_split[0] + '[' + index + ']';
	var p_name = p_split[0] + '[' + index + ']';
	var q_name = q_split[0] + '[' + index + ']';
	var f_name = f_split[0] + '[' + index + ']';
	var e_name = e_split[0] + '[' + index + ']';
	jQuery('.row_block:last input.custom').attr('name', c_name);
	jQuery('.row_block:last input.reg').attr('name', r_name);
	jQuery('.row_block:last input.profile').attr('name', p_name);
	jQuery('.row_block:last input.required').attr('name', q_name);
	jQuery('.row_block:last select.fieldtype').attr('name', f_name);
	jQuery('.row_block:last input.extraops').attr('name', e_name);
	set_add_del(); 
	return false;
}

jQuery(document).ready(function() {
	<?php if( !$piereg['code'] ){ ?>
	jQuery('#codepass').hide();
	<?php } ?>
	<?php if( !$piereg['password_meter'] ){ ?>
	jQuery('#meter').hide();
	<?php } ?>
	<?php if( !$piereg['disclaimer'] ){ ?>
	jQuery('#disclaim_content').hide();
	<?php } ?>
	<?php if( !$piereg['license'] ){ ?>
	jQuery('#lic_content').hide();
	<?php } ?>
	<?php if( !$piereg['privacy'] ){ ?>
	jQuery('#priv_content').hide();
	<?php } ?>
	<?php if( !$piereg['email_verify'] ){ ?>
	jQuery('#grace').hide();
	<?php } ?>
	<?php if( $piereg['captcha'] != 2 ){ ?>
	jQuery('#reCAPops').hide();
	<?php } ?>
	<?php if( $piereg['captcha'] != 1 ){ ?>
	jQuery('#SimpleDetails').hide();
	<?php } ?>
	<?php if( !$piereg['custom_msg'] ){ ?>
	jQuery('#enabled_msg').hide();
	<?php } ?>
	<?php if( !$piereg['custom_adminmsg'] ){ ?>
	jQuery('#enabled_adminmsg').hide();
	<?php } ?>
	jQuery('#email_verify').change(function() {
		if(jQuery('#email_verify').attr('checked'))
			jQuery('#grace').show();
		else
			jQuery('#grace').hide();
		return true;
	});
	jQuery('#code').change(function() {		
		if (jQuery('#code').attr('checked'))
			jQuery('#codepass').show();
		else
			jQuery('#codepass').hide();
		return true;
	});
	jQuery('#pwm').change(function() {		
		if (jQuery('#pwm').attr('checked'))
			jQuery('#meter').show();
		else
			jQuery('#meter').hide();
		return true;
	});
	jQuery('#disclaimer').change(function() {		
		if (jQuery('#disclaimer').attr('checked'))
			jQuery('#disclaim_content').show();
		else
			jQuery('#disclaim_content').hide();
		return true;
	});
	jQuery('#license').change(function() {		
		if (jQuery('#license').attr('checked'))
			jQuery('#lic_content').show();
		else
			jQuery('#lic_content').hide();
		return true;
	});
	jQuery('#privacy').change(function() {		
		if (jQuery('#privacy').attr('checked'))
			jQuery('#priv_content').show();
		else
			jQuery('#priv_content').hide();
		return true;
	});
	jQuery('#captcha').change(function() {
		if(jQuery('#captcha').attr('checked'))
			jQuery('#SimpleDetails').show();
		else
			jQuery('#SimpleDetails').hide();
		return true;
	});
	jQuery('#recaptcha').change(function() {
		if(jQuery('#recaptcha').attr('checked'))
			jQuery('#reCAPops').show();
		else
			jQuery('#reCAPops').hide();
		return true;
	});
	jQuery('#custom_msg').change(function() {
		if(jQuery('#custom_msg').attr('checked'))
			jQuery('#enabled_msg').show();
		else
			jQuery('#enabled_msg').hide();
		return true;
	});
	jQuery('#custom_adminmsg').change(function() {
		if(jQuery('#custom_adminmsg').attr('checked'))
			jQuery('#enabled_adminmsg').show();
		else
			jQuery('#enabled_adminmsg').hide();
		return true;
	});
	set_add_del_code();
	set_add_del();
});

</script>
            <?php
		}
		function UnverifiedHead(){
			if( $_GET['page'] == 'unverified-users')
				echo "<script type='text/javascript' src='".get_option('siteurl')."/wp-admin/js/forms.js?ver=20080317'></script>";
		}
		function AdminValidate(){
			global $wpdb;
			$piereg = get_option('pie_register');
			check_admin_referer('piereg-unverified');
			$valid = $_POST['vusers'];
			if($valid){
			foreach( $valid as $user_id ){
				if ( $user_id ) {
					if( $piereg['email_verify'] ){
						$login = get_usermeta($user_id, 'email_verify_user');
							$useremail=get_usermeta($user_id,'email_verify_email');
		
							$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
							$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
							delete_usermeta($user_id, 'email_verify_user');
							delete_usermeta($user_id, 'email_verify');
							delete_usermeta($user_id, 'email_verify_date');
							delete_usermeta($user_id, 'email_verify_user_email');
							
					}else if( $piereg['admin_verify'] ){
						$login = get_usermeta($user_id, 'admin_verify_user');
						$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
						$useremail=get_usermeta($user_id,'email_verify_email');
		
						$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
						delete_usermeta($user_id, 'admin_verify_user');
						delete_usermeta($user_id, 'email_verify_user_email');
					}else if( $piereg['paypal_option'] ){
							$login = get_usermeta($user_id, 'email_verify_user');
							$useremail=get_usermeta($user_id,'email_verify_email');
							$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
							$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
							delete_usermeta($user_id, 'email_verify_user_email');
							delete_usermeta($user_id, 'email_verify_user');
							delete_usermeta($user_id, 'email_verify');
							delete_usermeta($user_id, 'email_verify_date');
					}
					
					$this->VerifyNotification($user_id);
				}
			}
			}else{
			$_POST['notice'] = __("<strong>Error:</strong> Please select a user to validate!","piereg");
			return false;
			}
			$_POST['notice'] = __("Users Verified","piereg");
			
		}
		function PaymentLink(){
			global $wpdb;
			$piereg = get_option('pie_register');
			check_admin_referer('piereg-unverified');
			$valid = $_POST['vusers'];
			if($valid){
			foreach( $valid as $user_id ){
				if ( $user_id ) {
					if( $piereg['email_verify'] || $piereg['paypal_option']){
						$login = get_usermeta($user_id, 'email_verify_user');
						$user_email = get_usermeta($user_id, 'email_verify_email');	
							$pp="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=".$piereg['paypal_butt_id']."&custom=".$user_id;
							
							
					}else if( $piereg['admin_verify'] ){
						$login = get_usermeta($user_id, 'admin_verify_user');
						$user_email = get_usermeta($user_id, 'admin_verify_email');
						$pp="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=".$piereg['paypal_butt_id']."&custom=".$user_id;
					}
					$message = __('Dear User,') . "\r\n\r\n";
					$message .= __('You have successfuly registered but your payment has been overdue.') . "\r\n";
					$message .= sprintf(__('Username: %s', 'piereg'), $login) . "\r\n\r\n";
					$message .= __('Please Click or copy this link to browser to finish the registration.') . "\r\n\r\n";
					$message .= $pp." \r\n Thank you. \r\n";
					add_filter('wp_mail_from', array($this, 'userfrom'));
			add_filter('wp_mail_from_name', array($this, 'userfromname'));
			wp_mail($user_email, sprintf(__('[%s] Payment Pending', 'piereg'), get_option('blogname')), $message);
					//$this->VerifyNotification($user_id,$pp);
				}
			}
			}else{
			$_POST['notice'] = __("<strong>Error:</strong> Please select a user to send link to!","piereg");
			return false;
			}
			$_POST['notice'] = __("Payment link has been e-mail to the user(s)","piereg");
		
		}
		function AdminDeleteUnvalidated() {
			global $wpdb;
			$piereg = get_option('pie_register');
			check_admin_referer('piereg-unverified');
			$delete = $_POST['vusers'];
			include_once( ABSPATH . 'wp-admin/includes/user.php' );
			if($delete){
			foreach( $delete as $user_id ){
				if ( $user_id ) {	
					wp_delete_user($user_id);
				}
			}
			}else{
			$_POST['notice'] = __("<strong>Error:</strong> Please select a user to delete","piereg");
			return false;
			}
			$_POST['notice'] = __("Users Deleted","piereg");
		}
		function AdminEmailValidate(){
			global $wpdb;
			check_admin_referer('piereg-unverified');
			$valid = $_POST['vusers'];
			if( is_array($valid) ):
			foreach( $valid as $user_id ){
				$code = get_usermeta($user_id, 'email_verify');
				$user_login = get_usermeta($user_id, 'email_verify_user');
				$user_email = get_usermeta($user_id, 'email_verify_email');
				$email_code = '?piereg_verification=' . $code;




				$prelink = __('Verification URL: ', 'piereg');		
				$message  = sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n";
				//$message .= sprintf(__('Password: %s', 'piereg'), $plaintext_pass) . "\r\n";
				$message .= $prelink . get_option('siteurl') . "/wp-login.php" . $email_code . "\r\n"; 
				$message .= $notice; 
				add_filter('wp_mail_from', array($this, 'userfrom'));
				add_filter('wp_mail_from_name', array($this, 'userfromname'));
				wp_mail($user_email, sprintf(__('[%s] Verify Account Link', 'piereg'), get_option('blogname')), $message);
						
			}
			$_POST['notice'] = __("Verification Emails have been re-sent", "piereg");
			else:
			$_POST['notice'] = __("<strong>Error:</strong> Please select a user to send emails to.", "piereg");
			endif;
		}
		function VerifyNotification($user_id,$pp=""){
			global $wpdb;
			$piereg = get_option('pie_register');
			
			$user = $wpdb->get_row("SELECT user_login, user_email FROM $wpdb->users WHERE ID='$user_id'");
			$message = __('Your account has now been activated by an administrator.') . "\r\n";
			$message .= sprintf(__('Username: %s', 'piereg'), $user->user_login) . "\r\n";
			$message .= $prelink . get_option('siteurl') . "/wp-login.php" . $email_code . "\r\n";
			$user_email=get_usermeta($user_id, 'email_verify_email');
						 
			add_filter('wp_mail_from', array($this, 'userfrom'));
			add_filter('wp_mail_from_name', array($this, 'userfromname'));
			wp_mail($user_email, sprintf(__('[%s] User Account Registration', 'piereg'), get_option('blogname')), $message);
		}
		function Unverified(){
			global $wpdb;
			if( $_POST['notice'] )
				echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
				
			$unverified = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE user_login LIKE '%unverified__%'");
			$piereg = get_option('pie_register');
			?>
			<div class="wrap">
            	<h2><?php _e('Unverified Users', 'piereg')?></h2>
                <form id="verify-filter" method="post" action="">
                	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg-unverified'); ?>
                    <div class="tablenav">
                    <div class="alignleft">
                    <input value="<?php _e('Verify Checked Users','piereg');?>" name="verifyit" class="button-secondary" type="submit">  &nbsp;<?php if( $piereg['paypal_option'] ){ ?> <input value="<?php _e('Send Payment Link','piereg');?>" name="paymentl" class="button-secondary" type="submit"><?php } ?>  &nbsp; <?php if( $piereg['email_verify'] ){ ?>
                    <input value="<?php _e('Resend Verification E-mail','piereg');?>" name="emailverifyit" class="button-secondary" type="submit"> <?php } ?> &nbsp; <input value="<?php _e('Delete','piereg');?>" name="vdeleteit" class="button-secondary delete" type="submit">
                    </div> 
                    <br class="clear">
                    </div>
                    
                    <br class="clear">

                    <table class="widefat">
                        <thead>
                        	<tr class="thead">
                            	<th scope="col" class="check-column"><input onclick="checkAll(document.getElementById('verify-filter'));" type="checkbox"> </th>
                                <th><?php _e('Unverified ID','piereg');?></th>
                                <th><?php _e('User Name','piereg');?></th>
                                <th><?php _e('E-mail','piereg');?></th>
                                <th><?php _e('Role','piereg');?></th>
                            </tr>
                            </thead>
                            <tbody id="users" class="list:user user-list">
                            <?php 
								foreach( $unverified as $un) {
								if( $alt ) $alt = ''; else $alt = "alternate";
								$user_object = new WP_User($un->ID);
								$roles = $user_object->roles;
								$role = array_shift($roles);
								if( $piereg['email_verify'] )
									$user_login = get_usermeta($un->ID, 'email_verify_user');
								else if( $piereg['admin_verify'] )
									$user_login = get_usermeta($un->ID, 'admin_verify_user');
							?>
                                <tr id="user-1" class="<?php echo $alt;?>">
                                    <th scope="row" class="check-column"><input name="vusers[]" id="user_<?php echo $un->ID;?>" class="administrator" value="<?php echo $un->ID;?>" type="checkbox"></th>
                                    <td><strong><?php echo $un->user_login;?></strong></td>
                                    <td><strong><?php echo $user_login;?></strong></td>
                            
                                    <td><a href="mailto:<?php echo $un->user_email;?>" title="<?php _e('e-mail: ', 'piereg'); echo $un->user_email;?>"><?php echo $un->user_email;?></a></td>
                                    <td><?php echo ucwords($role);?></td>
                                </tr>
                             <?php } ?>
                             </tbody>
                          </table>
                      </form>
                 </div>
                 

           <?php
		}
		
		function RegPlusSettings(){
			$piereg = get_option( 'pie_register' );
			$piereg_custom = get_option( 'pie_register_custom' );
			$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
			if( $_POST['notice'] )
				echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
				
			if( !is_array($piereg['profile_req']) )
				$piereg['profile_req'] = array();
			if( is_array($piereg['codepass']) ){
				foreach( $piereg['codepass'] as $code ){
					$codes .= '<div class="code_block">
                                    <input type="text" name="piereg_codepass[]"  value="' . $code . '" /> &nbsp;
                                    <a href="#" onClick="return selremcode(this);" class="remove_code"><img src="' . $plugin_url . 'removeBtn.gif" alt="' . __("Remove Code","piereg") . '" title="' .  __("Remove Code","piereg") . '" /></a>
						<a href="#" onClick="return seladdcode(this);" class="add_code"><img src="' . $plugin_url . 'addBtn.gif" alt="' . __("Add Code","piereg") . '" title="' . __("Add Code","piereg") . '" /></a>
                                    </div>';
				}
			}
			$types = '<option value="text">Text Field</option><option value="date">Date Field</option><option value="select">Select Field</option><option value="checkbox">Checkbox</option><option value="radio">Radio Box</option><option value="textarea">Text Area</option><option value="hidden">Hidden Field</option>';
			$extras = '<div class="extraoptions" style="float:left"><label>Extra Options: <input type="text" class="extraops" name="extraoptions[0]" value="" /></label></div>';
			if( is_array($piereg_custom) ){
				foreach( $piereg_custom as $k => $v ) {
					$types = '<option value="text"';
					if( $v['fieldtype'] == 'text' ) $types .= ' selected="selected"';
					$types .='>Text Field</option><option value="date"';
					if( $v['fieldtype'] == 'date' ) $types .= ' selected="selected"';
					$types .='>Date Field</option><option value="select"';
					if( $v['fieldtype'] == 'select' ) $types .= ' selected="selected"';
					$types .= '>Select Field</option><option value="checkbox"';
					if( $v['fieldtype'] == 'checkbox' ) $types .= ' selected="selected"';
					$types .= '>Checkbox</option><option value="radio"';
					if( $v['fieldtype'] == 'radio' ) $types .= ' selected="selected"';
					$types .= '>Radio Box</option><option value="textarea"';
					if( $v['fieldtype'] == 'textarea' ) $types .= ' selected="selected"';
					$types .= '>Text Area</option><option value="hidden"';
					if( $v['fieldtype'] == 'hidden' ) $types .= ' selected="selected"';
					$types .= '>Hidden Field</option>';
					
					$extras = '<div class="extraoptions" style="float:left;"><label>Extra Options: <input type="text" name="extraoptions['.$k.']" class="extraops" value="' . $v['extraoptions'] . '" /></label></div>';

					
					$rows .= '<tr valign="top" class="row_block">
                       			 <th scope="row"><label for="custom">' . __('Custom Field', 'piereg') . '</label></th>
                        		<td><input type="text" name="label['.$k.']" class="custom" style="font-size:16px;padding:2px; width:150px;" value="' . $v['label'] . '" /> &nbsp; ';
					$rows .= '<select name="fieldtype['.$k.']" class="fieldtype">'.$types.'</select> '.$extras.' &nbsp; ';
					$rows .= '<label><input type="checkbox" name="reg['.$k.']" class="reg" value="1"';
					if( $v['reg'] ) $rows .= ' checked="checked"';
					$rows .= ' /> ' .  __('Add Registration Field', 'piereg') . '</label> &nbsp; <label><input type="checkbox" name="profile['.$k.']" class="profile" value="1"';
					if( $v['profile'] ) $rows .= ' checked="checked"';
					$rows .= ' /> ' . __('Add Profile Field', 'piereg') . '</label> &nbsp; <label><input type="checkbox" name="required['.$k.']" class="required" value="1"';
					if( $v['required'] ) $rows .= ' checked="checked"';
					$rows .= ' /> ' . __('Required', 'piereg') . '</label> &nbsp; 
                                
                                <a href="#" onClick="return selrem(this);" class="remove_row"><img src="' . $plugin_url . 'removeBtn.gif" alt="' . __("Remove Row","piereg") . '" title="' . __("Remove Row","piereg") . '" /></a>
						<a href="#" onClick="return seladd(this);" class="add_row"><img src="' . $plugin_url . 'addBtn.gif" alt="' . __("Add Row","piereg") . '" title="' . __("Add Row","piereg") . '" /></a></td>
                        	</tr>';
				}
			}
			?>
            <div class="wrap">
            	<h2><?php _e('Pie Register Settings', 'piereg')?></h2>
				<div style="background:#FFEBE8; border-color:#cc0000; padding:5px;-moz-border-radius-bottomleft:3px;
-moz-border-radius-bottomright:3px;
-moz-border-radius-topleft:3px;
-moz-border-radius-topright:3px;
border-style:solid;
border-width:1px;
margin:0 0 16px 8px;
padding:12px; width:400px;">Please put this code at the top of your wp-login.php otherwise the plugin won't work properly. <br /><code>&lt;&#0063;php <br />session_start(); &#0063;&gt;</code></div>
                <form method="post" action="" enctype="multipart/form-data">
                	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg-update-options'); ?>
                    <p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" />
                    <table class="form-table">
                        <tbody>
                        	<tr valign="top">
                       			 <th scope="row"><label for="password"><?php _e('Password', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_password" id="password" value="1" <?php if( $piereg['password'] ) echo 'checked="checked"';?> /> <?php _e('Allow New Registrations to set their own Password', 'piereg');?></label><br />
                                <label><input type="checkbox" name="piereg_password_meter" id="pwm" value="1" <?php if( $piereg['password_meter'] ) echo 'checked="checked"';?> /> <?php _e('Enable Password Strength Meter','piereg');?></label>
                                <div id="meter" style="margin-left:20px;">
                                	<label><?php _e('Short', 'piereg');?> <input type="text" name="piereg_short" value="<?php echo $piereg['short'];?>" /></label><br />
                                    <label><?php _e('Bad', 'piereg');?> <input type="text" name="piereg_bad" value="<?php echo $piereg['bad'];?>" /></label><br />
                                    <label><?php _e('Good', 'piereg');?> <input type="text" name="piereg_good" value="<?php echo $piereg['good'];?>" /></label><br />
                                    <label><?php _e('Strong', 'piereg');?> <input type="text" name="piereg_strong" value="<?php echo $piereg['strong'];?>" /></label><br />
                                </div>
                                </td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="logo"><?php _e('Custom Logo', 'piereg');?></label></th>
                        		<td><input type="file" name="piereg_logo" id="logo" value="1" /> &nbsp; <small><?php _e("Recommended Logo width is 358px, but any height should work.", "piereg");?></small><br /> <img src="<?php echo $piereg['logo'];?>" alt="" />
                                <?php if ( $piereg['logo'] ) {?>
                                <br /><label><input type="checkbox" name="remove_logo" value="1" /> <?php _e('Delete Logo', 'piereg');?></label>
                                <?php } else { ?>
                                <p><small><strong><?php _e('Having troubles uploading?','piereg');?></strong>  <?php _e('Uncheck "Organize my uploads into month- and year-based folders" in','piereg');?> <a href="<?php echo get_option('siteurl');?>/wp-admin/options-misc.php"><?php _e('Miscellaneous Settings', 'piereg');?></a>. <?php _e('(You can recheck this option after your logo has uploaded.)','piereg');?></small></p>
                                <?php } ?>
                                 </td>
                        	</tr>
							
							 <tr valign="top">
                       			 <th scope="row"><label for="paypal_option"><?php _e('Paypal Options', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_paypal_option" id="paypal_option" value="1" <?php if( $piereg['paypal_option'] ) echo 'checked="checked"';?> /> <?php _e('Add Paypal payment gateway for registration.', 'piereg');?></label><br />
                                <?php _e('Enter Your paypal hosted button ID.', 'piereg');?>
                                <div><label for="paypal_butt_id"><strong><?php _e('Paypal Button ID', 'piereg');?></strong>: </label><input type="text" name="piereg_paypal_butt_id" id="paypal_butt_id" style="width:100px;" value="<?php echo $piereg['paypal_butt_id'];?>" />
                            </div>
							<div><label for="paypal_PDT"><strong><?php _e('Paypal PDT Token', 'piereg');?></strong>: </label><input type="text" name="piereg_paypal_pdt" id="paypal_pdt" style="width:300px;" value="<?php echo $piereg['paypal_pdt'];?>" />
							<br />
							<strong>
							<?php _e('Set Thank You URL In Step 2<br>'.wp_login_url(), 'piereg');?><br />
							<?php _e('SET this variables at STEP 3', 'piereg');?><br />
							<?php _e('rm=2', 'piereg');?><br />
							<?php _e('notify_url='.wp_login_url(), 'piereg');?>
							</strong>
                            </div>
</td>
                        	</tr>
							
							
                            <tr valign="top">
                       			 <th scope="row"><label for="email_verify"><?php _e('Email Verification', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_email_verify" id="email_verify" value="1" <?php if( $piereg['email_verify'] ) echo 'checked="checked"';?> /> <?php _e('Prevent fake email address registrations.', 'piereg');?></label><br />
                                <?php _e('Requires new registrations to click a link in the notification email to enable their account.', 'piereg');?>
                                <div id="grace"><label for="email_delete_grace"><strong><?php _e('Grace Period (days)', 'piereg');?></strong>: </label><input type="text" name="piereg_email_delete_grace" id="email_delete_grace" style="width:50px;" value="<?php echo $piereg['email_delete_grace'];?>" /><br />
                                <?php _e('Unverified Users will be automatically deleted after grace period expires', 'piereg');?></div>
</td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="admin_verify"><?php _e('Admin Verification', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_admin_verify" id="admin_verify" value="1" <?php if( $piereg['admin_verify'] ) echo 'checked="checked"';?> /> <?php _e('Moderate all user registrations to require admin approval. NOTE: Email Verification must be DISABLED to use this feature.', 'piereg');?></label></td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="code"><?php _e('Invitation Code', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_code" id="code" value="1" <?php if( $piereg['code'] ) echo 'checked="checked"';?> /> <?php _e('Enable Invitation Code(s)', 'piereg');?></label>
                                    <div id="codepass">
                                    <label><input type="checkbox" name="piereg_dash_widget" value="1" <?php if( $piereg['dash_widget'] ) echo 'checked="checked"'; ?>  /> <?php _e('Enable Invitation Tracking Dashboard Widget', 'piereg');?></label><br />
                                    <label><input type="checkbox" name="piereg_code_req" id="code_req" value="1" <?php if( $piereg['code_req'] ) echo 'checked="checked"';?> /> <?php _e('Require Invitation Code to Register', 'piereg');?></label>
                              <?php if( $codes ){ echo $codes; } else { ?>
                                    <div class="code_block">
                                    <input type="text" name="piereg_codepass[]"  value="<?php echo $piereg['codepass'];?>" /> &nbsp;
                                    <a href="#" onClick="return selremcode(this);" class="remove_code"><img src="<?php echo $plugin_url; ?>removeBtn.gif" alt="<?php _e("Remove Code","piereg")?>" title="<?php _e("Remove Code","piereg")?>" /></a>
						<a href="#" onClick="return seladdcode(this);" class="add_code"><img src="<?php echo $plugin_url; ?>addBtn.gif" alt="<?php _e("Add Code","piereg")?>" title="<?php _e("Add Code","piereg")?>" /></a>
                                    </div>
                               <?php } ?>
                                    <small><?php _e('One of these codes will be required for users to register.', 'piereg');?></small></div>
                                    </td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="captcha"><?php _e('CAPTCHA', 'piereg');?></label></th>
                        		<td><label><input type="radio" name="piereg_captcha" id="none" value="0" <?php if( $piereg['captcha'] == 0 ) echo 'checked="checked"';?> /> <?php _e('None', 'piereg');?></label> <input type="radio" name="piereg_captcha" id="captcha" value="1" <?php if( $piereg['captcha'] == 1 ) echo 'checked="checked"';?> /> <?php _e('Simple CAPTCHA', 'piereg');?></label> <label><input type="radio" name="piereg_captcha" id="recaptcha" value="2" <?php if( $piereg['captcha'] == 2 ) echo 'checked="checked"';?> /> <a href="http://recaptcha.net/"><?php _e('reCAPTCHA','piereg');?></a></label>
                                <div id="SimpleDetails">
                                <p><?php _e('You may need to add the code <code>&lt;?php session_start(); ?></code> to the top line of the wp_login.php file to enable Simple CAPTCHA to work correctly.', 'piereg');?></p>
                                </div>
                                <div id="reCAPops">
                                <label for="public_key"><?php _e('reCAPTCHA Public Key:','piereg');?></label> <input type="text" style="width:500px;" name="piereg_reCAP_public_key" id="public_key" value="<?php echo $piereg['reCAP_public_key'];?>" /> <a href="<?php require_once ("recaptchalib.php"); echo rp_recaptcha_get_signup_url('pie-solutions.com','pie_register');?>" target="_blank"><?php _e('Sign up &raquo;','piereg');?></a><br />
								<label for="private_key"><?php _e('reCAPTCHA Private Key:','piereg');?></label> <input type="text" style="width:500px;" id="private_key" name="piereg_reCAP_private_key" value="<?php echo $piereg['reCAP_private_key'];?>" />
                                </div>
                                
                                </td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="disclaimer"><?php _e('Disclaimer', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_disclaimer" id="disclaimer" value="1" <?php if($piereg['disclaimer']) echo 'checked="checked"';?> /> <?php _e('Enable Disclaimer','piereg');?></label>
                                <div id="disclaim_content">
                                <label for="disclaimer_title"><?php _e('Disclaimer Title','piereg');?></label> <input type="text" name="piereg_disclaimer_title" id="disclaimer_title" value="<?php echo $piereg['disclaimer_title'];?>" /> <br />
                                <label for="disclaimer_content"><?php _e('Disclaimer Content','piereg');?></label><br />
                                <textarea name="piereg_disclaimer_content" id="disclaimer_content" cols="25" rows="10" style="width:80%;height:300px;display:block;"><?php echo stripslashes($piereg['disclaimer_content']);?></textarea><br />
                                <label for="disclaimer_agree"><?php _e('Agreement Text','piereg');?></label> <input type="text" name="piereg_disclaimer_agree" id="disclaimer_agree" value="<?php echo $piereg['disclaimer_agree'];?>" />
                                </div></td>
                        	</tr>
                            
                            <tr valign="top">
                       			 <th scope="row"><label for="license"><?php _e('License Agreement', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_license" id="license" value="1" <?php if($piereg['license']) echo 'checked="checked"';?> /> <?php _e('Enable License Agreement','piereg');?></label>
                                <div id="lic_content">
                                <label for="license_title"><?php _e('License Title','piereg');?></label> <input type="text" name="piereg_license_title" id="license_title" value="<?php echo $piereg['license_title'];?>" /> <br />
                                <label for="license_content"><?php _e('License Content','piereg');?></label><br />
                                <textarea name="piereg_license_content" id="license_content" cols="25" rows="10" style="width:80%;height:300px;display:block;"><?php echo stripslashes($piereg['license_content']);?></textarea><br />
                                <label for="license_agree"><?php _e('Agreement Text','piereg');?></label> <input type="text" name="piereg_license_agree" id="license_agree" value="<?php echo $piereg['license_agree'];?>" />
                                </div></td>
                        	</tr>
                            
                            <tr valign="top">
                       			 <th scope="row"><label for="privacy"><?php _e('Privacy Policy', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_privacy" id="privacy" value="1" <?php if($piereg['privacy']) echo 'checked="checked"';?> /> <?php _e('Enable Privacy Policy','piereg');?></label>
                                <div id="priv_content">
                                <label for="privacy_title"><?php _e('Privacy Policy Title','piereg');?></label> <input type="text" name="piereg_privacy_title" id="privacy_title" value="<?php echo $piereg['privacy_title'];?>" /> <br />
                                <label for="privacy_content"><?php _e('Privacy Policy Content','piereg');?></label><br />
                                <textarea name="piereg_privacy_content" id="privacy_content" cols="25" rows="10" style="width:80%;height:300px;display:block;"><?php echo stripslashes($piereg['privacy_content']);?></textarea><br />
                                <label for="privacy_agree"><?php _e('Agreement Text','piereg');?></label> <input type="text" name="piereg_privacy_agree" id="privacy_agree" value="<?php echo $piereg['privacy_agree'];?>" />
                                </div></td>
                        	</tr>
                            
                            <tr valign="top">
                       			 <th scope="row"><label for="email_exists"><?php _e('Allow Existing Email', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_email_exists" id="email_exists" value="1" <?php if( $piereg['email_exists'] ) echo 'checked="checked""';?> /> <?php _e('Allow new registrations to use an email address that has been previously registered', 'piereg');?></label></td>
                        	</tr>
                         </tbody>
                 	</table>
                    <h3><?php _e('Additional Profile Fields', 'piereg');?></h3>
                    <p><?php _e('Check the fields you would like to appear on the Registration Page.', 'piereg');?></p>
                    <table class="form-table">
                        <tbody>
                        	<tr valign="top">
                       			 <th scope="row"><label for="name"><?php _e('Name', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_firstname" id="name" value="1" <?php if( $piereg['firstname'] ) echo 'checked="checked"';?> /> <?php _e('First Name', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_lastname" value="1" <?php if( $piereg['lastname'] ) echo 'checked="checked"';?> /> <?php _e('Last Name', 'piereg');?></label></td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="contact"><?php _e('Contact Info', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_website" id="contact" value="1" <?php if( $piereg['website'] ) echo 'checked="checked"';?> /> <?php _e('Website', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_aim" value="1" <?php if( $piereg['aim'] ) echo 'checked="checked"';?> /> <?php _e('AIM', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_yahoo" value="1" <?php if( $piereg['yahoo'] ) echo 'checked="checked"';?> /> <?php _e('Yahoo IM', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_jabber" value="1" <?php if( $piereg['jabber'] ) echo 'checked="checked"';?> /> <?php _e('Jabber / Google Talk', 'piereg');?></label>  &nbsp; <label><input type="checkbox" name="piereg_phone" value="1" <?php if( $piereg['phone'] ) echo 'checked="checked"';?> /> <?php _e('Phone # / Mobile #.', 'piereg');?></label></td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="about"><?php _e('About Yourself', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_about" id="name" value="1" <?php if( $piereg['about'] ) echo 'checked="checked"';?> /> <?php _e('About Yourself', 'piereg');?></label></td>
                        	</tr>
                            <tr valign="top">
                       			 <th scope="row"><label for="req"><?php _e('Required Profile Fields', 'piereg');?></label></th>
                        		<td><label><input type="checkbox" name="piereg_profile_req[]" value="firstname" <?php if( in_array('firstname', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('First Name', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="lastname" <?php if( in_array('lastname', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('Last Name', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="website" <?php if( in_array('website', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('Website', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="aim" <?php if( in_array('aim', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('AIM', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="yahoo" <?php if( in_array('yahoo', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('Yahoo IM', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="jabber" <?php if( in_array('jabber', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('Jabber / Google Talk', 'piereg');?></label>  &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="phone" <?php if( in_array('phone', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('Phone # / Mobile #', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_profile_req[]" value="about" <?php if( in_array('about', $piereg['profile_req']) ) echo 'checked="checked"';?> /> <?php _e('About Yourself', 'piereg');?></label></td>
                        	</tr>
                            <tr valign="top">
                            	<th scope="row"><label for="require_style"><?php _e('Required Field Style Rules', 'piereg');?></label></th>
                                <td><input type="text" name="piereg_require_style" id="require_style" value="<?php echo $piereg['require_style'];?>" style="width: 350px;" /></td>
                            </tr>
                            
                         </tbody>
                     </table>
                     <h3><?php _e('User Defined Fields', 'piereg');?></h3>
                    <p><?php _e('Enter the custom fields you would like to appear on the Registration Page.', 'piereg');?></p>
                    <p><small><?php _e('Enter Extra Options for Select, Checkboxes and Radio Fields as comma seperated values. For example, if you chose a select box for a custom field of "Gender", your extra options would be "Male,Female".','piereg');?></small></p>
                    <table class="form-table">
                        <tbody>
                        <?php if( $rows ){ echo $rows; }else{ ?>
                        	<tr valign="top" class="row_block">
                       			 <th scope="row"><label for="custom"><?php _e('Custom Field', 'piereg');?></label></th>
                        		<td><input type="text" name="label[0]" class="custom" style="font-size:16px;padding:2px; width:150px;" value="" /> &nbsp; <select class="fieldtype" name="fieldtype[0]"><?php echo $types; ?></select> <?php echo $extras;?> &nbsp; <label><input type="checkbox" name="reg[0]" class="reg" value="1" />  <?php _e('Add Registration Field', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="profile[0]"  class="profile" value="1" /> <?php _e('Add Profile Field', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="required[0]" class="required" value="1" /> <?php _e('Required', 'piereg');?></label> &nbsp; 
                                
                                <a href="#" onClick="return selrem(this);" class="remove_row"><img src="<?php echo $plugin_url; ?>removeBtn.gif" alt="<?php _e("Remove Row","piereg")?>" title="<?php _e("Remove Row","piereg")?>" /></a>
						<a href="#" onClick="return seladd(this);" class="add_row"><img src="<?php echo $plugin_url; ?>addBtn.gif" alt="<?php _e("Add Row","piereg")?>" title="<?php _e("Add Row","piereg")?>" /></a></td>
                        	</tr>
                          <?php } ?>
                          	</tbody>
                       </table>
                       <table class="form-table">
                        <tbody>
                            <tr valign="top">
                       			 <th scope="row"><label for="date"><?php _e('Date Field Settings', 'piereg');?></label></th>
                        		<td><label><?php _e('First Day of the Week','piereg');?>: <select type="select" name="piereg_firstday">
                                		<option value="7" <?php if( $piereg['firstday'] == '7' ) echo 'selected="selected"';?>><?php _e('Monday','piereg');?></option>
                                        <option value="1" <?php if( $piereg['firstday'] == '1' ) echo 'selected="selected"';?>><?php _e('Tuesday','piereg');?></option>
                                        <option value="2" <?php if( $piereg['firstday'] == '2' ) echo 'selected="selected"';?>><?php _e('Wednesday','piereg');?></option>
                                        <option value="3" <?php if( $piereg['firstday'] == '3' ) echo 'selected="selected"';?>><?php _e('Thursday','piereg');?></option>
                                        <option value="4" <?php if( $piereg['firstday'] == '4' ) echo 'selected="selected"';?>><?php _e('Friday','piereg');?></option>
                                        <option value="5" <?php if( $piereg['firstday'] == '5' ) echo 'selected="selected"';?>><?php _e('Saturday','piereg');?></option>
                                        <option value="6" <?php if( $piereg['firstday'] == '6' ) echo 'selected="selected"';?>><?php _e('Sunday','piereg');?></option>
                                        </select>
                                    </label> &nbsp; 
                                     <label for="dateformat"><?php _e('Date Format','piereg');?>:</label> <input type="text" name="piereg_dateformat" id="dateformat" value="<?php echo $piereg['dateformat'];?>" style="width:100px;" /> &nbsp; 
                                      <label for="startdate"><?php _e('First Selectable Date','piereg');?>:</label> <input type="text" name="piereg_startdate" id="startdate" value="<?php echo $piereg['startdate'];?>"  style="width:100px;" /> <br />
                                       <label for="calyear"><?php _e('Default Year','piereg');?>:</label> <input type="text" name="piereg_calyear" id="calyear" value="<?php echo $piereg['calyear'];?>" style="width:40px;" /> &nbsp;
                                       <label for="calmonth"><?php _e('Default Month','piereg');?>:</label> <select name="piereg_calmonth" id="calmonth">
                                       		<option value="cur" <?php if( $piereg['calmonth'] === 'cur' ) echo 'selected="selected"';?>><?php _e('Current Month','piereg');?></option>
                                            <option value="0" <?php if( $piereg['calmonth'] == '0' ) echo 'selected="selected"';?>><?php _e('Jan','piereg');?></option>
                                            <option value="1" <?php if( $piereg['calmonth'] == '1' ) echo 'selected="selected"';?>><?php _e('Feb','piereg');?></option>
                                            <option value="2" <?php if( $piereg['calmonth'] == '2' ) echo 'selected="selected"';?>><?php _e('Mar','piereg');?></option>
                                            <option value="3" <?php if( $piereg['calmonth'] == '3' ) echo 'selected="selected"';?>><?php _e('Apr','piereg');?></option>
                                            <option value="4" <?php if( $piereg['calmonth'] == '4' ) echo 'selected="selected"';?>><?php _e('May','piereg');?></option>
                                            <option value="5" <?php if( $piereg['calmonth'] == '5' ) echo 'selected="selected"';?>><?php _e('Jun','piereg');?></option>
                                            <option value="6" <?php if( $piereg['calmonth'] == '6' ) echo 'selected="selected"';?>><?php _e('Jul','piereg');?></option>
                                            <option value="7" <?php if( $piereg['calmonth'] == '7' ) echo 'selected="selected"';?>><?php _e('Aug','piereg');?></option>
                                            <option value="8" <?php if( $piereg['calmonth'] == '8' ) echo 'selected="selected"';?>><?php _e('Sep','piereg');?></option>
                                            <option value="9" <?php if( $piereg['calmonth'] == '9' ) echo 'selected="selected"';?>><?php _e('Oct','piereg');?></option>
                                            <option value="10" <?php if( $piereg['calmonth'] == '10' ) echo 'selected="selected"';?>><?php _e('Nov','piereg');?></option>
                                            <option value="11" <?php if( $piereg['calmonth'] == '11' ) echo 'selected="selected"';?>><?php _e('Dec','piereg');?></option>
                                       </select>
                                     
                                    </td>
                            </tr>
                        </tbody>
                     </table>
                     
                     <h3><?php _e('Auto-Complete Queries', 'piereg');?></h3>
                     <p><?php _e('You can now link to the registration page with queries to autocomplete specific fields for the user.  I have included the query keys below and an example of a query URL.', 'piereg');?></p>
                                <code>user_login &nbsp; user_email &nbsp; firstname &nbsp; lastname &nbsp; website  &nbsp; aim &nbsp; yahoo &nbsp; jabber &nbsp; about &nbsp; code</code>
                               <p><?php _e('For any custom fields, use your custom field label with the text all lowercase, using underscores instead of spaces. For example if your custom field was "Middle Name" your query key would be <code>middle_name</code>', 'piereg');?></p>
                               <p><strong><?php _e('Example Query URL', 'piereg');?></strong></p>
                                <code>http://www.pie-solutions.com/wp-login.php?action=register&user_login=pie-solutions&user_email=info@pie-solutions.com&firstname=Pie&lastname=Solutions&website=www.pie-solutions.com&aim=skullaim&yahoo=skullhoo&jabber=skulltalk&about=We+are+a+WordPress+Plugin+developing+Company.&code=invitation&middle_name=Danger </code>
                     
                     <h3><?php _e('Customize User Notification Email', 'piereg');?></h3>
                    <table class="form-table"> 
                        <tbody>
                        <tr valign="top">
                       		<th scope="row"><label><?php _e('Custom User Email Notification', 'piereg');?></label></th>
                        	<td><label><input type="checkbox" name="piereg_custom_msg" id="custom_msg" value="1" <?php if( $piereg['custom_msg'] ) echo 'checked="checked"';?> /> <?php _e('Enable', 'piereg');?></label></td>
                       	</tr>
                   		</tbody>
                    </table>
                    <div id="enabled_msg">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                       		<th scope="row"><label for="from"><?php _e('From Email', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_from" id="from" style="width:250px;" value="<?php echo $piereg['from'];?>" /></td>
                         </tr>
                         <tr valign="top">
                            <th scope="row"><label for="fromname"><?php _e('From Name', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_fromname" id="fromname" style="width:250px;" value="<?php echo $piereg['fromname'];?>" /></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label for="subject"><?php _e('Subject', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_subject" id="subject" style="width:350px;" value="<?php echo $piereg['subject'];?>" /></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label for="msg"><?php _e('User Message', 'piereg');?></label></th>
                        	<td>
                            <?php
							if( $piereg['firstname'] ) $custom_keys .= ' &nbsp; %firstname%';
							if( $piereg['lastname'] ) $custom_keys .= ' &nbsp; %lastname%';
							if( $piereg['website'] ) $custom_keys .= ' &nbsp; %website%';
							if( $piereg['aim'] ) $custom_keys .= ' &nbsp; %aim%';
							if( $piereg['yahoo'] ) $custom_keys .= ' &nbsp; %yahoo%';
							if( $piereg['jabber'] ) $custom_keys .= ' &nbsp; %jabber%';
							if( $piereg['about'] ) $custom_keys .= ' &nbsp; %about%';
							if( $piereg['code'] ) $custom_keys .= ' &nbsp; %invitecode%';

							if( is_array($piereg_custom) ){
								foreach( $piereg_custom as $k=>$v ){
									$meta = $this->Label_ID($v['label']);
									$value = get_usermeta( $user_id, $meta );
									$custom_keys .= ' &nbsp; %'.$meta.'%';
								}
							}
							?>
                            <p><strong><?php _e('Replacement Keys', 'piereg');?>:</strong> &nbsp; %user_login%  &nbsp; %user_pass% &nbsp; %user_email% &nbsp; %blogname% &nbsp; %siteurl% <?php echo $custom_keys; ?>&nbsp; %user_ip% &nbsp; %user_ref% &nbsp; %user_host% &nbsp; %user_agent% </p>
                            <textarea name="piereg_msg" id="msg" rows="10" cols="25" style="width:80%;height:300px;"><?php echo $piereg['msg'];?></textarea><br /><label><input type="checkbox" name="piereg_html" id="html" value="1" <?php if( $piereg['html'] ) echo 'checked="checked"';?> /> <?php _e('Send as HTML', 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_user_nl2br" id="html" value="1" <?php if( $piereg['user_nl2br'] ) echo 'checked="checked"';?> /> <?php _e('Convert new lines to &lt;br/> tags (HTML only)' , 'piereg');?></label></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label for="login_redirect"><?php _e('Login Redirect URL', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_login_redirect" id="login_redirect" style="width:250px;" value="<?php echo $piereg['login_redirect'];?>" /> <small><?php _e('This will redirect the users login after registration.', 'piereg');?></small></td>
                       	</tr>
                        </tbody>
                     </table>
                     </div>
                     
                     <h3><?php _e('Customize Admin Notification Email', 'piereg');?></h3>
                    <table class="form-table"> 
                        <tbody>
                        <tr valign="top">
                       		<th scope="row"><label for="disable_admin"><?php _e('Admin Email Notification', 'piereg');?></label></th>
                        	<td><label><input type="checkbox" name="piereg_disable_admin" id="disable_admin" value="1" <?php if( $piereg['disable_admin'] ) echo 'checked="checked"';?> /> <?php _e('Disable', 'piereg');?></label></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label><?php _e('Custom Admin Email Notification', 'piereg');?></label></th>
                        	<td><label><input type="checkbox" name="piereg_custom_adminmsg" id="custom_adminmsg" value="1" <?php if( $piereg['custom_adminmsg'] ) echo 'checked="checked"';?> /> <?php _e('Enable', 'piereg');?></label></td>
                       	</tr>
                   		</tbody>
                    </table>
                    <div id="enabled_adminmsg">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                       		<th scope="row"><label for="adminfrom"><?php _e('From Email', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_adminfrom" id="adminfrom" style="width:250px;" value="<?php echo $piereg['adminfrom'];?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="adminfromname"><?php _e('From Name', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_adminfromname" id="adminfromname" style="width:250px;" value="<?php echo $piereg['adminfromname'];?>" /></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label for="adminsubject"><?php _e('Subject', 'piereg');?></label></th>
                        	<td><input type="text" name="piereg_adminsubject" id="adminsubject" style="width:350px;" value="<?php echo $piereg['adminsubject'];?>" /></td>
                       	</tr>
                        <tr valign="top">
                       		<th scope="row"><label for="adminmsg"><?php _e('Admin Message', 'piereg');?></label></th>
                        	<td>
                            <p><strong><?php _e('Replacement Keys', 'piereg');?>:</strong> &nbsp; %user_login%  &nbsp; %user_email% &nbsp; %blogname% &nbsp; %siteurl%  <?php echo $custom_keys; ?>&nbsp; %user_ip% &nbsp; %user_ref% &nbsp; %user_host% &nbsp; %user_agent%</p><textarea name="piereg_adminmsg" id="adminmsg" rows="10" cols="25" style="width:80%;height:300px;"><?php echo $piereg['adminmsg'];?></textarea><br /><label><input type="checkbox" name="piereg_adminhtml" id="adminhtml" value="1" <?php if( $piereg['adminhtml'] ) echo 'checked="checked"';?> /> <?php _e('Send as HTML' , 'piereg');?></label> &nbsp; <label><input type="checkbox" name="piereg_admin_nl2br" id="html" value="1" <?php if( $piereg['admin_nl2br'] ) echo 'checked="checked"';?> /> <?php _e('Convert new lines to &lt;br/> tags (HTML only)' , 'piereg');?></label></td>
                       	</tr>
                        </tbody>
                     </table>
                     </div><br />
                     <h3><?php _e('Custom CSS for Register & Login Pages', 'piereg');?></h3>
                     <p><?php _e('CSS Rule Example:', 'piereg');?>
<code>
#user_login{
	font-size: 20px;	
	width: 97%;
	padding: 3px;
	margin-right: 6px;
}</code>
                     <table class="form-table">
                        <tbody>
                        <tr valign="top">
                       		<th scope="row"><label for="register_css"><?php _e('Custom Register CSS', 'piereg');?></label></th>
                        	<td><textarea name="piereg_register_css" id="register_css" rows="20" cols="40" style="width:80%; height:200px;"><?php echo $piereg['register_css'];?></textarea></td>
                        </tr>
                        <tr valign="top">
                       		<th scope="row"><label for="login_css"><?php _e('Custom Login CSS', 'piereg');?></label></th>
                        	<td><textarea name="piereg_login_css" id="login_css" rows="20" cols="40" style="width:80%; height:200px;"><?php echo $piereg['login_css'];?></textarea></td>
                        </tr>
                        </tbody>
                     </table>
                     
                    <p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" />
                    <input name="action" value="pie_reg_update" type="hidden" />
                </form>
              	<?php $this->donate();?>
            </div>
           <?php
		}
		
		# Check Required Fields
		function RegErrors($errors){	
			$piereg = get_option( 'pie_register' );
			$piereg_custom = get_option( 'pie_register_custom' );
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			
			if( $piereg['email_exists'] ){
				if ( $errors->errors['email_exists'] ){
					unset($errors->errors['email_exists']);
				}
			}
			
			if( $piereg['firstname'] && in_array('firstname', $piereg['profile_req']) ){
				if(empty($_POST['firstname']) || $_POST['firstname'] == ''){
					$errors->add('empty_firstname', __('<strong>ERROR</strong>: Please enter your First Name.', 'piereg'));
				}
			}
			if( $piereg['lastname'] && in_array('lastname', $piereg['profile_req']) ){
				if(empty($_POST['lastname']) || $_POST['lastname'] == ''){
					$errors->add('empty_lastname', __('<strong>ERROR</strong>: Please enter your Last Name.', 'piereg'));
				}
			}
			if( $piereg['website'] && in_array('website', $piereg['profile_req']) ){
				if(empty($_POST['website']) || $_POST['website'] == ''){
					$errors->add('empty_website', __('<strong>ERROR</strong>: Please enter your Website URL.', 'piereg'));
				}
			}
			if( $piereg['aim'] && in_array('aim', $piereg['profile_req']) ){
				if(empty($_POST['aim']) || $_POST['aim'] == ''){
					$errors->add('empty_aim', __('<strong>ERROR</strong>: Please enter your AIM username.', 'piereg'));
				}
			}
			if( $piereg['yahoo'] && in_array('yahoo', $piereg['profile_req']) ){
				if(empty($_POST['yahoo']) || $_POST['yahoo'] == ''){
					$errors->add('empty_yahoo', __('<strong>ERROR</strong>: Please enter your Yahoo IM username.', 'piereg'));
				}
			}
			if( $piereg['jabber'] && in_array('jabber', $piereg['profile_req']) ){
				if(empty($_POST['jabber']) || $_POST['jabber'] == ''){
					$errors->add('empty_jabber', __('<strong>ERROR</strong>: Please enter your Jabber / Google Talk username.', 'piereg'));
				}
			}
			if( $piereg['phone'] && in_array('phone', $piereg['profile_req']) ){
				if(empty($_POST['phone']) || $_POST['phone'] == ''){
					$errors->add('empty_phone', __('<strong>ERROR</strong>: Please enter your Phone / Mobile number.', 'piereg'));
				}else if(preg_match('/\D/ism',$_POST['phone']) || (strlen($_POST['phone'])>13) || (strlen($_POST['phone'])<7)){
					$errors->add('Wrong_Phone', __('<strong>ERROR</strong>: Please enter your Phone / Mobile number in correct formart No Alphabet No more 13 Variables.', 'piereg'));
				}
			}
			if( $piereg['about'] && in_array('about', $piereg['profile_req']) ){
				if(empty($_POST['about']) || $_POST['about'] == ''){
					$errors->add('empty_about', __('<strong>ERROR</strong>: Please enter some information About Yourself.', 'piereg'));
				}
			}
			if (!empty($piereg_custom)) {
				foreach( $piereg_custom as $k=>$v ){
					if( $v['required'] && $v['reg'] ){
						$id = $this->Label_ID($v['label']);
						if(empty($_POST[$id]) || $_POST[$id] == ''){
							$errors->add('empty_' . $id, __('<strong>ERROR</strong>: Please enter your ' . $v['label'] . '.', 'piereg'));
						}
					}
				}
			}
					
			if ( $piereg['password'] ){
				if(empty($_POST['pass1']) || $_POST['pass1'] == '' || empty($_POST['pass2']) || $_POST['pass2'] == ''){
					$errors->add('empty_password', __('<strong>ERROR</strong>: Please enter a Password.', 'piereg'));
				}elseif($_POST['pass1'] !== $_POST['pass2']){
					$errors->add('password_mismatch', __('<strong>ERROR</strong>: Your Password does not match.', 'piereg'));
				}elseif(strlen($_POST['pass1'])<6){
					$errors->add('password_length', __('<strong>ERROR</strong>: Your Password must be at least 6 characters in length.', 'piereg'));
				}else{
					$_POST['user_pw'] = $_POST['pass1'];
				}
			}
			if ( $piereg['code'] && $piereg['code_req'] ){
				if(empty($_POST['regcode']) || $_POST['regcode'] == ''){
					$errors->add('empty_regcode', __('<strong>ERROR</strong>: Please enter the Invitation Code.', 'piereg'));
				}elseif( !in_array(strtolower($_POST['regcode']), $piereg['codepass']) ){
					$errors->add('regcode_mismatch', __('<strong>ERROR</strong>: Your Invitation Code is incorrect.', 'piereg'));
				}
			}
			
			if ( $piereg['captcha'] == 1 ){
				
				$key = $_SESSION['1k2j48djh'];
				$number = md5($_POST['captcha']);
				if($number!=$key){
				  	$errors->add('captcha_mismatch', __("<strong>ERROR</strong>: Image Validation does not match.", 'piereg'));
					unset($_SESSION['1k2j48djh']);
				}	
			} else if ( $piereg['captcha'] == 2){
				require_once('recaptchalib.php');
				$privatekey = $piereg['reCAP_private_key'];
				$resp = rp_recaptcha_check_answer ($privatekey,

												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
				
				if (!$resp->is_valid) {
				  $errors->add('recaptcha_mismatch', __("<strong>ERROR:</strong> The reCAPTCHA wasn't entered correctly.", 'piereg'));
				  //$errors->add('recaptcha_error', "(" . __("reCAPTCHA said: ", 'piereg') . $resp->error . ")");
				}
			}
			
			if ( $piereg['disclaimer'] ){
				if(!$_POST['disclaimer']){
				  	$errors->add('disclaimer', __('<strong>ERROR</strong>: Please accept the ', 'piereg') . stripslashes( $piereg['disclaimer_title'] ) . '.');
				}	
			}
			if ( $piereg['license'] ){
				if(!$_POST['license']){
				  	$errors->add('license', __('<strong>ERROR</strong>: Please accept the ', 'piereg') . stripslashes( $piereg['license_title'] ) . '.');
				}	
			}
			if ( $piereg['privacy'] ){
				if(!$_POST['privacy']){
				  	$errors->add('privacy', __('<strong>ERROR</strong>: Please accept the ', 'piereg') . stripslashes( $piereg['privacy_title'] ) . '.');
				}	
			}
			/*session_start();*/
			$_SESSION['secure_id']=$_POST['user_login'];
			session_register($_SESSION['secure_id']);
			return $errors;
		}	
		
		function RegMsg($errors){
			$piereg = get_option( 'pie_register' );
			
			/*session_start();*/
			if ( $errors->errors['registered'] ){
				unset($errors->errors['registered']);
			}
			if	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )	$errors->add('registeredit', __('Please check your e-mail and click the verification link to activate your account and complete your registration.'), 'message');
			return $errors;
		}
		
		# Add Fields to Register Form
		function RegForm(){
			$piereg = get_option( 'pie_register' );
			$piereg_custom = get_option( 'pie_register_custom' );
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			
			if ( $piereg['firstname'] ){	
				if( isset( $_GET['firstname'] ) ) $_POST['firstname'] = $_GET['firstname'];
			?>
   		<div style="clear:both"><label><?php _e('First Name:', 'piereg');?> <p>
		<input autocomplete="off" name="firstname" id="firstname" size="25" value="<?php echo $_POST['firstname'];?>" type="text" tabindex="30" /></p></label>
        </div>
            <?php
			}
			if ( $piereg['lastname'] ){
				if( isset( $_GET['lastname'] ) ) $_POST['lastname'] = $_GET['lastname'];
			?>
   		<div style="clear:both"><label><?php _e('Last Name:', 'piereg');?> <p>
		<input autocomplete="off" name="lastname" id="lastname" size="25" value="<?php echo $_POST['lastname'];?>" type="text" tabindex="31" /></p></label></div>
            <?php
			}
			if ( $piereg['website'] ){
				if( isset( $_GET['website'] ) ) $_POST['website'] = $_GET['website'];
			?>
   		<div style="clear:both"><label><?php _e('Website:', 'piereg');?> <p>
		<input autocomplete="off" name="website" id="website" size="25" value="<?php echo $_POST['website'];?>" type="text" tabindex="32" /></p></label></div>
            <?php
			}
			if ( $piereg['aim'] ){
				if( isset( $_GET['aim'] ) ) $_POST['aim'] = $_GET['aim'];
			?>
   		<div style="clear:both"><label><?php _e('AIM:', 'piereg');?> <p>
		<input autocomplete="off" name="aim" id="aim" size="25" value="<?php echo $_POST['aim'];?>" type="text" tabindex="32" /></p></label></div>
            <?php
			}
			if ( $piereg['yahoo'] ){
				if( isset( $_GET['yahoo'] ) ) $_POST['yahoo'] = $_GET['yahoo'];
			?>
   		<div style="clear:both"><label><?php _e('Yahoo IM:', 'piereg');?> <p>
		<input autocomplete="off" name="yahoo" id="yahoo" size="25" value="<?php echo $_POST['yahoo'];?>" type="text" tabindex="33" /></p></label></div>
            <?php
			}
			if ( $piereg['jabber'] ){
				if( isset( $_GET['jabber'] ) ) $_POST['jabber'] = $_GET['jabber'];
			?>
   		<div style="clear:both"><label><?php _e('Jabber / Google Talk:', 'piereg');?> <p>
		<input autocomplete="off" name="jabber" id="jabber" size="25" value="<?php echo $_POST['jabber'];?>" type="text" tabindex="34" /></p></label></div>
            <?php
			}
			if ( $piereg['phone'] ){
				if( isset( $_GET['phone'] ) ) $_POST['phone'] = $_GET['phone'];
			?>
   		<div style="clear:both"><label><?php _e('Phone # / Mobile #:', 'piereg');?> <p>
		<input autocomplete="off" name="phone" id="phone" size="25" value="<?php echo $_POST['phone'];?>" type="text" tabindex="34" /></p></label></div>
            <?php
			}
			if ( $piereg['about'] ){
				if( isset( $_GET['about'] ) ) $_POST['about'] = $_GET['about'];
			?>
   		<div style="clear:both"><label><?php _e('About Yourself:', 'piereg');?> <p>
		<textarea autocomplete="off" name="about" id="about" cols="25" rows="5" tabindex="35"><?php echo stripslashes($_POST['about']);?></textarea></p></label>
        <small><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.', 'piereg');?></small>
        </div>
            <?php
			}
			
			foreach( $piereg_custom as $k=>$v){
				if( $v['reg'] ){
				$id = $this->Label_ID($v['label']);
				if( isset( $_GET[$id] ) ) $_POST[$id] = $_GET[$id];
			 ?>
		
       
        <?php if( $v['fieldtype'] == 'text' ){ ?>
        <div style="clear:both"><label><?php echo $v['label'];?>: <p>
		<input autocomplete="off" class="custom_field" tabindex="36" name="<?php echo $id;?>" id="<?php echo $id;?>" size="25" value="<?php echo $_POST[$id];?>" type="text" /></p></label></div>
        
        <?php } else if( $v['fieldtype'] == 'date' ){ ?>
       <div style="clear:both"><label><?php echo $v['label'];?>: <p>
		<input autocomplete="off" class="custom_field date-pick" tabindex="36" name="<?php echo $id;?>" id="<?php echo $id;?>" size="25" value="<?php echo $_POST[$id];?>" type="text" /></p></label></div>
        
		<?php } else if( $v['fieldtype'] == 'select' ){ 
			$ops = explode(',',$v['extraoptions']);
				$options='';
			foreach( $ops as $op ){
				$options .= '<option value="'.$op.'" ';
				if( $_POST[$id] == $op ) $options .= 'selected="selected"';
				$options .= '>' . $op . '</option>';
			}
		?>
        <div style="clear:both"><label><?php echo $v['label'];?>: <p>
        <select class="custom_select" tabindex="36" name="<?php echo $id;?>" id="<?php echo $id;?>">
        	<?php echo $options;?>
        </select></p></label></div>
      
        <?php } else if( $v['fieldtype'] == 'checkbox' ){ 
				$ops = explode(',',$v['extraoptions']);
				$check='';
				foreach( $ops as $op ){
					$check .= '<label><input type="checkbox" class="custom_checkbox" tabindex="36" name="'.$id.'[]" id="'.$id.'" ';
					//if( in_array($op, $_POST[$id]) ) $check .= 'checked="checked" ';
					$check .= 'value="'.$op.'" /> '.$op.'</label> ';
				}
				?>
                <div style="clear:both"><label><?php echo $v['label'];?>:</label> <p><?php
				echo $check . '</p></div>';
			
			} else if( $v['fieldtype'] == 'radio' ){
				$ops = explode(',',$v['extraoptions']);
				$radio = '';
				foreach( $ops as $op ){
					$radio .= '<label><input type="radio" class="custom_radio" tabindex="36" name="'.$id.'" id="'.$id.'" ';
					//if( in_array($op, $_POST[$id]) ) $radio .= 'checked="checked" ';
					$radio .= 'value="'.$op.'" /> '.$op.'</label> ';
				}
				?>
               <div style="clear:both"><label><?php echo $v['label'];?>:</label> <p><?php
				echo $radio . '</p></div>';
				
			} else if( $v['fieldtype'] == 'textarea' ){ ?>
           <div style="clear:both"><label><?php echo $v['label'];?>: <p>
		<textarea tabindex="36" name="<?php echo $id;?>" cols="25" rows="5" id="<?php echo $id;?>" class="custom_textarea"><?php echo $_POST[$id];?></textarea></p></label></div>	
		
		<?php } else if( $v['fieldtype'] == 'hidden' ){ ?><p>
		<input class="custom_field" tabindex="36" name="<?php echo $id;?>" value="<?php echo $_POST[$id];?>" type="hidden" />  </p>        	
        <?php } ?>		
				
		<?php	}
        	}			
			
			
			if ( $piereg['password'] ){
			?>
        <div style="clear:both"><label><?php _e('Password:', 'piereg');?> <p>
		<input autocomplete="off" name="pass1" id="pass1" size="25" value="<?php echo $_POST['pass1'];?>" type="password" tabindex="40" /></p></label></div>
       <div> <label><?php _e('Confirm Password:', 'piereg');?> <p>
        <input autocomplete="off" name="pass2" id="pass2" size="25" value="<?php echo $_POST['pass2'];?>" type="password" tabindex="41" /></p></label></div>
        <?php if( $piereg['password_meter'] ){ ?>
       <div> <span id="pass-strength-result"><?php echo $piereg['short'];?></span>
		<small><?php _e('Hint: Use upper and lower case characters, numbers and symbols like !"?$%^&amp;( in your password.', 'piereg'); ?> </small></div><?php } ?>
            <?php
			}
			if ( $piereg['code'] ){
				if( isset( $_GET['regcode'] ) ) $_POST['regcode'] = $_GET['regcode'];
			?>
        <div style="clear:both"><label><?php _e('Invitation Code:', 'piereg');?> <p>
		<input name="regcode" id="regcode" size="25" value="<?php echo $_POST['regcode'];?>" type="text" tabindex="45" /></p></label>
        <?php if ($piereg['code_req']) {?>
		<p><small><?php _e('This website is currently closed to public registrations.  You will need an invitation code to register.', 'piereg');?></small></p>
        <?php }else{ ?>
        <p><small><?php _e('Have an invitation code? Enter it here. (This is not required)', 'piereg');?></small></p>
        <?php } ?>
        </div>
            <?php
			}
			
			if ( $piereg['disclaimer'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['disclaimer_title'] );?> <p>
        <span id="disclaimer"><?php echo stripslashes($piereg['disclaimer_content']); ?></span>
		<input name="disclaimer" value="1" type="checkbox" tabindex="50"<?php if($_POST['disclaimer']) echo ' checked="checked"';?> /> <?php echo $piereg['disclaimer_agree'];?></p></label></div>
            <?php
			}
			if ( $piereg['license'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['license_title'] );?> <p>
        <span id="license"><?php echo stripslashes($piereg['license_content']); ?></span>
		<input name="license" value="1" type="checkbox" tabindex="50"<?php if($_POST['license']) echo ' checked="checked"';?> /> <?php echo $piereg['license_agree'];?></p></label></div>
            <?php
			}
			if ( $piereg['privacy'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['privacy_title'] );?> <p>
        <span id="privacy"><?php echo stripslashes($piereg['privacy_content']); ?></span>
		<input name="privacy" value="1" type="checkbox" tabindex="50"<?php if($_POST['privacy']) echo ' checked="checked"';?> /> <?php echo $piereg['privacy_agree'];?></p></label></div>
            <?php
			}
			
			if ( $piereg['captcha'] == 1 ){
				$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
				$_SESSION['OK'] = 1;
				if( !isset( $_SESSION['OK'] ) )
					session_start(); 
				?>
               <div style="clear:both"><label><?php _e('Validation Image:', 'piereg');?> <p>
                <img src="<?php echo $plugin_url;?>captcha.php" id="captchaimg" alt="" />
                <input type="text" name="captcha" id="captcha" size="25" value="" tabindex="55" /></p></label>
                <small><?php _e('Enter the text from the image.', 'piereg');?></small></div>
               
                <?php
				
			} else if ( $piereg['captcha'] == 2 && $piereg['reCAP_public_key'] && $piereg['reCAP_private_key'] ){
				require_once('recaptchalib.php');
				$publickey = $piereg['reCAP_public_key'];
				echo '<div id="reCAPTCHA">';
				echo rp_recaptcha_get_html($publickey);
				echo '</div>';
			}
			
			if ($piereg['paypal_option']) {
			?>
			
			<div class="submit" style="margin-top:10px;padding-top:10px;">
<input class="button-primary" id="wp-submit" type="submit" tabindex="100" value="Continue" name="wp-submit"/>
</div>
<style>
p.submit{
display:none;
}
</style>
<?php
}
?>

			<?php
		}
		
		function Label_ID($label){
			$id = str_replace(' ', '_', $label);
			$id = strtolower($id);
			$id = sanitize_user($id, true);
			return $id;
		}
		# Add Javascript & CSS needed
		function PassHead(){
			$piereg = get_option( 'pie_register' );
			if( isset( $_GET['user_login'] ) ) $user_login = $_GET['user_login'];
			if( isset( $_GET['user_email'] ) ) $user_email = $_GET['user_email'];
			if ( $piereg['password'] ){
?>
<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.js?ver=1.2.3'></script>

<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-admin/js/common.js?ver=20080318'></script>
<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.color.js?ver=2.0-4561'></script>
<script type='text/javascript'>
/* <![CDATA[ */
	pwsL10n = {
		short: "<?php echo $piereg['short'];?>",
		bad: "<?php echo $piereg['bad'];?>",
		good: "<?php echo $piereg['good'];?>",
		strong: "<?php echo $piereg['strong'];?>"
	}
/* ]]> */
</script>
<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-admin/js/password-strength-meter.dev.js?ver=20070405'></script>
<script type="text/javascript">
	function check_pass_strength ( ) {

		var pass = jQuery('#pass1').val();
		var pass2 = jQuery('#pass2').val();
		var user = jQuery('#user_login').val();

		// get the result as an object, i'm tired of typing it
		var res = jQuery('#pass-strength-result');

		var strength = passwordStrength(pass, user, pass2);

		jQuery(res).removeClass('short bad good strong');
		
		 if ( strength == 1 ) {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}
		else if ( strength == 2 ) {
			jQuery(res).addClass('bad');
			jQuery(res).html( pwsL10n.bad );
		}
		else if ( strength == 3 ) {
			jQuery(res).addClass('good');
			jQuery(res).html( pwsL10n.good );
		}
		else if ( strength == 4 ) {
			jQuery(res).addClass('strong');
			jQuery(res).html( pwsL10n.strong );
		}
		else {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}

	}
	

	jQuery(function($) { 
		$('#pass1').keyup( check_pass_strength ) 
		$('.color-palette').click(function(){$(this).siblings('input[name=admin_color]').attr('checked', 'checked')});
	} );
	
	jQuery(document).ready( function() {
		jQuery('#pass1,#pass2').attr('autocomplete','off');
		jQuery('#user_login').val('<?php echo $user_login; ?>');
		jQuery('#user_email').val('<?php echo $user_email; ?>');
    });
</script>
<?php } ?>

<?php $plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/'; ?>
<!-- required plugins -->
<script type="text/javascript" src="<?php echo $plugin_url;?>datepicker/date.js"></script>
<!--[if IE]><script type="text/javascript" src="<?php echo $plugin_url;?>datepicker/jquery.bgiframe.js"></script><![endif]-->

<!-- jquery.datePicker.js -->
<script type="text/javascript" src="<?php echo $plugin_url;?>datepicker/jquery.datePicker.js"></script>
<link href="<?php echo $plugin_url;?>datepicker/datePicker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
jQuery.dpText = {
	TEXT_PREV_YEAR		:	'<?php _e('Previous year','piereg');?>',
	TEXT_PREV_MONTH		:	'<?php _e('Previous month','piereg');?>',
	TEXT_NEXT_YEAR		:	'<?php _e('Next year','piereg');?>',
	TEXT_NEXT_MONTH		:	'<?php _e('Next Month','piereg');?>',
	TEXT_CLOSE			:	'<?php _e('Close','piereg');?>',
	TEXT_CHOOSE_DATE	:	'<?php _e('Choose Date','piereg');?>'
}

Date.dayNames = ['<?php _e('Monday','piereg');?>', '<?php _e('Tuesday','piereg');?>', '<?php _e('Wednesday','piereg');?>', '<?php _e('Thursday','piereg');?>', '<?php _e('Friday','piereg');?>', '<?php _e('Saturday','piereg');?>', '<?php _e('Sunday','piereg');?>'];
Date.abbrDayNames = ['<?php _e('Mon','piereg');?>', '<?php _e('Tue','piereg');?>', '<?php _e('Wed','piereg');?>', '<?php _e('Thu','piereg');?>', '<?php _e('Fri','piereg');?>', '<?php _e('Sat','piereg');?>', '<?php _e('Sun','piereg');?>'];
Date.monthNames = ['<?php _e('January','piereg');?>', '<?php _e('February','piereg');?>', '<?php _e('March','piereg');?>', '<?php _e('April','piereg');?>', '<?php _e('May','piereg');?>', '<?php _e('June','piereg');?>', '<?php _e('July','piereg');?>', '<?php _e('August','piereg');?>', '<?php _e('September','piereg');?>', '<?php _e('October','piereg');?>', '<?php _e('November','piereg');?>', '<?php _e('December','piereg');?>'];
Date.abbrMonthNames = ['<?php _e('Jan','piereg');?>', '<?php _e('Feb','piereg');?>', '<?php _e('Mar','piereg');?>', '<?php _e('Apr','piereg');?>', '<?php _e('May','piereg');?>', '<?php _e('Jun','piereg');?>', '<?php _e('Jul','piereg');?>', '<?php _e('Aug','piereg');?>', '<?php _e('Sep','piereg');?>', '<?php _e('Oct','piereg');?>', '<?php _e('Nov','piereg');?>', '<?php _e('Dec','piereg');?>'];
Date.firstDayOfWeek = <?php echo $piereg['firstday'];?>; 
Date.format = '<?php echo $piereg['dateformat'];?>'; 
jQuery(function() { 
	jQuery('.date-pick').datePicker({
		clickInput:true,
		startDate:'<?php echo $piereg['startdate'];?>',
		year:<?php echo $piereg['calyear'];?>,
		month:<?php if( $piereg['calmonth'] != 'cur' ) echo $piereg['calmonth']; else echo date('n')-1;?>
	}) 
});
</script>
<style type="text/css">
a.dp-choose-date { float: left; width: 16px; height: 16px; padding: 0; margin: 5px 3px 0; display: block; text-indent: -2000px; overflow: hidden; background: url(<?php echo $plugin_url;?>datepicker/calendar.png) no-repeat; } a.dp-choose-date.dp-disabled { background-position: 0 -20px; cursor: default; } /* makes the input field shorter once the date picker code * has run (to allow space for the calendar icon */ input.dp-applied { width: 140px; float: left; }
																																																																																				
#phone, #pass1, #pass2, #regcode, #captcha, #firstname, #lastname, #website, #aim, #yahoo, #jabber, #about, .custom_field{
	font-size: 20px;	
	width: 97%;
	padding: 3px;
	margin-right: 6px;
}
.custom_select, .custom_textarea{	
	width: 97%;
	padding: 3px;
	margin-right: 6px;
}
#about, .custom_textarea{
	height: 60px;
}
#disclaimer, #license, #privacy{
	display:block;
	width: 97%;
	padding: 3px;
	background-color:#fff;
	border:solid 1px #A7A6AA;
	font-weight:normal;
}
<?php 
$piereg_custom = get_option( 'pie_register_custom' );
$custom = array();
if (!empty($piereg_custom)) {
	foreach( $piereg_custom as $k=>$v ){
		if( $v['required'] && $v['reg'] ){
			$custom[] = ', #' . $this->Label_ID($v['label']);		
		}		
	}
}

if( $piereg['profile_req'][0] ) $profile_req = ', #' . implode(', #', $piereg['profile_req']);
if( $custom[0] )$profile_req .= implode('', $custom);
?>
#phone, #user_login, #user_email, #pass1, #pass2 <?php echo $profile_req;?>{

	<?php echo $piereg['require_style'];?>
	
}
<?php if( strlen($piereg['disclaimer_content']) > 525){ ?>
#disclaimer{
	height: 200px;
	overflow:scroll;
}
<?php } ?>
<?php  if( strlen($piereg['license_content']) > 525){ ?>
#license{
	height: 200px;
	overflow:scroll;
}
<?php } ?>
<?php if( strlen($piereg['privacy_content']) > 525){ ?>
#privacy{
	height: 200px;
	overflow:scroll;
}
<?php } ?>
#captcha {
	width: 156px;
}
#captchaimg{
	float:left;
}
#reg_passmail{
	display:none;
}
small{
	font-weight:normal;
}
#pass-strength-result{
	padding-top: 3px;
	padding-right: 5px;
	padding-bottom: 3px;
	padding-left: 5px;
	margin-top: 3px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	display:block;
}
#reCAPTCHA{
	position:relative;
	margin-left:-32px;
}


</style>
		<?php
		}
		
		function HideLogin(){
			$piereg = get_option( 'pie_register' );
			if($piereg['paypal_option'] && $_GET['checkemail'] == 'registered' || $_GET['piereg_verification'] && $piereg['paypal_option'] ||($piereg['admin_verify'] || $piereg['email_verify'] ) && $_GET['checkemail'] == 'registered' ){
			
			?>

<style type="text/css">
label, #user_login, #user_pass, .forgetmenot, #wp-submit, .message {
	display:none;
}
</style>
		<?php
			}
			else if($_GET['piereg_verification'] && $piereg['paypal_option']){
			?>
<style type="text/css">
label, #user_login, #user_pass, .forgetmenot, #wp-submit, .message {
	display:none;
}
</style>
		
		


		<?php
			}
		}
		
		function LogoHead(){
			$piereg = get_option( 'pie_register' );
			if( $piereg['logo'] ){ 
				$logo = str_replace( trailingslashit( get_option('siteurl') ), ABSPATH, $piereg['logo'] );
				list($width, $height, $type, $attr) = getimagesize($logo);
				?>
                <?php if( $_GET['action'] != 'register' ) : ?>
                <script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.js?ver=1.2.3'></script>
                <?php endif; ?>
<script type="text/javascript">
	jQuery(document).ready( function() {
		jQuery('#login h1 a').attr('href', '<?php echo get_option('home'); ?>');
		jQuery('#login h1 a').attr('title', '<?php echo get_option('blogname') . ' - ' . get_option('blogdescription'); ?>');
    });
</script>
<style type="text/css">
#login h1 a {
	background-image: url(<?php echo $piereg['logo'];?>);
	background-position:center top;
	width: <?php echo $width; ?>px;
	min-width:292px;
	height: <?php echo $height; ?>px;
}
#login{
margin:0 auto;
width:330px;
}
#login p.register{
margin-right:-40px;
}
#login h1{
margin-left:10px;
}
form{
width:100%;
}
<?php if( $piereg['register_css'] &&  $_GET['action'] == 'register') echo $piereg['register_css']; 
else if( $piereg['login_css'] ) echo $piereg['login_css']; ?>
</style>
		<?php } 
		
		}
		
		function Add2Profile() {
			global $user_ID;
			get_currentuserinfo();
			if( $_GET['user_id'] ) $user_ID = $_GET['user_id'];
			$piereg_custom = get_option( 'pie_register_custom' );
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			if( count($piereg_custom) > 0){
				$top = '<h3>' . __('Additional Information', 'piereg') . '</h3><table class="form-table"><tbody>';
				$bottom = '</tbody></table>';
			}
			echo $top;
			if (!empty($piereg_custom)) {
				foreach( $piereg_custom as $k=>$v ){
					if( $v['profile'] ){
						$id = $this->Label_ID($v['label']);
						$value = get_usermeta( $user_ID, $id );
						$extraops = explode(',', $v['extraoptions']);
						switch( $v['fieldtype'] ){
							case "text" :
								$outfield = '<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '"  />';
								break;
							case "hidden" :
								$outfield = '<input type="text" disabled="disabled" name="' . $id . '" id="' . $id . '" value="' . $value . '"  />';
								break;
							case "select" :
								$outfield = '<select name="' . $id . '" id="' . $id . '">';
								foreach( $extraops as $op ){
									$outfield .= '<option value="' . $op . '"';
									if( $value == $op ) $outfield .= ' selected="selected"';
									$outfield .= '>' . $op . '</option>';
								}
								$outfield .= '</select>';
								break;
							case "textarea" :
								$outfield = '<textarea name="' . $id . '" id="' . $id . '" cols="25" rows="10">' . stripslashes($value) . '</textarea>';
								break;
							case "checkbox" :
								$outfield = '';
								$valarr = explode(', ', $value);
								foreach( $extraops as $op ){
									$outfield .= '<label><input type="checkbox" name="' . $id . '[]" value="' . $op . '"';
									if( in_array($op, $valarr) ) $outfield .= ' checked="checked"';
									$outfield .= ' /> ' . $op . '</label> &nbsp; ';
								}
								break;
							case "radio" :
								$outfield = '';
								foreach( $extraops as $op ){
									$outfield .= '<label><input type="radio" name="' . $id . '" value="' . $op . '"';
									if( $value == $op ) $outfield .= ' checked="checked"';
									$outfield .= ' /> ' . $op . '</label> &nbsp; ';
								}
								break;
						}
						?>		
						<tr>
							<th><label for="<?php echo $id;?>"><?php echo $v['label'];?>:</label></th>
							<td><?php echo $outfield; ?></td>
						</tr>      
					<?php 
					
					}		
				}
			}
			echo $bottom;
		}
		function SaveProfile(){
			global $wpdb, $user_ID;
			get_currentuserinfo();
			if( $_GET['user_id'] ) $user_ID = $_GET['user_id'];
			$piereg_custom = get_option( 'pie_register_custom' );
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			if (!empty($piereg_custom)) {
				foreach( $piereg_custom as $k=>$v ){
					if( $v['profile'] ){
						$key = $this->Label_ID($v['label']);

						if( is_array($_POST[$key]) ) $_POST[$key] = implode(', ', $_POST[$key]);
						$value = $wpdb->prepare($_POST[$key]);
						update_usermeta($user_ID ,$key ,$value);
					}
				}
			}
		}
		function RanPass($len=7) {
			$chars = "0123456789abcdefghijkl0123456789mnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQ0123456789RSTUVWXYZ0123456789";
			srand((double)microtime()*1000000);
			$i = 0;		
			$pass = '' ;		
			while ($i <= $len) {
				$num = rand() % 33;
				$tmp = substr($chars, $num, 1);	
				$pass = $pass . $tmp;		
				$i++;
			}
			return $pass;
		}
		
		function ValidateUser(){
			global $wpdb;
			$piereg = get_option( 'pie_register' );
			if( $piereg['admin_verify'] && isset( $_GET['checkemail'] ) ){
				
				echo '<p style="text-align:center;margin-bottom:10px;">' . __('Your account will be reviewed by an administrator and you will be notified when it is activated.', 'piereg') . '</p>';
			}else if( $piereg['email_verify'] && isset( $_GET['checkemail'] ) ){
					
				echo '<p style="text-align:center;margin-bottom:10px;">' . __('Please activate your account using the verification link sent to your email address.', 'piereg') . '</p>';
			}
			if( $piereg['email_verify'] && isset( $_GET['piereg_verification'] ) ){
				$piereg = get_option( 'pie_register' );
				$verify_key = $_GET['piereg_verification'];
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify' AND meta_value='$verify_key'");
				}else if($piereg['paypal_option'] && !$piereg['email_verify'] && isset( $_GET['checkemail'] ) ){
					
				echo '<p style="text-align:center;margin-bottom:10px;background-color:#FFFFE0;border:1px solid #E6DB55;padding:12px 0px;">' . __('Please click below to Continue and finish registration.', 'piereg') . '</p>';
				/*session_start();*/
				
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify_user' AND meta_value='".$_SESSION['secure_id']."'");
				
				$user_details_gender=$wpdb->get_row( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'gender' AND user_id='".$user_id."'");
				$user_details_username=$wpdb->get_row( "SELECT user_login FROM $wpdb->users WHERE ID='".$user_id."'");
				$user_name=$_SESSION['secure_id'];
				}
				if ( $user_id ) {
					if($piereg['paypal_option'] && !$piereg['email_verify']){
					$login = get_usermeta($user_id, 'email_verify_user');
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Hello <strong>%s</strong>, There is One-Time Subscription fee. Click to Complete your account registration.', 'piereg'), $login ) . '</p>';
					
					
					$paypalcode="<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&custom=".$user_id."&hosted_button_id=".$piereg['paypal_butt_id']."'><img src='https://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif' alt='PayPal - The safer, easier way to pay online' border='0' /></a>";
					
					}
					else if($piereg['paypal_option'] && $piereg['email_verify'] && isset( $_GET['piereg_verification'] )){
					$login = get_usermeta($user_id, 'email_verify_user');
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Thank you <strong>%s</strong>, your email has been verified. There is One-Time Subscription fee. Please Click below to Complete your account registration.', 'piereg'), $login ) . '</p>';
					$paypalcode="<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&custom=".$user_id."&hosted_button_id=".$piereg['paypal_butt_id']."'><img src='https://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif' alt='PayPal - The safer, easier way to pay online' border='0' /></a>";
					
					}else{
					$login = get_usermeta($user_id, 'email_verify_user');
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
					$user_email=get_usermeta($user_id, 'email_verify_email');
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$user_email' WHERE ID = '$user_id' " );
					delete_usermeta($user_id, 'email_verify_user');
					delete_usermeta($user_id, 'email_verify');
					delete_usermeta($user_id, 'email_verify_date');
					
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Thank you <strong>%s</strong>, for registration, Please login.', 'piereg'), $login ) . '</p>';
					}
					
					echo $msg;
					echo $paypalcode?$paypalcode:'';
				}
			
		}
		
		function ValidPUser(){
			global $wpdb;
			$piereg = get_option( 'pie_register' );
			if(isset($_GET['tx']) ){
			$req = 'cmd=_notify-synch';
$tx_token = $_GET['tx'];
$pptoken=$piereg['paypal_pdt'];
$auth_token = $pptoken;

$req .= "&tx=$tx_token&at=$auth_token";
foreach($_POST as $key=>$value) $req.=('&'.$key.'='.urlencode(stripslashes($value)));

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$url='www.paypal.com';
$fp = fsockopen ($url, 80, $errno, $errstr, 30);
			
	if (!$fp) {
	// HTTP ERROR
	
	}else {
	fputs ($fp, $header . $req);
	// read the body data
	$res = '';
	$headerdone = false;
	while (!feof($fp)) {
	$line = fgets ($fp, 1024);
	if (strcmp($line, "\r\n") == 0) {
	// read the header
	$headerdone = true;
	}
	else if ($headerdone)
	{
	// header has been read. now read the contents
	$res .= $line;
	}
	}
	fclose ($fp);
	// parse the data
	$lines = explode("\n", $res);
	$keyarray = array();
	
	if (strcmp ($lines[0], "SUCCESS") == 0) {
	for ($i=1; $i<count($lines);$i++){
	list($key,$val) = explode("=", $lines[$i]);
	$keyarray[urldecode($key)] = urldecode($val);
	}
	// check the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment
	$firstname = $keyarray['first_name'];
	$lastname = $keyarray['last_name'];
	$email= $keyarray['payer_email'];
	$itemname = $keyarray['item_name'];
	$amount = $keyarray['mc_gross'];
	$user_login=$keyarray['custom'];
	$user_id=trim($keyarray['custom']);
	$useremail=get_usermeta($user_id,'email_verify_email');
	
	
				/*$verify_key = $_GET['piereg_verification'];
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify' AND meta_value='$verify_key'");*/
				if ( $user_id ) {
					
					$loginE = get_usermeta($user_id, 'email_verify_user');
					$loginA = get_usermeta($user_id, 'admin_verify_user');
					if($loginE){
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$loginE' WHERE ID = '$user_id'" );
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
					delete_usermeta($user_id, 'email_verify_user');
					delete_usermeta($user_id, 'email_verify');
					delete_usermeta($user_id, 'email_verify_date');
					delete_usermeta($user_id, 'email_verify_user_pwd');
					delete_usermeta($user_id, 'email_verify_email');
					$msg = '<p class="message">' . sprintf(__('Thank you %s, your payment has been recieved. Please login to your account now!', 'piereg'), $login ) . '</p>';
					}else if($loginA){
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$loginA' WHERE ID = '$user_id'" );
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$uuseremail' WHERE ID = '$user_id'" );
					delete_usermeta($user_id, 'admin_verify_user');
					delete_usermeta($user_id, 'email_verify_user_pwd');
					delete_usermeta($user_id, 'email_verify_email');
					delete_usermeta($user_id, 'admin_verify');
					$msg = '<p class="message">' . sprintf(__('Thank you %s, your payment has been recieved, Please login to your account now!', 'piereg'), $login ) . '</p>';
					
					}
					
					
					
					echo $msg;
					
				}
				echo '<p style="text-align:center;">' . __('You have successfully Paid for your membership.', 'piereg') . '</p>';
	}
	else if (strcmp ($lines[0], "FAIL") == 0) {
	// log for manual investigation
	echo '<p style="text-align:center;">' . __('There\'s an error while verifying your payment.', 'piereg') . '</p>';
					
				
	}
	}
			
				
			}
			
				
			
		}
		
		function adminfrom(){
			$piereg = get_option( 'pie_register' );
			return $piereg['adminfrom'];
		}
		
		function userfrom(){
			$piereg = get_option( 'pie_register' );
			return $piereg['from'];
		}
		
		function adminfromname(){
			$piereg = get_option( 'pie_register' );
			return $piereg['adminfromname'];
		}
		
		function userfromname(){
			$piereg = get_option( 'pie_register' );
			return $piereg['fromname'];
		}
		
		function DeleteInvalidUsers(){
			global $wpdb;
			$piereg = get_option( 'pie_register' );
			$grace = $piereg['email_delete_grace'];
			$unverified = $wpdb->get_results( "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key='email_verify_date'" );
			$grace_date = date('Ymd', strtotime("-7 days"));
			if( $unverified ){
				foreach( $unverified as $bad ){
					if( $grace_date > $bad->meta_value ){
						include_once( ABSPATH . 'wp-admin/includes/user.php' );
						wp_delete_user($bad->user_id);
					}
				}
			}
		}
		
		function override_warning(){
			if( current_user_can(10) &&  $_GET['page'] == 'pie-register' )
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('You have another plugin installed that is conflicting with Pie Register.  This other plugin is overriding the user notification emails.  Please see <a href="http://pie-solutions.com/news/pie-register-conflicts/">Pie Register Conflicts</a> for more information.', 'piereg') . "</strong></p></div>";
		}
		
		function donate(){
			echo '<p><strong>' . __('If you find this plugin useful, please consider ', 'piereg') . '<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<input type="hidden" name="pay_to_email" value="info@pie-solutions.com">
<input type="hidden" name="status_url" value="http://www.pie-solutions.com/products/pie-register/donate">
<input type="hidden" name="language" value="EN">
USD <input type="text" size="5" name="amount" value="5">
<input type="hidden" name="currency" value="USD">
<input type="hidden" name="detail1_description" value="Donation to help support Pie-Solutions for providing free services.">
<input type="hidden" name="detail1_text" value="Donation for Pie-Register Wordpress plugin. Donate to help support Pie-Solutions to provide free services.">
<input type="image" src="http://www.moneybookers.com/images/banners/en/en_fasteasysecure.gif" value="Pay!" style="vertical-align:-15px;">
</form>'  . __('donating', 'piereg') . '</a></strong></p>';
		}
	}
}# END Class PieMemberRegister

# Run The Plugin!
if( class_exists('PieMemberRegister') ){
	$pie_register = new PieMemberRegister();
}
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
	$admin = trailingslashit( get_option('siteurl') ) . 'wp-admin/users.php';
	if( !is_array( $piereg_custom ) ) $piereg_custom = array();
	if( $piereg['password'] && $_POST['user_pw'] )
		$plaintext_pass = $wpdb->prepare($_POST['user_pw']);
	else if( $ref == $admin && $_POST['pass1'] == $_POST['pass2'] )
		$plaintext_pass = $wpdb->prepare($_POST['pass1']);
	else
		$plaintext_pass = $pie_register->RanPass(6);
		
	if( $piereg['firstname'] && $_POST['firstname'] )	
		update_usermeta( $user_id, 'first_name', $wpdb->prepare($_POST['firstname']));
	if( $piereg['lastname'] && $_POST['lastname'] )	
		update_usermeta( $user_id, 'last_name', $wpdb->prepare($_POST['lastname']));
	if( $piereg['website'] && $_POST['website'] )	
		update_usermeta( $user_id, 'user_url', $wpdb->prepare($_POST['website']));
	if( $piereg['aim'] && $_POST['aim'] )	
		update_usermeta( $user_id, 'aim', $wpdb->prepare($_POST['aim']));
	if( $piereg['yahoo'] && $_POST['yahoo'] )	
		update_usermeta( $user_id, 'yim', $wpdb->prepare($_POST['yahoo']));
	if( $piereg['jabber'] && $_POST['jabber'] )	
		update_usermeta( $user_id, 'jabber', $wpdb->prepare($_POST['jabber']));
	if( $piereg['phone'] && $_POST['phone'] )	
		update_usermeta( $user_id, 'phone', $wpdb->prepare($_POST['phone']));
	if( $piereg['about'] && $_POST['about'] )	
		update_usermeta( $user_id, 'description', $wpdb->prepare($_POST['about']));
	if( $piereg['code'] && $_POST['regcode'] )	
		update_usermeta( $user_id, 'invite_code', $wpdb->prepare($_POST['regcode']));
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
		$email_code = '?piereg_verification=' . $code;
		$prelink = __('Verification URL: ', 'piereg');
		$notice = __('Please use the link above to verify and activate your account', 'piereg') . "\r\n";
		$temp_id = 'unverified__' . $pie_register->RanPass(7);
	}else if( $ref != $admin){
		$code = $pie_register->RanPass(25);
		update_usermeta( $user_id, 'email_verify', $code );
		update_usermeta( $user_id, 'email_verify_user', $user->user_login );
		update_usermeta( $user_id, 'email_verify_user_pwd', $user->user_pass );
		update_usermeta( $user_id, 'email_verify_email', $user->user_email );
		$temp_id = 'unverified__' . $pie_register->RanPass(7);
		
	}
	if (!empty($piereg_custom)) {
		foreach( $piereg_custom as $k=>$v ){
			$id = $pie_register->Label_ID($v['label']);
			if( $v['reg'] && $_POST[$id] ){
				if( is_array( $_POST[$id] ) ) $_POST[$id] = implode(', ', $_POST[$id]);
				update_usermeta( $user_id, $id, $wpdb->prepare($_POST[$id]));
			}
		}
	}
	#-- END REGPLUS --#
	
	wp_set_password($plaintext_pass, $user_id);
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

	#-- REGPLUS --#
	if( !$piereg['custom_adminmsg'] && !$piereg['disable_admin'] ){
	#-- END REGPLUS --#
	
	$message  = sprintf(__('New user Register on your blog %s:', 'piereg'), get_option('blogname')) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n\r\n";
	$message .= sprintf(__('E-mail: %s', 'piereg'), $user_email) . "\r\n";

	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Register', 'piereg'), get_option('blogname')), $message);
	
	#-- REGPLUS --#
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
		if( $piereg['code'] ) $message = str_replace('%invitecode%', $_POST['regcode'], $message);
		
		if( !is_array( $piereg_custom ) ) $piereg_custom = array();
		if (!empty($piereg_custom)) {
			foreach( $piereg_custom as $k=>$v ){
				$meta = $pie_register->Label_ID($v['label']);
				$value = get_usermeta( $user_id, $meta );
				$message = str_replace('%'.$meta.'%', $value, $message);
			}
		}
		$siteurl = get_option('siteurl');
		$message = str_replace('%siteurl%', $siteurl, $message);
		
		if( $piereg['adminhtml'] && $piereg['admin_nl2br'] )
			$message = nl2br($message);
		
		wp_mail(get_option('admin_email'), $subject, $message, $headers); 
	}
	#-- END REGPLUS --#
	
	if ( empty($plaintext_pass) )
		return;
		
	#-- REGPLUS --#
	if( !$piereg['custom_msg'] ){
	#-- END REGPLUS --#
	
		$message  = sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n";
		$message .= sprintf(__('Password: %s', 'piereg'), $plaintext_pass) . "\r\n";
		//$message .= get_option('siteurl') . "/wp-login.php";
	
	#-- REGPLUS --#
		$message .= $email_code?$prelink . get_option('siteurl') . "/wp-login.php" . $email_code . "\r\n":"-xxx-"; 
		$message .= $notice; 
	#-- END REGPLUS --#
	
		wp_mail($user_email, sprintf(__('[%s] Your username and password', 'piereg'), get_option('blogname')), $message);
	
	#-- REGPLUS --#
	}else{
		if( $piereg['html'] ){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		}
		//$headers .= 'From: ' . $piereg['from'] . "\r\n" . 'Reply-To: ' . $piereg['from'] . "\r\n";
		add_filter('wp_mail_from', array($pie_register, 'userfrom'));
		add_filter('wp_mail_from_name', array($pie_register, 'userfromname'));
		$subject = $piereg['subject'];
		$message = str_replace('%user_pass%', $plaintext_pass, $piereg['msg']);
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
		if( $piereg['code'] ) $message = str_replace('%invitecode%', $_POST['regcode'], $message);
		
		if( !is_array( $piereg_custom ) ) $piereg_custom = array();
		if (!empty($piereg_custom)) {
			foreach( $piereg_custom as $k=>$v ){
				$meta = $pie_register->Label_ID($v['label']);
				$value = get_usermeta( $user_id, $meta );
				$message = str_replace('%'.$meta.'%', $value, $message);
			}
		}
		
		$redirect = 'redirect_to=' . $piereg['login_redirect'];
		if( $piereg['email_verify'] &&  !$piereg['paypal_option'])
			$siteurl = get_option('siteurl') . "/wp-login.php" . $email_code . '&' . $redirect;
			
		else if( $piereg['email_verify'] &&  $piereg['paypal_option'])
			$siteurl = get_option('siteurl') . "/wp-login.php" . $email_code;
		
		else if($piereg['paypal_option'])
			$siteurl = get_option('siteurl') . "/wp-login.php" . $email_code;
				
		else
			$siteurl = get_option('siteurl') . "/wp-login.php?" . $redirect;
			
		$message = str_replace('%siteurl%', $siteurl, $message);
		
		if( $piereg['html'] && $piereg['user_nl2br'] )
			$message = nl2br($message);
		
		wp_mail($user_email, $subject, $message, $headers); 
	}
	if( $ref != $admin && ( $piereg['email_verify'] || $piereg['admin_verify'] ) ) {
			$temp_user = $wpdb->query( "UPDATE $wpdb->users SET user_login = '$temp_id' WHERE ID = '$user_id'" );
	}else if( $ref != $admin && ($piereg['paypal_option']) ) {
	
			$temp_user = $wpdb->query( "UPDATE $wpdb->users SET user_login = '$temp_id' WHERE ID = '$user_id'" );
			$temp_email = $wpdb->query( "UPDATE $wpdb->users SET user_email = '$temp_id_".$user_email."' WHERE ID = '$user_id'" );
			//$wpdb->query( "UPDATE $wpdb->users SET user_email = '$user_email_$temp_id' WHERE ID = '$user_id'" );
			}
			
	#-- END REGPLUS --#
}
endif;
?>