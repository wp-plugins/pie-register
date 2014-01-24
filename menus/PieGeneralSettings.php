<script type="text/javascript">
function validateSettings()
{
	if(document.getElementById("block_wp_login_yes").checked && document.getElementById("alternate_login").value == "-1" )
	{
		alert("Please select an alternate login page.");
		return false;	
	}
	if(document.getElementById("block_wp_login_yes").checked && document.getElementById("alternate_register").value == "-1" )
	{
		alert("Please select an alternate register page.");
		return false;	
	}

	if(document.getElementById("block_wp_login_yes").checked && document.getElementById("alternate_forgotpass").value == "-1" )
	{
		alert("Please select an alternate forgot password page.");
		return false;	
	}
	return true;	
}
</script>
<?php
$piereg = get_option( 'pie_register' );
if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
if( $_POST['license_success'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['license_success'] . '.</strong></p></div>';
}

?>
<div id="container">
  <div class="right_section">
    <div class="settings">
      <h2><?php _e("Settings",'piereg') ?></h2>
      <form method="post" action="" onsubmit="return validateSettings();">
        <h3><?php _e("General Settings",'piereg') ?></h3>
         <div class="fields">
          <label for="support_license"><?php _e("Support License Key",'piereg') ?></label>
          <input type="text" name="support_license" id="support_license" class="input_fields" value="<?php echo $piereg['support_license']?>" />
          <span class="quotation"><?php _e("The license key is used for access to automatic upgrades and support.",'piereg') ?></span> </div>
        <!--<div class="fields">
          <label><?php _e("Get Theme Styles",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="theme_styles" <?php echo ($piereg['theme_styles']=="1")?'checked="checked"':''?> />
            <label><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="theme_styles" <?php echo ($piereg['theme_styles']=="0")?'checked="checked"':''?> />
            <label><?php _e("No",'piereg') ?></label>
          </div>
          <span class="quotation">Set this to No if you don't want the current theme header & footer in your form.</span> </div>-->
       
        <div class="fields">
          <label><?php _e("Display Hints",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="display_hints" id="display_hints_yes" <?php echo ($piereg['display_hints']=="1")?'checked="checked"':''?> />
            <label for="display_hints_yes"><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="display_hints" id="display_hints_no" <?php echo ($piereg['display_hints']=="0")?'checked="checked"':''?> />
            <label for="display_hints_no"><?php _e("No",'piereg') ?></label>
            </div>
            <span class="quotation"><?php _e("Set this to Yes if you would like to see the Tips on Form Editor Page .",'piereg') ?></span>
          </div>
       
        <div class="fields">
          <label><?php _e("Block WP-Login Pages",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="block_wp_login" id="block_wp_login_yes" <?php echo ($piereg['block_wp_login']=="1")?'checked="checked"':''?> />
            <label for="block_wp_login_yes"><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="block_wp_login" id="block_wp_login_no" <?php echo ($piereg['block_wp_login']=="0")?'checked="checked"':''?> />
            <label for="block_wp_login_no"><?php _e("No",'piereg') ?></label>
          </div>
          <span class="quotation"><?php _e("Set this to Yes if you would like to block WP Login. You must select alternate pages.",'piereg') ?></span> </div>
          
          
         <div class="fields">
          <label for="alternate_login"><?php _e("Login Page",'piereg') ?></label>
         
            <?php  $args =  array("show_option_no_change"=>"None","id"=>"alternate_login","name"=>"alternate_login","selected"=>$piereg['alternate_login']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><?php _e("This page must contain the Pie Register Login form short code.",'piereg') ?></span> 
        </div> 
        
         <div class="fields">
          <label for="alternate_login"><?php _e("Registration Page",'piereg') ?></label>
         
            <?php  $args =  array("show_option_no_change"=>"None","id"=>"alternate_register","name"=>"alternate_register","selected"=>$piereg['alternate_register']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><?php _e("This page must contain the Pie Register Registration form short code.",'piereg') ?></span> 
        </div> 
        
         <div class="fields">
          <label for="alternate_forgotpass"><?php _e("Forgot Password Page",'piereg') ?></label>
         
            <?php  $args =  array("show_option_no_change"=>"None","id"=>"alternate_forgotpass","name"=>"alternate_forgotpass","selected"=>$piereg['alternate_forgotpass']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><?php _e("This page must contain the Pie Register Forgot Password form short code.",'piereg') ?></span> 
        </div> 
     
     
        <div class="fields">
          <label><?php _e("Subscriber Redirect",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="redirect_user" id="redirect_user_yes" <?php echo ($piereg['redirect_user']=="1")?'checked="checked"':''?> />
            <label for="redirect_user_yes"><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="redirect_user" id="redirect_user_no" <?php echo ($piereg['redirect_user']=="0")?'checked="checked"':''?> />
            <label for="redirect_user_no"><?php _e("No",'piereg') ?></label>
          </div>
          <span class="quotation"><?php _e("Set this to Yes if you would like to block Login, Registration & Forgot Password pages for loggged in users.",'piereg') ?></span> </div>
       
       
       
       
        <div class="fields">
          <label><?php _e("Subscibers Access",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="subscriber_login" id="subscriber_login_yes" <?php echo ($piereg['subscriber_login']=="1")?'checked="checked"':''?> />
            <label for="subscriber_login_yes"><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="subscriber_login" id="subscriber_login_no" <?php echo ($piereg['subscriber_login']=="0")?'checked="checked"':''?> />
            <label for="subscriber_login_no"><?php _e("No",'piereg') ?></label>
          </div>
          <span class="quotation"><?php _e("Set this to No if you would like to disable the wp-admin login for subscribers.",'piereg') ?></span> </div>
       
       
       <div class="fields">
          <label for="after_login"><?php _e("After Login Page",'piereg') ?></label>
         
            <?php  $args =  array("show_option_no_change"=>"Default","id"=>"after_login","name"=>"after_login","selected"=>$piereg['after_login']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><?php _e("Only valid for subscribers.",'piereg') ?></span> 
        </div>
       
       
       
        <div class="fields">
          <label><?php _e("Output CSS",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="outputcss" id="outputcss_yes" <?php echo ($piereg['outputcss']=="1")?'checked="checked"':''?> />
            <label for="outputcss_yes"><?php _e("Yes",'piereg') ?></label>
            <input type="radio" value="0" name="outputcss" id="outputcss_no" <?php echo ($piereg['outputcss']=="0")?'checked="checked"':''?> />
            <label for="outputcss_no"><?php _e("No",'piereg') ?></label>
          </div>
          <span class="quotation"><?php _e("Set this to No if you would like to disable the plugin from outputting the form CSS.",'piereg') ?></span> </div>
        
        
        <div class="fields">
          <label for="currency"><?php _e("Currency",'piereg') ?></label>
          <select name="currency" id="currency">
            <option value="USD" <?php echo ($piereg['currency']=="USD")?'selected="selected"':''?>>US Dollar</option>           
            <option value="CAD" <?php echo ($piereg['currency']=="CAD")?'selected="selected"':''?>>Canadian Dollar</option>
          </select>
        </div>
        <div class="fields">
          <label><?php _e("Verifications",'piereg') ?></label>
          <div class="radio_fields">
            <input type="radio" value="2" name="verification" id="verification_2" <?php echo ($piereg['verification']=="2")?'checked="checked"':''?> />
            <label for="verification_2"><?php _e("Email Verification",'piereg') ?></label>
            <input type="radio" value="1" name="verification" id="verification_1" <?php echo ($piereg['verification']=="1")?'checked="checked"':''?> />
            <label for="verification_1"><?php _e("Admin Verification",'piereg') ?></label>
            <input type="radio" value="0" name="verification" id="verification_0"  <?php echo ($piereg['verification']=="0")?'checked="checked"':''?> />
            <label for="verification_0"><?php _e("Off",'piereg') ?></label>
          </div>
          <div class="verification_data">
            <p><?php _e("Requires new registrations to click a link in the notification email to enable their account.",'piereg') ?></p>
            <p><strong><?php _e("Grace Period (days)",'piereg') ?>:
              <input type="text" name="grace_period" class="input_fields2" value="<?php echo $piereg['grace_period']?>" />
              </strong></p>
            <p><?php _e("Unverified Users will be automatically deleted after grace period expires",'piereg') ?></p>
          </div>
           <div class="fields">
          <input type="submit" class="submit_btn" value="Save Settings" />
        </div>
        </div>
       
        
          
        
        <h3><?php _e("Installation Status",'piereg') ?></h3>
        <div class="fields">
          <label><?php _e("PHP Version",'piereg') ?></label>
          <span class="installation_status"><?php echo phpversion()?></span> </div>
        <div class="fields">
          <label><?php _e("MySQL Version",'piereg') ?></label>
          <span class="installation_status"><?php echo mysql_get_server_info()?></span> </div>
        <div class="fields">
          <label><?php _e("Wordpress Version",'piereg') ?></label>
          <span class="installation_status"><?php bloginfo('version'); ?></span> </div>
        <h3><?php _e("reCAPTCHA Settings",'piereg') ?></h3>
        <div class="fields">
          <p><?php _e("Pie Register integrates with reCAPTCHA, a free CAPTCHA services that helps to digitize Books while Protecting your forms from spam bots. Readmore about reCAPTCHA.",'piereg') ?></p>
        </div>
        <div class="fields">
          <label for="captcha_publc"><?php _e("reCAPTCHA Public Key",'piereg') ?></label>
          <input type="text" id="captcha_publc" name="captcha_publc" class="input_fields" value="<?php echo $piereg['captcha_publc']?>" />
          <span class="quotation"><?php _e("Required Only if you decide to Use the reCAPTCHA field. Sign Up for a Free account to get the key.",'piereg') ?></span> </div>
        <div class="fields">
          <label for="captcha_private"><?php _e("reCAPTCHA Private Key",'piereg') ?></label>
          <input type="text" id="captcha_private" name="captcha_private" class="input_fields" value="<?php echo $piereg['captcha_private']?>" />
          <span class="quotation"><?php _e("Required Only if you decide to Use the reCAPTCHA field. Sign Up for a Free account to get the key.",'piereg') ?></span> </div>
        <div class="fields">
          <input type="submit" class="submit_btn" value="Save Settings" />
        </div>
     
      
        <h3><?php _e("Custom CSS",'piereg'); ?></h3>
        <div class="fields">
         <span class="quotation" style="margin-left:0px;"><?php _e("Please don't use style tags.",'piereg') ?></span>
          <textarea name="custom_css"><?php echo $piereg['custom_css']?></textarea>        
          <input type="submit" class="submit_btn" value="Save Changes" />
           
        </div>
        <h3><?php _e("Tracking Code",'piereg'); ?></h3>
        <div class="fields">
          <textarea name="tracking_code"><?php echo $piereg['tracking_code']?></textarea>
          <input type="submit" class="submit_btn" value="Save Changes" />
        </div>
        <div class="fields fields2">
          <input type="submit" class="submit_btn" value="Save Changes" />
          <a href="javascript:;" onclick="jQuery('#frm_default').submit();" class="restore"><?php _e("Reset to Default",'piereg'); ?></a> </div>
        <input name="action" value="pie_reg_update" type="hidden" />
        <input type="hidden" name="general_settings_page" value="1" />
      </form>
    </div>
  </div>
</div>
<form id="frm_default" method="post" onsubmit="return window.confirm('Are you sure? It will restore all the plugin settings to default.');">
<input type="hidden" value="1" name="default_settings" />
</form>