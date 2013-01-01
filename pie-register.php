<?php
/*
Plugin Name: Pie Register
Plugin URI: http://pie-solutions.com/products/pie-register/
Description: <strong>WordPress 3.2 + ONLY.</strong> Enhance your Registration Page.  Add Custom Logo, Password Field, Invitation Codes, Disclaimer, Captcha Validation, Email Validation, User the fork of register-plus, however many things have been changed since.


Author: Johnibom
Version: 1.2.91
Author URI: http://www.pie-solutions.com

LOCALIZATION
* Currently This feature is not available. We are working on it to improve.
				
CHANGELOG
See readme.txt
*/

/*Created by Skullbit

 Enhanced by JOHNIBOM
 (website: pie-solutions.com       email : johnibom@pie-solutions.com)
*/

$rp = get_option( 'pie_register' ); //load options
if( $rp['dash_widget'] ) //if dashboard widget is enabled
	include_once('dash_widget.php'); //add the dashboard widget
	
if( !class_exists('PieMemberRegister') ){  
	class PieMemberRegister{
		public static $instance;
		protected $retrieve_password_for   = '';
		public    $during_user_creation    = false; // hack
		
		/**
		* Constructor
		*/
		public function __construct() {
			$this->PieMemberRegister();
		}
		function PieMemberRegister() { //constructor
			global $wp_version;
			self::$instance = $this;
			
			$this->plugin_dir = dirname(__FILE__);
			$this->plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
			$this->ref = explode('?',$_SERVER['REQUEST_URI']);
			$this->ref = $this->ref[0];
			$this->admin_edit_profile_page = '/wp-admin/user-edit.php';
			$this->admin_own_profile_page = '/wp-admin/profile.php';		
			//ACTIONS
				if( ($this->ref == $this->admin_edit_profile_page) || ($this->ref == $this->admin_own_profile_page) ){
					add_action( 'admin_head', array($this, 'ProfilesHead') );
				}
				add_action( 'retrieve_password', array( &$this, 'retrieve_password' ) );
				
				#Add Settings Panel
				
				add_action( 'admin_menu', array($this, 'AddPanel') );
				#Update Settings on Save
				if( isset($_POST['action']) && $_POST['action'] == 'pie_reg_update' )
					add_action( 'init', array($this,'SaveSettings') );
				#Enable jQuery on Settings panel
				if( isset($_GET['page']) && $_GET['page'] == 'pie-register' ){
					wp_enqueue_script('jquery');
					add_action( 'admin_head', array($this, 'SettingsHead') );
				}
				add_action( 'login_init', array($this, 'SessionStart'),1 );
				#Add Register Form Fields
				//Julian Fix
				add_action( 'register_form', array($this, 'RegForm'),5 );	
				#Add Register Page Javascript & CSS
						
				if(isset($_GET['action']) && $_GET['action'] == 'register')
					add_action( 'login_head', array($this, 'PassHead') );
				#Add Custom Logo CSS to Login Page
					add_action( 'login_head', array($this, 'LogoHead') );
				#Hide initial login fields when email verification is enabled
					add_action( 'login_head', array($this, 'HideLogin') );
				#Save Default Settings
					add_action( 'init', array($this, 'DefaultSettings') );
				#Profile 
					add_action( 'show_user_profile', array($this, 'Add2Profile') );
					add_filter( 'user_contactmethods' , array($this, 'update_contact_methods') , 10 , 1 );
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
					if( isset($_POST['verifyit']) )
						add_action( 'init', array($this, 'AdminValidate') );
				#Admin Send Payment Link
					if( isset($_POST['paymentl']) )
						add_action( 'init', array($this, 'PaymentLink') );
				#Admin Resend VerificatioN Email
					if( isset($_POST['emailverifyit']) )
						add_action( 'init', array($this, 'AdminEmailValidate') );
				#Admin Delete Unverified User
					if( isset($_POST['vdeleteit']) )
						add_action( 'init', array($this, 'AdminDeleteUnvalidated') );
						
			//FILTERS
				#Check Register Form for Errors
				add_filter( 'registration_errors', array($this, 'RegErrors'),1 );
				/* Since 1.2.9 Hack for wp >= 3.0 */
				add_filter( 'pre_user_email',             array( &$this, 'hack_pre_user_email' ) );
				add_filter( 'retrieve_password_message', array( &$this, 'retrieve_password_message' ) );
					
			//LOCALIZATION
				#Place your language file in the plugin folder and name it "piereg-{language}.mo"
				#replace {language} with your language value from wp-config.php
				load_plugin_textdomain( 'piereg', '/wp-content/plugins/pie-register' );
			
			//VERSION CONTROL
				if( $wp_version < 3.2 )
					add_action('admin_notices', array($this, 'version_warning'));
					
					// Load this plugin last to ensure other plugins don't overwrite the settings

		  add_action( 'activated_plugin', array($this, 'load_last') );
			
		}
		function Install(){
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			$wpdb->query("CREATE TABLE ".$codetable."(`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`created` DATE NOT NULL ,`modified` DATE NOT NULL ,`name` TEXT NOT NULL ,`count` INT( 5 ) NOT NULL ,`status` INT( 2 ) NOT NULL) ENGINE = MYISAM ;");
			$wpdb->flush();
		}
		function Uninstall(){
			global $wpdb;
			$prefix=$wpdb->prefix.'pieregister_';
			$codetable=$prefix.'code';
			$wpdb->query('DROP TABLE `'.$codetable.'`');
			$wpdb->flush();
		}
		function InsertCode($name){
			if(empty($name)) return false;
			
			global $wpdb;
			$piereg=get_option( 'pie_register' );
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			$expiry=$piereg['codeexpiry'];
			$date=date("Y-m-d");
			$check=$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$codetable." WHERE `name`='".$name."';" ) );
			if($check > 0){
				$counts=$wpdb->get_var( $wpdb->prepare( "SELECT `count` FROM ".$codetable." WHERE `name`='".$name."';" ) );
				
				if($counts>=$expiry){
					$wpdb->query("DELETE FROM ".$codetable." WHERE `name`='".$name."' AND `status`='2'");
					$wpdb->flush();
					
					$wpdb->query("INSERT INTO ".$codetable." (`created`,`modified`,`name`,`count`,`status`)VALUES('".$date."','".$date."','".$name."','0','1')");
					$wpdb->flush();
					return true;
				}else{
					return false;
				}
				
			}else{
				$wpdb->query("INSERT INTO ".$codetable." (`created`,`modified`,`name`,`count`,`status`)VALUES('".$date."','".$date."','".$name."','0','1')");
				$wpdb->flush();
				return true;
			}
			
		}
		/**
		* This is a HACK because WP 3.0 introduced a change that made it
		* impossible to suppress the unique email check when creating a new user.
		*
		* For the hack, this filter is invoked just before wp_insert_user() checks
		* for the uniqueness of the email address.  What this is doing is setting a
		* flag so that the get_user_by_email() overridden by this plugin, when
		* called in the wp_insert_user() context, knows to return false, making WP
		* think the email address isn't in use.
		*
		* @since 1.2.9
		*
		* @param string $email Email for the user
		* @return string The same value as passed to the function
		*/
		function hack_pre_user_email( $email ) {
			$this->during_user_creation = true;
			return $email;
		}
		function UpdateCode($name){
			if(empty($name)) return false;
			
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			$piereg=get_option( 'pie_register' );
			$expiry=$piereg['codeexpiry'];
			$date=date("Y-m-d");
			$counts=$wpdb->get_var( $wpdb->prepare( "SELECT `count` FROM ".$codetable." WHERE `name`='".$name."';" ) );
			if( ($expiry > 0) && ($counts == $expiry) ){
				$wpdb->query("UPDATE ".$codetable." SET `modified`='".$date."' ,`status`='2' WHERE `name`='".$name."'");
				
				$pieregcodes=explode("\n", $piereg['codepass']);
				$newcodes='';
				foreach($pieregcodes as $k=>$v){
					if($name !== trim($v)){
						$newcodes.=$v."\n";
					}
				}
				$newcodes=trim($newcodes,"\n");
				$piereg["codepass"] = $newcodes;
				update_option( 'pie_register', $piereg );
				if($piereg['code_auto_del']){
					$wpdb->query("DELETE FROM ".$codetable." WHERE `name`='".$name."'");
				}
				
				return 2;
			}else{
				$wpdb->query("UPDATE ".$codetable." SET `modified`='".$date."' ,`count`='".($counts+1)."' WHERE `name`='".$name."'");
				
				return true;
			}
			
		}
		function SelectCode($name=''){
			global $wpdb;
			$prefix=$wpdb->prefix."pieregister_";
			$codetable=$prefix."code";
			if(empty($name)){
				$result='';
				$result=$wpdb->get_results( "SELECT * FROM ".$codetable." WHERE `status`='2';" );
				return $result;
			}else{
				$counts=$wpdb->get_var( $wpdb->prepare( "SELECT `count` FROM ".$codetable." WHERE `name`='".$name."';" ) );
			return $counts;
			}	
		}
		function SessionStart(){
			return session_start();
		}
		function disable_magic_quotes_gpc($rpg){
			$drf=$rpg;
			if (TRUE == function_exists('get_magic_quotes_gpc') && 1 == get_magic_quotes_gpc()){
				$mqs = strtolower(ini_get('magic_quotes_sybase'));
		
				if (TRUE == empty($mqs) || 'off' == $mqs){
					// we need to do stripslashes on $_GET, $_POST and $_COOKIE
					$rpg=stripslashes($rpg);
				}
				else{
					// we need to do str_replace("''", "'", ...) on $_GET, $_POST, $_COOKIE
					$rpg=str_replace("''","'",$rpg);
				}
			}
			return $rpg;
			//return $drf;
		}
	
		function version_warning(){ //Show warning if plugin is installed on a WordPress lower than 3.2
			global $wp_version;
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Pie-Register is only compatible with WordPress v3.2.1 and up.  You are currently using WordPress v.', 'piereg').$wp_version."</strong> </p></div>
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
			add_menu_page( "Pie Register Settings", 'Pie Register', 10, 'pie-register', array($this, 'RegPlusSettings') );
			add_submenu_page( 'pie-register', 'Payment Gateway Settings', 'Payment Gateway', 10, 'pie-gateway-settings', array($this, 'PieRegPaymentGateway') );
			add_submenu_page( 'pie-register', 'Email Notification Settings', 'Email Notification', 10, 'pie-email-notification', array($this, 'PieRegEmailNotification') );
			add_submenu_page( 'pie-register', 'Presentation Settings', 'Presentation Settings', 10, 'pie-presentation', array($this, 'PieRegPresentationSettings') );
			add_submenu_page( 'pie-register', 'Customised Site Messages', 'Customise Site Messages', 10, 'pie-custom-messages', array($this, 'PieRegCustomMessages') );
			//add_options_page( 'Pie Register', 'Pie Register', 10, 'pie-register', array($this, 'RegPlusSettings') );
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
								'mismatch'				=> 'Mis Match',
								'code' 					=> '0',
								'codename'				=> 'Invitation',
								'codepass' 				=> '',
								'codeexpiry'			=> '0',
								'code_auto_del'			=> '0',
								'Expcodepass' 			=> '',
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
								'emailvmsghtml'					=> '0',
								'adminvmsghtml'					=> '0',
								'adminhtml'				=> '0',
								'from'					=> get_option('admin_email'),
								'fromname'				=> get_option('blogname'),
								'subject'				=> sprintf(__('[%s] Your username and password', 'piereg'), get_option('blogname')),
								'custom_msg'			=> '0',
								'adminvmsguser_nl2br'			=> '0',
								'adminvmsg'					=> " %blogname% Registration \r\n --------------------------- \r\n\r\n Here are your credentials: \r\n Username: %user_login% \r\n Password: %user_pass% \r\n Confirm Registration: %siteurl% \r\n\r\n Thank you for registering with %blogname%!  \r\n",
								'emailvmsguser_nl2br'			=> '0',
								'emailvmsg'					=> " %blogname% Registration \r\n --------------------------- \r\n\r\n Here are your credentials: \r\n Username: %user_login% \r\n Password: %user_pass% \r\n Confirm Registration: %siteurl% \r\n\r\n Thank you for registering with %blogname%!  \r\n",
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
								'register_css'			=> 'body{height:auto;}',
								'login_css'				=> 'body{height:auto;}',
								'firstday'				=> 6,
								'dateformat'			=> 'mm/dd/yyyy',
								'startdate'				=> '',
								'calyear'				=> '',
								'calmonth'				=> 'cur',
								'_admin_message_1'      => 'Please select a user to validate!',
								'_admin_message_2' => 'Users Verified',
								'_admin_message_3' => 'Dear User,',
								'_admin_message_4' => 'You have successfuly registered but your payment has been overdue.',
								'_admin_message_5' => 'Please Click or copy this link to browser to finish the registration.',
								'_admin_message_6' => 'Thank you.',
								'_admin_message_7' => 'Payment Pending',
								'_admin_message_8' => 'Please select a user to send link to!',
								'_admin_message_9' => 'Payment link has been e-mail to the user(s)',
								'_admin_message_10' => 'Please select a user to delete',
								'_admin_message_12' => 'Users Deleted',
								'_admin_message_13' => 'Verification URL: ',
								'_admin_message_14' => 'Verify Account Link',
								'_admin_message_15' => 'Verification Emails have been re-sent',
								'_admin_message_16' => 'Please select a user to send emails to.',
								'_admin_message_17' => 'Your account has now been activated by an administrator.',
								'_admin_message_18' => 'User Account Registration',
								'_admin_message_19' => 'Please enter your First Name.',
								'_admin_message_20' => 'Please enter your Last Name.',
								'_admin_message_21' => 'Please enter your Website URL.',
								'_admin_message_22' => 'Please enter your AIM username.',
								'_admin_message_23' => 'Please enter your Yahoo IM username.',
								'_admin_message_24' => 'Please enter your Jabber / Google Talk username.',
								'_admin_message_25' => 'Please enter your Phone / Mobile number.',
								'_admin_message_26' => 'Please enter your Phone / Mobile number in correct formart No Alphabet No more 13 Variables.',
								'_admin_message_27' => 'Please enter some information About Yourself.',
								'_admin_message_28' => 'Please enter a Password.',
								'_admin_message_29' => 'Your Password does not match.',
								'_admin_message_30' => 'Your Password must be at least 6 characters in length.',
								'_admin_message_31' => 'Image Validation does not match.',
								'_admin_message_32' => 'The reCAPTCHA wasn\'t entered correctly.',
								'_admin_message_33' => 'Please accept the ',
								'_admin_message_34' => 'Please enter the ',
								'_admin_message_35' => 'Code has expired or no longer accepted.',
								'_admin_message_36' => 'Code is incorrect.',
								'_admin_message_37' => 'Please check your e-mail and click the verification link to activate your account and complete your registration.',
								'_admin_message_38' => 'This website is currently closed to public registrations.  You will need a [prcodename] code to register.',
								'_admin_message_39' => 'Have a [prcodename] code? Enter it here. (This is not required)',
								'_admin_message_40' => 'Enter the text from the image.',
								'_admin_message_41' => 'Your account will be reviewed by an administrator and you will be notified when it is activated.',
								'_admin_message_42' => 'Please activate your account using the verification link sent to your email address.',
								'_admin_message_43' => 'Please click below to Continue and finish registration.',
								'_admin_message_44' => 'There is One-Time Subscription fee. Click to Complete your account registration.',
								'_admin_message_45' => 'your email has been verified. There is One-Time Subscription fee. Please Click below to Complete your account registration.',
								'_admin_message_46' => 'for registration, Please login.',
								'_admin_message_47' => 'your payment has been recieved. Please login to your account now!',
								'_admin_message_48' => 'You have successfully Paid for your membership.',
								'_admin_message_49' => 'There\'s an error while verifying your payment.'
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
			if( get_option("piereg_codename") )
			  	$default['codename'] = get_option("piereg_codename");
			if( get_option("piereg_codepass") )
			  	$default['codepass'] = get_option("piereg_codepass");
			if( get_option("piereg_code_auto_del") )
			  	$default['code_auto_del'] = get_option("piereg_code_auto_del");
			if( get_option("piereg_codeexpiry") )
			  	$default['codeexpiry'] = get_option("piereg_codeexpiry");
			if( get_option("piereg_captcha") )
			  	$default['captcha'] = get_option("piereg_captcha");
			#Delete Previous Saved Items
			delete_option('paypal_option');
			delete_option('paypal_butt_id');
			delete_option('paypal_pdt');
			delete_option('piereg_password');
			delete_option('piereg_code');
			delete_option('piereg_codename');
			delete_option('piereg_codepass');
			delete_option('piereg_code_auto_del');
			delete_option('piereg_codeexpiry');
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
			$update = array();
			$update = get_option( 'pie_register' );
			$custom = get_option( 'pie_register_custom' );
			$update["paypal_option"] = $this->disable_magic_quotes_gpc($_POST['piereg_paypal_option']);
			if(isset($_POST['payment_gateway_page'])){
			$update["paypal_butt_id"] = $this->disable_magic_quotes_gpc($_POST['piereg_paypal_butt_id']);
			$update["paypal_pdt"] = $this->disable_magic_quotes_gpc($_POST['piereg_paypal_pdt']);
			}
			if(isset($_POST['email_notification_page'])){
			
			$update['html'] = $this->disable_magic_quotes_gpc($_POST['piereg_html']);
			$update['from'] = $this->disable_magic_quotes_gpc($_POST['piereg_from']);
			$update['fromname'] = $this->disable_magic_quotes_gpc($_POST['piereg_fromname']);
			$update['subject'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_subject']));
			$update['custom_msg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_custom_msg']));
			$update['user_nl2br'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_user_nl2br']));
			$update['user_nl2br'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_emailvmsguser_nl2br']));
			$update['user_nl2br'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminvmsguser_nl2br']));
			$update['msg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_msg']));
			$update['adminvmsg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminvmsg']));
			$update['emailvmsg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_emailvmsg']));
			$update['disable_admin'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_disable_admin']));
			$update['adminhtml'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminhtml']));
			$update['adminfrom'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminfrom']));
			$update['adminfromname'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminfromname']));
			$update['adminsubject'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminsubject']));
			$update['custom_adminmsg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_custom_adminmsg']));
			$update['admin_nl2br'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_admin_nl2br']));
			$update['adminmsg'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_adminmsg']));
			}
			if(isset($_POST['presentation_page'])){
			$update['register_css'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_register_css']));
			$update['login_css'] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_login_css']));
			}
			if(isset($_POST['pieregister_page'])){
			$update['login_redirect'] = $this->disable_magic_quotes_gpc($_POST['piereg_login_redirect']);
			$update["password"] = $this->disable_magic_quotes_gpc($_POST['piereg_password']);
			$update["password_meter"] = $this->disable_magic_quotes_gpc($_POST['piereg_password_meter']);
			$update["short"] = $this->disable_magic_quotes_gpc($_POST['piereg_short']);
			$update["bad"] = $this->disable_magic_quotes_gpc($_POST['piereg_bad']);
			$update["good"] = $this->disable_magic_quotes_gpc($_POST['piereg_good']);
			$update["strong"] = $this->disable_magic_quotes_gpc($_POST['piereg_strong']);
			$update["mismatch"] = $this->disable_magic_quotes_gpc($_POST['piereg_mismatch']);
			$update["code"] = $this->disable_magic_quotes_gpc($_POST['piereg_code']);
			
			if(isset($_POST['piereg_codeexpiry']) && is_numeric($_POST['piereg_codeexpiry'])){
			$update["codeexpiry"] = $_POST['piereg_codeexpiry'];
			}
			$update["code_auto_del"] = $this->disable_magic_quotes_gpc($_POST['piereg_code_auto_del']);
			$update["codename"] = $this->disable_magic_quotes_gpc($_POST['piereg_codename']);
			if( isset($_POST['piereg_code']) ) {
				$update["codepass"] = $_POST['piereg_codepass'];
				$codespasses=explode("\n",$update["codepass"]);
				
				foreach( $codespasses as $k=>$v ){
					$this->InsertCode(trim($v));
				}
				$update["code_req"] = $this->disable_magic_quotes_gpc($_POST['piereg_code_req']);
			}
			$update["captcha"] = $this->disable_magic_quotes_gpc($_POST['piereg_captcha']);
			$update["disclaimer"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_disclaimer']));
			$update["disclaimer_title"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_disclaimer_title']));
			$update["disclaimer_content"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_disclaimer_content']));
			$update["disclaimer_agree"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_disclaimer_agree']));
			$update["license"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_license']));
			$update["license_title"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_license_title']));
			$update["license_content"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_license_content']));
			$update["license_agree"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_license_agree']));
			$update["privacy"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_privacy']));
			$update["privacy_title"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_privacy_title']));
			$update["privacy_content"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_privacy_content']));
			$update["privacy_agree"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_privacy_agree']));
			$update["email_exists"] = $this->disable_magic_quotes_gpc($_POST['piereg_email_exists']);
			$update["firstname"] = $this->disable_magic_quotes_gpc($_POST['piereg_firstname']);
			$update["lastname"] = $this->disable_magic_quotes_gpc($_POST['piereg_lastname']);
			$update["website"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_website']));
			$update["aim"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_aim']));
			$update["yahoo"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_yahoo']));
			$update["jabber"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_jabber']));
			$update["phone"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_phone']));
			$update["about"] = $this->disable_magic_quotes_gpc(htmlentities($_POST['piereg_about']));
			$update["profile_req"] = $this->disable_magic_quotes_gpc($_POST['piereg_profile_req']);
			$update["require_style"] = $this->disable_magic_quotes_gpc(($_POST['piereg_require_style']));
			$update["dash_widget"] = $this->disable_magic_quotes_gpc(($_POST['piereg_dash_widget']));
			$update["admin_verify"] = $this->disable_magic_quotes_gpc($_POST['piereg_admin_verify']);
			$update["email_verify"] = $this->disable_magic_quotes_gpc($_POST['piereg_email_verify']);
			$update["email_verify_date"] = $this->disable_magic_quotes_gpc($_POST['piereg_email_verify_date']);
			$update["email_delete_grace"] = $this->disable_magic_quotes_gpc($_POST['piereg_email_delete_grace']);
			$update["reCAP_public_key"] = $this->disable_magic_quotes_gpc($_POST['piereg_reCAP_public_key']);
			$update["reCAP_private_key"] = $this->disable_magic_quotes_gpc($_POST['piereg_reCAP_private_key']);
			
			
			
			$update['firstday'] = ($_POST['piereg_firstday']);
			$update['dateformat'] = ($_POST['piereg_dateformat']);
			$update['startdate'] = ($_POST['piereg_startdate']);
			$update['calyear'] = ($_POST['piereg_calyear']);
			$update['calmonth'] = $_POST['piereg_calmonth'];
			if( $_FILES['piereg_logo']['name'] ) $update['logo'] = $this->UploadLogo();
			else if( $_POST['remove_logo'] ) $update['logo'] = '';

			if( $_POST['label'] ){
				foreach( $_POST['label'] as $k => $field ){
					if( $field )
					$custom[$k] = array( 'label' => $field, 'profile' => $_POST['profile'][$k], 'reg' => $_POST['reg'][$k], 'required' => $_POST['required'][$k], 'fieldtype' => $_POST['fieldtype'][$k], 'extraoptions' => $_POST['extraoptions'][$k] );
				}
			}			
			}
			if(isset($_POST['customised_messages_page'])){
				$update['_admin_message_1'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_1']);
				$update['_admin_message_2'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_2']);
				$update['_admin_message_3'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_3']);
				$update['_admin_message_4'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_4']);
				$update['_admin_message_5'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_5']);
				$update['_admin_message_6'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_6']);
				$update['_admin_message_7'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_7']);
				$update['_admin_message_8'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_8']);
				$update['_admin_message_9'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_9']);
				$update['_admin_message_10'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_10']);
				$update['_admin_message_12'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_12']);
				$update['_admin_message_13'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_13']);
				$update['_admin_message_14'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_14']);
				$update['_admin_message_15'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_15']);
				$update['_admin_message_16'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_16']);
				$update['_admin_message_17'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_17']);
				$update['_admin_message_18'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_18']);
				$update['_admin_message_19'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_19']);
				$update['_admin_message_20'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_20']);
				$update['_admin_message_21'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_21']);
				$update['_admin_message_22'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_22']);
				$update['_admin_message_23'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_23']);
				$update['_admin_message_24'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_24']);
				$update['_admin_message_25'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_25']);
				$update['_admin_message_26'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_26']);
				$update['_admin_message_27'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_27']);
				$update['_admin_message_28'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_28']);
				$update['_admin_message_29'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_29']);
				$update['_admin_message_30'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_30']);
				$update['_admin_message_31'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_31']);
				$update['_admin_message_32'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_32']);
				$update['_admin_message_33'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_33']);
				$update['_admin_message_34'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_34']);
				$update['_admin_message_35'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_35']);
				$update['_admin_message_36'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_36']);
				$update['_admin_message_37'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_37']);
				$update['_admin_message_38'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_38']);
				$update['_admin_message_39'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_39']);
				$update['_admin_message_40'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_40']);
				$update['_admin_message_41'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_41']);
				$update['_admin_message_42'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_42']);
				$update['_admin_message_43'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_43']);
				$update['_admin_message_44'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_44']); 
				$update['_admin_message_45'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_45']);
				$update['_admin_message_46'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_46']);
				$update['_admin_message_47'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_47']);
				$update['_admin_message_48'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_48']); 
				$update['_admin_message_49'] = $this->disable_magic_quotes_gpc($_POST['piereg__admin_message_49']);
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
		
		function ProfilesHead(){
			$piereg=get_option( 'pie_register' );
		?>
			<script type="text/javascript" src="<?php echo $this->plugin_url;?>datepicker/date.js"></script>
			<!--[if IE]><script type="text/javascript" src="<?php echo $plugin_url;?>datepicker/jquery.bgiframe.min.js"></script><![endif]-->
			
			<!-- jquery.datePicker.js -->
			<script type="text/javascript" src="<?php echo $this->plugin_url;?>datepicker/jquery.datePicker.js"></script>
			<link href="<?php echo $this->plugin_url;?>datepicker/datePicker.css" rel="stylesheet" type="text/css" />
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
			year:<?php if($piereg['calyear']){echo $piereg['calyear'];}else{echo date("Y");}?>,
			month:<?php if( $piereg['calmonth'] != 'cur' ) echo $piereg['calmonth']-1; else echo date('n')-1;?>
			}) 
			});
			</script>
			<style type="text/css">
			a.dp-choose-date { float: left; width: 16px; height: 16px; padding: 0; margin: 5px 3px 0; display: block; text-indent: -2000px; overflow: hidden; background: url(<?php echo $this->plugin_url;?>datepicker/calendar.png) no-repeat; } a.dp-choose-date.dp-disabled { background-position: 0 -20px; cursor: default; } /* makes the input field shorter once the date picker code * has run (to allow space for the calendar icon */ input.dp-applied { width: 140px; float: left; }
			</style>
		<?php
		}
		function SettingsHead(){
			
			$piereg = get_option( 'pie_register' );
			
			?>
<script type="text/javascript">
<?php
require_once($this->plugin_dir.'/js/pie-register-main.js');
?>
</script>

<style type="text/css">

#pie-register{
line-height:16px;
}
#pie-register .label{
display:inline;
}
.expired_code{
width:240px;
border:1px solid #333333;
background-color:#e1e1e1;
max-height:100px;
overflow:auto;
margin-bottom:10px;
padding:5px 10px;
}
</style>
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
						$login = get_user_meta($user_id, 'email_verify_user',true);
							$useremail=get_user_meta($user_id,'email_verify_email',true);
		
							$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
							$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
							delete_user_meta($user_id, 'email_verify_user');
							delete_user_meta($user_id, 'email_verify');
							delete_user_meta($user_id, 'email_verify_date');
							delete_user_meta($user_id, 'email_verify_user_email');
							
					}else if( $piereg['admin_verify'] ){
						$login = get_user_meta($user_id, 'admin_verify_user',true);
						$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
						$useremail=get_user_meta($user_id,'email_verify_email',true);
		
						$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
						delete_user_meta($user_id, 'admin_verify_user');
						delete_user_meta($user_id, 'email_verify_user_email');
					}else if( $piereg['paypal_option'] ){
							$login = get_user_meta($user_id, 'email_verify_user',true);
							$useremail=get_user_meta($user_id,'email_verify_email',true);
							$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
							$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
							delete_user_meta($user_id, 'email_verify_user_email');
							delete_user_meta($user_id, 'email_verify_user');
							delete_user_meta($user_id, 'email_verify');
							delete_user_meta($user_id, 'email_verify_date');
					}
					
					$this->VerifyNotification($user_id);
				}
			}
			}else{
			$_POST['notice'] = __("<strong>Error:</strong> ".$piereg['_admin_message_1'],"piereg");
			return false;
			}
			$_POST['notice'] = __($piereg['_admin_message_2'],"piereg");
			
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
						$login = get_user_meta($user_id, 'email_verify_user',true);
						$user_email = get_user_meta($user_id, 'email_verify_email',true);	
							$pp="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=".$piereg['paypal_butt_id']."&custom=".$user_id;
							
							
					}else if( $piereg['admin_verify'] ){
						$login = get_user_meta($user_id, 'admin_verify_user',true);
						$user_email = get_user_meta($user_id, 'email_verify_email',true);
						$pp="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=".$piereg['paypal_butt_id']."&custom=".$user_id;
					}
					$message = __($piereg['_admin_message_3']) . "\r\n\r\n";
					$message .= __($piereg['_admin_message_4']) . "\r\n";
					$message .= sprintf(__('Username: %s', 'piereg'), $login) . "\r\n\r\n";
					$message .= __($piereg['_admin_message_5']) . "\r\n\r\n";
					$message .= $pp." \r\n ".$piereg['_admin_message_6']." \r\n";
					add_filter('wp_mail_from', array($this, 'userfrom'));
			add_filter('wp_mail_from_name', array($this, 'userfromname'));
			wp_mail($user_email, sprintf(__('[%s] '.$piereg['_admin_message_7'], 'piereg'), get_option('blogname')), $message);
					//$this->VerifyNotification($user_id,$pp);
				}
			}
			}else{
			$_POST['notice'] = __("<strong>Error:</strong> ".$piereg['_admin_message_8'],"piereg");
			return false;
			}
			$_POST['notice'] = __($piereg['_admin_message_9'],"piereg");
		
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
			$_POST['notice'] = __("<strong>Error:</strong> ".$piereg['_admin_message_10'],"piereg");
			return false;
			}
			$_POST['notice'] = __($piereg['_admin_message_12'],"piereg");
		}
		function AdminEmailValidate(){
			global $wpdb;
			check_admin_referer('piereg-unverified');
			$valid = $_POST['vusers'];
			if( is_array($valid) ):
			foreach( $valid as $user_id ){
				$code = get_user_meta($user_id, 'email_verify',true);
				if(empty($code)){
					$code = $this->RanPass(25);
					update_usermeta( $user_id, 'email_verify', $code );
					update_usermeta( $user_id, 'email_verify_date', date('Ymd') );
					
					$email_code = '?piereg_verification=' . $code;
				}
				//$code = get_user_meta($user_id, 'email_verify',true);
				$user_login = get_user_meta($user_id, 'email_verify_user',true);
				if(empty($user_login)){
				$user_login = get_user_meta($user_id, 'admin_verify_user',true);
				}				
				$user_email = get_user_meta($user_id, 'email_verify_email',true);
				$email_code = '?piereg_verification=' . $code;




				$prelink = __($piereg['_admin_message_13'], 'piereg');		
				$message  = sprintf(__('Username: %s', 'piereg'), $user_login) . "\r\n";
				//$message .= sprintf(__('Password: %s', 'piereg'), $plaintext_pass) . "\r\n";
				$message .= $prelink . get_option('siteurl') . "/wp-login.php" . $email_code . "\r\n"; 
				$message .= $notice; 
				add_filter('wp_mail_from', array($this, 'userfrom'));
				add_filter('wp_mail_from_name', array($this, 'userfromname'));
				wp_mail($user_email, sprintf(__('[%s] '.$piereg['_admin_message_14'], 'piereg'), get_option('blogname')), $message);
						
			}
			$_POST['notice'] = __($piereg['_admin_message_15'], "piereg");
			else:
			$_POST['notice'] = __("<strong>Error:</strong> ".$piereg['_admin_message_16'], "piereg");
			endif;
		}
		function VerifyNotification($user_id,$pp=""){
			global $wpdb;
			$piereg = get_option('pie_register');
			
			$user = $wpdb->get_row("SELECT user_login, user_email FROM $wpdb->users WHERE ID='$user_id'");
			$message = __($piereg['_admin_message_17']) . "\r\n";
			$message .= sprintf(__('Username: %s', 'piereg'), $user->user_login) . "\r\n";
			$message .= $prelink . get_option('siteurl') . "/wp-login.php" . "\r\n";
			$user_email=get_user_meta($user_id, 'email_verify_email',true);
									 
			add_filter('wp_mail_from', array($this, 'userfrom'));
			add_filter('wp_mail_from_name', array($this, 'userfromname'));
			wp_mail($user_email, sprintf(__('[%s] '.$piereg['_admin_message_18'], 'piereg'), get_option('blogname')), $message);
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
									$user_login = get_user_meta($un->ID, 'email_verify_user',true);
								else if( $piereg['admin_verify'] )
									$user_login = get_user_meta($un->ID, 'admin_verify_user',true);
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
			//error_reporting(E_ALL);
			
			require_once($this->plugin_dir.'/menus/pieregisterSettings.php');
		}
		function PieRegPaymentGateway(){
			
			require_once($this->plugin_dir.'/menus/PieRegPaymentGateway.php');
				
		}
		function PieRegPresentationSettings(){
			
			require_once($this->plugin_dir.'/menus/PieRegPresentationSettings.php');
		}
		function PieRegEmailNotification(){
			
			require_once($this->plugin_dir.'/menus/PieRegEmailNotification.php');
		}
		function PieRegCustomMessages(){
			
			require_once($this->plugin_dir.'/menus/PieRegCustomMessages.php');
		}
		function count_multiple_accounts( $email, $user_id =  null ) {
			global $wpdb;
			$sql = "SELECT COUNT(*) AS count FROM $wpdb->users WHERE user_email = %s";
			if ( $user_id )
				$sql .= ' AND ID != %d';
			return (int) $wpdb->get_var( $wpdb->prepare( $sql, $email, $user_id ) );
		}
		
		function get_users_by_email( $email ) {
			return get_users( array( 'search' => $email, 'blog_id' => '' ) );
		}
		
		function has_multiple_accounts( $email ) {
			return $this->count_multiple_accounts( $email ) > 1 ? true : false;
		}
		
		function retrieve_password( $user_login ) {
			$this->retrieve_password_for = $user_login;
			
			return $user_login;
		}
		function retrieve_password_message( $message ) {
			$user = get_user_by( 'login', $this->retrieve_password_for );
			if ( $this->has_multiple_accounts( $user->user_email ) ) {
				$message .= "\r\n\r\n";
				$message .= __( 'For your information, your e-mail address is also associated with the following accounts:', 'piereg' ) . "\r\n\r\n";
				foreach ( $this->get_users_by_email( $user->user_email ) as $user ) {
					$message .= "\t" . $user->user_login . "\r\n";
				}
				$message .= "\r\n";
				$message .= __( 'In order to reset the password for any of these (if you aren\'t already successfully in the middle of doing so already), you should specify the login when requesting a password reset rather than using your e-mail.', 'piereg' ) . "\r\n\r\n";
			}
			return $message;
		}
		# Check Required Fields
		function RegErrors($errors){
			$CeRror=0;
			$piereg = get_option( 'pie_register' );
			$piereg_custom = get_option( 'pie_register_custom' );
			$Cexpiry=$piereg['codeexpiry'];
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			
			if( $piereg['email_exists'] ){
				
				if ( $errors->errors['email_exists'] ){
					unset($errors->errors['email_exists']);
				}
				
			}else{
				if ( $errors->errors['email_exists'] ){
					$CeRror=1;
				}
			}
			
			if( $piereg['firstname'] && in_array('firstname', $piereg['profile_req']) ){
				if(empty($_POST['firstname']) || $_POST['firstname'] == ''){
					$errors->add('empty_firstname', __('<strong>ERROR</strong>: '.$piereg['_admin_message_19'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['lastname'] && in_array('lastname', $piereg['profile_req']) ){
				if(empty($_POST['lastname']) || $_POST['lastname'] == ''){
					$errors->add('empty_lastname', __('<strong>ERROR</strong>: '.$piereg['_admin_message_20'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['website'] && in_array('website', $piereg['profile_req']) ){
				if(empty($_POST['website']) || $_POST['website'] == ''){
					$errors->add('empty_website', __('<strong>ERROR</strong>: '.$piereg['_admin_message_21'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['aim'] && in_array('aim', $piereg['profile_req']) ){
				if(empty($_POST['aim']) || $_POST['aim'] == ''){
					$errors->add('empty_aim', __('<strong>ERROR</strong>: '.$piereg['_admin_message_22'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['yahoo'] && in_array('yahoo', $piereg['profile_req']) ){
				if(empty($_POST['yahoo']) || $_POST['yahoo'] == ''){
					$errors->add('empty_yahoo', __('<strong>ERROR</strong>: '.$piereg['_admin_message_23'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['jabber'] && in_array('jabber', $piereg['profile_req']) ){
				if(empty($_POST['jabber']) || $_POST['jabber'] == ''){
					$errors->add('empty_jabber', __('<strong>ERROR</strong>: '.$piereg['_admin_message_24'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['phone'] && in_array('phone', $piereg['profile_req']) ){
				if(empty($_POST['phone']) || $_POST['phone'] == ''){
					$errors->add('empty_phone', __('<strong>ERROR</strong>: '.$piereg['_admin_message_25'], 'piereg'));
					$CeRror=1;
				}else if(preg_match('/\D/ism',$_POST['phone']) || (strlen($_POST['phone'])>13) || (strlen($_POST['phone'])<7)){
					$errors->add('Wrong_Phone', __('<strong>ERROR</strong>: '.$piereg['_admin_message_26'], 'piereg'));
					$CeRror=1;
				}
			}
			if( $piereg['about'] && in_array('about', $piereg['profile_req']) ){
				if(empty($_POST['about']) || $_POST['about'] == ''){
					$errors->add('empty_about', __('<strong>ERROR</strong>: '.$piereg['_admin_message_27'], 'piereg'));
					$CeRror=1;
				}
			}
			if (!empty($piereg_custom)) {
				foreach( $piereg_custom as $k=>$v ){
					if( $v['required'] && $v['reg'] ){
						$id = $this->Label_ID($v['label']);
						if(empty($_POST[$id]) || $_POST[$id] == ''){
							$errors->add('empty_' . $id, __('<strong>ERROR</strong>: Please enter your ' . $v['label'] . '.', 'piereg'));
							$CeRror=1;
						}
					}
				}
			}
					
			if ( $piereg['password'] ){
				if(empty($_POST['pass1']) || $_POST['pass1'] == '' || empty($_POST['pass2']) || $_POST['pass2'] == ''){
					$errors->add('empty_password', __('<strong>ERROR</strong>: '.$piereg['_admin_message_28'], 'piereg'));
					$CeRror=1;
				}elseif($_POST['pass1'] !== $_POST['pass2']){
					$errors->add('password_mismatch', __('<strong>ERROR</strong>: '.$piereg['_admin_message_29'], 'piereg'));
					$CeRror=1;
				}elseif(strlen($_POST['pass1'])<6){
					$errors->add('password_length', __('<strong>ERROR</strong>: '.$piereg['_admin_message_30'], 'piereg'));
					$CeRror=1;
				}else{
					$_POST['user_pw'] = $_POST['pass1'];
				}
			}
			
			
			if ( $piereg['captcha'] == 1 ){
				
				$key = $_SESSION['1k2j48djh'];
				$number = md5($_POST['captcha']);
				if($number!=$key){
				  	$errors->add('captcha_mismatch', __("<strong>ERROR</strong>: ".$piereg['_admin_message_31'], 'piereg'));
					$CeRror=1;
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
				  $errors->add('recaptcha_mismatch', __("<strong>ERROR:</strong> ".$piereg['_admin_message_32'], 'piereg'));
				  $CeRror=1;
				  //$errors->add('recaptcha_error', "(" . __("reCAPTCHA said: ", 'piereg') . $resp->error . ")");
				}
			}
			
			if ( $piereg['disclaimer'] ){
				if(!$_POST['disclaimer']){
				  	$errors->add('disclaimer', __('<strong>ERROR</strong>: '.$piereg['_admin_message_33'], 'piereg') . stripslashes( $piereg['disclaimer_title'] ) . '.');
					$CeRror=1;
				}	
			}
			if ( $piereg['license'] ){
				if(!$_POST['license']){
				  	$errors->add('license', __('<strong>ERROR</strong>: '.$piereg['_admin_message_33'], 'piereg') . stripslashes( $piereg['license_title'] ) . '.');
					$CeRror=1;
				}	
			}
			if ( $piereg['privacy'] ){
				if(!$_POST['privacy']){
				  	$errors->add('privacy', __('<strong>ERROR</strong>: '.$piereg['_admin_message_33'], 'piereg') . stripslashes( $piereg['privacy_title'] ) . '.');
					$CeRror=1;
				}	
			}
			
			if ( $piereg['code'] && $piereg['code_req'] ){
				$pieregcodes=explode("\n", $piereg['codepass']);
				foreach($pieregcodes as $key=>$val){
					$Pieregcodes[$key]=trim($val);
				}
				if(empty($_POST['regcode']) || $_POST['regcode'] == ''){
					$errors->add('empty_regcode', __('<strong>ERROR</strong>: '.$piereg['_admin_message_34'].' '.$piereg['codename'].' Code.', 'piereg'));
					$CeRror=1;
				}elseif( ($Cexpiry > 0) && $this->SelectCode($_POST['regcode']) == $Cexpiry ){
					$this->UpdateCode($_POST['regcode']);
					$errors->add('expired_regcode', __('<strong>ERROR</strong>: Your '.$piereg['codename'].' '.$piereg['_admin_message_35'], 'piereg'));
					$CeRror=1;
				}elseif( !in_array($_POST['regcode'], $Pieregcodes) ){
					$errors->add('regcode_mismatch', __('<strong>ERROR</strong>: Your '.$piereg['codename'].' '.$piereg['_admin_message_36'], 'piereg'));
					$CeRror=1;
				}else{
					if($CeRror != 1){
					$this->UpdateCode($_POST['regcode']);
					}
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
				unset( $errors->error_data['email_exists'] );
			}
			if	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )	$errors->add('registeredit', __($piereg['_admin_message_37']), 'message');
			return $errors;
		}
		
		# Add Fields to Register Form
		function RegForm(){
			$piereg = get_option( 'pie_register' );
			$piereg_custom = get_option( 'pie_register_custom' );
			if( !is_array( $piereg_custom ) ) $piereg_custom = array();
			
			if ( $piereg['password'] ){
			?>
			<div style="clear:both"><label><?php _e('Password:', 'piereg');?> <p>
			<input autocomplete="off" name="pass1" id="pass1" size="25" value="<?php echo $_POST['pass1'];?>" type="password" tabindex="28" /></p></label></div>
		   <div> <label><?php _e('Confirm Password:', 'piereg');?> <p>
			<input autocomplete="off" name="pass2" id="pass2" size="25" value="<?php echo $_POST['pass2'];?>" type="password" tabindex="29" /></p></label></div>
			<?php if( $piereg['password_meter'] ){ ?>
		   <div> <span id="pass-strength-result"><?php echo $piereg['short'];?></span>
			<small><?php _e('Hint: Use upper and lower case characters, numbers and symbols like !"?$%^&amp;( in your password.', 'piereg'); ?> </small></div>
			<?php } ?>
            <?php
			}
			
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
		<input autocomplete="off" name="aim" id="aim" size="25" value="<?php echo $_POST['aim'];?>" type="text" tabindex="33" /></p></label></div>
            <?php
			}
			if ( $piereg['yahoo'] ){
				if( isset( $_GET['yahoo'] ) ) $_POST['yahoo'] = $_GET['yahoo'];
			?>
   		<div style="clear:both"><label><?php _e('Yahoo IM:', 'piereg');?> <p>
		<input autocomplete="off" name="yahoo" id="yahoo" size="25" value="<?php echo $_POST['yahoo'];?>" type="text" tabindex="34" /></p></label></div>
            <?php
			}
			if ( $piereg['jabber'] ){
				if( isset( $_GET['jabber'] ) ) $_POST['jabber'] = $_GET['jabber'];
			?>
   		<div style="clear:both"><label><?php _e('Jabber / Google Talk:', 'piereg');?> <p>
		<input autocomplete="off" name="jabber" id="jabber" size="25" value="<?php echo $_POST['jabber'];?>" type="text" tabindex="35" /></p></label></div>
            <?php
			}
			if ( $piereg['phone'] ){
				if( isset( $_GET['phone'] ) ) $_POST['phone'] = $_GET['phone'];
			?>
   		<div style="clear:both"><label><?php _e('Phone # / Mobile #:', 'piereg');?> <p>
		<input autocomplete="off" name="phone" id="phone" size="25" value="<?php echo $_POST['phone'];?>" type="text" tabindex="36" /></p></label></div>
            <?php
			}
			if ( $piereg['about'] ){
				if( isset( $_GET['about'] ) ) $_POST['about'] = $_GET['about'];
			?>
   		<div style="clear:both"><label><?php _e('About Yourself:', 'piereg');?> <p>
		<textarea autocomplete="off" name="about" id="about" cols="25" rows="5" tabindex="37"><?php echo stripslashes($_POST['about']);?></textarea></p></label>
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
		<input autocomplete="off" class="custom_field" tabindex="38" name="<?php echo $id;?>" id="<?php echo $id;?>" size="25" value="<?php echo $_POST[$id];?>" type="text" /></p></label></div>
        
        <?php } else if( $v['fieldtype'] == 'date' ){ ?>
       <div style="clear:both"><label><?php echo $v['label'];?>: <p>
		<input autocomplete="off" class="custom_field date-pick" tabindex="39" name="<?php echo $id;?>" id="<?php echo $id;?>" size="25" value="<?php echo $_POST[$id];?>" type="text" /></p></label></div>
        
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
        <select class="custom_select" tabindex="40" name="<?php echo $id;?>" id="<?php echo $id;?>">
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
		<textarea tabindex="42" name="<?php echo $id;?>" cols="25" rows="5" id="<?php echo $id;?>" class="custom_textarea"><?php echo $_POST[$id];?></textarea></p></label></div>	
		
		<?php } else if( $v['fieldtype'] == 'hidden' ){ ?><p>
		<input class="custom_field" tabindex="43" name="<?php echo $id;?>" value="<?php echo $_POST[$id];?>" type="hidden" />  </p>        	
        <?php } ?>		
				
		<?php	}
        	}			
			
			if ( $piereg['code'] ){
			$pieregcodes=explode("\n", $piereg['codepass']);
						
				if( isset( $_GET['regcode'] ) ) $_POST['regcode'] = $_GET['regcode'];
			?>
        <div style="clear:both"><label><?php _e($piereg['codename'].' Code:', 'piereg');?> <p>
		<input name="regcode" id="regcode" size="25" value="<?php echo $_POST['regcode'];?>" type="text" tabindex="44" /></p></label>
        <?php if ($piereg['code_req']) {?>
		<p><small><?php _e(str_replace('[prcodename]',$piereg['codename'],$piereg['_admin_message_38']), 'piereg');?></small></p>
        <?php }else{ ?>
        <p><small><?php _e(str_replace('[prcodename]',$piereg['codename'],$piereg['_admin_message_39']), 'piereg');?></small></p>
        <?php } ?>
        </div>
            <?php
			}
			
			if ( $piereg['disclaimer'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['disclaimer_title'] );?> <p>
        <span id="disclaimer"><?php echo stripslashes($piereg['disclaimer_content']); ?></span>
		<input name="disclaimer" value="1" type="checkbox" tabindex="45"<?php if($_POST['disclaimer']) echo ' checked="checked"';?> /> <?php echo $piereg['disclaimer_agree'];?></p></label></div>
            <?php
			}
			if ( $piereg['license'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['license_title'] );?> <p>
        <span id="license"><?php echo stripslashes($piereg['license_content']); ?></span>
		<input name="license" value="1" type="checkbox" tabindex="46"<?php if($_POST['license']) echo ' checked="checked"';?> /> <?php echo $piereg['license_agree'];?></p></label></div>
            <?php
			}
			if ( $piereg['privacy'] ){
			?>
   		<div style="clear:both"><label><?php echo stripslashes( $piereg['privacy_title'] );?> <p>
        <span id="privacy"><?php echo stripslashes($piereg['privacy_content']); ?></span>
		<input name="privacy" value="1" type="checkbox" tabindex="47"<?php if($_POST['privacy']) echo ' checked="checked"';?> /> <?php echo $piereg['privacy_agree'];?></p></label></div>
            <?php
			}
			
			if ( $piereg['captcha'] == 1 ){
				
				$_SESSION['OK'] = 1;
				if( !isset( $_SESSION['OK'] ) )
					session_start(); 
				?>
               <div style="clear:both"><label><?php _e('Validation Image:', 'piereg');?> <p>
                <img src="<?php echo $this->plugin_url;?>captcha.php" id="captchaimg" alt="" />
                <input type="text" name="captcha" id="captcha" size="25" value="" tabindex="48" /></p></label>
                <small><?php _e($piereg['_admin_message_40'], 'piereg');?></small></div>
               
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
			?>
			<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.js?ver=1.7.1'></script>
			<!--<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-admin/js/common.js?ver=20080318'></script>-->
			<?php
			if ( $piereg['password'] ){
?>



<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.color.js?ver=2.0-4561'></script>
<script type='text/javascript'>
/* <![CDATA[ */
	pwsL10n = {
		short: "<?php echo $piereg['short'];?>",
		bad: "<?php echo $piereg['bad'];?>",
		good: "<?php echo $piereg['good'];?>",
		strong: "<?php echo $piereg['strong'];?>",
		mismatch: "<?php echo $piereg['mismatch'];?>"
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

		jQuery(res).removeClass('short bad good strong mismatch');
		
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
		else if ( strength == 5 ) {
			jQuery(res).addClass('mismatch');
			jQuery(res).html( pwsL10n.mismatch );
		}
		else {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}

	}
	

	jQuery(function($) { 
		$('#pass1').keyup( check_pass_strength );
		$('#pass2').keyup( check_pass_strength )
		$('.color-palette').click(function(){$(this).siblings('input[name=admin_color]').attr('checked', 'checked')});
	} );
	
	jQuery(document).ready( function() {
		jQuery('#pass1,#pass2').attr('autocomplete','off');
		jQuery('#user_login').val('<?php echo $user_login; ?>');
		jQuery('#user_email').val('<?php echo $user_email; ?>');
    });
</script>
<?php } ?>

<!-- required plugins -->
<script type="text/javascript" src="<?php echo $this->plugin_url;?>datepicker/date.js"></script>
<!--[if IE]><script type="text/javascript" src="<?php echo $plugin_url;?>datepicker/jquery.bgiframe.js"></script><![endif]-->

<!-- jquery.datePicker.js -->
<script type="text/javascript" src="<?php echo $this->plugin_url;?>datepicker/jquery.datePicker.js"></script>
<link href="<?php echo $this->plugin_url;?>datepicker/datePicker.css" rel="stylesheet" type="text/css" />
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
		year:<?php if($piereg['calyear']){echo $piereg['calyear'];}else{echo date("Y");}?>,
		month:<?php if( $piereg['calmonth'] != 'cur' ) echo $piereg['calmonth']-1; else echo date('n')-1;?>
	}) 
});
</script>
<style type="text/css">
a.dp-choose-date { float: left; width: 16px; height: 16px; padding: 0; margin: 5px 3px 0; display: block; text-indent: -2000px; overflow: hidden; background: url(<?php echo $this->plugin_url;?>datepicker/calendar.png) no-repeat; } a.dp-choose-date.dp-disabled { background-position: 0 -20px; cursor: default; } /* makes the input field shorter once the date picker code * has run (to allow space for the calendar icon */ input.dp-applied { width: 140px; float: left; }
																																																																																				
#phone, #pass1, #pass2, #regcode, #captcha, #firstname, #lastname, #website, #aim, #yahoo, #jabber, #about, .custom_field{
	font-size: 20px;	
	width: 99%;
	padding: 3px;
	margin-right: 6px;
}
.custom_select, .custom_textarea{	
	width: 99%;
	padding: 3px;
	margin-right: 6px;
}
#about, .custom_textarea{
	height: 60px;
}
#disclaimer, #license, #privacy{
	display:block;
	width: 99%;
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
	width: 48%;
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
	margin-left:0px;
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
	margin:0 auto;
}

<?php if( $piereg['register_css'] &&  $_GET['action'] == 'register') echo html_entity_decode(stripslashes($piereg['register_css'])); 
else if( $piereg['login_css'] ) echo html_entity_decode(stripslashes($piereg['login_css'])); ?>
</style>
		<?php } 
		
		}
		
		
		
		function update_contact_methods( $contactmethods ) {
		
			// Add new fields
			$contactmethods['phone'] = 'Phone';			
			return $contactmethods;
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
						$single = true;
						$value = get_user_meta( $user_ID, $id, $single );
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
							case "date" :
								$outfield = '<input autocomplete="off" class="custom_field date-pick" tabindex="36" name="' . $id . '" id="' . $id . '" value="' . $value . '"  />';
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
		//With Thanks
		//Fixed by Julian Warren
		function SaveProfile($user_id){
			global $wpdb;
			//get_currentuserinfo();
			//if( $_GET['user_id'] ) $user_ID = $_GET['user_id'];
			$user_ID=$user_id;
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
				
				echo '<p style="text-align:center;margin-bottom:10px;">' . __($piereg['_admin_message_41'], 'piereg') . '</p>';
			}else if( $piereg['email_verify'] && isset( $_GET['checkemail'] ) ){
					
				echo '<p style="text-align:center;margin-bottom:10px;">' . __($piereg['_admin_message_42'], 'piereg') . '</p>';
			}
			if( $piereg['email_verify'] && isset( $_GET['piereg_verification'] ) ){
				$piereg = get_option( 'pie_register' );
				$verify_key = $_GET['piereg_verification'];
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify' AND meta_value='$verify_key'");
				}else if($piereg['paypal_option'] && !$piereg['email_verify'] && isset( $_GET['checkemail'] ) ){
					
				echo '<p style="text-align:center;margin-bottom:10px;background-color:#FFFFE0;border:1px solid #E6DB55;padding:12px 0px;">' . __($piereg['_admin_message_43'], 'piereg') . '</p>';
				/*session_start();*/
				
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify_user' AND meta_value='".$_SESSION['secure_id']."'");
				
				$user_details_gender=$wpdb->get_row( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'gender' AND user_id='".$user_id."'");
				$user_details_username=$wpdb->get_row( "SELECT user_login FROM $wpdb->users WHERE ID='".$user_id."'");
				$user_name=$_SESSION['secure_id'];
				}
				if ( $user_id ) {
					if($piereg['paypal_option'] && !$piereg['email_verify']){
					$login = get_user_meta($user_id, 'email_verify_user',true);
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Hello <strong>%s</strong>, '.$piereg['_admin_message_44'], 'piereg'), $login ) . '</p>';
					
					
					$paypalcode="<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&custom=".$user_id."&hosted_button_id=".$piereg['paypal_butt_id']."'><img src='https://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif' alt='PayPal - The safer, easier way to pay online' border='0' /></a>";
					
					}
					else if($piereg['paypal_option'] && $piereg['email_verify'] && isset( $_GET['piereg_verification'] )){
					$login = get_user_meta($user_id, 'email_verify_user',true);
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Thank you <strong>%s</strong>, '.$piereg['_admin_message_45'], 'piereg'), $login ) . '</p>';
					$paypalcode="<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&custom=".$user_id."&hosted_button_id=".$piereg['paypal_butt_id']."'><img src='https://www.paypal.com/en_US/i/btn/btn_subscribe_LG.gif' alt='PayPal - The safer, easier way to pay online' border='0' /></a>";
					
					}else{
					$login = get_user_meta($user_id, 'email_verify_user',true);
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$login' WHERE ID = '$user_id'" );
					$user_email=get_user_meta($user_id, 'email_verify_email',true);
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$user_email' WHERE ID = '$user_id' " );
					delete_user_meta($user_id, 'email_verify_user');
					delete_user_meta($user_id, 'email_verify');
					delete_user_meta($user_id, 'email_verify_date');
					
					$msg = '<p style="margin-bottom:10px;">' . sprintf(__('Thank you <strong>%s</strong>, '.$piereg['_admin_message_46'], 'piereg'), $login ) . '</p>';
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
	$useremail=get_user_meta($user_id,'email_verify_email',true);
	
	
				/*$verify_key = $_GET['piereg_verification'];
				$user_id = $wpdb->get_var( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'email_verify' AND meta_value='$verify_key'");*/
				if ( $user_id ) {
					
					$loginE = get_user_meta($user_id, 'email_verify_user',true);
					$loginA = get_user_meta($user_id, 'admin_verify_user',true);
					if($loginE){
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$loginE' WHERE ID = '$user_id'" );
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$useremail' WHERE ID = '$user_id'" );
					delete_user_meta($user_id, 'email_verify_user');
					delete_user_meta($user_id, 'email_verify');
					delete_user_meta($user_id, 'email_verify_date');
					delete_user_meta($user_id, 'email_verify_user_pwd');
					delete_user_meta($user_id, 'email_verify_email');
					$msg = '<p class="message">' . sprintf(__('Thank you %s, '.$piereg['_admin_message_47'], 'piereg'), $login ) . '</p>';
					}else if($loginA){
					$wpdb->query( "UPDATE $wpdb->users SET user_login = '$loginA' WHERE ID = '$user_id'" );
					$wpdb->query( "UPDATE $wpdb->users SET user_email = '$uuseremail' WHERE ID = '$user_id'" );
					delete_user_meta($user_id, 'admin_verify_user');
					delete_user_meta($user_id, 'email_verify_user_pwd');
					delete_user_meta($user_id, 'email_verify_email');
					delete_user_meta($user_id, 'admin_verify');
					$msg = '<p class="message">' . sprintf(__('Thank you %s, '.$piereg['_admin_message_47'], 'piereg'), $login ) . '</p>';
					
					}
					
					
					
					echo $msg;
					
				}
				echo '<p style="text-align:center;">' . __($piereg['_admin_message_48'], 'piereg') . '</p>';
	}
	else if (strcmp ($lines[0], "FAIL") == 0) {
	// log for manual investigation
	echo '<p style="text-align:center;">' . __($piereg['_admin_message_49'], 'piereg') . '</p>';
					
				
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
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('You have another plugin installed that is conflicting with Pie Register.  This other plugin is overriding the user notification emails.  Please see <a href="http://pie-solutions.com/products/pie-register/">Pie Register Conflicts</a> for more information.', 'piereg') . "</strong></p></div>";
		}
		
		function donate(){
			echo '<p><strong>' . __('If you find this plugin useful, please consider ', 'piereg') . '<form target="_blank" method="post" action="https://www.paypal.com/cgi-bin/webscr">

<input type="hidden" value="_s-xclick" name="cmd">
<input type="hidden" value="LB2XC8BNHCQ4W" name="hosted_button_id">
<input type="image" border="0" alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif">
<img width="1" border="0" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt=""><br>

</form>'  . __('donating', 'piereg') . '</strong></p>';
		}
	}
}# END Class PieMemberRegister


# Run The Plugin!
if( class_exists('PieMemberRegister') ){
	$pie_register = new PieMemberRegister();
	if(isset($pie_register)){
		register_activation_hook( __FILE__, array(  &$pie_register, 'Install' ) );
		register_deactivation_hook( __FILE__, array(  &$pie_register, 'Uninstall' ) );
	}
}
if ( version_compare( $GLOBALS['wp_version'], '3.3', '<' ) && ! function_exists( 'get_user_by_email' ) ) {
	
	function get_user_by_email( $email ) {
		$piereg = get_option( 'pie_register' );
		if(PieMemberRegister::$instance->during_user_creation ){
			return false;
		}
		return get_user_by( 'email', $email );
	}
}
if ( version_compare( $GLOBALS['wp_version'], '3.2.99', '>' ) &&! function_exists( 'get_user_by' ) ) {
		
		function get_user_by( $field, $value ) {
			$piereg = get_option( 'pie_register' );
			
			if ( 'email' == $field && PieMemberRegister::$instance->during_user_creation  )
				return false;

			$userdata = WP_User::get_data_by( $field, $value );

			if ( !$userdata )
				return false;

			$user = new WP_User;
			$user->init( $userdata );

			return $user;
		}
	}

require_once('piewpnun.php');
?>