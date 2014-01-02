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
      <h2><? _e("Settings","pie_register") ?></h2>
      <form method="post" action="" onsubmit="return validateSettings();">
        <h3><? _e("General Settings","pie_register") ?></h3>
         <div class="fields">
          <label for="support_license"><? _e("Support License Key","pie_register") ?></label>
          <input type="text" name="support_license" id="support_license" class="input_fields" value="<?=$piereg['support_license']?>" />
          <span class="quotation"><? _e("The license key is used for access to automatic upgrades and support.","pie_register") ?></span> </div>
        <!--<div class="fields">
          <label><? _e("Get Theme Styles","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="theme_styles" <?=($piereg['theme_styles']=="1")?'checked="checked"':''?> />
            <label><? _e("Yes","pie_register") ?></label>
            <input type="radio" value="0" name="theme_styles" <?=($piereg['theme_styles']=="0")?'checked="checked"':''?> />
            <label><? _e("No","pie_register") ?></label>
          </div>
          <span class="quotation">Set this to No if you don't want the current theme header & footer in your form.</span> </div>-->
       
        <div class="fields">
          <label><? _e("Display Hints","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="display_hints" id="display_hints_yes" <?=($piereg['display_hints']=="1")?'checked="checked"':''?> />
            <label for="display_hints_yes"><? _e("Yes","pie_register") ?></label>
            <input type="radio" value="0" name="display_hints" id="display_hints_no" <?=($piereg['display_hints']=="0")?'checked="checked"':''?> />
            <label for="display_hints_no"><? _e("No","pie_register") ?></label>
            </div>
            <span class="quotation"><? _e("Set this to Yes if you would like to see the Tips on Form Editor Page .","pie_register") ?></span>
          </div>
       
        <div class="fields">
          <label><? _e("Block WP-Login","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="block_wp_login" id="block_wp_login_yes" <?=($piereg['block_wp_login']=="1")?'checked="checked"':''?> />
            <label for="block_wp_login_yes"><? _e("Yes","pie_register") ?></label>
            <input type="radio" value="0" name="block_wp_login" id="block_wp_login_no" <?=($piereg['block_wp_login']=="0")?'checked="checked"':''?> />
            <label for="block_wp_login_no"><? _e("No","pie_register") ?></label>
          </div>
          <span class="quotation"><? _e("Set this to Yes if you would like to block WP Login. You must select alternate pages.","pie_register") ?></span> </div>
          
          
         <div class="fields">
          <label for="alternate_login"><? _e("Alternate Login Page","pie_register") ?></label>
         
            <?  $args =  array("show_option_no_change"=>"None","id"=>"alternate_login","name"=>"alternate_login","selected"=>$piereg['alternate_login']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><? _e("This page must contain the Pie Register Login form short code.","pie_register") ?></span> 
        </div> 
        
         <div class="fields">
          <label for="alternate_login"><? _e("Registration Page","pie_register") ?></label>
         
            <?  $args =  array("show_option_no_change"=>"None","id"=>"alternate_register","name"=>"alternate_register","selected"=>$piereg['alternate_register']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><? _e("This page must contain the Pie Register Registration form short code.","pie_register") ?></span> 
        </div> 
        
         <div class="fields">
          <label for="alternate_forgotpass"><? _e("Forgot Password Page","pie_register") ?></label>
         
            <?  $args =  array("show_option_no_change"=>"None","id"=>"alternate_forgotpass","name"=>"alternate_forgotpass","selected"=>$piereg['alternate_forgotpass']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><? _e("This page must contain the Pie Register Forgot Password form short code.","pie_register") ?></span> 
        </div> 
       
       
       
        <div class="fields">
          <label><? _e("Subscibers Login","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="subscriber_login" id="subscriber_login_yes" <?=($piereg['subscriber_login']=="1")?'checked="checked"':''?> />
            <label for="subscriber_login_yes"><? _e("Yes","pie_register") ?></label>
            <input type="radio" value="0" name="subscriber_login" id="subscriber_login_no" <?=($piereg['subscriber_login']=="0")?'checked="checked"':''?> />
            <label for="subscriber_login_no"><? _e("No","pie_register") ?></label>
          </div>
          <span class="quotation"><? _e("Set this to No if you would like to disable the wp-admin login for subscribers.","pie_register") ?></span> </div>
       
       
       <div class="fields">
          <label for="after_login"><? _e("After Login Page","pie_register") ?></label>
         
            <?  $args =  array("show_option_no_change"=>"Default","id"=>"after_login","name"=>"after_login","selected"=>$piereg['after_login']);         
			wp_dropdown_pages( $args ); ?>
        
           
          <span class="quotation"><? _e("Only valid for subscribers.","pie_register") ?></span> 
        </div>
       
       
       
        <div class="fields">
          <label><? _e("Output CSS","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="1" name="outputcss" id="outputcss_yes" <?=($piereg['outputcss']=="1")?'checked="checked"':''?> />
            <label for="outputcss_yes"><? _e("Yes","pie_register") ?></label>
            <input type="radio" value="0" name="outputcss" id="outputcss_no" <?=($piereg['outputcss']=="0")?'checked="checked"':''?> />
            <label for="outputcss_no"><? _e("No","pie_register") ?></label>
          </div>
          <span class="quotation"><? _e("Set this to No if you would like to disable the plugin from outputting the form CSS.","pie_register") ?></span> </div>
        
        
        <div class="fields">
          <label for="currency"><? _e("Currency","pie_register") ?></label>
          <select name="currency" id="currency">
            <option value="USD" <?=($piereg['currency']=="USD")?'selected="selected"':''?>>US Dollar</option>           
            <option value="CAD" <?=($piereg['currency']=="CAD")?'selected="selected"':''?>>Canadian Dollar</option>
          </select>
        </div>
        <div class="fields">
          <label><? _e("Verifications","pie_register") ?></label>
          <div class="radio_fields">
            <input type="radio" value="2" name="verification" id="verification_2" <?=($piereg['verification']=="2")?'checked="checked"':''?> />
            <label for="verification_2"><? _e("Email Verification","pie_register") ?></label>
            <input type="radio" value="1" name="verification" id="verification_1" <?=($piereg['verification']=="1")?'checked="checked"':''?> />
            <label for="verification_1"><? _e("Admin Verification","pie_register") ?></label>
            <input type="radio" value="0" name="verification" id="verification_0"  <?=($piereg['verification']=="0")?'checked="checked"':''?> />
            <label for="verification_0"><? _e("Off","pie_register") ?></label>
          </div>
          <div class="verification_data">
            <p><? _e("Requires new registrations to click a link in the notification email to enable their account.","pie_register") ?></p>
            <p><strong><? _e("Grace Period (days)","pie_register") ?>:
              <input type="text" name="grace_period" class="input_fields2" value="<?=$piereg['grace_period']?>" />
              </strong></p>
            <p><? _e("Unverified Users will be automatically deleted after grace period expires","pie_register") ?></p>
          </div>
           <div class="fields">
          <input type="submit" class="submit_btn" value="Save Settings" />
        </div>
        </div>
       
        
          
        
        <h3><? _e("Installation Status","pie_register") ?></h3>
        <div class="fields">
          <label><? _e("PHP Version","pie_register") ?></label>
          <span class="installation_status"><?=phpversion()?></span> </div>
        <div class="fields">
          <label><? _e("MySQL Version","pie_register") ?></label>
          <span class="installation_status"><?=mysql_get_server_info()?></span> </div>
        <div class="fields">
          <label><? _e("Wordpress Version","pie_register") ?></label>
          <span class="installation_status"><? bloginfo('version'); ?></span> </div>
        <h3><? _e("reCAPTCHA Settings","pie_register") ?></h3>
        <div class="fields">
          <p><? _e("Pie Register integrates with reCAPTCHA, a free CAPTCHA services that helps to digitize Books while Protecting your forms from spam bots. Readmore about reCAPTCHA.","pie_register") ?></p>
        </div>
        <div class="fields">
          <label for="captcha_publc"><? _e("reCAPTCHA Public Key","pie_register") ?></label>
          <input type="text" id="captcha_publc" name="captcha_publc" class="input_fields" value="<?=$piereg['captcha_publc']?>" />
          <span class="quotation"><? _e("Required Only if you decide to Use the reCAPTCHA field. Sign Up for a Free account to get the key.","pie_register") ?></span> </div>
        <div class="fields">
          <label for="captcha_private"><? _e("reCAPTCHA Private Key","pie_register") ?></label>
          <input type="text" id="captcha_private" name="captcha_private" class="input_fields" value="<?=$piereg['captcha_private']?>" />
          <span class="quotation"><? _e("Required Only if you decide to Use the reCAPTCHA field. Sign Up for a Free account to get the key.","pie_register") ?></span> </div>
        <div class="fields">
          <input type="submit" class="submit_btn" value="Save Settings" />
        </div>
     
      
        <h3><? _e("Custom CSS","pie_register"); ?></h3>
        <div class="fields">
         <span class="quotation" style="margin-left:0px;"><? _e("Please don't use style tags.","pie_register") ?></span>
          <textarea name="custom_css"><?=$piereg['custom_css']?></textarea>        
          <input type="submit" class="submit_btn" value="Save Changes" />
           
        </div>
        <h3><? _e("Tracking Code","pie_register"); ?></h3>
        <div class="fields">
          <textarea name="tracking_code"><?=$piereg['tracking_code']?></textarea>
          <input type="submit" class="submit_btn" value="Save Changes" />
        </div>
        <div class="fields fields2">
          <input type="submit" class="submit_btn" value="Save Changes" />
          <a href="javascript:;" onclick="jQuery('#frm_default').submit();" class="restore"><? _e("Reset to Default","pie_register"); ?></a> </div>
        <input name="action" value="pie_reg_update" type="hidden" />
        <input type="hidden" name="general_settings_page" value="1" />
      </form>
    </div>
  </div>
</div>
<form id="frm_default" method="post" onsubmit="return window.confirm('Are you sure? It will restore all the plugin settings to default.');">
<input type="hidden" value="1" name="default_settings" />
</form>