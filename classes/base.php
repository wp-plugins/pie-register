<?
class Base
{
	var $user_table;		
	var $user_meta_table; 
	function __construct()
	{
			
	}
	function getPieMeta()
	{
		global $wpdb;
		$this->user_table		= $wpdb->prefix . "users";
		$this->user_meta_table 	= $wpdb->prefix . "usermeta";
		$result	 = $wpdb->get_results("SELECT distinct(meta_key) FROM ".$this->user_meta_table." WHERE `meta_key` like 'pie_%'" );	
		
		if(sizeof($result) > 0)
		{
			return $result;
		}
		return false;
	}
	function replaceMetaKeys($text,$user_id)
	{
		if($result = $this->getPieMeta())
		{	
			foreach($result as $meta)
			{	
				$key 		= "%".$meta->meta_key."%";
				$value		= get_user_meta($user_id, $meta->meta_key );				
				$value 		= is_array($value[0])? implode(", ",$value[0]) : $value[0] ;
				$text		= str_replace($key,$value,$text);						
			}
		}
		return $text;
	}
	function getCurrentFields()
	{		
		$data 	= get_option("pie_fields");			
		$data 	= maybe_unserialize($data );				
		
		if(!$data)
		{
			return false;		
		}
		return $data;			
	}
	function install_settings()
	{
		
		//Alternate Pages
		
		//Login
		$_p = array();
		$_p['post_title'] 		= "Pie Register - Login";
		$_p['post_content'] 	= "[pie_register_login]";
		$_p['post_status'] 		= 'publish';
		$_p['post_type'] 		= 'page';
		$_p['comment_status'] 	= 'closed';
		$_p['ping_status'] 		= 'closed';
		$login_page_id 			= wp_insert_post( $_p );
		
		//Registration
		$_p = array();
		$_p['post_title'] 		= "Pie Register - Registration";
		$_p['post_content'] 	= "[pie_register_form]";
		$_p['post_status'] 		= 'publish';
		$_p['post_type'] 		= 'page';
		$_p['comment_status'] 	= 'closed';
		$_p['ping_status'] 		= 'closed';
		$reg_page_id 			= wp_insert_post( $_p );
		
			//Registration
		$_p = array();
		$_p['post_title'] 		= "Pie Register - Forgot Password";
		$_p['post_content'] 	= "[pie_register_forgot_password]";
		$_p['post_status'] 		= 'publish';
		$_p['post_type'] 		= 'page';
		$_p['comment_status'] 	= 'closed';
		$_p['ping_status'] 		= 'closed';
		$forPas_page_id 		= wp_insert_post( $_p );
		
		$pie_pages = array($login_page_id,$reg_page_id,$forPas_page_id);
		add_option("pie_pages",$pie_pages);
		
		//Countries
		$country = array("Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo","Costa Rica","Côte d'Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Greenland","Grenada","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","North Korea","South Korea","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Northern Mariana Islands","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","Sudan","Sudan, South","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Yemen","Zambia","Zimbabwe");
		add_option("pie_countries",$country);	
		
		//USA States
		$us_states = array("Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","District of Columbia","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island", "South Carolina", "South Dakota", "Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia", "Wisconsin","Wyoming","Armed Forces Americas","Armed Forces Europe","Armed Forces Pacific");
		add_option("pie_us_states",$us_states);
				
		//Canada States
		$can_states = array("Alberta","British Columbia","Manitoba","New Brunswick","Newfoundland &amp; Labrador","Northwest Territories","Nova Scotia","Nunavut","Ontario","Prince Edward Island","Quebec","Saskatchewan","Yukon");
		add_option("pie_can_states",$can_states);
		
		
		//E-Mail TYpes
		$email_type = array("default_template"=>"Default Template","admin_verification"=>"Admin Verification","email_verification"=>"E-Mail Verification","email_thankyou"=>"Thank You Message","pending_payment"=>"Pending Payment","payment_success"=>"Payment Success","pending_payment_reminder"=>"Pending Payment Reminder","email_verification_reminder"=>"E-Mail Verification Reminder");
		//"email_forgotpassword"=>"Forgot Password"
		add_option("pie_user_email_types",$email_type);			
		
		
		$update = get_option( 'pie_register' );
		
		$update["paypal_butt_id"] = "";
		$update["paypal_pdt"]     = "";
		$update["paypal_sandbox"] = "";			
		$update['enable_admin_notifications'] = 1;
		$update['enable_paypal'] = 0;
		
		
		$update['admin_sendto_email'] 	= get_option( 'admin_email' );				
		$update['admin_from_name'] 		= "Administrator";
		$update['admin_from_email'] 	= get_option( 'admin_email' );
		$update['admin_to_email'] 		= get_option( 'admin_email' );
		$update['admin_bcc_email'] 		= get_option( 'admin_email' );
		$update['admin_subject_email'] 	= __("New User Registration","piereg");
		$update['admin_message_email'] 	= __('<div>&nbsp;%blogname% Registration&nbsp;</div><div>	&nbsp;---------------------------&nbsp;</div><div>	&nbsp;</div><div>	The following user has registered at&nbsp;&nbsp;%blogname%</div><div>	&nbsp;</div><div>	&nbsp;Username: %user_login%&nbsp;</div><div>	&nbsp;Password: %user_pass%&nbsp;</div><div>	&nbsp;</div>',"piereg");
		
		
		$update['display_hints']			= 1;
		$update['subscriber_login']			= 0;
		$update['block_wp_login']			= 0;
		$update['alternate_login']			= $login_page_id;
		$update['alternate_register']		= $reg_page_id;
		$update['alternate_forgotpass']		= $forPas_page_id;		
		$update['after_login']				= -1;
		$update['support_license'] 			= "";
		$update['outputcss'] 				= 1;
		$update['theme_styles']				= 1;		
		$update['outputhtml'] 				= 1;
		$update['no_conflict']				= 0;
		$update['currency'] 				= "USD";
		$update['verification'] 			= 0;
		$update['grace_period'] 			= 10;
		$update['captcha_publc'] 			= "";
		$update['captcha_private'] 			= "";
		$update['paypal_button_id'] 		= "";
		$update['paypal_pdt_token'] 		= "";
		$update['custom_css'] 				= "";
		$update['tracking_code'] 			= "";
		$update['enable_invitation_codes'] 	= 0;
		$update['invitation_codes'] 		= "";
		
		
		$pie_user_email_types 	= get_option( 'pie_user_email_types'); 
				
		foreach ($pie_user_email_types as $val=>$type) 
		{
			$update['enable_user_notifications'] = 0;
			
			$update['user_from_name_'.$val] 	= "Admin";
			$update['user_from_email_'.$val] 	= get_option( 'admin_email' );
			$update['user_to_email_'.$val]	 	= get_option( 'admin_email' );					
			$update['user_subject_email_'.$val] = $type;			
		}
		

		
		$update['user_message_email_admin_verification'] 	= __("Thank you for your registration at %blogname%. Your account is being moderated by the administrator.","piereg");
		$update['user_message_email_email_verification'] 	= __("Thank you for your registration at %blogname%. Please click on the following link to confirm your registration. <br/><br/><a href='%activationurl%'>%activationurl%</a>");
		$update['user_message_email_email_thankyou'] 		= __("Thank you for your registration at %blogname%.","piereg");
		//$update['user_message_email_email_forgotpassword'] 	= "";
		$update['user_message_email_payment_success'] 		= __("Thank you for your registration at %blogname%.","piereg");
		$update['user_message_email_pending_payment'] 		= __("Thank you for your registration at %blogname%. Please complete your payment to confirm your registration at %blogname%.","piereg");
		$update['user_message_email_default_template'] 		= __("Thank you for your registration at %blogname%.","piereg");
		
		$update['user_message_email_pending_payment_reminder'] 		= __("Thank you for your registration at %blogname%.","piereg");
		$update['user_message_email_email_verification_reminder'] 	= __("Hello Unverified %user_login%, <br /><br /> Someday ago you have been signed up at Our Website, an email were sent to you to activate Your account. This is a reminder email that if you failed to activate in xx days, You account will be deleted from our system. You can follow the link below to activate your account.<br><br> Sincerely,The %blogname% Team.","piereg");
		
						
		update_option( 'pie_register', $update );
		
		
		$current_fields 	= maybe_unserialize(get_option( 'pie_fields' ));	
		
		
		$fields 					= array();
		
		
		$fields['form']['label'] 				= __("Registration Form","piereg");
		$fields['form']['desc'] 				= __("Please fill the form below to register.","piereg");
		$fields['form']['label_alignment'] 		= "left";
		$fields['form']['css']					= "";
		$fields['form']['type']					= "form";
		$fields['form']['meta']					= 0;
		$fields['form']['reset']				= 0;
		
		
		
		$fields[0]['label'] 		= "Username";
		$fields[0]['type'] 			= "username";
		$fields[0]['id'] 			= 0;
		$fields[0]['remove'] 		= 0;
		$fields[0]['required'] 		= 1;
		$fields[0]['desc'] 			= "";
		$fields[0]['length'] 		= "";
		$fields[0]['default_value'] = "";
		$fields[0]['placeholder'] 	= "";
		$fields[0]['css'] 			= ""; 
		$fields[0]['meta']			= 0;
		
		$fields[1]['label'] 			= "E-mail";
		$fields[1]['type'] 				= "email";
		$fields[1]['id'] 				= 1;
		$fields[1]['remove'] 			= 0;
		$fields[1]['required'] 			= 1;
		$fields[1]['desc'] 				= "";
		$fields[1]['length'] 			= "";
		$fields[1]['default_value'] 	= "";
		$fields[1]['placeholder'] 		= "";
		$fields[1]['css'] 				= ""; 
		$fields[1]['validation_rule'] 	= "email";
		$fields[1]['meta']				= 0;
		
		$fields[2]['label'] 			= "Password";
		$fields[2]['type'] 				= "password";
		$fields[2]['id'] 				= 2;
		$fields[2]['remove'] 			= 0;
		$fields[2]['required'] 			= 1;
		$fields[2]['desc'] 				= "";
		$fields[2]['length'] 			= "";
		$fields[2]['default_value'] 	= "";
		$fields[2]['placeholder'] 		= "";
		$fields[2]['css'] 				= ""; 
		$fields[2]['validation_rule'] 	= ""; 
		$fields[2]['meta']				= 0;	
		$fields[2]['show_meter']		= 1;		
		
		
		//Getting data from old plugins
		$num = 3;
		
		if ($update['firstname'] || $update ['lastname'])
		{
			$fields[$num]['type'] 			= "name";
			$fields[$num]['label'] 			= "First Name";	
			$fields[$num]['field_name'] 	= "first_name";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		
		if ($update['website'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= "Website";	
			$fields[$num]['field_name'] 	= "url";		
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($update ['aim'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= "AIM";
			$fields[$num]['field_name'] 	= "aim";			
			$fields[$num]['id'] 			= $num;	
			$num++;			
		}
		if ($update['yahoo'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= "Yahoo IM";
			$fields[$num]['field_name'] 	= "yim";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($update['jabber'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= "Jabber / Google Talk";
			$fields[$num]['field_name'] 	= "jabber";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		if ($update['about'])
		{
			$fields[$num]['type'] 			= "default";
			$fields[$num]['label'] 			= "About Yourself";	
			$fields[$num]['field_name'] 	= "description";			
			$fields[$num]['id'] 			= $num;	
			$num++;		
		}
		
		
		
		$piereg_custom = get_option( 'pie_register_custom' );
		if( is_array($piereg_custom ))
		{
			foreach( $piereg_custom as $k=>$v)
			{	
				
				if($v['fieldtype']=="select" || $v['fieldtype']=="checkbox" || $v['fieldtype']=="radio")//Populating values
				{
					$ops = explode(',',$v['extraoptions']);
					foreach( $ops as $op )
					{
						$fields[$num]['value'][] 	= $op;
						$fields[$num]['display'][] 	= $op;
					}
				}
				else
				{
					$fields[$num]['default_value'] 	= $v['extraoptions'];				
				}
				
				$fields[$num]['type'] 			= $v['fieldtype'];
				$fields[$num]['label'] 			= $v['label'];			
				$fields[$num]['id'] 			= $num;			
				$fields[$num]['required'] 		= $v['required'];
				
				if($fields[$num]['type']=="select")
				{
					$fields[$num]['type'] = "dropdown";	
				}
				
				if($fields[$num]['type']=="date")
				{
					$fields[$num]['date_type'] 	 	= "datepicker";
					$fields[$num]['date_format'] 	= $update["dateformat"];
					$fields[$num]['firstday'] 		= $update["firstday"];
					$fields[$num]['startdate'] 		= $update["startdate"];
					$fields[$num]['calyear'] 		= $update["calyear"];	
					$fields[$num]['calmonth'] 		= $update["calmonth"];				
					
				}
				
				$num++;
			}
		}
		
		$fields['submit']['message'] 			= "Thank you for your registration";
		$fields['submit']['confirmation'] 		= "text";
		$fields['submit']['text'] 				= "Submit";
		$fields['submit']['reset']				= 0;
		$fields['submit']['reset_text'] 		= "Reset";
		$fields['submit']['type'] 				= "submit";
		$fields['submit']['meta']				= 0;	
	
			
		//if(!is_array($current_fields ) || sizeof($current_fields ) == 0)
		{
			update_option( 'pie_fields', $fields  );
		}	
		
		update_option( 'pie_fields_default', $fields  );
		
		$structure 	= $this->getDefaultMeta();
		
				
		update_option( 'pie_fields_meta', $structure  );
		
		global $wpdb;
		$prefix=$wpdb->prefix."pieregister_";
		$codetable=$prefix."code";
		$wpdb->query("CREATE TABLE ".$codetable."(`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`created` DATE NOT NULL ,`modified` DATE NOT NULL ,`name` TEXT NOT NULL ,`count` INT( 5 ) NOT NULL ,`status` INT( 2 ) NOT NULL ,`usage` INT( 5 ) NOT NULL) ENGINE = MYISAM ;");	
		
		//Adding active meta to existing users
		 $blogusers = get_users();
   		 foreach ($blogusers as $user) 
		 {
        	update_user_meta( $user->ID, 'active', 1);
    	 }
			
	}
	function getDefaultMeta()
	{
		$structure = array();
		$structure["text"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="length_%d%">Length</label><input type="text" name="field[%d%][length]" id="length_%d%" class="input_fields character_fields field_length numeric"></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="default_value_%d%">Default Value</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_rule_%d%">Validation Rule</label><select name="field[%d%][validation_rule]" id="validation_rule_%d%"><option>None</option><option value="number">Number</option><option value="alphanumeric">Alphanumeric</option><option value="email">E-Mail</option><option value="website">Website</option><option value="standard">Phone Standard (xxx)xxx-xxxx)</option><option value="international">Phone International xxx-xxx-xxxx</option>  </select></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>'; 
		
		$structure["username"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		$structure["password"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="1" name="field[%d%][required]"><input type="hidden" name="field[%d%][label]"><input type="hidden" name="field[%d%][validation_rule]"><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields">
  <label for="show_meter_%d%">Show Strength Meter</label><select class="show_meter" name="field[%d%][show_meter]" id="show_meter_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
			$structure['email']	= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main">  <div class="advance_options_fields">        <input type="hidden" value="1" name="field[%d%][required]">    <input type="hidden" name="field[%d%][label]">    <input type="hidden" name="field[%d%][validation_rule]">    <div class="advance_fields"> <label for="desc_%d%">Description</label> <textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea>    </div>    <div class="advance_fields"> <label for="confirm_email_%d%">Confirm E-Mail</label> <input name="field[%d%][confirm_email]" id="confirm_email" value="%d%" type="checkbox" class="checkbox_fields">    </div> <div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div>   <div class="advance_fields"> <label for="validation_message_%d%">Validation Message</label> <input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields">    </div>    <div class="advance_fields"> <label for="css_%d%">CSS Class Name</label> <input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields">    </div>  </div>  </div>';
		
		
		$structure["textarea"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="rows_%d%">Rows</label><input type="text" value="8" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields field_rows numeric"></div><div class="advance_fields"><label for="cols_%d%">Columns</label><input type="text" value="73" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields field_cols numeric"></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="default_value_%d%">Default Value</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure["dropdown"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">Display Value</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">Value</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>Checked</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'radio\',jQuery(this));">+</a><a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">List Type</label><select name="field[%d%][list_type]" id="list_type_%d%"><option>None</option><option value="country">Country</option><option value="us_states">US States</option><option value="can_states">Canada States</option> </select></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure["multiselect"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">Display Value</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields select_option_display"><label for="value_%d%">Value</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields select_option_value"><label>Checked</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="select_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'checkbox\',jQuery(this));">+</a><a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"> <label for="list_type_%d%">List Type</label><select name="field[%d%][list_type]" id="list_type_%d%"><option>None</option><option value="country">Country</option><option value="us_states">US States</option><option value="can_states">Canada States</option></select></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure["number"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="min_%d%">Min</label><input type="text" name="field[%d%][min]" id="min_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label for="max_%d%">Max</label><input type="text" name="field[%d%][max]" id="max_%d%" class="input_fields character_fields  numeric"></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="default_value_%d%">Default Value</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure["checkbox"] = '<div class="fields_main">  <div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div>  <div class="advance_fields  sel_options_%d%"><label for="display_%d%">Display Value</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields checkbox_option_display"><label for="value_%d%">Value</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields checkbox_option_value"><label>Checked</label><input type="checkbox" value="0" id="check_%d%" name="field[%d%][selected][]" class="checkbox_option_checked"><a style="color:white" href="javascript:;" onClick="addOptions(%d%,\'checkbox\');">+</a></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div> <div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div> </div></div>';
		
		$structure["radio"] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields  sel_options_%d%"><label for="display_%d%">Display Value</label><input type="text" name="field[%d%][display][]" id="display_%d%" class="input_fields character_fields radio_option_display"><label for="value_%d%">Value</label><input type="text" name="field[%d%][value][]" id="value_%d%" class="input_fields character_fields radio_option_value"><label>Checked</label><input type="radio" value="0" id="check_%d%" name="field[%d%][selected][]" class="radio_option_checked"><a style="color:white" href="javascript:;" onclick="addOptions(%d%,\'radio\');">+</a></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure["html"] 			= '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><textarea rows="8" id="htmlbox_%d%" class="ckeditor" name="field[3][html]" cols="16"></textarea></div></div></div>';
		
		$structure["sectionbreak"] = '<input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		$structure["pagebreak"] 	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" value="0" class="input_fields" name="field[%d%][meta]" id="meta_%d%"><div class="advance_fields"><label for="next_button_%d%">Next Button</label><div class="calendar_icon_type">  <input class="next_button" type="radio" id="next_button_%d%_text" name="field[%d%][next_button]" value="text" checked="checked">  <label for="next_button_%d%_text">Text </label>  <input class="next_button" type="radio" id="next_button_%d%_url" name="field[%d%][next_button]" value="url"><label for="next_button_%d%_url"> Image</label></div><div id="next_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="next_button_%d%_url"> Image Path: </label>  <input type="text" name="field[%d%][next_button_url]" class="input_fields" id="next_button_%d%_url"></div><div id="next_button_text_container_%d%" style="float:left;clear: both;">  <label for="next_button_%d%_text"> Text: </label>  <input type="text" name="field[%d%][next_button_text]" value="Next" class="input_fields" id="next_button_%d%_text"></div></div><div class="advance_fields"><label for="prev_button_%d%">Previous Button</label><div class="calendar_icon_type">  <input class="prev_button" type="radio" id="prev_button_%d%_text" name="field[%d%][prev_button]" value="text" checked="checked">  <label for="prev_button_%d%_text">Text </label>  <input class="prev_button" type="radio" id="prev_button_%d%_url" name="field[%d%][prev_button]" value="url">  <label for="prev_button_%d%_url"> Image</label></div><div id="prev_button_url_container_%d%" style="float:left;clear: both;display: none;">  <label for="prev_button_%d%_url"> Image Path: </label>  <input type="text" name="field[%d%][prev_button_url]" class="input_fields" id="prev_button_%d%_url"></div><div id="prev_button_text_container_%d%" style="float:left;clear: both;">  <label for="prev_button_%d%_text"> Text: </label>  <input type="text" name="field[%d%][prev_button_text]" value="Previous" class="input_fields" id="prev_button_%d%_text"></div></div></div></div>';
		
		
		$structure['name']	= '<input type="hidden" value="First Name" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"><div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		

	$structure['time']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="time_type_%d%">List Type</label><select class="time_format" name="field[%d%][time_type]" id="time_type_%d%"><option value="12">12 hour</option><option value="24">24 hour</option></select></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		

	
	$structure['website']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
	
	$structure['upload']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="file_types_%d%">File Types</label><input type="text" name="field[%d%][file_types]" id="file_types_%d%" class="input_fields"><a class="info" href="javascript:;">Separated with commas (i.e. jpg, gif, png, pdf)</a></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label>Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
	
	$structure['address']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"> <label for="address_type_%d%">List Type</label><select class="address_type" name="field[%d%][address_type]" id="address_type_%d%"><option value="International">International</option><option value="United States">United States</option><option value="Canada">Canada</option></select></div><div id="default_country_div_%d%" class="advance_fields"> <label for="default_country_%d%">Default Country</label><select class="default_country" name="field[%d%][default_country]" id="default_country_%d%"><option value="" selected="selected"></option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option><option value="Congo, Republic of the">Congo, Republic of the</option><option value="Costa Rica">Costa Rica</option><option value="Côte d\'Ivoire">Côte d\'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor">East Timor</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="North Korea">North Korea</option><option value="South Korea">South Korea</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Norway">Norway</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Palestine">Palestine</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Sudan, South">Sudan, South</option><option value="Suriname">Suriname</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City">Vatican City</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Virgin Islands, British">Virgin Islands, British</option><option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="hide_address2_%d%">Hide Address 2</label><input onchange="checkEvents(this,\'address_address2_%d%\')" name="field[%d%][hide_address2]" id="hide_address2_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_address2_%d%" class="required"></label></div><div class="advance_fields"><label for="hide_state_%d%">Hide State</label><input class="hide_state" name="field[%d%][hide_state]" id="hide_state_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="hide_state_%d%" class="required"></label></div><div style="display:none;" id="default_state_div_%d%" class="advance_fields"><label for="default_state_%d%">Default State</label><select id="us_states_%d%" style="display:none;" class="default_state us_states_%d%" name="field[%d%][us_default_state]"><option value="" selected="selected"></option><option value="Alabama">Alabama</option><option value="Alaska">Alaska</option><option value="Arizona">Arizona</option><option value="Arkansas">Arkansas</option><option value="California">California</option><option value="Colorado">Colorado</option><option value="Connecticut">Connecticut</option><option value="Delaware">Delaware</option><option value="District of Columbia">District of Columbia</option><option value="Florida">Florida</option><option value="Georgia">Georgia</option><option value="Hawaii">Hawaii</option><option value="Idaho">Idaho</option><option value="Illinois">Illinois</option><option value="Indiana">Indiana</option><option value="Iowa">Iowa</option><option value="Kansas">Kansas</option><option value="Kentucky">Kentucky</option><option value="Louisiana">Louisiana</option><option value="Maine">Maine</option><option value="Maryland">Maryland</option><option value="Massachusetts">Massachusetts</option><option value="Michigan">Michigan</option><option value="Minnesota">Minnesota</option><option value="Mississippi">Mississippi</option><option value="Missouri">Missouri</option><option value="Montana">Montana</option><option value="Nebraska">Nebraska</option><option value="Nevada">Nevada</option><option value="New Hampshire">New Hampshire</option><option value="New Jersey">New Jersey</option><option value="New Mexico">New Mexico</option><option value="New York">New York</option><option value="North Carolina">North Carolina</option><option value="North Dakota">North Dakota</option><option value="Ohio">Ohio</option><option value="Oklahoma">Oklahoma</option><option value="Oregon">Oregon</option><option value="Pennsylvania">Pennsylvania</option><option value="Rhode Island">Rhode Island</option><option value="South Carolina">South Carolina</option><option value="South Dakota">South Dakota</option><option value="Tennessee">Tennessee</option><option value="Texas">Texas</option><option value="Utah">Utah</option><option value="Vermont">Vermont</option><option value="Virginia">Virginia</option><option value="Washington">Washington</option><option value="West Virginia">West Virginia</option><option value="Wisconsin">Wisconsin</option><option value="Wyoming">Wyoming</option><option value="Armed Forces Americas">Armed Forces Americas</option><option value="Armed Forces Europe">Armed Forces Europe</option><option value="Armed Forces Pacific">Armed Forces Pacific</option></select><select id="can_states_%d%" style="display:none;" class="default_state can_states_%d%" name="field[%d%][canada_default_state]"><option value="" selected="selected"></option><option value="Alberta">Alberta</option><option value="British Columbia">British Columbia</option><option value="Manitoba">Manitoba</option><option value="New Brunswick">New Brunswick</option><option value="Newfoundland &amp; Labrador">Newfoundland &amp; Labrador</option><option value="Northwest Territories">Northwest Territories</option><option value="Nova Scotia">Nova Scotia</option><option value="Nunavut">Nunavut</option><option value="Ontario">Ontario</option><option value="Prince Edward Island">Prince Edward Island</option><option value="Quebec">Quebec</option><option value="Saskatchewan">Saskatchewan</option><option value="Yukon">Yukon</option></select></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
				
		$structure['captcha']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div></div></div>';
		
		$structure['phone']	= '<div class="fields_main"><div class="advance_options_fields"><input type="hidden" class="input_fields" name="field[%d%][type]"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="default_value_%d%">Default Value</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div><div class="advance_fields"> <label for="phone_format_%d%">Phone Format</label><select class="phone_format" name="field[%d%][phone_format]" id="phone_format_%d%"><option value="standard">(###)### - ####</option><option value="international">International</option></select></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure['date']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"> <label for="date_type_%d%">Date Format</label><select class="date_format" name="field[%d%][date_format]" id="date_format_%d%"><option value="mm/dd/yy">mm/dd/yy</option><option value="dd/mm/yy">dd/mm/yy</option><option value="dd-mm-yy">dd-mm-yy</option><option value="dd.mm.yy">dd.mm.yy</option><option value="yy/mm/dd">yy/mm/dd</option><option value="yy.mm.dd">yy.mm.dd</option></select></div><div class="advance_fields"> <label for="date_type_%d%">Date Input Type</label><select class="date_type" name="field[%d%][date_type]" id="date_type_%d%"><option value="datefield">Date Field</option><option value="datepicker">Date Picker</option><option value="datedropdown">Date Drop Down</option></select></div><div style="display:none;" id="icon_div_%d%" class="advance_fields"> <label for="date_type_%d%">&nbsp;</label><div class="calendar_icon_type"><input class="calendar_icon" type="radio" id="calendar_icon_%d%_none" name="field[%d%][calendar_icon]" value="none" checked="checked"><label for="calendar_icon_%d%_none"> No Icon </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_calendar" name="field[%d%][calendar_icon]" value="calendar"><label for="calendar_icon_%d%_calendar"> Calendar Icon </label>&nbsp;&nbsp;<input class="calendar_icon" type="radio" id="calendar_icon_%d%_custom" name="field[%d%][calendar_icon]" value="custom"><label for="calendar_icon_%d%_custom"> Custom Icon </label></div><div id="icon_url_container_%d%" style="display: none;float:left;clear: both;">  <label for="cfield_calendar_icon_%d%_url"> Image Path: </label>  <input type="text" class="input_fields" name="field[%d%][calendar_icon_url]" id="cfield_calendar_icon_%d%_url"></div></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure['list'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="required_%d%">Rules</label><input name="field[%d%][required]" id="required_%d%" value="%d%" type="checkbox" class="checkbox_fields"><label for="required_%d%" class="required">Required</label></div><div class="advance_fields"><label for="rows_%d%">Rows</label><input type="text" value="1" name="field[%d%][rows]" id="rows_%d%" class="input_fields character_fields list_rows numeric greaterzero"></div><div class="advance_fields"><label for="cols_%d%">Columns</label><input type="text" value="1" name="field[%d%][cols]" id="cols_%d%" class="input_fields character_fields list_cols numeric greaterzero"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div><div class="advance_fields"><label for="show_in_profile_%d%">Show in Profile</label><select class="show_in_profile" name="field[%d%][show_in_profile]" id="show_in_profile_%d%"  class="checkbox_fields"><option value="1" selected="selected">Yes</option><option value="0">No</option></select></div></div></div>';
		
		$structure['hidden'] = '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="default_value_%d%">Default Value</label><input type="text" name="field[%d%][default_value]" id="default_value_%d%" class="input_fields field_default_value"></div></div></div>';
		
		$structure['invitation']	= '<div class="fields_main"><div class="advance_options_fields"><div class="advance_fields"><label for="label_%d%">Label</label><input type="text" name="field[%d%][label]" id="label_%d%" class="input_fields field_label"></div><div class="advance_fields"><label for="desc_%d%">Description</label><textarea name="field[%d%][desc]" id="desc_%d%" rows="8" cols="16"></textarea></div><div class="advance_fields"><label for="placeholder_%d%">Placeholder</label><input type="text" name="field[%d%][placeholder]" id="placeholder_%d%" class="input_fields field_placeholder"></div><div class="advance_fields"><label for="validation_message_%d%">Validation Message</label><input type="text" name="field[%d%][validation_message]" id="validation_message_%d%" class="input_fields"></div><div class="advance_fields"><label for="css_%d%">CSS Class Name</label><input type="text" name="field[%d%][css]" id="css_%d%" class="input_fields"></div></div></div>';
		
		return $structure;	
	}
	function uninstall_settings()
	{
	/*	delete_option( 'pie_countries');
		delete_option( 'pie_us_states');
		delete_option( 'pie_can_states');
		delete_option( 'pie_user_email_types');		
		delete_option( 'pie_register');
		delete_option( 'pie_fields_default');	
		delete_option( 'pie_fields');	
		delete_option( 'pie_fields_meta');
		delete_option( 'pie_register_custom');*/
		
		$pie_pages = get_option("pie_pages",$pie_pages);
		
		if(is_array($pie_pages ))
		{
			foreach ($pie_pages as $page)	
			{
				wp_delete_post($page);	
			}
		}
		
		delete_option( 'pie_pages');
		global $wpdb;
		$prefix=$wpdb->prefix.'pieregister_';
		$codetable=$prefix.'code';
		$wpdb->query('DROP TABLE `'.$codetable.'`');
		$wpdb->flush();	
	}
	function pluginURL($add = "")
	{
		return plugins_url()."/pie-register/".$add;	
	}
	function getMetaKey($text)
	{
		return str_replace("-","_",sanitize_title($text));	
	}
	function filterEmail($text,$user,$user_pass="")
	{
		$text			= $this->replaceMetaKeys($text,$user->ID);
		
		$user_login 	= stripslashes($user->user_login);
		$user_email 	= stripslashes($user->user_email);
		$blog_name 		= get_option("blogname"); 
		$site_url 		= get_option("siteurl"); 
		$first_name		= get_user_meta( $user->ID, 'first_name' );
		$last_name		= get_user_meta( $user->ID, 'last_name' );
		
		$user_url				= $user->user_url ;
		$user_aim				= get_user_meta( $user->ID, 'aim' );
		$user_yim				= get_user_meta( $user->ID, 'yim' );
		$user_jabber			= get_user_meta( $user->ID, 'jabber' );
		$user_biographical_nfo	= get_user_meta( $user->ID, 'description' );
		
		
		$hash 			= get_user_meta( $user->ID, 'hash' );
		$activationurl	= home_url('/').'wp-login.php?action=activate&id='.$user_login.'&activation_key='.$hash[0];
		
		$keys 	= array("%user_login%","%user_pass%","%user_email%","%blogname%","%siteurl%","%activationurl%","%firstname%","%lastname%","%user_url%","%user_aim%","%user_yim%","%user_jabber%","%user_biographical_nfo%" );
		$values = array($user_login ,$user_pass,$user_email,$blog_name, $site_url,$activationurl,$first_name[0],$last_name[0],$user_url[0],$user_aim[0],$user_yim[0],$user_jabber[0],$user_biographical_nfo[0] );
		return str_replace($keys,$values,$text);
	}	
	function createDropdown($options,$sel = "")
	{
		$html = "";
		for($a = 0 ;$a < sizeof($options);$a++)
		{
			$selected = "";
			if($options[$a]==$sel)
			$selected = 'selected="selected"';
			$html .= '<option '.$selected.' value="'.$options[$a].'">'.$options[$a].'</option>';	
		}
		return $html;
	}
	function codeTable()
	{
		global $wpdb;		
		return $wpdb->prefix."pieregister_code";			
	}
	function checkLicense($key="")
	{
		if(empty($key))	
			return;
		
		$ch = curl_init();
			
		
		
		curl_setopt($ch, CURLOPT_URL,"http://pieregister.genetech.co/license.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"domain=".get_bloginfo("url")."&key=".urlencode($key));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch); 
		curl_close ($ch);	
		if(strip_tags($server_output)=="True")
		{
			add_option("pie_register_key",$key);
			add_option("pie_register_active",1);
			return $key; 	
		}	
		return	""; 
	}
	function warnings()
	{ //Show warning if plugin is installed on a WordPress lower than 3.2
			global $wp_version;			
			//VERSION CONTROL
			if( $wp_version < 3.5 )			
			echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Pie-Register is only compatible with WordPress v3.5 and up. You are currently using WordPress v.', 'piereg').$wp_version.". The plugin may not work as expected.</strong> </p></div>";
			
			$key 	=  get_option("pie_register_key");
			$active =  get_option("pie_register_active");
			
			if(empty($key) ||  $active != 1)
			{
				echo "<div id='piereg-warning' class='updated fade-ff0000'><p><strong>".__('Your are using the unregistered version of Pie Register. Click <a href="http://pieregister.genetech.co/license-generation-form/" target="_blank">here</a> to get your key. ', 'piereg')."</strong></p></div>";	
			}
		
	}
}