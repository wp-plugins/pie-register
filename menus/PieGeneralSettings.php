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

var piereg = jQuery.noConflict();

piereg(document).ready(function(){



	if(piereg("#support_license").val().trim() == "")



	{



		piereg("#support_license").focus();



	}







	piereg( document ).tooltip({



		track: true



	});



});



 



</script>



<?php







$piereg = get_option( 'pie_register_2' );







if( $_POST['notice'] ){



	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '</strong></p></div>';



}



if( $_POST['license_success'] ){



	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['license_success'] . '.</strong></p></div>';



}











?>



<div id="container">



  <div class="right_section">



    <div class="settings">



      <h2><?php _e("Settings",'piereg') ?></h2>



      



        <h3><?php _e("Free License Key Setting",'piereg') ?></h3>



		<form method="post" action="" onsubmit="return validateSettings();">



        



        <div class="fields">



            <input type="hidden" name="domainname" value="<?php echo get_bloginfo("url");?>" />



        </div>



        



        <div class="fields">



            <label for="support_email"><?php _e("E-mail Address",'piereg') ?></label>



            <input type="email" style="width:58%;" required="required" name="support_email" id="support_email" class="input_fields" value="<?php echo get_bloginfo("admin_email");?>" />



            <span class="quotation"><?php _e("The license key for support.",'piereg') ?></span>



        </div>



        



        <div class="fields">



            <label for="support_license"><?php _e("License Key",'piereg') ?></label>



            <input type="text" style="width:58%;" 



            <?php echo (isset($piereg['support_license']) and $piereg['support_license'] != "")? 'readonly="readonly"' : ""; ?>



            name="support_license" id="support_license" class="input_fields" value="<?php echo $piereg['support_license']?>" />



            



            <?php if(isset($piereg['support_license']) and $piereg['support_license'] == ""){ ?>



	            <input type="hidden" name="empty_license_key" value="yes"/>



            <?php } ?>



            



        <?php if(isset($piereg['support_license'])	and $piereg['support_license'] != "")



        {?> 



            <img src="<?php echo plugins_url("images/registerd.png",dirname(__FILE__));?>" style="margin:3px;float:left;" title="<?php _e("You have been using registered version of pie-register","piereg"); ?>" />



            <input type="image" name="Remove_license" src="<?php echo plugins_url("images/key_remove.png",dirname(__FILE__));?>" value="&nbsp;&nbsp; <?php _e("Remove License Key"); ?> &nbsp;&nbsp;" title="<?php _e("Click here to de-activate your license key","piereg"); ?>" />



            



            <span class="quotation"><?php _e("The license key is used for access to automatic upgrades and support.",'piereg') ?></span>



            <!--</div>



            <div class="fields">



            <input type="submit" name="Remove_license" class="submit_btn" style="margin-top:0px;" value="&nbsp;&nbsp; <?php _e("Remove License Key","piereg"); ?> &nbsp;&nbsp;" />-->



            



        <?php } else {?>



        	<img src="<?php echo plugins_url("images/help.png",dirname(__FILE__));?>" style="margin:7px 0px;float:left;" title="<?php _e("Please Click on the license icon to get and auto-filled your free license key","piereg"); ?>" />



<!--<input type="submit" class="submit_btn" style="margin-top:0px;" value="&nbsp;&nbsp; <?php _e("Get Free License Key"); ?> &nbsp;&nbsp;" />-->



 <input type="image" src="<?php echo plugins_url("images/key.png",dirname(__FILE__));?>" value="&nbsp;&nbsp; <?php _e("Get Free License Key"); ?> &nbsp;&nbsp;" title="<?php _e("Click here to get your free license key","piereg"); ?>" />



            <span class="quotation"><?php _e("The license key is used for access to automatic upgrades and support.",'piereg') ?></span>



            



        <?php } ?>



            



            



        </div>



            



      



        <h3><?php _e("General Settings",'piereg') ?></h3>



        



         <!--<div class="fields">



          <label for="support_license"><?php _e("Support License Key",'piereg') ?></label>



              <input type="text" name="support_license" id="support_license" class="input_fields" value="<?php echo $piereg['support_license']?>" />



              <span class="quotation"><?php _e("The license key is used for access to automatic upgrades and support.",'piereg') ?></span>



          </div>-->



          



          



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



          <label for="alternate_profilepage"><?php _e("Profile Page",'piereg') ?></label>



         



            <?php  $args =  array("show_option_no_change"=>"None","id"=>"alternate_profilepage","name"=>"alternate_profilepage","selected"=>$piereg['alternate_profilepage']);         



			wp_dropdown_pages( $args ); ?>



        



           



          <span class="quotation"><?php _e("This page must contain the Pie Register Forgot Password form short code.",'piereg') ?></span> 



        </div>

        

        

        <div class="fields">



          <label for="after_login"><?php _e("After Sign-in Page",'piereg') ?></label>



         



            <?php  $args =  array("show_option_no_change"=>"Default","id"=>"after_login","name"=>"after_login","selected"=>$piereg['after_login']);         



			wp_dropdown_pages( $args ); ?>



          <span class="quotation"><?php _e("Subscriber level users will redirect to this page after signing in.",'piereg') ?></span> 



        </div>

        

        

        <div class="fields">
            <label for="alternate_logout"><?php _e("After Logout Page",'piereg') ?></label>
            <?php  $args =  array("show_option_no_change"=>"None","id"=>"alternate_logout","name"=>"alternate_logout","selected"=>$piereg['alternate_logout']);
            wp_dropdown_pages( $args ); ?>
            <span class="quotation"><?php _e("After logout will redirect to this page after Logout page.",'piereg') ?></span> 
        </div>
        
        <div class="fields">
            <center><strong><?php _e("OR","piereg"); ?></strong></center>
        </div>
        
        <div class="fields">
            <label for="alternate_logout_url"><?php _e("After Logout URL",'piereg') ?></label>
            <input type="url" name="alternate_logout_url" id="alternate_logout_url" value="<?php echo $piereg['alternate_logout_url']; ?>" class="input_fields" />
            
            <span class="quotation"><?php _e("After logout will redirect to this url after Logout.",'piereg') ?></span> 
        </div>
        
        



        



        



     



     



        <div class="fields">



          <label><?php _e("Redirect Logged-in Users",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="redirect_user" id="redirect_user_yes" <?php echo ($piereg['redirect_user']=="1")?'checked="checked"':''?> />



            <label for="redirect_user_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="redirect_user" id="redirect_user_no" <?php echo ($piereg['redirect_user']=="0")?'checked="checked"':''?> />



            <label for="redirect_user_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Set this to Yes if you would like to block Login, Registration & Forgot Password pages for loggged in users.",'piereg') ?></span>

       </div>

          

          

          

          

          



        



       <?php /*?> <div class="fields">



          <label><?php _e("Modify Avatars",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="modify_avatars" id="modify_avatars_yes" <?php echo ($piereg['modify_avatars']=="1")?'checked="checked"':''?> />



            <label for="modify_avatars_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="modify_avatars" id="modify_avatars_no" <?php echo ($piereg['modify_avatars']=="0")?'checked="checked"':''?> />



            <label for="modify_avatars_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Use Profile Picture as Avatars (if available)",'piereg') ?></span>



        </div><?php */?>



       



       



       <div class="fields">



          <label><?php _e("Show Admin Bar",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="show_admin_bar" id="show_admin_bar_yes" <?php echo ($piereg['show_admin_bar']=="1")?'checked="checked"':''?> />



            <label for="show_admin_bar_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="show_admin_bar" id="show_admin_bar_no" <?php echo ($piereg['show_admin_bar']=="0")?'checked="checked"':''?> />



            <label for="show_admin_bar_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Show Admin Bar for Subscriber.",'piereg') ?></span>



       </div>



       <div class="fields">



          <label><?php _e("Modify WP-LOGIN",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="allow_pr_edit_wplogin" id="allow_pr_edit_wplogin_yes" <?php echo ($piereg['allow_pr_edit_wplogin']=="1")?'checked="checked"':''?> />



            <label for="allow_pr_edit_wplogin_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="allow_pr_edit_wplogin" id="allow_pr_edit_wplogin_no" <?php echo ($piereg['allow_pr_edit_wplogin']=="0")?'checked="checked"':''?> />



            <label for="allow_pr_edit_wplogin_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Allow Pie-Register to Add header Footer on wp-login.php.",'piereg') ?></span>



       </div>



       



       <div class="fields">



          <label><?php _e("Override WP-Profile",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="block_WP_profile" id="block_WP_profile_yes" <?php echo ($piereg['block_WP_profile']=="1")?'checked="checked"':''?> />



            <label for="block_WP_profile_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="block_WP_profile" id="block_WP_profile_no" <?php echo ($piereg['block_WP_profile']=="0")?'checked="checked"':''?> />



            <label for="block_WP_profile_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Redirect Your Subscriber to Custom Profile Page (if Exists)",'piereg') ?></span>



       </div>



       



       



       



       



       



       



        <div class="fields">



          <label><?php _e("Output CSS",'piereg') ?></label>



          <div class="radio_fields">



            <input type="radio" value="1" name="outputcss" id="outputcss_yes" <?php echo ($piereg['outputcss']=="1")?'checked="checked"':''?> />



            <label for="outputcss_yes"><?php _e("Yes",'piereg') ?></label>



            <input type="radio" value="0" name="outputcss" id="outputcss_no" <?php echo ($piereg['outputcss']=="0")?'checked="checked"':''?> />



            <label for="outputcss_no"><?php _e("No",'piereg') ?></label>



          </div>



          <span class="quotation"><?php _e("Set this to No if you would like to disable Pie-Register from outputting the form CSS.",'piereg') ?></span> </div>



        



        



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



            <p><?php _e("Unverified Users will be automatically deleted after grace period expires. 0 (Zero) For Unlimited",'piereg') ?></p>



          </div>



           <div class="fields">



          <input type="submit" class="submit_btn" value="<?php _e("Save Settings","piereg"); ?>" />



        </div>



        </div>



       



        <h3><?php _e("Installation Status",'piereg') ?></h3>



        <div class="fields">



          <label><?php _e("PHP Version",'piereg') ?></label>



          <?php if(version_compare(phpversion(),  "5.0") == 1)



		  {



			  echo '<span class="installation_status">'.phpversion().'</span>';



		  }



		  else



		  {



			  echo '<span class="installation_status_faild">'.phpversion().'</span>';



			  echo '<span class="quotation">'.__("Sorry, Pie-Register requires PHP 5.0 or higher. Please deactivate Pie-Register","piereg").'</span>';



		  }



		  ?>



        </div>



        <div class="fields">



          <label><?php _e("MySQL Version",'piereg') ?></label>



          <?php if(version_compare(mysql_get_server_info(),  "5.0") == 1)



		  {



			  echo '<span class="installation_status">'.mysql_get_server_info().'</span>';



		  }



		  else



		  {



			  echo '<span class="installation_status_faild">'.mysql_get_server_info().'</span>';



			  echo '<span class="quotation">'.__("Sorry, Pie-Register requires MySQL 5.0 or higher. Please deactivate Pie-Register","piereg").'</span>';



		  }



		  ?>



        </div>



        <div class="fields">



          <label><?php _e("Wordpress Version",'piereg') ?></label>



          <?php if(version_compare(get_bloginfo('version'),  "3.5") == 1)



		  {



			  echo '<span class="installation_status">'.get_bloginfo('version').'</span>';



		  }



		  else



		  {



			  echo '<span class="installation_status_faild">'.get_bloginfo('version').'</span>';



			  echo '<span class="quotation">'.__("Sorry, Pie-Register requires Wordpress 3.5 or higher. Please deactivate Pie-Register","piereg").'</span>';



		  }



		  ?>



        </div>



        <div class="fields">



          <label><?php _e("Enable Curl",'piereg') ?></label>



          <?php if(function_exists('curl_version'))



		  {



			  echo '<span class="installation_status">'.__("CURL Enable","piereg").'</span>';



		  }



		  else



		  {



			  echo '<span class="installation_status_faild">'.__("CURL Enable","piereg").'</span>';



			  echo '<span class="quotation">'.__("Please install CURL on server","piereg").'</span>';



		  }



		  ?>



        </div>



        



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



          <input type="submit" class="submit_btn" value="<?php _e("Save Settings","piereg"); ?>" />



        </div>



     



      



        <h3><?php _e("Custom CSS",'piereg'); ?></h3>



        <div class="fields">



         <span class="quotation" style="margin-left:0px;"><?php _e("Please don't use style tags.",'piereg') ?></span>



          <textarea name="custom_css"><?php echo $piereg['custom_css']?></textarea>        



          <input type="submit" class="submit_btn" value=" <?php _e("Save Changes","piereg");?> " />



           



        </div>



        <h3><?php _e("Tracking Code",'piereg'); ?></h3>



        <div class="fields">



          <textarea name="tracking_code"><?php echo $piereg['tracking_code']?></textarea>



          <input type="submit" class="submit_btn" value=" <?php _e("Save Changes","piereg");?> " />



        </div>



        



        <?php /*?><h3><?php _e("Payment Setting",'piereg'); ?></h3>



        <!-- Payment Setting-->



                <div class="fields">



                    <label for="payment_setting_amount" style="min-width:291px;"><?php echo __("Activation Amount",'piereg'); ?></label>



                    <input id="payment_setting_amount" class="input_fields" type="text" name="payment_setting_amount" <?php echo (trim($piereg['payment_setting_amount']) != "")? 'value="'.$piereg['payment_setting_amount'].'"':'0'?> />



                </div>



                <?php



				$update = get_option("pie_register_2");



				if($this->check_plugin_activation() == "true")



				{



				?>



                <div class="fields">



                    <label for="payment_setting_activation_cycle" style="min-width:291px;"><?php echo __("Activation Cycle","piereg"); ?></label>



                    <select id="payment_setting_activation_cycle" name="payment_setting_activation_cycle">



                        <option value="0" <?php echo ($piereg['payment_setting_activation_cycle']=="0")?'selected="selected"':''?>>One Time</option>



                        <option value="7" <?php echo ($piereg['payment_setting_activation_cycle']=="7")?'selected="selected"':''?>>Weekly</option>



                        <option value="30" <?php echo ($piereg['payment_setting_activation_cycle']=="30")?'selected="selected"':''?>>Monthly</option>



                        <option value="182" <?php echo ($piereg['payment_setting_activation_cycle']=="182")?'selected="selected"':''?>>Half Yearly</option>



                        <option value="273" <?php echo ($piereg['payment_setting_activation_cycle']=="273")?'selected="selected"':''?>>Quarterly</option>



                        <option value="365" <?php echo ($piereg['payment_setting_activation_cycle']=="365")?'selected="selected"':''?>>Yearly</option>



                    </select>



                </div>



                



                <div class="fields">



                    <label for="payment_setting_expiry_notice_days" style="min-width:291px;"><?php echo __("Expiry Notice (Days)","piereg"); ?></label>



                    <select id="payment_setting_expiry_notice_days" name="payment_setting_expiry_notice_days">



                            <option value="0" <?php echo ($piereg['payment_setting_expiry_notice_days']=='0')?'selected="selected"':''?>>NO</option>



                        <?php for($a = 1; $a <= 15; $a++){ ?>



                            <option value="<?php echo $a; ?>" <?php echo ($piereg['payment_setting_expiry_notice_days']==$a)?'selected="selected"':''?>><?php echo $a; ?></option>



                        <?php } ?>



                    </select>



                </div>



                



                <div class="fields">



                    <label for="payment_setting_remove_user_days" style="min-width:291px;"><?php echo __("User permanently Remove(Days)","piereg"); ?></label>



                    <select id="payment_setting_remove_user_days" name="payment_setting_remove_user_days">



                            <option value="0" <?php echo ($piereg['payment_setting_remove_user_days']=='0')?'selected="selected"':''?>>NO</option>



                        <?php for($a = 1; $a <= 15; $a++){ ?>



                            <option value="<?php echo $a; ?>" <?php echo ($piereg['payment_setting_remove_user_days']==$a)?'selected="selected"':''?>><?php echo $a; ?></option>



                        <?php } ?>



                    </select>



                </div>



                



                <div class="fields">



                    <label for="payment_setting_user_block_notice" style="min-width:291px;"><?php echo __("User Temporary Block Notice","piereg"); ?></label>



                    <input id="payment_setting_user_block_notice" class="input_fields" type="text" name="payment_setting_user_block_notice" <?php echo (trim($piereg['payment_setting_user_block_notice']) != "")? 'value="'.$piereg['payment_setting_user_block_notice'].'"':''?> />



                </div>



                 <?php 



				}



				?>



                



                <div class="fields">



                    <input name="submit_btn" style="margin:0;" class="submit_btn" value="Save Changes" type="submit" />



                </div><?php */?>



        

		<h3><?php _e("Custom Logo",'piereg'); ?></h3>



        <div class="fields">



            <label for="logo"><?php _e('Custom Logo URL', 'piereg');?></label>

            

<?php

wp_enqueue_script('thickbox');

?>
<style>
/* thickbox fix / hack because wordpress has changed the includes thickbox.css core file */

#TB_overlay {

	z-index: 99998 !important; /*they have it set at some crazy number */
}
#TB_window {

	z-index: 99999 !important; /*they have it set at some crazy number */
}
#TB_window {
	font: 12px "Open Sans", sans-serif;
	color: #333333;
}

#TB_secondLine {
	font: 10px "Open Sans", sans-serif;
	color:#666666;
}

.rtl #TB_window,
.rtl #TB_secondLine {
	font-family: Tahoma, sans-serif;
}

:lang(he-il) .rtl #TB_window,
:lang(he-il) .rtl #TB_secondLine {
	font-family: Arial, sans-serif;
}

#TB_window a:link {color: #666666;}
#TB_window a:visited {color: #666666;}
#TB_window a:hover {color: #000;}
#TB_window a:active {color: #666666;}
#TB_window a:focus{color: #666666;}

/* end thickbox fixes */
</style>
<script type="text/javascript">

/*************************************************/

///////////////// CUSTOM LOGO /////////////////////

piereg(document).on("click", "#pie_custom_logo_button", function() {



	var $Width = window.innerWidth - 100;

	var $Height = window.innerHeight - 100;

	formfield = piereg("#pie_custom_logo_url").prop("name");



	tb_show("<?php _e( 'Upload/Select Logo', 'piereg' ); ?>", "<?php echo admin_url('media-upload.php') ?>?post_id=0&amp;type=image&amp;context=custom-logo&amp;TB_iframe=1&amp;height="+$Height+"&amp;width="+$Width);



});



window.send_to_editor = function(html) {

	piereg("#pie_custom_logo_url").val(piereg("img", html).attr("src"));

	tb_remove();

}

/*************************************************/

</script>

            

            

            

<?php



 if( ( isset($piereg['custom_logo_url']) && $piereg['custom_logo_url'] == '') && (isset($piereg['logo']) && $piereg['logo'] != '') )

			$piereg['custom_logo_url'] = $piereg['logo'];?>

<input id="pie_custom_logo_url" type="text" name="custom_logo_url" value="<?php echo $piereg['custom_logo_url'];?>" placeholder="<?php _e("Please enter Logo URL","piereg"); ?>" class="input_fields" style="width:50%;" />

&nbsp;<sub><span style="font-size:16px;"><?php _e( 'OR', 'piereg' ); ?></span></sub>&nbsp;
<?php add_thickbox();?>
<button id="pie_custom_logo_button" class="button" type="button" value="1" name="pie_custom_logo_button">

<?php _e( 'Select Image to Upload', 'piereg' ); ?>

</button>

</div>

<div class="fields">

    <label for="custom_logo_title"><?php _e( 'Tooltip Text', 'piereg' ); ?></label>

	<input type="text" name="custom_logo_tooltip" class="input_fields" id="custom_logo_title" value="<?php echo $piereg['custom_logo_tooltip'];?>" placeholder="<?php _e("Enter logo tooltip","piereg"); ?>" />

    <span class="quotation"><?php _e("Show tooltip on custom logo.","piereg"); ?></span>

</div>



<div class="fields">

    <label for="custom_logo_link"><?php _e( 'Link URL', 'piereg' ); ?></label>

	<input type="text" name="custom_logo_link" class="input_fields" id="custom_logo_link" value="<?php echo $piereg['custom_logo_link'];?>" placeholder="<?php _e("Enter logo Link","piereg"); ?>" />

</div>



<div class="fields">

<?php if ( $piereg['custom_logo_url'] ) {?>

	

    <label><?php _e( 'Selected Logo', 'piereg' ); ?></label>

    <img src="<?php echo $piereg['custom_logo_url'];?>" alt="<?php _e( 'Custom Logo', 'piereg' ); ?>" />

        

    </div>

    <div class="fields">

        <label><?php _e( 'Show Custom Logo', 'piereg' ); ?></label>

        <div class="radio_fields">

            <input type="radio" name="show_custom_logo" value="1" id="show_custom_logo_yes" <?php echo ($piereg['show_custom_logo'] == "1")? 'checked="checked"' : '' ?> />

            <label for="show_custom_logo_yes"><?php _e('Yes', 'piereg');?></label>

            <input type="radio" name="show_custom_logo" value="0" id="show_custom_logo_no" <?php echo ($piereg['show_custom_logo'] == "0")? 'checked="checked"' : '' ?> />

            <label for="show_custom_logo_no"><?php _e('No', 'piereg');?></label>

        </div>

    

<?php } ?>   

            <input type="submit" class="submit_btn" value=" <?php _e("Save Changes","piereg");?> " />



        </div>

        

        

        <div class="fields fields2">



          <input type="submit" class="submit_btn" value=" <?php _e("Save Changes","piereg");?> " />



          <a href="javascript:;" onclick="jQuery('#frm_default').submit();" class="restore"><?php _e("Reset to Default",'piereg'); ?></a> </div>



        <input name="action" value="pie_reg_update" type="hidden" />



        <input type="hidden" name="general_settings_page" value="1" />



      </form>



    </div>



  </div>



</div>



<form id="frm_default" method="post" onsubmit="return window.confirm('Are you sure? It will restore all the plugin settings to default.');">



<input type="hidden" value="1" name="piereg_default_settings" />



</form>